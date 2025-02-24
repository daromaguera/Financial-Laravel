<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientFNResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'client_LastName' => $this->q_clnt_l_name,
            'client_FirstName' => $this->q_clnt_f_name,
            'client_MiddleName' => $this->q_clnt_m_name,
        ];
    }
}
