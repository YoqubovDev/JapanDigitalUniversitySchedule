<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Group index']);
    }

    public function show($group)
    {
        return response()->json(['message' => 'Group show']);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'Group store']);
    }

    public function update(Request $request, $group)
    {
        return response()->json(['message' => 'Group update']);
    }

    public function destroy($group)
    {
        return response()->json(['message' => 'Group destroy']);
    }
}
