<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department; 
use App\Models\Budget;  

class BudgetController extends Controller
{
    /**
     * Show budgets list
     */
    public function index()
    {
        $budgets = Budget::with('department')->latest()->paginate(10);
        return view('finance.budgets.index', compact('budgets'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $departments = Department::all();
        return view('finance.budgets.create', compact('departments'));
    }

    /**
     * Store new budget
     */
  public function store(Request $request)
{
    // Validation
    $request->validate([
        'title' => 'required|string|max:255',
        'department_id' => 'required|integer',
        'allocated' => 'required|numeric',
        'spent' => 'required|numeric',
        'status' => 'required|string|max:50',
        'attachment' => 'nullable|file|mimes:pdf,jpg,png',
        'transaction_no' => 'nullable|string|max:255',
    ]);

    // File upload (agar attachment hai)
    $attachmentPath = null;
    if ($request->hasFile('attachment')) {
        $attachmentPath = $request->file('attachment')->store('attachments');
    }

    // Budget create
    $budget = new Budget();
    $budget->title = $request->title;
    $budget->department_id = $request->department_id;
    $budget->allocated = $request->allocated;
    $budget->spent = $request->spent;
    
    // Balance calculate
    $budget->balance = $request->allocated - $request->spent;
    
    $budget->status = $request->status;
    $budget->attachment = $attachmentPath;
    $budget->transaction_no = $request->transaction_no;

    $budget->save();

    // ✅ Redirect to index page with success message
    return redirect()->route('finance.budgets.index')->with('success', 'Budget added successfully!');
}



    /**
     * Show edit form
     */
    public function edit(Budget $budget)
    {
        $departments = Department::all();
        return view('finance.budgets.edit', compact('budget','departments'));
    }

    /**
     * Update budget
     */
  public function update(Request $request, Budget $budget)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'department_id' => 'required|exists:departments,id',
        'allocated' => 'required|numeric|min:0',
        'spent' => 'nullable|numeric|min:0',
        'status' => 'required|string|in:Pending,Approved,Rejected',
        'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx,xlsx|max:2048',
    ]);

    $status = (auth()->check() && auth()->user()->role === 'admin') 
        ? $request->status 
        : 'Pending';

    $spent = $request->spent ?? 0;

    $data = [
        'title' => $request->title,
        'department_id' => $request->department_id,
        'allocated' => $request->allocated,
        'spent' => $spent,
        'balance' => $request->allocated - $spent,
        'status' => $status,
    ];

    if ($request->hasFile('attachment')) {
        $data['attachment'] = $request->file('attachment')->store('attachments','public');
    }

    $budget->update($data);

    return redirect()->route('budgets.index')
                 ->with('success', 'Budget updated successfully!');

}
    /**
     * Delete budget
     */
    public function destroy(Budget $budget)
    {
        $budget->delete();
        return redirect()->route('finance.budgets.index')
            ->with('success', 'Budget deleted successfully!');
    }
}
