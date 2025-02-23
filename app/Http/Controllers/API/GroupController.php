<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Subject;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $groups = Group::query()->paginate($perPage);
        return response()->json($groups);
    }

    public function show(Group $group)
    {
        return response()->json($group);
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255|min:3',
        ]);
        Group::query()->create($validator);
        return response()->json([
            'message' => 'Group created successfully'
        ], 201);
    }

    public function update(Request $request, $group)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255|min:3',
        ]);
        $group->update($validator);
        return response()->json([
            'message' => 'Group updated successfully'
        ], 201);

    }

    public function destroy( Group $group)
    {
        $group->delete();
        return response()->json(['message' => 'Group destroy']);
    }
}
