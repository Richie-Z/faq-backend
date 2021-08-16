<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Exceptions\InvalidOrderException;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function login(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);
        if ($validate->fails()) return $this->sendResponse('Validation Error', $validate->messages(), 422);
        $credential = request(['username', 'password']);
        $token = auth('admin')->attempt($credential);
        return $token ? $this->sendToken($token, 'admin') :  $this->sendResponse('Error,Wrong Email/Password', null, 401);
    }
    public function logout()
    {
        try {
            auth('admin')->logout();
            return $this->sendResponse('Success Logout', null, 200);
        } catch (InvalidOrderException $th) {
            return $this->sendResponse('Error Logout', $th, 401);
        }
    }
    public function detail()
    {
        try {
            return $this->sendResponse(null, auth('admin')->user(), 200);
        } catch (InvalidOrderException $th) {
            return $this->sendResponse('Error ', $th, 401);
        }
    }
}
