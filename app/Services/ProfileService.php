<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\Skill;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileService
{
    public function createProfile(array $data)
    {
        $user = Auth::user();

        if (isset($data['img'])) {
            $data['img'] = $this->storeImage($data['img']);
        }

        $skills = $this->processSkills($data['skills'] ?? []);
        unset($data['skills']);

        $profile = $user->profile()->create($data);
        $profile->skills()->sync($skills);

        return $profile;
    }

    public function updateProfile(array $data, Profile $profile)
    {
        if (isset($data['img']) && $data['img'] instanceof \Illuminate\Http\UploadedFile) {
            if ($profile->img) {
                Storage::disk('public')->delete($profile->img);
            }
            $data['img'] = $this->storeImage($data['img']);
        } else {
            unset($data['img']); 
        }

        if (array_key_exists('skills', $data)) {
            if (count($data['skills']) > 0) {
                $skills = $this->processSkills($data['skills']);
                $profile->skills()->sync($skills);
            }
            unset($data['skills']);
        }

        Log::info('Before update:', $profile->toArray());
        $profile->update($data);
        Log::info('After update:', $profile->fresh()->toArray());

        return $profile;
    }

    protected function storeImage($imgFile)
    {
        return $imgFile->store('profiles', 'public');
    }

    protected function processSkills(array $skills)
    {
        return collect($skills)->map(function ($name) {
            $skill = Skill::firstOrCreate(['name' => ucfirst(strtolower($name))]);
            return $skill->id;
        })->toArray();
    }
}
