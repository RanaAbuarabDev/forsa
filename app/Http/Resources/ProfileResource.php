<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'img' => $this->img ? asset('storage/' . $this->img) : null,
            'BD' => $this->BD,
            'age' => $this->BD ? Carbon::parse($this->BD)->age : null,
            'cv_path' => $this->cv_path ? asset('storage/' . $this->cv_path) : null,
            'residence_area' => $this->residence_area,
            'gender' => $this->gender,
            'employment_status' => $this->employment_status,
            'governorate' => $this->governorate?->name,
            'skills' => $this->skills->pluck('name'),
            'desired_job' => $this->whenLoaded('desiredJob', fn() => [
                    'job_title' => $this->desiredJob->job_title,
                    'job_location' => $this->desiredJob->job_location,
                    'employment_type' => $this->desiredJob->employment_type,
                ]),


            //'desired_job' => $this->whenLoaded('desiredJob'),
            //'educational_qualification' => $this->whenLoaded('educationalQualifications'),
            'educational_qualification' => $this->educationalQualification ? [
                'education_level' => $this->educationalQualification->education_level,
                'institution_name' => $this->educationalQualification->institution_name,
                'major' => $this->educationalQualification->major,
                'graduation_date' => $this->educationalQualification->graduation_date,
                ] : null,

            'social_links' => $this->socialLinks->map(function ($link) {
                return [
                    'id' => $link->id,
                    'platform' => $link->platform,
                    'url' => $link->url,
                ];
            }),
            'certificates' => $this->certificates->map(function ($cert) {
                return [
                    'id'=>$cert->id,
                    'name' => $cert->name,
                    'issued_at' => $cert->pivot->issued_at,
                ];
            }),
            'experiences' => $this->experiences->map(function ($exp) {
                return [
                    'id'=>$exp->id,
                    'job_title' => $exp->job_title,
                    'years_of_experience' => $exp->pivot->years_of_experience,
                    'job_description' => $exp->pivot->job_description,
                ];
            }),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
