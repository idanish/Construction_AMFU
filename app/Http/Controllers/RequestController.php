<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function approve($id) {
    $req = Request::findOrFail($id);
    $req->status = 'approved';
    $req->save();

    createNotification(null, "Your request #{$req->id} approved", $req->user_id, 'approve');
    createNotification('Admin', "Request #{$req->id} approved by ".auth()->user()->name);
}

public function reject($id)
{
    $req = \App\Models\Request::findOrFail($id);
    $req->status = 'rejected';
    $req->save();

    // User ke liye notification
    createNotification(
        null,
        "Your request #{$req->id} rejected",
        $req->user_id,
        'reject'
    );

    // Admin ke liye notification
    createNotification(
        'Admin',
        "Request #{$req->id} rejected by " . auth()->user()->name
    );

    return redirect()->back()->with('error', 'Request rejected successfully.');
}

}
