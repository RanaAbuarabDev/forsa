<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Services\SettingsService;
use App\Http\Requests\UpdateThemeRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateNotificationsRequest;

class SettingsController extends Controller
{
    protected SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
        $this->middleware('auth:api');
    }

    // public function middleware(): array
    // {
    //     return [
    //         'auth:api',
    //     ];
    // }



    /**
    * Update Notifications Setting
    *
    * @method POST
    * @route /user/notifications
    * @desc Updates the user's notifications preference (enabled or disabled).
    * @requires JWT Auth (auth:api)
    * @request UpdateNotificationsRequest
    * @body {
    *   "enabled": boolean
    * }
    * @response {
    *   "message": "تم تحديث الإشعارات"
    * }
    */
    public function updateNotifications(UpdateNotificationsRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = auth('api')->user();
        $this->settingsService->updateNotifications($user, $request->enabled);
    
        return response()->json(['message' => 'تم تحديث الإشعارات']);
    }


    /**
     * Update Theme Preference
     *
     * @method POST
     * @route /user/theme
     * @desc Updates the user's theme setting (dark mode on or off).
     * @requires JWT Auth (auth:api)
     * @request UpdateThemeRequest
     * @body {
     *   "dark_mode": boolean
     * }
     * @response {
     *   "message": "تم تحديث الوضع"
     * }
     */
    public function updateTheme(UpdateThemeRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = auth('api')->user();
        $this->settingsService->updateTheme($user, $request->dark_mode);

        return response()->json(['message' => 'تم تحديث الوضع']);
    }


    
    
    /**
     * Change User Password
     *
     * @method POST
     * @route /user/update-password
     * @desc Allows the authenticated user to change their password by verifying the current one.
     * @requires JWT Auth (auth:api)
     * @request UpdatePasswordRequest
     * @body {
     *   "current_password": string,
     *   "new_password": string (min: 6)
     * }
     * @response {
     *   "message": "تم تغيير كلمة المرور"
     * }
     * @error {
     *   "message": "كلمة المرور الحالية غير صحيحة"
     * }
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = auth('api')->user();
        $success = $this->settingsService->updatePassword(
            $user,
            $request->current_password,
            $request->new_password
        );

        if (! $success) {
            return response()->json(['message' => 'كلمة المرور الحالية غير صحيحة'], 422);
        }

        return response()->json(['message' => 'تم تغيير كلمة المرور']);
    }
}
