<?php

declare(strict_types=1);

/**
 * includes/env.php — loader .env tanpa dependency.
 * API: loadEnv(string $path) / env(string $key, ?string $default = null)
 *
 * Dipanggil dari index.php (front controller) dan partial header
 * sebagai jaring pengaman untuk akses langsung file shell .php.
 */

if (!function_exists('env')) {
    function env(string $key, ?string $default = null): ?string
    {
        $value = getenv($key);
        return ($value === false || $value === '') ? $default : $value;
    }
}

if (!function_exists('loadEnv')) {
    function loadEnv(string $path): void
    {
        static $loaded = false;
        if ($loaded || !is_file($path)) {
            return;
        }
        $loaded = true;

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
            $key   = trim($key);
            $value = trim($value);

            if ($key === '') {
                continue;
            }

            $length = strlen($value);
            if ($length >= 2) {
                $first = $value[0];
                $last  = $value[$length - 1];
                if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                    $value = substr($value, 1, -1);
                }
            }

            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
        }
    }
}
