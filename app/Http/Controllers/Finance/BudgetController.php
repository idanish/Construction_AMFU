<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Budget;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Budget::with('department')->latest()->paginate(10);
        return view('finance.budgets.index', compact('budgets'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('finance.budgets.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|integer',
            'year'          => 'required|integer',
            'allocated'     => 'required|numeric',
            'spent'         => 'required|numeric',
            'notes'         => 'nullable|string',
            'status'        => 'required|string',
            'attachment'    => 'nullable|file',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('budgets', 'public');
        }

        Budget::create([
            'department_id' => $request->department_id,
            'year'          => $request->year,
            'allocated'     => $request->allocated,
            'spent'         => $request->spent,
            'balance'       => $request->allocated - $request->spent,
            'notes'         => $request->notes,
            'status'        => $request->status,
            'attachment'    => $attachmentPath,
        ]);

        return redirect()->route('finance.budgets.index')
                         ->with('success', 'Budget created successfully!');
    }

    public function edit(Budget $budget)
    {
        $departments = Department::all();
        return view('finance.budgets.edit', compact('budget', 'departments'));
    }

  public function update(Request $request, $id)
{
    $budget = Budget::findOrFail($id);

    $budget->department_id = $request->department_id;
    $budget->year = $request->year;
    $budget->allocated = $request->allocated;
    $budget->spent = $request->spent;
    $budget->balance = $request->balance;
    $budget->notes = $request->notes;
    $budget->status = $request->status;

    if ($request->hasFile('attachment')) {
        $budget->attachment = $request->file('attachment')->store('budgets', 'public');
    }

    $budget->save();

    return redirect()->route('finance.budgets.index')->with('success', 'Budget updated successfully!');
}


    public function destroy(Budget $budget)
    {
        if ($budget->attachment && \Storage::disk('public')->exists($budget->attachment)) {
            \Storage::disk('public')->delete($budget->attachment);
        }

        $budget->delete();

        return redirect()->route('finance.budgets.index')
                         ->with('success', 'Budget deleted successfully!');
    }
}
