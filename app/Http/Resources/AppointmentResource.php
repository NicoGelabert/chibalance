<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'first_name'      => $this->first_name,
            'last_name'       => $this->last_name,
            'email'           => $this->email,
            'contact_number'  => $this->contact_number,
            'product_id'      => $this->product_id,
            'product_title'   => $this->product->title ?? null,
            'date'            => $this->date ? (new \DateTime($this->date))->format('Y-m-d') : null,
            'start_time'      => $this->start_time ? (new \DateTime($this->start_time))->format('H:i') : null,
            'end_time'        => $this->end_time ? (new \DateTime($this->end_time))->format('H:i') : null,
            'status'          => $this->status,
            'notes'           => $this->notes,
            'cancel_token'    => $this->cancel_token,
            'created_by'      => $this->created_by,
            'updated_by'      => $this->updated_by,
            'created_at'      => $this->created_at ? (new \DateTime($this->created_at))->format('Y-m-d H:i:s') : null,
            'updated_at'      => $this->updated_at ? (new \DateTime($this->updated_at))->format('Y-m-d H:i:s') : null,
        ];
    }
}
