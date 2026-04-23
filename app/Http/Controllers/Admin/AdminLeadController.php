<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductLead;

class AdminLeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    /**
     * List all leads for the admin.
     */
    public function index(Request $request)
    {
        $query = ProductLead::with(['product:id,name,thumb_image,slug', 'vendor:id,shop_name'])
            ->orderBy('created_at', 'desc');

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->vendor_id) {
            $query->where('vendor_id', $request->vendor_id);
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
        $lead = ProductLead::with(['product:id,name,thumb_image,slug', 'vendor:id,shop_name'])
            ->findOrFail($id);

        return response()->json(['lead' => $lead], 200);
    }

    /**
     * Update the status of a lead.
     */
    public function updateStatus(Request $request, $id)
    {
        $lead = ProductLead::findOrFail($id);

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
        $lead = ProductLead::findOrFail($id);
        $lead->delete();

        return response()->json(['message' => trans('Lead deleted successfully')], 200);
    }
}
