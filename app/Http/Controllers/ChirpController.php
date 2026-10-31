<?php

namespace App\Http\Controllers;

use App\Services\ChirpFileStore;
use Illuminate\Http\Request;

class ChirpController extends Controller
{
    /**
     * Display all Chirps.
     */
    public function index(ChirpFileStore $store)
    {
        $chirps = $store->all();

        // Newest first
        usort($chirps, function ($a, $b) {
            return strtotime($b['created_at'])
                <=> strtotime($a['created_at']);
        });

        return view('home', compact('chirps'));
    }

    /**
     * Create a new Chirp.
     */
    public function store(
        Request $request,
        ChirpFileStore $store
    ) {
        $validated = $request->validate([
            'content' => 'required|string|max:280',
        ]);

        $store->create($validated['content']);

        return redirect()
            ->route('home')
            ->with('success', 'Chirp created successfully!');
    }

    /**
     * Display the edit form.
     */
    public function edit(
        string $chirp,
        ChirpFileStore $store
    ) {
        $foundChirp = $store->find($chirp);

        if ($foundChirp === null) {
            abort(404, 'Chirp not found.');
        }

        return view('chirps.edit', [
            'chirp' => $foundChirp,
        ]);
    }

    /**
     * Update an existing Chirp.
     */
    public function update(
        Request $request,
        string $chirp,
        ChirpFileStore $store
    ) {
        // Step 1: Validate content
        $validated = $request->validate([
            'content' => 'required|string|max:280',
        ]);

        // Step 2: Update selected Chirp
        $updatedChirp = $store->update(
            $chirp,
            $validated['content']
        );

        // Step 3: Return 404 when UUID is missing
        if ($updatedChirp === null) {
            abort(404, 'Chirp not found.');
        }

        // Step 4: Redirect back to feed
        return redirect()
            ->route('home')
            ->with('success', 'Chirp updated successfully!');
    }
}
