<?php


namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class OtpRateLimiterService
{
    /**
     * The duration (in minutes) for which the user will be locked after exceeding OTP rate limits.
     *
     * @var int
     */
    protected $lockDuration = 30;

    /**
     * Check if the given email is currently locked due to exceeding OTP requests.
     *
     * @param string $email
     * @return bool True if the email is currently locked, false otherwise.
     */
    public function isLocked(string $email): bool
    {
        return Cache::has($this->getLockKey($email));
    }

    /**
     * Get the remaining time (in seconds) until the lock is lifted for the given email.
     *
     * @param string $email
     * @return int Remaining lock time in seconds.
     */
    public function getRemainingLockTime(string $email): int
    {
        $lockedUntil = Cache::get($this->getLockKey($email));
        return max(0, $lockedUntil - now()->timestamp);
    }

    /**
     * Lock the given email for a predefined duration to prevent further OTP requests.
     *
     * @param string $email
     * @return void
     */
    public function lock(string $email): void
    {
        Cache::put(
            $this->getLockKey($email),
            now()->addMinutes($this->lockDuration)->timestamp,
            now()->addMinutes($this->lockDuration)
        );
    }

    /**
     * Get the cache key used to store lock information for the given email.
     *
     * @param string $email
     * @return string
     */
    protected function getLockKey(string $email): string
    {
        return "otp_lock_{$email}";
    }
}
