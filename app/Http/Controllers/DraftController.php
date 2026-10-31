<?php

namespace App\Http\Controllers;

use App\Models\FormDraft;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DraftController extends Controller
{
    public function show(Request $request, string $key): JsonResponse
    {
        $draft = FormDraft::query()
            ->where('user_id', $request->user()->id)
            ->where('form_key', $key)
            ->first();

        return response()->json(['payload' => $draft?->payload]);
    }

    public function store(Request $request, string $key): JsonResponse
    {
        abort_unless(preg_match('/^[a-z0-9._-]+$/', $key) === 1, 404);

        $validated = $request->validate([
            'payload' => ['required', 'array'],
        ]);

        $draft = FormDraft::updateOrCreate(
            ['user_id' => $request->user()->id, 'form_key' => $key],
            ['payload' => $validated['payload']],
        );

        return response()->json([
            'saved_at' => $draft->updated_at->toIso8601String(),
        ]);
    }
}
