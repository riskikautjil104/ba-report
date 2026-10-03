<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Throwable;

trait HasEncryptedRouteKey
{
    /**
     * Encrypt an ID into a URL-safe, deterministic encrypted token using APP_KEY.
     */
    public static function encryptRouteId(int|string $id): string
    {
        $appKey = (string) config('app.key');
        $key = hash('sha256', $appKey, true);
        $iv = substr(hash('sha256', $appKey.'rsud-chasan-route-iv', true), 0, 16);

        $encrypted = openssl_encrypt((string) $id, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        if ($encrypted === false) {
            return (string) $id;
        }

        return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
    }

    /**
     * Decrypt a URL-safe encrypted token back into the original ID.
     */
    public static function decryptRouteId(string $token): ?string
    {
        try {
            $base64 = strtr($token, '-_', '+/');
            $padding = strlen($base64) % 4;
            if ($padding > 0) {
                $base64 .= str_repeat('=', 4 - $padding);
            }

            $raw = base64_decode($base64, true);
            if ($raw === false) {
                return null;
            }

            $appKey = (string) config('app.key');
            $key = hash('sha256', $appKey, true);
            $iv = substr(hash('sha256', $appKey.'rsud-chasan-route-iv', true), 0, 16);

            $decrypted = openssl_decrypt($raw, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

            return $decrypted !== false ? $decrypted : null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Get the value of the model's route key in encrypted URL-safe format.
     */
    public function getRouteKey(): mixed
    {
        return self::encryptRouteId($this->getKey());
    }

    /**
     * Retrieve the model for a bound value by decrypting the route key.
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return null;
        }

        $decryptedId = self::decryptRouteId((string) $value);

        if ($decryptedId !== null) {
            return $this->where($field ?? $this->getRouteKeyName(), $decryptedId)->first();
        }

        // Backward compatibility fallback for direct numeric IDs
        if (is_numeric($value)) {
            return $this->where($field ?? $this->getRouteKeyName(), $value)->first();
        }

        return null;
    }
}
