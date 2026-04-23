<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\BreadcrumbImage;

use Auth;

use App\Models\Country;

use App\Models\CountryState;

use App\Models\City;

use App\Models\Address;

use App\Models\Vendor;

use App\Models\Setting;

use App\Models\Wishlist;

use App\Models\StripePayment;

use App\Models\RazorpayPayment;

use App\Models\Flutterwave;

use App\Models\PaystackAndMollie;

use App\Models\BankPayment;

use App\Models\InstamojoPayment;

use App\Models\PaypalPayment;

use App\Models\ShoppingCart;

use App\Models\SslcommerzPayment;

use App\Models\Coupon;

use App\Models\Shipping;
use App\Models\DeunaPayment;

use Cart;

use Session;

class CheckoutController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function checkout(Request $request){
        $user = Auth::guard('api')->user();
        $cartProductsQuery = ShoppingCart::with('product','variants.variantItem')
            ->where('user_id', $user->id);

        if ($request->vendor_id) {
            $cartProductsQuery->whereHas('product', function($q) use ($request) {
                $q->where('vendor_id', $request->vendor_id);
            });
        }

        $cartProducts = $cartProductsQuery->select('id','product_id','qty')->get();

        if($cartProducts->count() == 0){
            $notification = trans('Your shopping cart is empty');
            return response()->json(['message' => $notification],403);
        }

        $addresses = Address::with('country','countryState','city')->where(['user_id' => $user->id])->get();
        $shippings = Shipping::all();
        $couponOffer = '';
        if($request->coupon){
            $coupon = Coupon::where(['code' => $request->coupon, 'status' => 1])->first();
            if($coupon){
                if($coupon->expired_date >= date('Y-m-d')){
                    if($coupon->apply_qty <  $coupon->max_quantity ){
                        $couponOffer = $coupon;
                    }
                }
            }
        }

        $stripePaymentInfo = StripePayment::first();
        $razorpayPaymentInfo = RazorpayPayment::first();
        $flutterwavePaymentInfo = Flutterwave::first();
        $paypalPaymentInfo = PaypalPayment::first();
        $bankPaymentInfo = BankPayment::first();
        $paystackAndMollie = PaystackAndMollie::first();
        $instamojo = InstamojoPayment::first();
        $sslcommerz = SslcommerzPayment::first();
        $deunaPaymentInfo = DeunaPayment::first();

        // Vendor Specific Overrides
        if ($request->vendor_id) {
            $vendor = Vendor::find($request->vendor_id);
            if ($vendor) {
                // Override Bank Info
                if ($vendor->bank_account_info && $bankPaymentInfo) {
                    $bankPaymentInfo->account_info = $vendor->bank_account_info;
                    $bankPaymentInfo->is_vendor_override = true;
                }

                // Stripe Connect Destination
                if ($vendor->stripe_account_id && $stripePaymentInfo) {
                    $stripePaymentInfo->stripe_account_id = $vendor->stripe_account_id;
                    $stripePaymentInfo->is_connected = true;
                }

                // Deuna Logic
                if ($deunaPaymentInfo) {
                    $deunaPaymentInfo->vendor_configured = false;
                    $deunaPaymentInfo->dynamic_link = null;

                    if ($vendor->deuna_api_key && $vendor->deuna_api_secret) {
                        // Logic for dynamic link would go here
                        // For now, we flag it as configured
                        $deunaPaymentInfo->vendor_configured = true;
                        $deunaPaymentInfo->dynamic_link = "DYNAMIC_GENERATION_REQUIRED"; 
                    } elseif ($vendor->deuna_link) {
                        $deunaPaymentInfo->vendor_configured = true;
                        $deunaPaymentInfo->dynamic_link = $vendor->deuna_link;
                    }
                }
            }
        }

        return response()->json([
            'cartProducts' => $cartProducts,
            'addresses' => $addresses,
            'shippings' => $shippings,
            'couponOffer' => $couponOffer,
            'stripePaymentInfo' => $stripePaymentInfo,
            'razorpayPaymentInfo' => $razorpayPaymentInfo,
            'flutterwavePaymentInfo' => $flutterwavePaymentInfo,
            'paypalPaymentInfo' => $paypalPaymentInfo,
            'bankPaymentInfo' => $bankPaymentInfo,
            'paystackAndMollie' => $paystackAndMollie,
            'instamojo' => $instamojo,
            'sslcommerz' => $sslcommerz,
            'deunaPaymentInfo' => $deunaPaymentInfo,
        ],200);
    }

    public function deunaDynamicLink(Request $request) {
        $user = Auth::guard('api')->user();
        $vendorId = $request->vendor_id;
        $vendor = Vendor::find($vendorId);

        if (!$vendor || !$vendor->deuna_api_key) {
            return response()->json(['message' => 'Vendor not configured for Deuna'], 422);
        }

        // Calculate Total for this specific vendor
        $cartProducts = ShoppingCart::with('product')
            ->where('user_id', $user->id)
            ->whereHas('product', function($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })->get();

        $subTotal = 0;
        foreach($cartProducts as $cartProduct) {
            $price = $cartProduct->product->offer_price ?: $cartProduct->product->price;
            $subTotal += $price * $cartProduct->qty;
        }

        // Shipping and Coupon (Simplified for placeholder)
        $total = $subTotal;
        if($request->shipping_method_id) {
            $shipping = Shipping::find($request->shipping_method_id);
            if($shipping) $total += $shipping->shipping_fee;
        }

        if($request->coupon) {
            $coupon = Coupon::where(['code' => $request->coupon, 'status' => 1])->first();
            if($coupon && $coupon->expired_date >= date('Y-m-d')) {
                if($coupon->offer_type == '1') { // percentage
                    $total -= ($coupon->discount / 100) * $total;
                } else { // amount
                    $total -= $coupon->discount;
                }
            }
        }

        // MOCK DEUNA API LOGIC
        // In a real scenario, you would call Deuna API here using $vendor->deuna_api_key
        // to get a unique sessionId or checkout link.
        $dynamicLink = "https://pay.deuna.com/checkout?merchant_id=" . $vendor->deuna_api_key . "&amount=" . $total . "&order_ref=" . time();

        return response()->json(['link' => $dynamicLink], 200);
    }
}
