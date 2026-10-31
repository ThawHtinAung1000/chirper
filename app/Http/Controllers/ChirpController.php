<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChirpController extends Controller
{
    /**
     * Feature 3: Display all Chirps
     */
    public function index()
    {
        $disk = Storage::disk('local');
        $file = 'chirps.json';

        // Step 1: Read existing Chirps
        if ($disk->exists($file)) {
            $chirps = json_decode(
                $disk->get($file),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } else {
            $chirps = [];
        }

        // Step 2: Sort newest first
        usort($chirps, function ($a, $b) {
            return strtotime($b['created_at'])
                <=> strtotime($a['created_at']);
        });

        // Step 3: Pass Chirps to Blade
        return view('home', [
            'chirps' => $chirps
        ]);
    }

    /**
     * Feature 2: Create a Chirp
     */
    public function store(Request $request)
    {
        // Step 1: Validate
        $validated = $request->validate([
            'content' => 'required|string|max:280',
        ]);

        $disk = Storage::disk('local');
        $file = 'chirps.json';

        // Step 2: Read existing Chirps
        $chirps = $disk->exists($file)
            ? json_decode(
                $disk->get($file),
                true,
                512,
                JSON_THROW_ON_ERROR
            )
            : [];

        // Step 3: Create Chirp
        $chirp = [
            'id' => (string) Str::uuid(),
            'author' => 'Guest',
            'content' => $validated['content'],
            'created_at' => now()->toIso8601String(),
        ];

        // Step 4: Add new Chirp
        array_unshift($chirps, $chirp);

        // Step 5: Save to JSON
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

        return redirect()
            ->route('home')
            ->with('success', 'Chirp created successfully!');
    }
}
