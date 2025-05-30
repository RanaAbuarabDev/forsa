<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrUpdateProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\StoreProfileRequest;
use App\Services\ProfileService;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ProfileController extends Controller
{

    use AuthorizesRequests;

    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function store(StoreProfileRequest $request): JsonResponse
    {
        $profile = $this->profileService->createProfile($request->validated());

        return response()->json([
            'message' => 'تم انشاء الملف الشخصي بنجاح',
            'data' => new ProfileResource($profile),
        ]);
    }

    public function show(): JsonResponse
    {
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json(['message' => 'الملف الشخصي غير موجود'], 404);
        }

        return response()->json([
            'data' => new ProfileResource($profile),
        ]);
    }

    public function update(UpdateProfileRequest $request): JsonResponse{
        $user = Auth::user();
        $profile = $user->profile;

        $this->authorize('update', $profile);

        $updatedProfile = $this->profileService->updateProfile($request->validated(), $profile);

        return response()->json([
            'message' => 'تم تحديث الملف الشخصي بنجاح',
            'data' => new ProfileResource($updatedProfile),
        ]);
    }


    public function showProfile($userId): JsonResponse{
        $profile = \App\Models\Profile::where('user_id', $userId)->first();

        if (!$profile) {
            return response()->json(['message' => 'الملف الشخصي غير موجود'], 404);
        }

        $this->authorize('view', $profile);

        return response()->json([
            'data' => new ProfileResource($profile),
        ]);
    }


   
}
