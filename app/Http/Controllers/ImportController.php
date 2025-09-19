<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function importExcel(Request $r)
{
    $r->validate([
        'file'=>'required|mimes:xlsx,xls,csv|max:5120',
        'type'=>'nullable|string' // e.g. invoices, budgets etc.
    ]);

    // store file for later processing
    $path = $r->file('file')->store('imports');

    return response()->json(['message'=>'File uploaded & validated','path'=>$path]);
}

}
