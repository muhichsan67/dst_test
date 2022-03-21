<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public $successStatus = 200;

    public function index(Request $request)
    {
        // dd($request->all());
        $size = $request->size ? $request->size : 10;
        $user_id = Auth()->user()->id;
        $role = DB::table('roles')->where('user_id', $user_id)->first();
        if ($role->role != 'admin') {
            $data = Transaction::where('deleted_at', NULL)->where('user_id', $user_id)->orderBy('created_at', 'desc')->paginate($size)->withQueryString();
        } else {
            $data = Transaction::where('deleted_at', NULL)->orderBy('created_at', 'desc')->paginate($size)->withQueryString();
        }
        // dd($data); 
        return response()->json($data, $this->successStatus);
    }

    public function detail($uuid)
    {
        $data = Transaction::where('deleted_at', NULL)->where('uuid', $uuid)->first();
        return response()->json($data, $this->successStatus);
    }

    public function store(Request $request)
    {
        $json_arr = [
            'status'    => '',
            'message'   => ''
        ];
        $user_id = Auth()->user()->id;
        $role = DB::table('roles')->where('user_id', $user_id)->first();
        if ($role->role == 'admin') {
            $json_arr['status']     = 'error';
            $json_arr['message']    = 'Admin tidak dapat melakukan transaksi';
            return response()->json($json_arr, 401);
        }

        $product = Product::where('uuid', $request->uuid)->first();

        $base_price = $product->price;
        $base_stock = $product->quantity;
        $tax = $base_price * 10/100;
        $admin_fee = ($base_price * 5/100) + $tax;
        $total = ($base_price * $request->amount) + $tax + $admin_fee;

        $after_transaction_stock = $base_stock - $request->amount;
        if ($base_stock <= 0) {
            $json_arr['status']     = 'error';
            $json_arr['message']    = 'Maaf stok kosong';
            return response()->json($json_arr, 401);
        }
        if ($after_transaction_stock < 0) {
            $json_arr['status']     = 'error';
            $json_arr['message']    = 'Maaf amount melebihi stok tersedia';
            return response()->json($json_arr, 401);
        }

        $transaction = Transaction::create([
                    "uuid"  => Str::uuid(),
                    "user_id"  => $user_id,
                    "product_id"  => $product->id,
                    "amount"  => $request->amount,
                    "tax"  => $tax,
                    "admin_fee"  => $admin_fee,
                    "total"  => $total,
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s"),
                ]);
        if ($transaction) {
            $update_stock = DB::table('products')->where('id', $product->id)->update(['quantity' => $after_transaction_stock]);
            $json_arr = [
                'status'    => 'success',
                'message'   => 'Transaction succeed'
            ];
        } else {
            $json_arr = [
                'status'    => 'error',
                'message'   => 'Transaction failed'
            ];
        }
        return response()->json($json_arr, $this->successStatus);
    }
}
