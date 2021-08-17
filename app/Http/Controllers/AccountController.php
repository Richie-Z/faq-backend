<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\InvalidOrderException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function store(Request $request)
    {
        $user = $this->user;
        if (count($user->detail()->get()) >= 1)
            return $this->sendResponse('Error Limit reach', null, 422);
        DB::beginTransaction();
        try {
            $user->detail()->create([
                'name' => $request->name
            ]);
            DB::commit();
            return $this->sendResponse('Success', $user->load(['detail']), 200);
        } catch (InvalidOrderException $th) {
            DB::rollback();
            return $this->sendResponse('Error', $th, 500);
        }
    }
    public function update(Request $request)
    {
        $user = $this->user;
        DB::beginTransaction();
        try {
            $user->detail()->update([
                'name' => $request->name
            ]);
            DB::commit();
            return $this->sendResponse('Success Update', $user->load(['detail']), 200);
        } catch (InvalidOrderException $th) {
            DB::rollback();
            return $this->sendResponse('Error', $th, 200);
        }
    }
}
