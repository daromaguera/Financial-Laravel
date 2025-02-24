<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class AdvActResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly = strtoupper(Carbon::parse($this->q_advAct_dateCreated)->format('M'));
        return [
            'advAct_id' => $this->q_advAct_id,
            'client' => [
                'First_Name' => $this->q_clnt_f_name,
                'Middle_Name' => $this->q_clnt_m_name,
                'Last_Name' => $this->q_clnt_l_name,
            ],
            'activity_details' => $this->q_advAct_actDescription,
            'dateCreated' => Carbon::parse($this->q_advAct_dateCreated)->format(strcmp($monthOnly,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
        ];
    }
}
