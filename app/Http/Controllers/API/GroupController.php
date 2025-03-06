<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
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

    public function store(StoreGroupRequest $request)
    {
        $validatorDate = $request->validated();
        Group::query()->create($validatorDate);
        return response()->json([
            'message' => 'Group created successfully'
        ], 200);
    }

    public function update(UpdateGroupRequest $request, Group $group)
    {
        // Validate the request data
        $validatedData = $request->validated();

        // Update the group with the validated data
        $group->update($validatedData);

        // Return the response with status code 200 (OK)
        return response()->json([
            'message' => 'Group updated successfully',
        ], 200);
    }




    public function destroy( Group $group)
    {
        $group->delete();
        return response()->json(['message' => 'Group deleted successfully']);
    }
}
