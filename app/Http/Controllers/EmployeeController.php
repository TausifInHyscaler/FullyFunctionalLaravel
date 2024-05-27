<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    // public function index()
    // {
    //     $employees = Employee::all();
    //     return view('employees.index', ['employees' => $employees]);
    // }

    // public function create()
    // {
    //     return view('employees.create');
    // }

    // public function store(Request $request)
    // {
    //     // Validate incoming request data
    //     $validatedData = $request->validate([
    //         'taskName' => 'required|string|max:255',
    //         'title' => 'required|string|max:255',
    //         'description' => 'required|string',
    //     ]);

    //     // Create a new Employee instance
    //     $employee = new Employee();
        
    //     // Generate a 6-digit random number for id
    //     $employee->id = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

    //     // Assign other attributes
    //     $employee->taskName = $validatedData['taskName'];
    //     $employee->title = $validatedData['title'];
    //     $employee->description = $validatedData['description'];

    //     // Set created_at to current time
    //     $employee->created_at = now();

    //     // Save the employee
    //     $employee->save();

    //     // Redirect to index page with success message
    //     return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    // }


    // public function edit($id)
    // {
    //     $employee = Employee::findOrFail($id);
    //     return view('employees.edit', ['employee' => $employee]);
    // }

    // public function update(Request $request, $id)
    // {
    //     // Validate incoming request data
    //     $validatedData = $request->validate([
    //         'taskName' => 'required|string|max:255',
    //         'title' => 'required|string|max:255',
    //         'description' => 'required|string',
    //     ]);

    //     // Find the employee
    //     $employee = Employee::findOrFail($id);

    //     // Update attributes
    //     $employee->taskName = $validatedData['taskName'];
    //     $employee->title = $validatedData['title'];
    //     $employee->description = $validatedData['description'];

    //     // Set updated_at to current time
    //     $employee->updated_at = now();

    //     // Save the employee
    //     $employee->save();

    //     // Redirect to index page with success message
    //     return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    // }

    // public function destroy($id)
    // {
    //     // Find and delete the employee
    //     $employee = Employee::findOrFail($id);
    //     $employee->delete();

    //     // Redirect to index page with success message
    //     return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    // }



    //API CONTROLLER
    public function index()
    {
        $employees = Employee::all();
        return response()->json($employees);
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'taskName' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Create a new Employee instance
        $employee = new Employee();
        
        // Generate a 6-digit random number for id
        $employee->id = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

        // Assign other attributes
        $employee->taskName = $validatedData['taskName'];
        $employee->title = $validatedData['title'];
        $employee->description = $validatedData['description'];

        // Set created_at to current time
        $employee->created_at = now();

        // Save the employee
        $employee->save();

        // Return the created employee as JSON
        return response()->json($employee, 201);
    }

    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        return response()->json($employee);
    }

    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'taskName' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Find the employee
        $employee = Employee::findOrFail($id);

        // Update attributes
        $employee->taskName = $validatedData['taskName'];
        $employee->title = $validatedData['title'];
        $employee->description = $validatedData['description'];

        // Set updated_at to current time
        $employee->updated_at = now();

        // Save the employee
        $employee->save();

        // Return the updated employee as JSON
        return response()->json($employee);
    }

    public function destroy($id)
    {
        // Find and delete the employee
        $employee = Employee::findOrFail($id);
        $employee->delete();

        // Return a success response
        return response()->json(['message' => 'Employee deleted successfully.']);
    }
}
