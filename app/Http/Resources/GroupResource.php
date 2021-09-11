<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'faqs' => FaQResource::collection($this->whenLoaded('faq')),
        ];
        $cond = $request->route()[1]['as'] ?? "" == "show_member";
        if (true) {
            $members = json_decode($this->members) ?? [];
            $user = User::whereIn('id', $members)->get();
            $data['members'] = GroupMemberResource::collection($user);
        }
        return $data;
    }
}
