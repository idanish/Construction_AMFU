<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use App\Models\Department;
use App\Models\RequestAttachment;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RequestController extends Controller
{
    // Show all requests
    public function index()
    {
        $requests = RequestModel::with('department', 'requestor', 'attachments')
            ->latest()
            ->get();

        return view('requests.index', compact('requests'));
    }

    // Show create form (all users allowed)
    public function create()
    {
        $departments = Department::all();
        return view('requests.create', compact('departments'));
    }

    // Store new request (all users allowed)
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'description'   => 'required|string',
            'amount'        => 'required|numeric',
            'comments'      => 'nullable|string',
            'attachment.*'  => 'nullable|file|max:102400',
        ]);

        $requestModel = RequestModel::create([
            'department_id' => $request->department_id,
            'description'   => $request->description,
            'amount'        => $request->amount,
            'comments'      => $request->comments,
            'status'        => 'pending',
            'requestor_id'  => auth()->id(),
        ]);

        if ($request->hasFile('attachment')) {
            foreach ($request->file('attachment') as $file) {
                $path = $file->store('attachments', 'public');

                RequestAttachment::create([
                    'request_id' => $requestModel->id,
                    'file_name'  => $file->getClientOriginalName(),
                    'file_path'  => $path,
                    'file_type'  => $file->getClientMimeType(),
                ]);
            }
        }

        return redirect()->route('requests.index')->with('success', 'Request created successfully.');
    }

    // Show details
    public function show($id)
    {
        $requestModel = RequestModel::with('department', 'requestor', 'attachments', 'requestor.roles')
            ->findOrFail($id);

        return view('requests.show', compact('requestModel'));
    }

    // Edit (Admin only)
    public function edit($id)
    {
         if (auth()->user()->role !== 'admin') {
        abort(403, 'Unauthorized action.');
        }

        $requestModel = RequestModel::with('attachments')->findOrFail($id);
        $departments = Department::all();

        return view('requests.edit', compact('requestModel', 'departments'));
    }

    // Update (Admin only)
    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $req = RequestModel::findOrFail($id);

        $data = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'description'   => 'required|string',
            'amount'        => 'required|numeric',
            'comments'      => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:102400',
        ]);

        $req->update($data);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');

                $req->attachments()->create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                ]);
            }
        }

        return redirect()->route('requests.index')->with('success', 'Request updated successfully.');
    }

    // Delete (Admin only)
    public function destroy($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $req = RequestModel::with('attachments')->findOrFail($id);

        foreach ($req->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
            $attachment->delete();
        }

        $req->delete();

        return redirect()->route('requests.index')->with('success', 'Request deleted successfully.');
    }

    // Delete attachment (Admin only)
    public function deleteAttachment($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $attachment = RequestAttachment::findOrFail($id);
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return back()->with('success', 'Attachment deleted successfully.');
    }

    // Add comment (all users allowed)
    public function addComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        Comment::create([
            'request_id' => $id,
            'user_id'    => auth()->id(),
            'comment'    => $request->comment,
        ]);

        return back()->with('success', 'Comment added successfully.');
    }
}
