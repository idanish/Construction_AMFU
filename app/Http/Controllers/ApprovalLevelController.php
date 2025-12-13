<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\ApprovalLevel;
use App\Models\Department;
use App\Models\RequestModel;
use Illuminate\Http\Request;

class ApprovalLevelController extends Controller
{
    /**
     * Show list
     */
    public function index()
    {
        $levels = ApprovalLevel::with('department')
                    ->orderBy('department_id')
                    ->orderBy('sequence')
                    ->get();

        return view('admin.approval-levels.index', compact('levels'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.approval-levels.create', compact('departments'));
    }

    /**
     * Store a new approval level
     */
    public function store(Request $req)
    {
        $req->validate([
            'department_id' => 'required|exists:departments,id',
            'name'          => 'required|string|max:255',
            'sequence'      => 'required|integer|min:1',
        ]);

        ApprovalLevel::create([
            'department_id' => $req->department_id,
            'name'          => $req->name,
            'sequence'      => $req->sequence,
        ]);

        return redirect()->route('approval.levels.index')
                ->with('success', 'Approval level added successfully.');
    }

    /**
     * Edit form
     */
    public function edit($id)
    {
        $level = ApprovalLevel::with(['department', 'users'])->findOrFail($id);
        $departments = Department::orderBy('name')->get();
        
        // Check if this level is being used in any active requests
        $activeRequestsCount = RequestModel::where('department_id', $level->department_id)
            ->where('current_level', $level->sequence)
            ->where('status', 'pending')
            ->count();
        
        return view('admin.approval-levels.edit', compact('level', 'departments', 'activeRequestsCount'));
    }

    public function update(Request $req, $id)
    {
        $level = ApprovalLevel::findOrFail($id);
        
        $req->validate([
            'department_id' => 'required|exists:departments,id',
            'name'          => 'required|string|max:255',
            'sequence'      => 'required|integer|min:1',
        ]);

        // Check if sequence is changing and if it will cause conflicts
        $sequenceChanged = $level->sequence != $req->sequence;
        $departmentChanged = $level->department_id != $req->department_id;
        
        if ($sequenceChanged || $departmentChanged) {
            // Check for duplicate sequence in the same department
            $duplicateExists = ApprovalLevel::where('department_id', $req->department_id)
                ->where('sequence', $req->sequence)
                ->where('id', '!=', $id)
                ->exists();
            
            if ($duplicateExists) {
                return back()->with('error', 'Sequence number already exists for this department.');
            }
            
            // Check if there are active requests at this level
            $activeRequests = RequestModel::where('department_id', $level->department_id)
                ->where('current_level', $level->sequence)
                ->where('status', 'pending')
                ->count();
            
            if ($activeRequests > 0) {
                return back()->with('warning', 
                    "Cannot modify sequence/department. {$activeRequests} active request(s) are currently at this level.");
            }
        }

        $level->update([
            'department_id' => $req->department_id,
            'name'          => $req->name,
            'sequence'      => $req->sequence,
        ]);

        return redirect()->route('approval.levels.index')
                ->with('success', 'Approval level updated successfully.');
    }

    public function destroy($id)
    {
        $level = ApprovalLevel::findOrFail($id);
        
        // Check if this level has any associated approvals
        $hasApprovals = Approval::whereHas('request', function($query) use ($level) {
            $query->where('department_id', $level->department_id);
        })->where('level', $level->sequence)->exists();
        
        if ($hasApprovals) {
            return redirect()->route('approval.levels.index')
                ->with('error', 'Cannot delete this level. It has associated approval records.');
        }
        
        // Check if there are active requests at this level
        $activeRequests = RequestModel::where('department_id', $level->department_id)
            ->where('current_level', $level->sequence)
            ->where('status', 'pending')
            ->count();
        
        if ($activeRequests > 0) {
            return redirect()->route('approval.levels.index')
                ->with('error', "Cannot delete this level. {$activeRequests} active request(s) are currently at this level.");
        }
        
        // Detach users based on relationship type
        // If Many-to-Many relationship (with pivot table)
        if (method_exists($level->users(), 'detach')) {
            $level->users()->detach();
        }
        // If HasMany relationship, you might want to handle it differently
        // For example: nullify the foreign key or prevent deletion if users exist
        else if ($level->users()->exists()) {
            return redirect()->route('approval.levels.index')
                ->with('error', 'Cannot delete this level. It has associated users.');
        }
        
        $level->delete();

        return redirect()->route('approval.levels.index')
                ->with('success', 'Approval level deleted successfully.');
    }
}