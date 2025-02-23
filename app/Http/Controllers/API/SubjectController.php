<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = Subject::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->get('search') . '%');
        }

        if ($request->boolean('sort_by_date')) {
            $query->orderBy('id', 'desc');
        }

        $subjects = $query->paginate($perPage);

        return response()->json($subjects);
    }


    public function show(Subject $subject)
    {
        return response()->json($subject);
    }
    public function store(Request $request)
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
            'name' => 'required|unique:subjects,name,' . $subject->id ,
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
