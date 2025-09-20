<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use App\Models\Department;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = RequestModel::with(['requestor', 'department'])->latest();
            
            return DataTables::of($data->get()) // .get() is needed here for collection
                ->addIndexColumn()
                ->addColumn('requestor_name', function($row){
                    return $row->requestor->name ?? 'N/A';
                })
                ->addColumn('department_name', function($row){
                    return $row->department->name ?? 'N/A';
                })
                ->addColumn('status', function($row){
                    if($row->status == 'pending'){
                        return '<span class="badge bg-warning">Pending</span>';
                    } elseif($row->status == 'approved'){
                        return '<span class="badge bg-success">Approved</span>';
                    } else {
                        return '<span class="badge bg-danger">Rejected</span>';
                    }
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('requests.show', $row->id).'" class="btn btn-info btn-sm">Show</a> ';
                    if ($row->status === 'rejected') {
                        $btn .= '<a href="'.route('requests.edit', $row->id).'" class="btn btn-warning btn-sm">Edit</a> ';
                    }
                    $btn .= '<form action="'.route('requests.destroy', $row->id).'" method="POST" style="display:inline">';
                    $btn .= csrf_field();
                    $btn .= method_field('DELETE');
                    $btn .= '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</button>';
                    $btn .= '</form>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        
        return view('requests.index');
    }

    public function create()
    {
        $departments = Department::all();
        return view('requests.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        RequestModel::create([
            'requestor_id' => auth()->id(),
            'department_id' => $request->department_id,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        return redirect()->route('requests.index')->with('success', 'Request has been submitted successfully!');
    }

    public function show(RequestModel $request)
    {
        
        return view('requests.show', compact('request'));
    }

    public function edit(RequestModel $request)
    {
        if ($request->status !== 'rejected') {
            return back()->with('error', 'Only rejected requests can be edited.');
        }

        $departments = Department::all();
        return view('requests.edit', compact('request', 'departments'));
    }

    public function update(Request $request, RequestModel $requestModel)
    {
        if ($requestModel->status !== 'rejected') {
            return back()->with('error', 'Only rejected requests can be updated.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $requestModel->update([
            'title' => $request->title,
            'department_id' => $request->department_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'status' => 'pending',
            'comments' => null, 
        ]);

        return redirect()->route('requests.index')->with('success', 'Request has been updated and resubmitted successfully.');
    }

}