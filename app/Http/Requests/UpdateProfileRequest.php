<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
       return [
            'img' => 'nullable|image|max:2048',
            'BD' => 'nullable|date|before:today',
            'governorate_id' => 'nullable|exists:governorates,id',
            'residence_area' => 'nullable|string',
            'gender' => 'nullable|in:male,female',
            'employment_status' => 'nullable|in:employed,unemployed,seeking_better_opportunity',
            'cv_path' => 'nullable|file|mimes:pdf|max:2048',
    
            'skills' => 'nullable|array|min:1',
            'skills.*' => 'string|max:50',
    
            'desired_job.job_title' => 'nullable|string|max:255',
            'desired_job.job_location' => 'nullable|string|max:255',
            'desired_job.employment_type' => 'nullable|in:full-time,part-time,freelance,volunteer,training,temporary,contracts',
    
    
            'educational_qualification.education_level' => 'nullable|in:PhD,Master,Higher Diploma,Diploma,Bachelor,High School,Middle School,None',
            'educational_qualification.institution_name' => 'nullable|string|max:255',
            'educational_qualification.major' => 'nullable|string|max:255',
            'educational_qualification.graduation_date' => 'nullable|date',
    
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'nullable|in:facebook,instagram,whatsapp',
            'social_links.*.url' => 'nullable|url',
    
            'certificates' => 'nullable|array',
            'certificates.*.name' => 'nullable|string|max:255',
            'certificates.*.issued_at' => 'nullable|date',
    
            'experiences' => 'nullable|array',
            'experiences.*.job_title' => 'required|string|max:255',
            'experiences.*.years_of_experience' => 'nullable|integer|min:0',
            'experiences.*.job_description' => 'nullable|string',
        ];
    }
}
