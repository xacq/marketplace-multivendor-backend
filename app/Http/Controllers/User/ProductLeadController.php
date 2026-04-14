<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductLead;
use Auth;

class ProductLeadController extends Controller
{
    /**
     * Store a new lead (public — no auth required).
     * Works for both guests and logged-in users.
     */
    public function store(Request $request)
    {
        $rules = [
            'product_id' => 'required|exists:products,id',
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'phone'      => 'nullable|string|max:50',
            'message'    => 'nullable|string|max:2000',
        ];

        $customMessages = [
            'product_id.required' => trans('Product is required'),
            'product_id.exists'   => trans('Product not found'),
            'name.required'       => trans('Name is required'),
            'email.required'      => trans('Email is required'),
            'email.email'         => trans('Please provide a valid email'),
        ];

        $this->validate($request, $rules, $customMessages);

        $product = Product::find($request->product_id);

        if (!$product || $product->product_type !== 'contact') {
            return response()->json(['message' => trans('This product does not accept contact requests')], 422);
        }

        // Detect if there's an authenticated user from the token
        $userId = null;
        try {
            $user = Auth::guard('api')->user();
            if ($user) {
                $userId = $user->id;
            }
        } catch (\Exception $e) {
            // Guest, no token
        }

        $lead = ProductLead::create([
            'product_id' => $product->id,
            'vendor_id'  => $product->vendor_id,
            'user_id'    => $userId,
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'message'    => $request->message,
            'status'     => 'new',
        ]);

        return response()->json([
            'message' => trans('Your message has been sent successfully. The seller will contact you soon.'),
            'lead_id' => $lead->id,
        ], 201);
    }
}
