<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Query banayi with relations
            $data = RequestModel::with(['requestor', 'department'])->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('requestor_name', function ($row) {
                    return $row->requestor->name ?? 'N/A';
                })
                ->addColumn('department_name', function ($row) {
                    return $row->department->department_name ?? 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $btn  = '<a href="'.route('requests.show', $row->id).'" class="btn btn-sm btn-info">View</a> ';
                    $btn .= '<a href="'.route('requests.edit', $row->id).'" class="btn btn-sm btn-primary">Edit</a> ';
                    $btn .= '<form action="'.route('requests.destroy', $row->id).'" method="POST" style="display:inline-block">';
                    $btn .= csrf_field();
                    $btn .= method_field("DELETE");
                    $btn .= '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>';
                    $btn .= '</form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('requests.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('requests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'requestor_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        RequestModel::create($request->all());

        return redirect()->route('requests.index')->with('success', 'Request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $request = RequestModel::with(['requestor', 'department'])->findOrFail($id);
        return view('requests.show', compact('request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $request = RequestModel::findOrFail($id);
        return view('requests.edit', compact('request'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'requestor_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $req = RequestModel::findOrFail($id);
        $req->update($request->all());

        return redirect()->route('requests.index')->with('success', 'Request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $req = RequestModel::findOrFail($id);
        $req->delete();

        return redirect()->route('requests.index')->with('success', 'Request deleted successfully.');
    }

   


}
