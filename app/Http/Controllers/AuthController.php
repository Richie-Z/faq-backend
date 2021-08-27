<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Exceptions\InvalidOrderException;
use App\Http\Resources\UserResource;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->plan = new Plan();
    }

    public function register(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'email' => 'required|string|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string'
        ]);
        if ($validate->fails()) return $this->sendResponse('Validation Error', $validate->messages(), 422);
        DB::beginTransaction();
        try {
            $user = new User();
            $user->username = $req->username;
            $user->email = $req->email;
            $user->password = app('hash')->make($req->password);
            $user->save();
            $user->plan()->create([
                'plan_id' => $this->plan->freeId(),
                'expires_at' => Carbon::now()->addYear(1),
            ]);
            DB::commit();
            return $this->sendResponse('Success Register', $user, 200);
        } catch (InvalidOrderException $th) {
            DB::rollback();
            return $this->sendResponse('Error Register', $th, 200);
        }
    }
    public function login(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        if ($validate->fails()) return $this->sendResponse('Validation Error', $validate->messages(), 422);
        $credential = request(['email', 'password']);
        $token = auth()->attempt($credential);
        return $token ? $this->sendToken($token) :  $this->sendResponse('Error,Wrong Email/Password', null, 401);
    }
    public function logout()
    {
        try {
            auth()->logout();
            return $this->sendResponse('Success Logout', null, 200);
        } catch (InvalidOrderException $th) {
            return $this->sendResponse('Error Logout', $th, 401);
        }
    }
    public function detail()
    {
        return $this->sendResponse(null, new UserResource(auth()->user()->load(['detail', 'group'])), 200);
    }
}
