<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;


class GroupSubjectController extends Controller
{
    public function store(Request $request, $id)
    {
        $validator = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $group = Group::query()->findOrFail($id); // group_id URL dan olinadi
        $group->subjects()->attach($validator['subject_id'], ['created_at' => now(), 'updated_at' => now()]);

        return response()->json(
            ['message' => 'Subject attached to group'], 201);
    }



}
