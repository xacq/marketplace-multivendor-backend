<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductLead;
use App\Models\Vendor;
use Auth;

class SellerLeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:vendor-api');
    }

    private function getVendor()
    {
        return Auth::guard('vendor-api')->user();
    }

    /**
     * List all leads for the authenticated vendor.
     */
    public function index(Request $request)
    {
        $vendor = $this->getVendor();

        $query = ProductLead::with('product:id,name,thumb_image,slug')
            ->where('vendor_id', $vendor->id)
            ->orderBy('created_at', 'desc');

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        $leads = $query->paginate(20);

        return response()->json([
            'leads'    => $leads,
            'statuses' => ProductLead::STATUSES,
            'colors'   => ProductLead::STATUS_COLORS,
        ], 200);
    }

    /**
     * Show a single lead detail.
     */
    public function show($id)
    {
        $vendor = $this->getVendor();
        $lead = ProductLead::with('product:id,name,thumb_image,slug')
            ->where('vendor_id', $vendor->id)
            ->findOrFail($id);

        return response()->json(['lead' => $lead], 200);
    }

    /**
     * Update the status of a lead.
     */
    public function updateStatus(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $lead = ProductLead::where('vendor_id', $vendor->id)->findOrFail($id);

        $validStatuses = array_keys(ProductLead::STATUSES);
        if (!in_array($request->status, $validStatuses)) {
            return response()->json(['message' => trans('Invalid status')], 422);
        }

        $lead->status = $request->status;
        $lead->save();

        return response()->json([
            'message' => trans('Status updated successfully'),
            'lead'    => $lead,
        ], 200);
    }

    /**
     * Delete a lead.
     */
    public function destroy($id)
    {
        $vendor = $this->getVendor();
        $lead = ProductLead::where('vendor_id', $vendor->id)->findOrFail($id);
        $lead->delete();

        return response()->json(['message' => trans('Lead deleted successfully')], 200);
    }
}
