<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appoval;
use App\Models\Auditlog;
use App\Models\Budget;
use App\Models\Department;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Procurement;
use App\Models\RequestModel;
use App\Models\Role;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\Setting;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return redirect()->back();
        }

        // Budget model mein search logic
        // Text columns ko LIKE se, aur numeric columns ko barabari se dhoondhein
        $budgets = Budget::where('status', 'like', "%{$query}%")
                         ->when(is_numeric($query), function ($q) use ($query) {
                             $q->orWhere('allocated', '=', $query)
                               ->orWhere('spent', '=', $query)
                               ->orWhere('balance', '=', $query);
                         })
                         ->get();

        // Invoice model mein search logic
        // Invoice number aur status text hain, jabke amount numeric hai
        $invoices = Invoice::where('invoice_no', 'like', "%{$query}%")
                           ->orWhere('status', 'like', "%{$query}%")
                           ->when(is_numeric($query), function ($q) use ($query) {
                               $q->orWhere('amount', '=', $query)
                                 ->orWhere('request_id', '=', $query);
                           })
                           ->get();

        return view('search.results', [
            'query'    => $query,
            'budgets'  => $budgets,
            'invoices' => $invoices,
        ]);
    }
}