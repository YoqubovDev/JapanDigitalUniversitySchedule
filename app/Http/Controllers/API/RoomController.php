<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Room index']);
    }

    public function show($room)
    {
        return response()->json(['message' => 'Room show']);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'Room store']);
    }

    public function update(Request $request, $room)
    {
        return response()->json(['message' => 'Room update']);
    }

    public function destroy($room)
    {
        return response()->json(['message' => 'Room destroy']);
    }
}
