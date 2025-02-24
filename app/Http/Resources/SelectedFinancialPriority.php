<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\FinancialPriorities;
use App\Http\Resources\ClientResource;
use App\Http\Resources\FinancialPrioritiesResource;

class SelectedFinancialPriority extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly = strtoupper(Carbon::parse($this->q_sfp_dateCreated)->format('M'));
        return [
            'sfp_id' => $this->q_sfp_id,
            'fplist_id' => $this->q_sfp_fp_id,
            'rank' => $this->q_sfp_rank,
            'reason' => $this->q_sfp_reason,
            'selected_financial_priority' => FinancialPrioritiesResource::collection(FinancialPriorities::where('q_fp_id',$this->q_sfp_fp_id)->get()),
            'dateCreated' => Carbon::parse($this->q_sfp_dateCreated)->format(strcmp($monthOnly,'MAY') == 0 ? 'd, M Y' : 'd, M. Y')
        ];
    }
}
