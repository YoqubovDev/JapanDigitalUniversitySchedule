<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGroupMemberRequest;
use App\Http\Requests\UpdateGroupMemberRequest;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupMemberController extends Controller
{
    public function store(StoreGroupMemberRequest $request)
    {
        $group = Group::find($request->group_id);
        $group->users()->attach($request->user_id);
        return response()->json(['message' => 'Student attached to group successfully'], 201);
    }

    public function update(UpdateGroupMemberRequest $request, $id)
    {
        $group = Group::findOrFail($id);
        $group->users()->detach($request->user_id);
        return response()->json(['message' => 'Student update from group successfully']);
    }

    public function destroy(Request $request, $id)
    {
        $request->validate(['group_id' => 'required|exists:groups,id']);
        $group = Group::findOrFail($request->group_id);
        $group->users()->detach($id);
        return response()->json(['message' => 'Student detached from group successfully']);
    }
}
