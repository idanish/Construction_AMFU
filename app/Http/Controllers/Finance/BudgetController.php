<?php

// namespace App\Http\Controllers\Finance;
// use Yajra\DataTables\DataTables;
// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Department;
// use App\Models\Budget;

// class BudgetController extends Controller
// {
//  public function index()
// {
//     $budgets = Budget::with('department')->get(); 
//     return view('finance.budgets.index', compact('budgets'));
// }
//     public function create()
//     {
//         $departments = Department::all();
//         return view('finance.budgets.create', compact('departments'));
//     }

//     public function store(Request $r)
//     {
//         $r->validate([
//             'department_id' => 'required|integer|exists:departments,id',
//             'year'          => 'required|integer',
//             'allocated'     => 'required|numeric|min:0',
//             // Spent rule
//             'spent'         => 'nullable|numeric|min:0|lte:allocated',
//             'notes'         => 'nullable|string',
//             'status'        => 'required|in:pending,approved,rejected',
//             'attachment'    => 'nullable|file|max:5120',
//         ]);

//         $path = $r->file('attachment') ? $r->file('attachment')->store('budgets','public') : null;
    
//         $allocated = $data['allocated'];
//         $spent = $data['spent'] ?? 0;


//     Budget::create([

//     'department_id' => $r->department_id,
//     'year' => $r->year,
//     'allocated' => $r->allocated,
//     'spent' => $r->spent ?? 0,
//     'balance' => $r->allocated - $r->spent,
//     'notes' => $r->notes,
//     'status' => $r->status,
//     'attachment' => $path,

//     ]);


//     return redirect()->route('finance.budgets.index')->with('success','Budget created successfully!');

//     }


//     public function edit(Budget $budget)
//     {
//         $departments = Department::all();
//         return view('finance.budgets.edit', compact('budget','departments'));
//     }


//     public function update(Request $r, Budget $budget)
//     {
//         $data = $r->validate([
//             'department_id' => 'required|integer|exists:departments,id',
//             'year'          => 'required|integer',
//             'allocated'     => 'required|numeric|min:0',
//             'spent'         => 'nullable|numeric|min:0|lte:allocated',
//             'notes'         => 'nullable|string',
//             'status'        => 'required|in:pending,approved,rejected',
//             'attachment'    => 'nullable|file|max:5120',
//         ]);

//         // calculation
//         $data['balance'] = $data['allocated'] - ($data['spent'] ?? 0);
        
//         // Attachment handling
//         if ($r->hasFile('attachment')) {
//             $data['attachment'] = $r->file('attachment')->store('budgets','public');
//         }

// calculation
//         $data['balance'] = $data['allocated'] - ($data['spent'] ?? 0);
//         $budget->fill($data);
//         $budget->save();

//         return redirect()->route('finance.budgets.index')->with('success','Budget updated successfully!');
//     }
 


// }





namespace App\Http\Controllers\Finance;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Budget;

class BudgetController extends Controller
{
public function index(Request $r)
{
    $perPage = $r->input('per_page', 10);
    if (!in_array($perPage, [10, 25, 50, 100])) {
        $perPage = 10;
    }

    $budgetsQuery = \App\Models\Budget::with('department')->latest();

    // 🔹 Department filter
    if ($r->filled('department_id')) {
        $budgetsQuery->where('department_id', $r->department_id);
    }

    // 🔹 Year filter
    if ($r->filled('year')) {
        $budgetsQuery->where('year', $r->year);
    }

    // 🔹 Status filter
    if ($r->filled('status')) {
        $budgetsQuery->where('status', $r->status);
    }

    $budgets = $budgetsQuery->paginate($perPage);

    // Dropdown ke liye departments list
    $departments = \App\Models\Department::all();

    return view('finance.budgets.index', compact('budgets', 'departments'));
}
  public function create()
  {
    $departments = Department::all();
    return view('finance.budgets.create', compact('departments'));
  }

  public function store(Request $r)
  {
    $r->validate([
      'department_id' => 'required|integer|exists:departments,id',
      'year'     => 'required|integer',
      'allocated'   => 'required|numeric|min:0',
      'spent'     => 'nullable|numeric|min:0|lte:allocated',
      'notes'     => 'nullable|string',
      'status'    => 'required|string',
      'attachment'  => 'nullable|file|max:5120',
    ]);

    $path = $r->file('attachment') ? $r->file('attachment')->store('budgets','public') : null;

    Budget::create([
      'department_id' => $r->department_id,
      'year'     => $r->year,
      'allocated'   => $r->allocated,
      'spent'     => $r->spent ?? 0,
      'balance'    => $r->allocated - $r->spent,
      'notes'     => $r->notes,
      'status'    => $r->status,
      'attachment'  => $path,
    ]);

    return redirect()->route('finance.budgets.index')->with('success','Budget created successfully!');
  }

  public function edit(Budget $budget)
  {
    $departments = Department::all();
    return view('finance.budgets.edit', compact('budget','departments'));
  }

  public function update(Request $r, Budget $budget)
  {
    $r->validate([
      'department_id' => 'required|integer|exists:departments,id',
      'year'     => 'required|integer',
      'allocated'   => 'required|numeric|min:0',
      'spent'     => 'nullable|numeric|min:0|lte:allocated',
      'status'    => 'required|string',
      'attachment'  => 'nullable|file|max:5120',
    ]);

    $budget->department_id = $r->department_id;
    $budget->year     = $r->year;
    $budget->allocated   = $r->allocated;
    $budget->spent     = $r->spent ?? 0;
    $budget->balance    = $budget->allocated - $budget->spent;
    $budget->notes     = $r->notes;
    $budget->status    = $r->status;

    if ($r->hasFile('attachment')) {
      $budget->attachment = $r->file('attachment')->store('budgets','public');
    }

        // // calculation
        // $data['balance'] = $data['allocated'] - ($data['spent'] ?? 0);
        // $budget->fill($data);


    $budget->save();

    return redirect()->route('finance.budgets.index')->with('success','Budget updated successfully!');
  }

  public function destroy(Budget $budget)
  {
    if ($budget->attachment && \Storage::disk('public')->exists($budget->attachment)) {
      \Storage::disk('public')->delete($budget->attachment);
    }
    $budget->delete();

    return redirect()->route('finance.budgets.index')->with('success','Budget deleted successfully!');
  }
  
// Approved Request
public function updateStatus(Request $request, $id)
{
  
  $request->validate(['status' => 'required|in:approved,rejected']);

  
  $budget = Budget::find($id);

  
  if ($budget) {
    $budget->status = $request->input('status');
    $budget->save();
    
    return redirect()->back()->with('success', 'Budget Status updated successfully!');
  }

  return redirect()->back()->with('error', 'Budget not found!');
}


}