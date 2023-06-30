<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'order_num' => $this->number,
            'order_count' => $this->count,
            'order_cost' => $this->total_cost,
            'order_isClosed' => $this->is_closed,
            'order_closedAt' => $this->closed_at,
            'dishes' => $this->dishes,
            'waiter' => $this->user,
        ];
    }
}
