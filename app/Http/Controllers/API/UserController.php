<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;

class UserController extends Controller
{
    public $successStatus = 200;

    public function login(){
        $json_arr = [
            'status'    => '',
            'message'   => ''
        ];
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('nApp')->accessToken;

            $json_arr['status']     = 'success';
            $json_arr['message']    = $success;
            return response()->json($json_arr, $this->successStatus);
        } else {
            $json_arr['status']     = 'error';
            $json_arr['message']    = 'Unauthorised';
            return response()->json($json_arr, 401);
        }
    }

    public function logout(){
        $id = Auth::user()->id;

        $delete_token = DB::table('oauth_access_tokens')->where('user_id', $id)->delete();
        if ($delete_token) {
            $json_arr = [
                'status'    => 'success',
                'message'   => 'Logout success'
            ];
        } else {
            $json_arr = [
                'status'    => 'error',
                'message'   => 'Logout failed'
            ];
        }

        return response()->json($json_arr, $this->successStatus);
    }

    public function details()
    {
        $user = Auth::user();
        $json_arr = [
            'status'    => 'success',
            'message'   => '',
            'data'      => $user
        ];
        return response()->json($json_arr, $this->successStatus);
    }
}
