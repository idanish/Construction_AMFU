<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Department;

class BudgetController extends Controller
{
    /**
     * Display a listing of budgets.
     */
    public function index()
    {
        $budgets = Budget::with('department')->latest()->get();
        return view('finance.budgets.index', compact('budgets'));
    }

    /**
     * Show the form for creating a new budget.
     */
    public function create()
    {
        $departments = Department::all();
        return view('finance.budgets.create', compact('departments'));
    }

    /**
     * Store a newly created budget in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'allocated'     => 'required|numeric|min:0',
            'spent'         => 'required|numeric|min:0',
            'balance'       => 'required|numeric|min:0',
            'status'        => 'required|in:pending,approved,rejected',
        ]);

        Budget::create($validated);

        return redirect()->route('finance.budgets.index')
                         ->with('success', 'Budget added successfully.');
    }

    /**
     * Show the form for editing the specified budget.
     */
    public function edit(Budget $budget)
    {
        $departments = Department::all();
        return view('finance.budgets.edit', compact('budget', 'departments'));
    }

    /**
     * Update the specified budget in storage.
     */
    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'allocated'     => 'required|numeric|min:0',
            'spent'         => 'required|numeric|min:0',
            'balance'       => 'required|numeric|min:0',
            'status'        => 'required|in:pending,approved,rejected',
        ]);

        $budget->update($validated);

        return redirect()->route('finance.budgets.index')
                         ->with('success', 'Budget updated successfully.');
    }

    /**
     * Remove the specified budget from storage.
     */
    public function destroy(Budget $budget)
    {
        $budget->delete();

        return redirect()->route('finance.budgets.index')
                         ->with('success', 'Budget deleted successfully.');
    }
}
