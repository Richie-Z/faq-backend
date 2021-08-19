<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\InvalidOrderException;
use App\Http\Resources\PlanResource;
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
        return $this->sendResponse(null, Plan::all(), 200);
    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|integer',
        ]);
        if ($validate->fails()) return $this->sendResponse('Validation Error', $validate->messages(), 422);
        try {
            $plan = Plan::create($request->all());
            return $this->sendResponse('Success add Plan', $plan, 200);
        } catch (InvalidOrderException $th) {
            return $this->sendResponse("error", $th, 500);
        }
    }
    public function show($id)
    {
        $plan = Plan::findOrFail($id);
        return $this->sendResponse(null, new PlanResource($plan), 200);
    }
    public function update(Request $request, int $id)
    {
        $plan = Plan::findOrFail($id);
        $plan->update($request->all());
        return $this->sendResponse('Success update Plan', $plan, 200);
    }
    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        if ($id == 1) return $this->sendResponse("Error,'$plan->name' plan cannot be deleted", null, 422);
        $plan->delete();
        return $this->sendResponse("Success Delete", null, 200);
    }
}
