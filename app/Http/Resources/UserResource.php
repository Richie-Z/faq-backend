<?php

namespace App\Http\Resources;

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
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'plan' => $plan,
            'detail' => $this->whenLoaded('detail', function () {
                return ['name' => $this->detail->name];
            }),
            'groups' => GroupResource::collection($this->whenLoaded('group'))
        ];
    }
}
