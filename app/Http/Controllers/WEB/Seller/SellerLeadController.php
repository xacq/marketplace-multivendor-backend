<?php

namespace App\Http\Controllers\WEB\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductLead;
use Auth;

class SellerLeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    private function getVendor()
    {
        return Auth::guard('web')->user()->seller;
    }

    public function index(Request $request)
    {
        $seller = $this->getVendor();

        $query = ProductLead::with('product:id,name,slug')
            ->where('vendor_id', $seller->id)
            ->orderBy('created_at', 'desc');

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $leads = $query->paginate(20);
        $statuses = ProductLead::STATUSES;

        return view('seller.leads', compact('leads', 'statuses'));
    }

    public function show($id)
    {
        $seller = $this->getVendor();
        $lead = ProductLead::with('product')->where('vendor_id', $seller->id)->findOrFail($id);
        $statuses = ProductLead::STATUSES;

        return view('seller.show_lead', compact('lead', 'statuses'));
    }

    public function updateStatus(Request $request, $id)
    {
        $seller = $this->getVendor();
        $lead = ProductLead::where('vendor_id', $seller->id)->findOrFail($id);
        
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
        $seller = $this->getVendor();
        $lead = ProductLead::where('vendor_id', $seller->id)->findOrFail($id);
        $lead->delete();

        $notification = trans('Lead deleted successfully');
        $notification = array('mesage' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }
}
