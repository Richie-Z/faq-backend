<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class FaQResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'answer_question' => AnswerQuestion::collection($this->whenLoaded('answerQuestion'))
        ];
    }
}
