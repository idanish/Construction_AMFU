<?php

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
        'year' => 'required|integer',
        'allocated' => 'required|numeric|min:0',
        'requested_budget' => 'required|numeric|min:0',
        'budget_type' => 'required|in:monthly,weekly',
        'spent' => 'nullable|numeric|min:0|lte:allocated',
        'notes' => 'nullable|string',
        'status' => 'required|string',
        'attachment' => 'nullable|array|max:10',
        'attachment.*' => 'file|mimes:JPG,JPEG,PNG,PDF,DOC,DOCX,jpg,jpeg,png,pdf,doc,docx|max:2048',
    ]);

    // 🔹 Check duplicate budgets
    $monthStart = now()->startOfMonth();
    $monthEnd = now()->endOfMonth();

    if($r->budget_type === 'monthly') {
        $existingMonthly = Budget::where('department_id', $r->department_id)
                                 ->where('budget_type', 'monthly')
                                 ->whereBetween('created_at', [$monthStart, $monthEnd])
                                 ->count();
        if($existingMonthly > 0) {
            $dept = \App\Models\Department::find($r->department_id);
            $deptName = $dept ? $dept->name : 'Department';
            return redirect()->back()->with('monthly_conflict', [
                'department_id' => $r->department_id,
                'department_name' => $deptName,
                'year' => $r->year,
                'message' => 'You have already submitted a monthly budget for this month. You can send a special request to admin to request an override.'
            ])->withInput();
        }
    } elseif($r->budget_type === 'weekly') {
        $existingWeekly = Budget::where('department_id', $r->department_id)
                                ->where('budget_type', 'weekly')
                                ->whereBetween('created_at', [$monthStart, $monthEnd])
                                ->count();
        if($existingWeekly >= 4) {
            return redirect()->back()->with('error', 'Is mahine ke liye weekly budget ki limit (4) poori ho chuki hai.');
        }
    }

    // 🔹 Handle attachments (multiple) and keep original filenames
    $paths = null;
    if ($r->hasFile('attachment')) {
        $paths = [];
        foreach ($r->file('attachment') as $file) {
            $stored = $file->store('budgets', 'public');
            $paths[] = [
                'path' => $stored,
                'name' => $file->getClientOriginalName(),
            ];
        }
    }

   Budget::create([
    'department_id' => $r->department_id,
    'year' => $r->year,
    'allocated' => (float)$r->allocated,
    'requested_budget' => (float)$r->requested_budget,
    'budget_type' => $r->budget_type,
    'spent' => (float)($r->spent ?? 0),
    'balance' => (float)($r->allocated - ($r->spent ?? 0)),
    'notes' => $r->notes,
    'status' => $r->status,
    'attachment' => $paths,
]);


    return redirect()->route('finance.budgets.index')->with('success','Budget created successfully!');
}

    /**
     * Handle special override requests when a monthly budget already exists.
     */
    public function requestOverride(Request $r)
    {
        // Log incoming request for debugging 404 issue
        try {
            \Log::info('BudgetController::requestOverride called', ['user_id' => auth()->id(), 'input' => $r->all()]);
        } catch (\Exception $e) {
            // ignore logging errors
        }
        $r->validate([
            'department_id' => 'required|integer|exists:departments,id',
            'year' => 'required|integer',
            'note' => 'nullable|string',
        ]);

        $dept = Department::find($r->department_id);
        $user = auth()->user();
        $note = $r->input('note', '');

        $message = "Special override request by {$user->name} for department {$dept->name} (Year: {$r->year}).";
        if ($note) {
            $message .= " Note: {$note}";
        }

        try {
            // notify admins via helper
            createNotification('Admin', $message);
        } catch (\Exception $e) {
            \Log::error('Failed to create special request notification: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send special request. Please try again.');
        }

        return redirect()->back()->with('success', 'Special request sent to admin.');
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
        'year' => 'required|integer',
        'allocated' => 'required|numeric|min:0',
        'requested_budget' => 'required|numeric|min:0',
        'budget_type' => 'required|in:monthly,weekly',
        'spent' => 'nullable|numeric|min:0|lte:allocated',
        'notes' => 'nullable|string',
        'status' => 'required|string',
        'r_attachment' => 'nullable',
        'attachment' => 'nullable|array|max:10',
        'attachment.*' => 'file|mimes:JPG,JPEG,PNG,PDF,DOC,DOCX,jpg,jpeg,png,pdf,doc,docx|max:2048',

    ]);
// 🔹 Check duplicate budgets
if ($r->budget_type === 'monthly') {

    $existingMonthly = Budget::where('department_id', $r->department_id)
        ->where('budget_type', 'monthly')
        ->whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->when(isset($budget), function ($query) use ($budget) {
            return $query->where('id', '!=', $budget->id); // For update
        })
        ->count();

    if ($existingMonthly > 0) {
        return redirect()->back()->with('error', 'You have already submitted a monthly budget for this month.');
    }

} elseif ($r->budget_type === 'weekly') {

    $existingWeekly = Budget::where('department_id', $r->department_id)
        ->where('budget_type', 'weekly')
        ->whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->when(isset($budget), function ($query) use ($budget) {
            return $query->where('id', '!=', $budget->id); // For update
        })
        ->count();

    if ($existingWeekly >= 4) {
        return redirect()->back()->with('error', 'Is mahine ke liye weekly budget ki limit (4) poori ho chuki hai.');
    }
}



    // 🔹 Update fields
    $budget->department_id = $r->department_id;
    $budget->year = $r->year;
    $budget->allocated = $r->allocated;
    $budget->requested_budget = $r->requested_budget;
    $budget->budget_type = $r->budget_type;
    $budget->spent = $r->spent ?? 0;
    $budget->balance = $r->allocated - ($r->spent ?? 0);
    $budget->notes = $r->notes;
    $budget->status = $r->status;

        if ($r->hasFile('attachment')) {
            $existing = is_array($budget->attachment) ? $budget->attachment : ($budget->attachment ? (json_decode($budget->attachment, true) ?? []) : []);
            try {
                foreach ($r->file('attachment') as $file) {
                    $stored = $file->store('budgets','public');
                    $existing[] = [
                        'path' => $stored,
                        'name' => $file->getClientOriginalName(),
                    ];
                }
            } catch (\Exception $e) {
                \Log::error('Budget attachment upload failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Attachment upload failed. Please try again.');
            }
            $budget->attachment = $existing;
        }




    try {
        $budget->save();
    } catch (\Exception $e) {
        \Log::error('Budget update failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to update budget. Please try again.');
    }

    return redirect()->route('finance.budgets.index')->with('success','Budget updated successfully!');
}

  public function destroy(Budget $budget)
  {
        if ($budget->attachment) {
          $atts = is_array($budget->attachment) ? $budget->attachment : (json_decode($budget->attachment, true) ?? [$budget->attachment]);
          foreach ($atts as $att) {
              $attPath = is_array($att) ? ($att['path'] ?? $att) : $att;
              if (\Storage::disk('public')->exists($attPath)) {
                  \Storage::disk('public')->delete($attPath);
              }
          }
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