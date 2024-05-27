<?php

namespace App\Http\Controllers;
use App\Models\Priority;
use Illuminate\Http\Request;

class PriorityController extends Controller
{
    public function index()
    {
        return Priority::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        return Priority::create($request->all());
    }

    public function show(Priority $priority)
    {
        return $priority;
    }

    public function update(Request $request, Priority $priority)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $priority->update($request->all());

        return $priority;
    }

    public function destroy(Priority $priority)
    {
        $priority->delete();

        return response()->noContent();
    }
}
