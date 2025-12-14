<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Procurement;
use App\Models\ProcurementApproval;
use App\Models\Department;
use App\Events\ProcurementApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Approval;
use App\Models\ApprovalLevel;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProcurementController extends Controller
{
    public function index(Request $request)
{
    $query = Procurement::with('department');

    //  Filter by Department
    if ($request->filled('department_id')) {
        $query->where('department_id', $request->department_id);
    }

    //  Filter by Status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    //  Filter by Item Name (search)
    if ($request->filled('search')) {
        $query->where('item_name', 'like', '%' . $request->search . '%');
    }

    //  Pagination (10 per page)
    $procurements = $query->orderBy('id')->paginate(05);

    // Departments dropdown ke liye
    $departments = \App\Models\Department::all();

    return view('finance.procurements.index', compact('procurements', 'departments'));
}
   public function create()
    {
        $departments = Department::all();
        $users = User::all();
        return view('finance.procurements.create', compact('departments'));
    }

   public function store(Request $request)
    {
        $data = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'cost_estimate' => 'required|numeric|min:0',
            'department_id' => 'nullable|exists:departments,id',
            'justification' => 'nullable|string',
            'attachment'     => 'nullable|file|mimes:JPG,JPEG,PNG,PDF,DOC,DOCX,jpg,jpeg,png,pdf,doc,docx|max:2048',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        
        $procurement = Procurement::create($data);

    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {
            $procurement->addMedia($file)->toMediaCollection('attachments'); // Attach to Model
        }
    }

    $recipientEmail = auth()->user()->email;

    Mail::raw("Your procurement request {$procurement->item_name} has been submitted successfully.",
        function ($message) use ($recipientEmail) {
            $message->to($recipientEmail) ->subject('Procurement Submitted Successfully');
        });

        return redirect()->route('finance.procurements.index')->with('success', 'Procurement created successfully!');
    }


public function show($id)
{
    $procurement = Procurement::with('department', 'media')->findOrFail($id);
    return view('finance.procurements.show', compact('procurement'));
}





    public function edit($id)
    {
        $procurement = Procurement::findOrFail($id);
        $departments = Department::all();
        return view('finance.procurements.edit', compact('procurement', 'departments'));
    }

   public function update(Request $r, $id)
{
    $proc = Procurement::findOrFail($id);

    $r->validate([
        'item_name'     => 'required|string|max:255',
        'quantity'      => 'required|numeric|min:1',
        'cost_estimate' => 'required|numeric|min:0',
        'department_id' => 'required|exists:departments,id',
        'justification' => 'nullable|string',
        'attachment'     => 'nullable|file|mimes:JPG,JPEG,PNG,PDF,DOC,DOCX,jpg,jpeg,png,pdf,doc,docx|max:2048',
        'status'        => 'required|in:pending,approved,rejected'
    ]);

    $data = $r->only(['item_name','quantity','cost_estimate','department_id','justification','status']);


     // Handling attachments
    if ($r->hasFile('attachments')) {

        // Optional: Remove existing attachments if "replace all" logic
        if ($r->input('replace_attachments')) {
            $proc->clearMediaCollection('attachments');
        }

        foreach ($r->file('attachments') as $file) {
            $proc->addMedia($file)->toMediaCollection('attachments');
        }
    }

    $proc->update($data);

    $recipientEmail = auth()->user()->email;

    Mail::raw("Your procurement request {$proc->item_name} has been updated successfully.",
        function ($message) use ($recipientEmail) {
            $message->to($recipientEmail) ->subject('Procurement updated successfully');
        });

    return redirect()->route('finance.procurements.index')->with('success','Procurement updated successfully!');
}


    public function destroy($id)
    {
        $proc = Procurement::findOrFail($id);
        // delete stored attachments if any
        if ($proc->attachment) {
            $atts = is_array($proc->attachment) ? $proc->attachment : (json_decode($proc->attachment, true) ?? [$proc->attachment]);
            foreach ($atts as $att) {
                $attPath = is_array($att) ? ($att['path'] ?? $att) : $att;
                if (\Storage::disk('public')->exists($attPath)) {
                    \Storage::disk('public')->delete($attPath);
                }
            }
        }

        $proc->delete();

        return redirect()->route('finance.procurements.index')->with('success','Procurement deleted successfully!');
    }

    // Approved Request
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);

        $procurement = Procurement::find($id);

        if ($procurement) {
            $procurement->status = $request->input('status');
            $procurement->save();
            return redirect()->back()->with('success', 'Status updated successfully!');
        }

        return redirect()->back()->with('error', 'Procurement not found!');
    }

}