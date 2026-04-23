<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductLead;
use Auth;

class AdminLeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $query = ProductLead::with(['product:id,name,slug', 'vendor:id,shop_name'])
            ->orderBy('created_at', 'desc');

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $leads = $query->paginate(20);
        $statuses = ProductLead::STATUSES;

        return view('admin.leads', compact('leads', 'statuses'));
    }

    public function show($id)
    {
        $lead = ProductLead::with(['product', 'vendor'])->findOrFail($id);
        $statuses = ProductLead::STATUSES;

        return view('admin.show_lead', compact('lead', 'statuses'));
    }

    public function updateStatus(Request $request, $id)
    {
        $lead = ProductLead::findOrFail($id);
        
        $validStatuses = array_keys(ProductLead::STATUSES);
        if (!in_array($request->status, $validStatuses)) {
            $notification = trans('Invalid status');
            $notification = array('mesage' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }

        $lead->status = $request->status;
        $lead->save();

        $notification = trans('Status updated successfully');
        $notification = array('mesage' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function destroy($id)
    {
        $lead = ProductLead::findOrFail($id);
        $lead->delete();

        $notification = trans('Lead deleted successfully');
        $notification = array('mesage' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }
}
