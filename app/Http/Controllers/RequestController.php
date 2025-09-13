<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use App\Models\Department;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index()
    {
        $requests = RequestModel::with('department', 'requestor')->latest()->get();
        return view('requests.index', compact('requests'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('requests.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'description'   => 'required|string',
            'amount'        => 'required|numeric',
            'comments'      => 'nullable|string',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,docx'
        ]);

        $data['requestor_id'] = auth()->id();
        $data['status'] = 'pending';

        $req = RequestModel::create($data);

        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $req->addMedia($file)->toMediaCollection('attachments');
            }
        }

        return redirect()->route('requests.index')->with('success', 'Request created successfully.');
    }

    public function show($id)
    {
        $requestModel = RequestModel::with('department', 'requestor', 'media')->findOrFail($id);
        return view('requests.show', compact('requestModel'));
    }

    public function edit($id)
    {
        $requestModel = RequestModel::findOrFail($id);
        $departments = Department::all();
        return view('requests.edit', compact('requestModel', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $req = RequestModel::findOrFail($id);

        $data = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'description'   => 'required|string',
            'amount'        => 'required|numeric',
            'comments'      => 'nullable|string',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,docx'
        ]);

        $req->update($data);

        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $req->addMedia($file)->toMediaCollection('attachments');
            }
        }

        return redirect()->route('requests.index')->with('success', 'Request updated successfully.');
    }

    public function destroy($id)
    {
        $req = RequestModel::findOrFail($id);
        $req->delete();
        return redirect()->route('requests.index')->with('success', 'Request deleted successfully.');
    }

    public function approve($id)
    {
        $req = RequestModel::findOrFail($id);
        $req->status = 'approved';
        $req->save();

        if (function_exists('createNotification')) {
            createNotification(null, "Your request #{$req->id} approved", $req->requestor_id, 'approve');
            createNotification('Admin', "Request #{$req->id} approved by " . (auth()->user()?->name ?? 'System'));
        }

        return redirect()->back()->with('success', 'Request approved successfully.');
    }

    public function reject($id)
    {
        $req = RequestModel::findOrFail($id);
        $req->status = 'rejected';
        $req->save();

        if (function_exists('createNotification')) {
            createNotification(null, "Your request #{$req->id} rejected", $req->requestor_id, 'reject');
            createNotification('Admin', "Request #{$req->id} rejected by " . (auth()->user()?->name ?? 'System'));
        }

        return redirect()->back()->with('error', 'Request rejected successfully.');
    }
}
