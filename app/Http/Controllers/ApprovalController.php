<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Approval;
use App\Models\RequestModel; 

class ApprovalController extends Controller
{
    /**
     * Show all approvals for a given request
     */
    public function index()
{
    
    $approvals = Approval::with('request')->latest()->get();

    return view('approvals.index', compact('approvals'));
}


    /**
     * Show the form for creating a new approval for a given request
     */
    public function create($requestId)
    {
        $request = RequestModel::findOrFail($requestId);
        return view('approvals.create', compact('request'));
    }

    /**
     * Store a new approval for a request
     */
    public function store(Request $req, $requestId)
    {
        $req->validate([
            'status'   => 'required|in:approve,reject',
            'comments' => 'nullable|string|max:500',
        ]);

        Approval::create([
            'request_id'  => $requestId,
            'approver_id' => auth()->id(),
            'status'      => $req->status,
            'comments'    => $req->comments,
        ]);

        return redirect()
            ->route('approvals.index', $requestId)
            ->with('success', 'Approval submitted successfully.');
    }
}
