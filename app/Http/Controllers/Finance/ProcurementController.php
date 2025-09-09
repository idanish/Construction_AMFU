<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Department;
use App\Models\Procurement;

class ProcurementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $procurements = Procurement::all();
        return view('finance.procurements.index', compact('procurements')); // plural
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        $budgets = Budget::all();

        return view('finance.procurements.create', compact('departments', 'budgets'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'department_id' => 'required|exists:departments,id',
        'description' => 'nullable|string',
        'status' => 'required|in:pending,approved,rejected',
        'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
    ]);

    $attachmentPath = null;

    if ($request->hasFile('attachment')) {
        // ✅ File save
        $attachmentPath = $request->file('attachment')->store('attachments', 'public');
    }

    Procurement::create([
        'title' => $request->title,
        'department_id' => $request->department_id,
        'description' => $request->description,
        'status' => $request->status,
        'attachment' => $attachmentPath, // yahan path save hoga
    ]);

    return redirect()->route('finance.procurements.index')
        ->with('success', 'Procurement created successfully!');
}




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $procurement = Procurement::findOrFail($id); // singular
        return view('finance.procurements.show', compact('procurement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $procurement = Procurement::findOrFail($id); // singular
        $departments = Department::all();
        $budgets = Budget::all();

        return view('finance.procurements.edit', compact('procurement', 'departments', 'budgets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $request->validate([
        'title'        => 'required|string|max:255',
        'department_id'=> 'required|exists:departments,id',
        'description'  => 'nullable|string',
        'attachment'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'status'       => 'nullable|string|in:pending,approved,rejected',
    ]);

    $procurement = Procurement::findOrFail($id);

    $data = $request->only(['title', 'department_id', 'description', 'status']);

    // ✅ Agar user normal hai to status update na ho
    if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'officer') {
        $data['status'] = $procurement->status; // purana status hi rahega
    }

    // ✅ File upload handle
    if ($request->hasFile('attachment')) {
        $data['attachment'] = $request->file('attachment')->store('procurements', 'public');
    }

    $procurement->update($data);

    return redirect()->route('finance.procurements.index')
                     ->with('success', 'Procurement updated successfully!');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $procurement = Procurement::findOrFail($id); // singular
        $procurement->delete();

        return redirect()->route('finance.procurements.index')
                         ->with('success', 'Procurement deleted successfully!');
    }
}
