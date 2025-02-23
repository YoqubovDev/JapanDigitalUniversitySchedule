<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupStudentController extends Controller
{
    public function attachGroupToStudent(Request $request, $id)
    {
        $validator = $request->validate([
            'student_id' => 'required',
        ]);

        $group = Group::query()->findOrFail($id); // group_id URL dan olinadi
        $group->students()->attach($validator['user_id'], ['created_at' => now(), 'updated_at' => now()]);

        return response()->json(
            ['message' => 'Student attached to group'], 201);
    }
}
