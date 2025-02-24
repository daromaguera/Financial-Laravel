<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class CashFlowListResource extends JsonResource
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
            'cfl_id' => $this->q_cfl_id,
            'cfl_description' => $this->q_cfl_descripx,
            'cashflow_type' => intval($this->q_cfl_type) == 0 ? "Inflow" : "Outflow",
            'dateCreated' => Carbon::parse($this->q_cfl_dateCreated)->format('d, M. Y'),
            'is_other' => intval($this->q_cfl_isOther) == 1 ? "Yes" : "No",
            'order' => $this->q_cfl_order,
            'Cash_Flow_Data' => $this->q_cfd_id ? [
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
                'dateCreated' => $this->q_cfd_dateUpdated ? Carbon::parse($this->q_cfd_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y') : null,
                'dateUpdated' => $this->q_cfd_dateCreated ? Carbon::parse($this->q_cfd_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y') : null,
            ] : []
        ];
    }
}
