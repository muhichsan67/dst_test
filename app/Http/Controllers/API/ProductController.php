<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public $successStatus = 200;

    public function index(Request $request)
    {

        $size = $request->size ? $request->size : 10;
        $data = Product::where('deleted_at', NULL)->orderBy('created_at', 'desc')->paginate($size)->withQueryString();
        return response()->json($data, $this->successStatus);
    }

    public function detail($uuid)
    {
        $data = Product::where('deleted_at', NULL)->where('uuid', $uuid)->first();
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
        if ($role->role != 'admin') {
            $json_arr['status']     = 'error';
            $json_arr['message']    = 'Unauthorised';
            return response()->json($json_arr, 401);
        }

        $products = Product::create([
                    "uuid"  => Str::uuid(),
                    "name"  => $request->name,
                    "type"  => $request->type,
                    "price"  => $request->price,
                    "quantity"  => $request->quantity,
                    "created_at"    => date("Y-m-d H:i:s"),
                    "updated_at"    => date("Y-m-d H:i:s"),
                ]);
        $json_arr = [
            'status'    => 'success',
            'message'   => 'Store product success'
        ];
        return response()->json($json_arr, $this->successStatus);
    }

    public function delete($uuid)
    {
        $json_arr = [
            'status'    => '',
            'message'   => ''
        ];
        $user_id = Auth()->user()->id;
        $role = DB::table('roles')->where('user_id', $user_id)->first();
        if ($role->role != 'admin') {
            $json_arr['status']     = 'error';
            $json_arr['message']    = 'Unauthorised';
            return response()->json($json_arr, 401);
        }

        $products = Product::where('uuid', $uuid)->delete();
        $json_arr = [
            'status'    => 'success',
            'message'   => 'Delete product success'
        ];
        return response()->json($json_arr, $this->successStatus);
    }
}
