<?php

namespace App\Http\Controllers\Finance;

use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Budget;
use App\Models\User;
use App\Models\Approval;
use App\Models\ApprovalLevel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BudgetController extends Controller
{
    public function index(Request $r)
    {
        $perPage = $r->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $budgetsQuery = Budget::with(['department', 'approvals'])->latest();

        // Department filter
        if ($r->filled('department_id')) {
            $budgetsQuery->where('department_id', $r->department_id);
        }

        // Year filter
        if ($r->filled('year')) {
            $budgetsQuery->where('year', $r->year);
        }

        // Month filter
        if ($r->filled('month')) {
            $budgetsQuery->where('month', $r->month);
        }

        // Status filter
        if ($r->filled('status')) {
            $budgetsQuery->where('status', $r->status);
        }

        $budgets = $budgetsQuery->paginate($perPage);

        // Dropdown ke liye departments list
        $departments = Department::all();

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return view('finance.budgets.index', compact('budgets', 'departments', 'months'));
    }

    public function create()
    {
        $departments = Department::all();
        $users = User::all();
        return view('finance.budgets.create', compact('departments'));
    }

    public function store(Request $r)
    {
        // 1. Validation
        $validatedData = $r->validate([
            'department_id' => 'required|integer|exists:departments,id',
            'year'          => 'required|integer',
            'month'         => 'required|integer|min:1|max:12',
            'allocated'     => 'required|numeric|min:0',
            'spent'         => 'nullable|numeric|min:0|lte:allocated',
            'notes'         => 'nullable|string',
            'status'        => 'required|string',
            'attachments.*' => 'nullable|file|mimes:JPG,JPEG,PNG,PDF,DOC,DOCX,jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $spent = $r->spent ?? 0;
        $budget = Budget::create([
            'department_id' => $r->department_id,
            'year'          => $r->year,
            'month'         => $r->month,
            'allocated'     => $r->allocated,
            'spent'         => $spent,
            'balance'       => $r->allocated - $spent,
            'notes'         => $r->notes,
            'status'        => $r->status,
        ]);

        // Handle attachments
        if ($r->hasFile('attachments')) {
            foreach ($r->file('attachments') as $file) {
                $budget->addMedia($file)->toMediaCollection('attachments');
            }
        }

        // Email Notification - FIXED
        $recipientEmail = auth()->user()->email;
        $allocatedAmount = number_format($budget->allocated, 2);

        // Use single quotes or concatenation to avoid variable interpolation issues
        $emailMessage = "Your budget amounting to $" . $allocatedAmount . " has been added successfully.";

        Mail::raw($emailMessage, function ($message) use ($recipientEmail) {
            $message->to($recipientEmail)
                    ->subject('Budget Added Successfully');
        });

        return redirect()->route('finance.budgets.index')->with('success', 'Budget added successfully!');
    }

    public function show($id)
    {
        $budget = Budget::with(['department', 'approvals.approver'])->findOrFail($id);
        return view('finance.budgets.show', compact('budget'));
    }

    public function edit(Budget $budget)
    {
        $departments = Department::all();
        return view('finance.budgets.edit', compact('budget', 'departments'));
    }

    public function update(Request $r, Budget $budget)
    {
        $r->validate([
            'department_id' => 'required|integer|exists:departments,id',
            'year'          => 'required|integer',
            'month'         => 'required|integer|min:1|max:12',
            'allocated'     => 'required|numeric|min:0',
            'spent'         => 'nullable|numeric|min:0|lte:allocated',
            'status'        => 'required|string',
            'attachments.*' => 'nullable|file|mimes:JPG,JPEG,PNG,PDF,DOC,DOCX,jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        // Check duplicate budgets (if budget_type field exists)
        if ($r->filled('budget_type') && $r->budget_type === 'monthly') {
            $existingMonthly = Budget::where('department_id', $r->department_id)
                ->where('budget_type', 'monthly')
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->where('id', '!=', $budget->id)
                ->count();

            if ($existingMonthly > 0) {
                return redirect()->back()->with('error', 'You have already submitted a monthly budget for this month.');
            }
        } elseif ($r->filled('budget_type') && $r->budget_type === 'weekly') {
            $existingWeekly = Budget::where('department_id', $r->department_id)
                ->where('budget_type', 'weekly')
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->where('id', '!=', $budget->id)
                ->count();

            if ($existingWeekly >= 4) {
                return redirect()->back()->with('error', 'Weekly budget limit (4) for this month has been reached.');
            }
        }

        // Update fields
        $spent = $r->spent ?? 0;
        $budget->update([
            'department_id' => $r->department_id,
            'year'          => $r->year,
            'month'         => $r->month,
            'allocated'     => $r->allocated,
            'spent'         => $spent,
            'balance'       => $r->allocated - $spent,
            'notes'         => $r->notes,
            'status'        => $r->status,
        ]);

        // Handle attachments
        if ($r->hasFile('attachments')) {
            foreach ($r->file('attachments') as $file) {
                $budget->addMedia($file)->toMediaCollection('attachments');
            }
        }

        return redirect()->route('finance.budgets.index')->with('success', 'Budget updated successfully!');
    }

    public function destroy(Budget $budget)
    {
        $budget->clearMediaCollection('attachments');
        $budget->delete();

        return redirect()->route('finance.budgets.index')->with('success', 'Budget deleted successfully!');
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