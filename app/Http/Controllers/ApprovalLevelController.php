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

        return view('approval-levels.index', compact('levels'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('approval-levels.create', compact('departments'));
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
        $level = ApprovalLevel::findOrFail($id);
        $departments = Department::orderBy('name')->get();

        return view('approval-levels.edit', compact('level', 'departments'));
    }

    /**
     * Update approval level
     */
    public function update(Request $req, $id)
    {
        $req->validate([
            'department_id' => 'required|exists:departments,id',
            'name'          => 'required|string|max:255',
            'sequence'      => 'required|integer|min:1',
        ]);

        $level = ApprovalLevel::findOrFail($id);

        $level->update([
            'department_id' => $req->department_id,
            'name'          => $req->name,
            'sequence'      => $req->sequence,
        ]);

        return redirect()->route('approval.levels.index')
                ->with('success', 'Approval level updated.');
    }

    /**
     * Delete approval level
     */
    public function destroy($id)
    {
        $level = ApprovalLevel::findOrFail($id);
        $level->delete();

        return redirect()->route('approval.levels.index')
                ->with('success', 'Approval level deleted.');
    }
}
