<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectTeacherController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $subject = Subject::findOrFail($validated['subject_id']);
        $subject->teachers()->attach($validated['user_id']);

        return response()->json(['message' => 'Teacher attached to subject successfully'], 201);
    }

    public function update(string $id, Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $subject = Subject::findOrFail($id);
        $subject->teachers()->detach($validated['user_id']);

        return response()->json(['message' => 'Teacher update from subject successfully'], 200);
    }

    public function destroy(string $id, Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $subject = Subject::findOrFail($validated['subject_id']);
        $subject->teachers()->detach($id);

        return response()->json(['message' => 'Teacher detached from subject successfully'], 200);
    }
}
