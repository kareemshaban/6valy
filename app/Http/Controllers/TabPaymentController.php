<?php

namespace App\Http\Controllers;

use App\CPU\CartManager;
use App\CPU\OrderManager;
use App\Model\Order;
use App\Model\ShippingAddress;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class TabPaymentController extends Controller
{
    //
    public function index()
    {
        return "let's do some shit";
    }

    public function card_token(Request $request)
    {


        $apiURL = 'https://api.tap.company/v2/tokens';
        $localIP = getHostByName(getHostName());
        $shipping_addresses = ShippingAddress::where('customer_id', auth('customer')->id())->where('is_billing', 0)->get()->first();
        $postInput = [
            'card' => [
                'number' => $request->number,
                'exp_month' => $request->exp_month,
                'exp_year' => $request->exp_year,
                'cvc' => $request->cvc,
                'name' => $request->name,
                'address' => [
                    'country' => $shipping_addresses['country'],
                    'line1' => $shipping_addresses['address'],
                    'city' => $shipping_addresses['city'],
                    'street' => $shipping_addresses['city'],
                    'avenue' => $shipping_addresses['address_type'],
                ],
                'client_ip' => $localIP
            ]
        ];
        $headers = [
            'Authorization' => 'Bearer sk_test_XKokBfNWv6FIYuTMg5sLPjhJ'
        ];

        $id = '' ;
        try {
            $response = Http::withHeaders($headers)->post($apiURL, $postInput);
        }catch (\Exception $exception){
            return redirect() ->back() -> with('error' , 'Can not complete Payment Process');
        }
         $id = $response['id'];
        return $this -> create_charge($id);


    }

    public function create_charge($token_id)
    {
        $tran = Str::random(6) . '-' . rand(1, 1000);
        $order_id = Order::orderBy('id', 'DESC')->first()->id ?? 100001;
        $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
        $value = CartManager::cart_grand_total() - $discount;
        $user = User::find(auth('customer')->id());
        $apiURL = 'https://api.tap.company/v2/charges';
        $postInput = [
            "amount" => $value,
            "currency" => "USD",
            "'threeDSecure'" => true,
            "save_card" => false,
            "description" => 'Transaction ID: ' . $tran,
            "statement_descriptor" => $order_id,
            "reference" => [
                "transaction" => 'Transaction ID: ' . $tran,
                "order" => $order_id
            ],
            "receipt" => [
                "email" => false,
                "sms" => true
            ],
            "customer" => [
                "first_name" => $user -> name,
                "middle_name" => $user -> name,
                "last_name" =>  $user -> l_name,
                "email" => $user -> email,
                "phone" => [
                    "country_code" => "965",
                    "number" => "50000000"
                ]
            ],
            "merchant" => [
                "id" => ""
            ],
            "source" => [
                "id" => $token_id
            ],
            "redirect" => [
                "url" => route('tapPay_callback'),
            ]
        ];

        $headers = [
            'Authorization' => 'Bearer sk_test_XKokBfNWv6FIYuTMg5sLPjhJ',
            'lang_code' => 'en'
        ];


        $id = '' ;
        try {
            $response = Http::withHeaders($headers)->post($apiURL, $postInput);
        }catch (\Exception $exception){
            return redirect() ->back() -> with('error' , 'Can not complete Payment Process');
        }
        $id = $response['id'];
        $url =$response['transaction']['url'];

        return Redirect::to($url);

    }
    public function callback(Request $request){
       $tap_id = $request['tap_id'];
        $apiURL = 'https://api.tap.company/v2/charges/'. $tap_id;
        $headers = [
            'Authorization' => 'Bearer sk_test_XKokBfNWv6FIYuTMg5sLPjhJ'
        ];

        try {
            $response = Http::withHeaders($headers)->get($apiURL);
        }catch (\Exception $exception){
            return redirect() ->back() -> with('error' , 'Can not complete Payment Process');
        }

        $status = $response['status'];
        if($status == "Authorized"){
            $unique_id = OrderManager::gen_unique_id();
            $order_ids = [];
            foreach (CartManager::get_cart_group_ids() as $group_id) {
                $data = [
                    'payment_method' => 'Tap',
                    'order_status' => 'confirmed',
                    'payment_status' => 'paid',
                    'transaction_ref' => $tap_id,
                    'order_group_id' => $unique_id,
                    'cart_group_id' => $group_id
                ];
                $order_id = OrderManager::generate_order($data);
                array_push($order_ids, $order_id);
            }
            CartManager::cart_clean();
            return redirect()->route('payment-success');
        } else {
            return redirect()->route('payment-fail');
        }

    }
}
