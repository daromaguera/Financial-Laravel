<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class CashFlowDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_cfd_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_cfd_dateCreated)->format('M'));
        return [
            'cfd_id' => $this->q_cfd_id,
            'client_id' => $this->q_cfd_clnt_id,
            'cfl_id' => $this->q_cfd_cfl_id,
            'isNeedClient' => intval($this->q_cfd_isNeedsForClient) == 1 ? "Need" : "Want",
            'cfda_client_amount' => $this->q_cfd_cfda_clientAmt,
            'isNeedSpouse' => intval($this->q_cfd_isNeedsForSpouse) == 1 ? "Need" : "Want",
            'cfda_spouse_amount' => $this->q_cfd_cfda_spouseAmt,
            'cfda_client_amount_expense' => $this->q_cfd_cfda_clientAmtExpense,
            'cfda_spouse_amount_expense' => $this->q_cfd_cfda_spouseAmtExpense,
            'target_retirement' => $this->q_cfd_targetRetireAmtInPercent,
            'cfdb_client_amount' => $this->q_cfd_cfdb_clientAmt,
            'cfdb_spouse_amount' => $this->q_cfd_cfdb_spouseAmt,
            'dateCreated' => Carbon::parse($this->q_cfd_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateUpdated' => Carbon::parse($this->q_cfd_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
        ];
    }
}
