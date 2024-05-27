<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use App\Models\Priority;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index()
    {
        // $tasks = Task::with(['categories' => function ($query) {
        //     $query->withPivot('priority_id')->with('priority');
        // }])->get();
        $tasks = Task::all(); 
        return response()->json($tasks);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'start_date' => 'required|date',
        'deadline' => 'required|date',
        'categories' => 'required|array',
        'categories.*.id' => 'required|exists:categories,id',
        'categories.*.priority_id' => 'required|exists:priorities,id',
    ]);

    $task = Task::create($request->only(['title', 'description', 'start_date', 'deadline']));

    foreach ($request->categories as $category) {
        $task->categories()->attach([
            $category['id'] => ['priority_id' => $category['priority_id']]
        ]);
    }

    return response()->json($task->load(['categories']), 201);
}

    /**
     * Display the specified task.
     */
    public function show($id)
    {
        $task = Task::with(['categories' => function ($query) {
            $query->withPivot('priority_id')->with('priority');
        }])->findOrFail($id);

        return response()->json($task);
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'start_date' => 'sometimes|date',
            'deadline' => 'sometimes|date',
            'categories' => 'sometimes|array',
            'categories.*.id' => 'sometimes|exists:categories,id',
            'categories.*.priority_id' => 'sometimes|exists:priorities,id',
        ]);

        $task = Task::findOrFail($id);
        $task->update($request->only(['title', 'description', 'start_date', 'deadline']));

        if ($request->has('categories')) {
            $task->categories()->detach();

            foreach ($request->categories as $category) {
                $task->categories()->attach($category['id'], ['priority_id' => $category['priority_id']]);
            }
        }

        return response()->json($task->load(['categories' => function ($query) {
            $query->withPivot('priority_id')->with('priority');
        }]));
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->categories()->detach();
        $task->delete();

        return response()->json(null, 204);
    }
}
