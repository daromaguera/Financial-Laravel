<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class CashFlowAnalysisResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_cfa_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_cfa_dateCreated)->format('M'));
        return [
            'cfa_id' => $this->q_cfa_id,
            'client_id' => $this->q_cfa_clnt_id,
            'target_cashinflow_client' => $this->q_cfa_targetCashInF_client,
            'target_cashinflow_spouse' => $this->q_cfa_targetCashInF_spouse,
            'target_cashoutflow_client' => $this->q_cfa_targetCashOutF_client,
            'target_cashoutflow_spouse' => $this->q_cfa_targetCashOutF_spouse,
            'clientshare_rfn' => $this->q_cfa_clientShareRFN,
            'spouseshare_rfn' => $this->q_cfa_spouseShareRFN,
            'expected_savings' => $this->q_cfa_expectedSavings,
            'goes_well' => $this->q_cfa_goesWell,
            'reduce_cf_attempt' => $this->q_cfa_reduceCFAttempt,
            'dateCreated' => Carbon::parse($this->q_cfa_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateUpdated' => Carbon::parse($this->q_cfa_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
        ];
    }
}
