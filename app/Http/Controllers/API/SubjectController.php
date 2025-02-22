<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $subject = Subject::withCount('subjects')
            ->where('user_id', auth()->user()->id);
        if (request()->has('search')) {
            $subject->where('name', 'like', '%' . request('search') . '%')
                ->orWhere('room', 'like', '%' . request('search') . '%');
        }
        if (request()->has('sort_by_date')) {
            $subject->orderBy('id', 'desc');
        }
       $perPage = $request->get('per_page', 10);
       $subjects = Subject::query()->paginate($perPage);
       return response()->json($subjects);
    }

    public function show(Subject $subject)
    {
        return response()->json($subject);
    }
    public function create(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255|min:3',
        ]);
        Subject::query()->create($validator);
        return response()->json([
            'message' => 'Subject created successfully'
        ], 201);
    }

    public function update(Request $request, Subject $subject)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255|min:3',
        ]);
        $subject->update($validator);
        return response()->json([
            'message' => 'Subject updated successfully'
        ], 201);

    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return response()->json([
            'message' => 'Subject deleted successfully'
        ], 201);
    }
}
