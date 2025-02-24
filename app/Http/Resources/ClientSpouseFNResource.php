<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientSpouseFNResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'client_LastName' => $this->q_famComp_lastName,
            'client_FirstName' => $this->q_famComp_firstName,
            'client_MiddleName' => $this->q_famComp_middleInitial,
        ];
    }
}
