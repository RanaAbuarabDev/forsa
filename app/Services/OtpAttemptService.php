<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class OtpAttemptService
{
    protected $maxAttempts = 5;
    protected $lockDuration = 30; // in minutes

    /**
     * Get the cache key for OTP attempts based on the user's email.
     *
     * @param string $email
     * @return string
     */
    public function getAttemptsKey(string $email): string
    {
        return "otp_attempts_{$email}";
    }

    /**
     * Get the cache key for OTP lock based on the user's email.
     *
     * @param string $email
     * @return string
     */
    public function getLockKey(string $email): string
    {
        return "otp_lock_{$email}";
    }

    /**
     * Increment the number of OTP attempts for a given email.
     * Initializes the attempts if not already present in the cache.
     *
     * @param string $email
     * @return int The current number of attempts after increment.
     */
    public function incrementAttempts(string $email): int
    {
        $key = $this->getAttemptsKey($email);

        if (!Cache::has($key)) {
            Cache::put($key, 0, now()->addMinutes($this->lockDuration));
        }

        $attempts = Cache::increment($key);

        return $attempts;
    }

    /**
     * Check if the user has exceeded the maximum allowed OTP attempts.
     *
     * @param string $email
     * @return bool
     */
    public function hasExceededAttempts(string $email): bool
    {
        return Cache::get($this->getAttemptsKey($email), 0) >= $this->maxAttempts;
    }

    /**
     * Apply a lock to the user's OTP verification for a set duration.
     *
     * @param string $email
     * @return void
     */
    public function applyLock(string $email): void
    {
        Cache::put(
            $this->getLockKey($email),
            now()->addMinutes($this->lockDuration)->timestamp,
            now()->addMinutes($this->lockDuration)
        );

        Cache::forget($this->getAttemptsKey($email));
    }

    /**
     * Determine whether the user is currently locked out from OTP verification.
     *
     * @param string $email
     * @return bool
     */
    public function isLocked(string $email): bool
    {
        return Cache::has($this->getLockKey($email));
    }

    /**
     * Get the remaining lock time in seconds.
     *
     * @param string $email
     * @return int Remaining lock time in seconds.
     */
    public function getRemainingLockTime(string $email): int
    {
        return max(0, Cache::get($this->getLockKey($email)) - now()->timestamp);
    }

    /**
     * Clear all OTP attempts and lock data for the given email.
     *
     * @param string $email
     * @return void
     */
    public function clearAttempts(string $email): void
    {
        Cache::forget($this->getAttemptsKey($email));
        Cache::forget($this->getLockKey($email));
    }

    /**
     * Get the number of remaining OTP attempts before lock is applied.
     *
     * @param string $email
     * @return int
     */
    public function remainingAttempts(string $email): int
    {
        return $this->maxAttempts - Cache::get($this->getAttemptsKey($email), 0);
    }

    /**
     * Get the maximum number of allowed OTP attempts.
     *
     * @return int
     */
    public function getMaxAttempts(): int
    {
        return $this->maxAttempts;
    }
}

