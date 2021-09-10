<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;
use App\Exceptions\InvalidOrderException;
use Illuminate\Support\Facades\DB;

class GroupMemberController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $duplicate = false;
    private $no_item = false;
    private function checkValueJSON($json, $item): bool
    {
        return array_search($item, $json) != false;
    }
    private function addIntoJSON($s, $item)
    {
        $json = json_decode($s);
        if ($json) {
            if ($this->checkValueJSON($json, $item)) {
                $json[] = $item;
                return $json;
            }
            $this->duplicate = true;
        }
        $json = [$item];
        return $json;
    }
    private function removeValueJSON($s, $item)
    {
        $json = json_decode($s);
        if ($this->checkValueJSON($json, $item)) {
            $new = array_diff($json, [$item]);
            return $new;
        }
        $this->no_item = true;
        return $json;
    }
    public function showMember($id)
    {
    }
    public function addMember(Request $request, $id)
    {
        $id_user = $request->id_user;
        $group = Group::findOrFail($id);
        if ($group->user_id == $id_user) {
            return $this->sendResponse("Error, Group owner cannot be added", null, 400);
        }
        $user = User::findOrFail($id_user);
        $group_members = $this->addIntoJSON($group->members, $id_user);
        $user_members = $this->addIntoJSON($user->groups_member, $id);
        if ($this->duplicate) {
            return $this->sendResponse("Error, Member duplicate", null, 400);
        }
        DB::beginTransaction();
        try {
            $group->update([
                'members' => json_encode($group_members)
            ]);
            $user->update([
                'groups_member' => json_encode($user_members)
            ]);
            DB::commit();
            return $this->sendResponse("Success add $user->username into $group->name", null, 200);
        } catch (InvalidOrderException $th) {
            DB::rollback();
            return $this->sendResponse("Error", $th, 400);
        }
    }
    public function removeMember($id, $id_mem)
    {
        $group = Group::findOrFail($id);
        if (is_null($group->members)) {
            return $this->sendResponse("Error, Group doesnt have Members", null, 400);
        }
        $user = User::findOrFail($id_mem);
        $group_members = $this->removeValueJSON($group->members, $id_mem);
        $user_members = $this->removeValueJSON($user->groups_member, $id);
        if ($this->duplicate) {
            return $this->sendResponse("Error, Member not found", null, 400);
        }
    }
}
