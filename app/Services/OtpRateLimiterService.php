<?php


namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class OtpRateLimiterService
{
    protected $lockDuration = 30;
    protected $lockCache = []; 

    public function isLocked(string $email): bool
    {
        $key = $this->getLockKey($email);

        if (!array_key_exists($key, $this->lockCache)) {
            $this->lockCache[$key] = Cache::get($key);
        }

        return !is_null($this->lockCache[$key]);
    }

    public function getRemainingLockTime(string $email): int
    {
        $key = $this->getLockKey($email);

        if (!array_key_exists($key, $this->lockCache)) {
            $this->lockCache[$key] = Cache::get($key); 
        }

        return max(0, $this->lockCache[$key] - now()->timestamp);
    }

    protected function getLockKey(string $email): string
    {
        return "otp_lock_{$email}";
    }

    public function lock(string $email): void
    {
        $expiresAt = now()->addMinutes($this->lockDuration);
        $timestamp = $expiresAt->timestamp;

        Cache::put($this->getLockKey($email), $timestamp, $expiresAt);
        $this->lockCache[$this->getLockKey($email)] = $timestamp; 
    }
}
