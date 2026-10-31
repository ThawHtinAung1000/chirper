<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChirpController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate Chirp content
        $validated = $request->validate([
            'content' => 'required|string|max:280',
        ]);

        // 2. Select our JSON file
        $disk = Storage::disk('local');
        $file = 'chirps.json';

        // 3. Read existing Chirps
        $chirps = $disk->exists($file)
            ? json_decode(
                $disk->get($file),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
            : [];

        // 4. Create a new Chirp
        $chirp = [
            'id' => (string) Str::uuid(),
            'author' => 'Guest',
            'content' => $validated['content'],
            'created_at' => now()->toIso8601String(),
        ];

        // 5. Add newest Chirp first
        array_unshift($chirps, $chirp);

        // 6. Write Chirps to JSON file
        $saved = $disk->put(
            $file,
            json_encode(
                $chirps,
                JSON_PRETTY_PRINT |
                JSON_UNESCAPED_UNICODE |
                JSON_THROW_ON_ERROR
            )
        );

        if (!$saved) {
            abort(500, 'Unable to save Chirp.');
        }

        // 7. Redirect with success message
        return redirect()
            ->route('home')
            ->with('success', 'Chirp created successfully!');
    }
}
