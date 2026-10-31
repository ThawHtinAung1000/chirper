<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ChirpFileStore
{
    public function all(): array
    {
        return $this->withLock(
            false,
            fn (array $chirps): array => $chirps
        );
    }

    public function find(string $id): ?array
    {
        return $this->withLock(
            false,
            function (array $chirps) use ($id): ?array {
                foreach ($chirps as $chirp) {
                    if (($chirp['id'] ?? null) === $id) {
                        return $chirp;
                    }
                }

                return null;
            }
        );
    }

    public function create(string $content): array
    {
        return $this->withLock(
            true,
            function (array &$chirps) use ($content): array {
                $chirp = [
                    'id' => (string) Str::uuid(),
                    'author' => 'Guest',
                    'content' => $content,
                    'created_at' => now()->toIso8601String(),
                ];

                array_unshift($chirps, $chirp);

                return $chirp;
            }
        );
    }

    public function update(string $id, string $content): ?array
    {
        return $this->withLock(
            true,
            function (array &$chirps) use ($id, $content): ?array {
                foreach ($chirps as $index => $chirp) {
                    if (($chirp['id'] ?? null) === $id) {
                        $chirps[$index]['content'] = $content;

                        $chirps[$index]['updated_at'] =
                            now()->toIso8601String();

                        return $chirps[$index];
                    }
                }

                return null;
            }
        );
    }

    /**
     * Read or modify the JSON file with a lock.
     */
    private function withLock(bool $write, callable $callback): mixed
    {
        $path = Storage::disk('local')->path('chirps.json');

        File::ensureDirectoryExists(dirname($path));

        // c+b opens the file without truncating it.
        // It also creates the file if it does not exist.
        $handle = fopen($path, 'c+b');

        if ($handle === false) {
            throw new RuntimeException('Cannot open Chirps file.');
        }

        try {
            // Exclusive lock for writes, shared lock for reads.
            $lockType = $write ? LOCK_EX : LOCK_SH;

            if (!flock($handle, $lockType)) {
                throw new RuntimeException('Cannot lock Chirps file.');
            }

            rewind($handle);

            $json = stream_get_contents($handle);

            if ($json === false) {
                throw new RuntimeException('Cannot read Chirps file.');
            }

            $chirps = trim($json) === ''
                ? []
                : json_decode(
                    $json,
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );

            if (!is_array($chirps) || !array_is_list($chirps)) {
                throw new RuntimeException(
                    'Chirps file must contain a JSON array.'
                );
            }

            // Read or modify the array.
            $result = $callback($chirps);

            if ($write) {
                $encoded = json_encode(
                    $chirps,
                    JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_UNICODE |
                    JSON_THROW_ON_ERROR
                );

                rewind($handle);

                if (!ftruncate($handle, 0)) {
                    throw new RuntimeException(
                        'Cannot truncate Chirps file.'
                    );
                }

                // Handle partial writes.
                $remaining = $encoded;

                while ($remaining !== '') {
                    $written = fwrite($handle, $remaining);

                    if ($written === false || $written === 0) {
                        throw new RuntimeException(
                            'Cannot write Chirps file.'
                        );
                    }

                    $remaining = substr($remaining, $written);
                }

                if (!fflush($handle)) {
                    throw new RuntimeException(
                        'Cannot flush Chirps file.'
                    );
                }
            }

            return $result;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }
}
