<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SettingsService
{


    /**
     * Update Notifications Setting for the User
     *
     * @param User $user The user whose setting will be updated
     * @param bool $enabled Whether notifications are enabled (true) or disabled (false)
     * @return void
     *
     * This method updates the `notifications_enabled` field in the user's profile.
     */
    public function updateNotifications(User $user, bool $enabled): void
    {
        $user->update([
            'notifications_enabled' => $enabled,
        ]);
    }


    /**
     * Update Dark Mode Setting for the User
     *
     * @param User $user The user whose theme preference will be updated
     * @param bool $darkMode Whether dark mode is enabled (true) or disabled (false)
     * @return void
     *
     * This method updates the `dark_mode` field in the user's profile.
     */
    public function updateTheme(User $user, bool $darkMode): void
    {
        $user->dark_mode = $darkMode;
        $user->save();
    }


    /**
     * Change the User's Password After Verifying the Current One
     *
     * @param User $user The user who wants to update their password
     * @param string $currentPassword The user's current password (plain text)
     * @param string $newPassword The new password to be hashed and stored
     * @return bool True if password was changed successfully, false if current password is incorrect
     *
     * This method verifies the user's current password before updating to the new one.
     */
    public function updatePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        if (!Hash::check($currentPassword, $user->password)) {
            return false;
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        return true;
    }
}
