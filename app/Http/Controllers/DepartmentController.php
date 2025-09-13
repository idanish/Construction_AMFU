<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // Department list show karne ke liye
    public function index()
    {
        $departments = Department::all();
        return view('departments.index', compact('departments'));
    }

    // Department create form
    public function create()
    {
        return view('departments.create');
    }

    // Department save/store
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Department::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('departments.index')
                         ->with('success', 'Department created successfully.');
    }

    // Department edit form
    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    // Department update
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $department->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('departments.index')
                         ->with('success', 'Department updated successfully.');
    }

    // Department delete
    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.index')
                         ->with('success', 'Department deleted successfully.');
    }

    // Report page open karne ke liye
    public function requestReport()
    {
        $departments = Department::all(); // ✅ yahan se pass ho raha hai
        return view('reports.request-report', compact('departments'));
    }

    // Report generate karne ke liye
    public function generateRequestReport(Request $request)
    {
        $departmentId = $request->department_id;
        $fromDate     = $request->from_date;
        $toDate       = $request->to_date;

        // Yahan apni report banane ki logic likho
        return back()->with('success', 'Report generated!');
    }
}
