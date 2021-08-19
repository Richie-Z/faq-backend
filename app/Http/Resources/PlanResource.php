<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    private function getUser($carry, $item)
    {
        $user = User::findOrFail($item['user_id'])->toArray();
        $carry[] = array_filter($user, fn ($key) => in_array($key, ['id', 'username']), ARRAY_FILTER_USE_KEY);
        return $carry;
    }
    public function toArray($request)
    {
        $users = $this->users()->get()->toArray();
        $user = array_reduce($users, array($this, "getUser"));
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'users' => $user,
        ];
    }
}
