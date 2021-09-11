<?php

namespace App\Http\Resources;

use App\Models\Group;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $planInfo = $this->plan->plan()->first();
        $plan =  [
            'name' => $planInfo->name,
            'price' => $planInfo->price,
        ];
        $planInfo->price == 0 ?:   $plan['expires_at'] = $this->plan->expired_at;
        $userInfo = [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
        ];
        if (auth('admin')->check()) {
            $userInfo['created_at'] = $this->created_at;
            $userInfo['updated_at'] = $this->updated_at;
            $userInfo['deleted_at'] = $this->deleted_at;
        }
        $gm = json_decode($this->group_members) ?? [];
        $groupMembers = Group::whereIn('id', $gm)->get();
        return $userInfo + [
            'plan' => $plan,
            'detail' => $this->whenLoaded('detail', function () {
                return ['name' => $this->detail->name];
            }),
            'groups' => GroupResource::collection($this->whenLoaded('group')),
            'groups_member' => GroupResource::collection($groupMembers)
        ];
    }
}
