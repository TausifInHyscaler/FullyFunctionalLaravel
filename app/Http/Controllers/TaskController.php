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
        $tasks = Task::all();

        $tasksData = $tasks->map(function ($task) {
            $taskData = $task->toArray();
            $categoriesWithPriorities = [];

            foreach ($task->categoryTasks as $categoryTask) {
                $categoriesWithPriorities[] = [
                    'category' => $categoryTask->category->toArray(),
                    'priority' => $categoryTask->priority->toArray(),
                ];
            }

            $taskData['categories_with_priorities'] = $categoriesWithPriorities;
            return $taskData;
        });

        return response()->json($tasksData);
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
        'categories' => 'nullable|array', // Make categories optional
        'categories.*.id' => 'sometimes|exists:categories,id', // Validate only if categories are provided
        'categories.*.priority_id' => 'sometimes|exists:priorities,id', // Validate only if priorities are provided
    ]);

    $task = Task::create($request->only(['title', 'description', 'start_date', 'deadline']));

    // Attach categories only if they are provided
    if ($request->has('categories')) {
        foreach ($request->categories as $category) {
            $task->categories()->attach([
                $category['id'] => ['priority_id' => $category['priority_id']]
            ]);
        }
    }

    return response()->json($task->load(['categories']), 201);
}

    /**
     * Display the specified task.
     */
    // public function show($id)
    // {
    //     $task = Task::with(['categories' => function ($query) {
    //         $query->withPivot('priority_id')->with('priority');
    //     }])->findOrFail($id);

    //     return response()->json($task);
    // }
    public function show($id)
    {
        $task = Task::findOrFail($id);

        $taskData = $task->toArray();
        $categoriesWithPriorities = [];

        foreach ($task->categoryTasks as $categoryTask) {
            $categoriesWithPriorities[] = [
                'category' => $categoryTask->category->toArray(),
                'priority' => $categoryTask->priority->toArray(),
            ];
        }

        $taskData['categories_with_priorities'] = $categoriesWithPriorities;

        return response()->json($taskData);
    }
    /**
     * Update the specified task in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'title' => 'sometimes|string|max:255',
    //         'description' => 'sometimes|string',
    //         'start_date' => 'sometimes|date',
    //         'deadline' => 'sometimes|date',
    //         'categories' => 'sometimes|array',
    //         'categories.*.id' => 'sometimes|exists:categories,id',
    //         'categories.*.priority_id' => 'sometimes|exists:priorities,id',
    //     ]);

    //     $task = Task::findOrFail($id);
    //     $task->update($request->only(['title', 'description', 'start_date', 'deadline']));

    //     if ($request->has('categories')) {
    //         $task->categories()->detach();

    //         foreach ($request->categories as $category) {
    //             $task->categories()->attach($category['id'], ['priority_id' => $category['priority_id']]);
    //         }
    //     }

    //     return response()->json($task->load(['categories' => function ($query) {
    //         $query->withPivot('priority_id')->with('priority');
    //     }]));
    // }
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
            $query->withPivot('priority_id');
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

        return response()->json($task, 204);
    }
}
