<?php

namespace App\Http\Controllers;

use App\Models\NoteFeedback;
use Illuminate\Http\Request;

class NoteFeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'context' => 'required|string|in:module,question',
            'module_id' => 'required_if:context,module,question|exists:modules,id',
            'question_id' => 'required_if:context,question|exists:questions,id',
            'helpful' => 'required|boolean',
            'source' => 'nullable|string|max:64',
        ]);

        $user = $request->user();

        $payload = [
            'user_id' => $user?->id,
            'context' => $validated['context'],
            'module_id' => $validated['module_id'] ?? null,
            'question_id' => $validated['question_id'] ?? null,
            'source' => $validated['source'] ?? 'learn',
        ];

        NoteFeedback::updateOrCreate($payload, [
            'helpful' => (bool) $validated['helpful'],
        ]);

        $message = $validated['helpful']
            ? 'Glad this note helped!'
            : 'Thanks, we will use that to improve future notes.';

        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => $message,
            ]);
        }

        return back()->with([
            'status' => 'ok',
            'message' => $message,
        ]);
    }
}
