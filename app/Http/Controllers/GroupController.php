<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\InvalidOrderException;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->group = auth()->user()->group();
    }
    private function getGroup($array)
    {
        $carry = [];
        foreach ($array as $value) {
            $carry[] = array_filter($value, fn ($key) => in_array($key, ['id', 'name', 'code']), ARRAY_FILTER_USE_KEY);
        }
        return $carry;
    }
    public function index()
    {
        return $this->sendResponse(null, $this->getGroup($this->group->select('id', 'name', 'code')->get()->toArray()), 200);
        // return $this->sendResponse(null, $this->group->select('id', 'name', 'code')->get()->toArray(), 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);
        if ($validate->fails()) return $this->sendResponse('Validation Error', $validate->messages(), 422);
        DB::beginTransaction();
        try {
            $code = "G" . Str::random(6);
            $group = $this->group->create([
                'name' => $request->name,
                'code' => $code
            ]);
            DB::commit();
            return $this->sendResponse('Success create group', $group, 200);
        } catch (InvalidOrderException $th) {
            DB::rollback();
            return $this->sendResponse("Error ", $th, 500);
        }
    }
    public function show($id)
    {
        $group = Group::findOrFail($id);
        return $this->sendResponse(null, new GroupResource($group->load('faq')), 200);
    }
    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $group->update($request->only(['name']));
        return $this->sendResponse('Success update Group', $group, 200);
    }
    public function destroy($id, Request $request)
    {
        $group = Group::findOrFail($id);
        $method = $request->method ? $request->method : (empty($group->deleted_at) ? "soft" : "hard");
        if ($method == "soft") {
            $group->delete();
        } elseif ($method == "hard") {
            $group->forceDelete();
        }
        return $this->sendResponse("Success $method deleting Group", null, 200);
    }
    public function getTrashed()
    {
        return $this->sendResponse(null, $this->getGroup($this->group->select('id', 'name', 'code')->onlyTrashed()->get()->toArray()), 200);
    }
    public function restoreTrashed($id)
    {
        $group = Group::withTrashed()->where('id', $id);
        if ($group->count() == 0)
            abort(404);
        $group->restore();
        return $this->sendResponse("Success restore Group", null, 200);
    }
}
