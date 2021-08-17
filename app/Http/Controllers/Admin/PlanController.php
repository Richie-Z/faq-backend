<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\InvalidOrderException;
use App\Models\Plan;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function index()
    {
    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|integer',
        ]);
        if ($validate->fails()) return $this->sendResponse('Validation Error', $validate->messages(), 422);
        try {
            $plan = Plan::insert($request->all());
            return $this->sendResponse('Success add Plan', $plan, 200);
        } catch (InvalidOrderException $th) {
            return $this->sendResponse("error", $th, 500);
        }
    }
    public function show($id)
    {
    }
    public function update(Request $request, int $id)
    {
        $plan = Plan::findOrFail($id);
        $plan->update($request->all());
        return $this->sendResponse('Success update Plan', $plan, 200);
    }
    public function destroy()
    {
    }
}
