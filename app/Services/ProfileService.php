<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\Skill;
use App\Models\Certificate;
use App\Models\Experience;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Models\DesiredJob;

class ProfileService
{
    public function createProfile(array $data){

        return DB::transaction(function () use ($data) {
            $user = Auth::user();

            
            if (isset($data['img'])) {
                $data['img'] = $this->storeImage($data['img']);
            }

           
            $skills = $this->processSkills($data['skills'] ?? []);
            unset($data['skills']);


            $profileData = Arr::except($data, [
                'desired_job',
                'educational_qualification',
                'social_links',
                'certificates',
                'experiences'
            ]);

            
            $profile = $user->profile()->create($profileData);

            
            $profile->skills()->sync($skills);

            
            if (isset($data['desired_job'])) {
                $profile->desiredJob()->create($data['desired_job']);
            }

            
            if (isset($data['educational_qualification'])) {
                $profile->educationalQualification()->create($data['educational_qualification']);
            }

            
            if (!empty($data['social_links'])) {
                foreach ($data['social_links'] as $link) {
                    $profile->socialLinks()->updateOrCreate(
                        ['platform' => $link['platform']],
                        ['url' => $link['url']]
                    );

                }
            }

            
            if (!empty($data['certificates'])) {
                $certificates = $this->processCertificates($data['certificates']);
                $profile->certificates()->sync($certificates);
            }

          
            if (!empty($data['experiences'])) {
                $experienceData = $this->processExperiencesInput($data['experiences']);
                $profile->experiences()->attach($experienceData);
            }


            
            $profile->load(
                'desiredJob',
                'educationalQualification',
                'skills',
                'socialLinks',
                'certificates',
                'experiences'
            );

            return $profile;
        });
    }

    
    public function updateProfile(array $data, Profile $profile){
        
        DB::transaction(function () use (&$data, $profile) {

            
            if (array_key_exists('img', $data)) {
                if ($data['img'] === null) {
                    if ($profile->img) {
                        Storage::disk('public')->delete($profile->img);
                    }
                    $data['img'] = null;
                } elseif ($data['img'] instanceof \Illuminate\Http\UploadedFile) {
                    if ($profile->img) {
                        Storage::disk('public')->delete($profile->img);
                    }
                    $data['img'] = $this->storeImage($data['img']);
                } else {
                    unset($data['img']);
                }
            }

            
            if (array_key_exists('cv_path', $data)) {
                if ($data['cv_path'] === null) {
                    if ($profile->cv_path) {
                        Storage::disk('public')->delete($profile->cv_path);
                    }
                    $data['cv_path'] = null;
                } elseif ($data['cv_path'] instanceof \Illuminate\Http\UploadedFile) {
                    if ($profile->cv_path) {
                        Storage::disk('public')->delete($profile->cv_path);
                    }
                    $data['cv_path'] = $this->storeCvFile($data['cv_path']);
                } else {
                    unset($data['cv_path']);
                }
            }

        
            if (array_key_exists('skills', $data)) {
                $skills = $this->processSkills($data['skills']);
                $profile->skills()->sync($skills);
                unset($data['skills']);
            }

            
            if (array_key_exists('desired_job', $data)) {
                $desiredJobData = $data['desired_job'];
                $desiredJobData['profile_id'] = $profile->id;

                DesiredJob::updateOrCreate(
                    ['profile_id' => $profile->id],
                    $desiredJobData
                );

                unset($data['desired_job']);
            }

            
            if (array_key_exists('educational_qualification', $data)) {
                $profile->educationalQualification()->updateOrCreate(
                    ['profile_id' => $profile->id],
                    $data['educational_qualification']
                );

                unset($data['educational_qualification']);
            }

            
            if (array_key_exists('social_links', $data)) {
                $existingLinks = $profile->socialLinks()->get()->keyBy('platform');
                $incomingPlatforms = [];

                foreach ($data['social_links'] as $item) {
                    if (!empty($item['url']) && !empty($item['platform'])) {
                        $incomingPlatforms[] = $item['platform'];

                        if (isset($existingLinks[$item['platform']])) {
                            
                            $existingLinks[$item['platform']]->update([
                                'url' => $item['url'],
                            ]);
                        } else {
                         
                            $profile->socialLinks()->create([
                                'platform' => $item['platform'],
                                'url' => $item['url'],
                            ]);
                        }
                    }
                }

                $profile->socialLinks()
                    ->whereNotIn('platform', $incomingPlatforms)
                    ->delete();

                unset($data['social_links']);
            }



            
            if (array_key_exists('certificates', $data)) {
                $certificates = $this->processCertificates($data['certificates']);
                $profile->certificates()->sync($certificates);
                unset($data['certificates']);
            }

            
            if (array_key_exists('experiences', $data)) {
                $experienceData = $this->processExperiencesInput($data['experiences']);
                $profile->experiences()->sync($experienceData);
                unset($data['experiences']);
            }



            
            $profile->update($data);
        });

        return $profile->fresh([
            'user',
            'governorate',
            'skills',
            'desiredJob',
            'educationalQualification',
            'socialLinks',
            'certificates',
            'experiences',
        ]);
    }


    protected function storeImage($imgFile){

        return $imgFile->store('profiles', 'public');
    }


    protected function processSkills(array $skills){

        return collect($skills)->map(function ($name) {
            $skill = Skill::firstOrCreate(['name' => ucfirst(strtolower($name))]);
            return $skill->id;
        })->toArray();
    }


    protected function storeCvFile($cvFile){
        
        return $cvFile->store('cv', 'public');
    }


    private function processCertificates(array $certificates): array{

        $result = [];

        foreach ($certificates as $cert) {
            $certificate = Certificate::firstOrCreate(
                ['name' => $cert['name']],
                ['created_at' => now()]
            );

            $result[$certificate->id] = [
                'issued_at' => $cert['issued_at'] ?? null,
            ];
        }

        return $result;
    }


    protected function processExperiencesInput(array $experiences){

        $experienceData = [];

        foreach ($experiences as $exp) {
            if (empty($exp['job_title'])) {
                continue; 
            }

            $experience = Experience::firstOrCreate([
                'job_title' => $exp['job_title']
            ]);

            $experienceData[$experience->id] = [
                'years_of_experience' => $exp['years_of_experience'] ?? null,
                'job_description' => $exp['job_description'] ?? null,
            ];
        }

        return $experienceData;
    }



}
