<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Exceptions\InvalidOrderException;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserController extends Controller
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

    public function index()
    {
        return $this->sendResponse(null,  User::all(), 200);
    }
    public function show($id)
    {
        $user = User::findOrFail($id);
        return $this->sendResponse(null, new UserResource($user->load(['detail', 'group', 'group.faq', 'group.faq.answerQuestion'])), 200);
    }
    public function banned()
    {
        $user = new User;
        return $this->sendResponse(null, $user->onlyTrashed()->get(), 200);
    }
    public function ban($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return $this->sendResponse("Success Ban User", null, 200);
    }
    public function unban($id)
    {
        $user = User::withTrashed()->find($id);
        $user->restore();
        return $this->sendResponse("Success Unban User", null, 200);
    }
    public function destroy($id)
    {
        $user = User::withTrashed()->find($id);
        $user->delete();
        return $this->sendResponse("Success delete User", null, 200);
    }
}
