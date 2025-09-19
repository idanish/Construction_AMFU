<?php

namespace App\Http\Controllers\Finance;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Budget;

class BudgetController extends Controller
{
 public function index(Request $request)
{
    if ($request->ajax()) {
        $data = Budget::with('department')->latest(); 

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('department', function($row){
                return $row->department->name ?? 'N/A';
            })
            ->addColumn('allocated', function($row){
                return number_format($row->allocated, 2);
            })
            ->addColumn('spent', function($row){
                return number_format($row->spent, 2);
            })
            ->addColumn('balance', function($row){
                return number_format($row->allocated - $row->spent, 2);
            })
            ->addColumn('status', function($row){
                return ucfirst($row->status);
            })
            ->addColumn('attachment', function($row){
                if($row->attachment){
                    return '<a href="'.asset('storage/'.$row->attachment).'" target="_blank" class="btn btn-sm btn-info">View</a>';
                }
                return '<span class="badge bg-secondary">No File</span>';
            })
            ->addColumn('action', function($row){
                return '
                    <a href="'.route('finance.budgets.edit',$row->id).'" class="btn btn-sm btn-primary">Edit</a>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'">Delete</button>
                ';
            })
            ->rawColumns(['attachment','action'])
            ->make(true);
    }

    return view('finance.budgets.index');
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
            'year'          => 'required|integer',
            'allocated'     => 'required|numeric|min:0',
            'spent'         => 'nullable|numeric|min:0',
            'notes'         => 'nullable|string',
            'status'        => 'required|string',
            'attachment'    => 'nullable|file|max:5120',
        ]);

        $path = $r->file('attachment') ? $r->file('attachment')->store('budgets','public') : null;

        Budget::create([
            'department_id' => $r->department_id,
            'year'          => $r->year,
            'allocated'     => $r->allocated,
            'spent'         => $r->spent ?? 0,
            'balance'       => $r->allocated - ($r->spent ?? 0),
            'notes'         => $r->notes,
            'status'        => $r->status,
            'attachment'    => $path,
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
            'year'          => 'required|integer',
            'allocated'     => 'required|numeric|min:0',
            'spent'         => 'nullable|numeric|min:0',
            'status'        => 'required|string',
            'attachment'    => 'nullable|file|max:5120',
        ]);

        $budget->department_id = $r->department_id;
        $budget->year          = $r->year;
        $budget->allocated     = $r->allocated;
        $budget->spent         = $r->spent ?? 0;
        $budget->balance       = $budget->allocated - $budget->spent;
        $budget->notes         = $r->notes;
        $budget->status        = $r->status;

        if ($r->hasFile('attachment')) {
            $budget->attachment = $r->file('attachment')->store('budgets','public');
        }

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
    
}