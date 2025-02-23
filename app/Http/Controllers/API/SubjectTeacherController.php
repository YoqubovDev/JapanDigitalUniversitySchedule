<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectTeacherController extends Controller
{
    public function attachSubjectToGroup(Request $request, $id)
    {
        $validator = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $subject = Subject::query()->findOrFail($id); // subject_id URL dan olinadi
        $subject->students()->attach($validator['user_id'], ['created_at' => now(), 'updated_at' => now()]);

        return response()->json(
            ['message' => 'Student attached to group'], 201);
    }
}
