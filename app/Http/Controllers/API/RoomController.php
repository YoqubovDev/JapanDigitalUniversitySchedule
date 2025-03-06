<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = Room::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->get('search') . '%');
        }

        if ($request->boolean('sort')) {
            $query->orderBy('id', 'desc');
        }

        $rooms = $query->paginate($perPage);

        return response()->json($rooms);
    }

    public function show(Room $room)
    {
        return response()->json($room);
    }
    public function store(StoreRoomRequest $request)
    {
        $validatorDate = $request->validated();
        Room::query()->create($validatorDate);
        return response()->json([
            'message' => 'Room created successfully'
        ],200);
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        $validatorDate = $request->validated();
        $room->update($validatorDate);
        return response()->json([
            'message'=>"Room updated successfully"
        ],201);
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return response()->json(['message' => 'Room deleted successfully']);
    }
}
