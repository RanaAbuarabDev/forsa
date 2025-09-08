<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class OtpAttemptService
{
    protected $maxAttempts = 5;
    protected $lockDuration = 30; 
    protected array $localCache = [];

    protected function getCached(string $key)
    {
        if (!array_key_exists($key, $this->localCache)) {
            $this->localCache[$key] = Cache::get($key);
        }

        return $this->localCache[$key];
    }

    public function getAttemptsKey(string $email): string
    {
        return "otp_attempts_{$email}";
    }

    public function getLockKey(string $email): string
    {
        return "otp_lock_{$email}";
    }

    public function incrementAttempts(string $email): int
    {
        $key = $this->getAttemptsKey($email);

        $attempts = Cache::increment($key);

        if (is_null($attempts)) {
            
            $attempts = 1;
            Cache::put($key, $attempts, now()->addMinutes($this->lockDuration));
        }

        $this->localCache[$key] = $attempts;

        return $attempts;
    }

    public function hasExceededAttempts(string $email): bool
    {
        return $this->getCached($this->getAttemptsKey($email)) >= $this->maxAttempts;
    }

    public function applyLock(string $email): void
    {
        $lockKey = $this->getLockKey($email);
        $lockUntil = now()->addMinutes($this->lockDuration)->timestamp;

        Cache::put($lockKey, $lockUntil, now()->addMinutes($this->lockDuration));
        Cache::forget($this->getAttemptsKey($email));

        $this->localCache[$lockKey] = $lockUntil;
        unset($this->localCache[$this->getAttemptsKey($email)]);
    }

    public function isLocked(string $email): bool
    {
        return $this->getCached($this->getLockKey($email)) !== null;
    }

    public function getRemainingLockTime(string $email): int
    {
        $lockedUntil = $this->getCached($this->getLockKey($email));
        return max(0, $lockedUntil - now()->timestamp);
    }

    public function clearAttempts(string $email): void
    {
        Cache::forget($this->getAttemptsKey($email));
        Cache::forget($this->getLockKey($email));

        unset($this->localCache[$this->getAttemptsKey($email)]);
        unset($this->localCache[$this->getLockKey($email)]);
    }

    public function remainingAttempts(string $email): int
    {
        $attempts = $this->getCached($this->getAttemptsKey($email));
        return $this->maxAttempts - ($attempts ?? 0); 
    }

    public function getMaxAttempts(): int
    {
        return $this->maxAttempts;
    }
}
