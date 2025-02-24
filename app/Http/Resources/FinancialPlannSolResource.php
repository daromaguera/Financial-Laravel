<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class FinancialPlannSolResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_finPlSo_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_finPlSo_dateCreated)->format('M'));
        return [
            'fps_id' => $this->q_finPlSo_id,
            'client_id' => $this->q_finPlSo_clientID,
            'q_finPlSo_forTable' => $this->q_finPlSo_forTable,
            'monthly_budget1' => $this->q_finPlSo_monthlyBud1,
            'monthly_budget2' => $this->q_finPlSo_monthlyBud2,
            'actual_net_cashflow1' => $this->q_finPlSo_actNetCashflow1,
            'actual_net_cashflow2' => $this->q_finPlSo_actNetCashflow2,
            'advisor_suggestion' => $this->q_finPlSo_advisorSuggestion,
            'status' => $this->q_finPlSo_status,
            'modePayment' => $this->q_finPlSo_modePayment,
            'formPayment' => $this->q_finPlSo_formPayment,
            'goal_review' => $this->q_finPlSo_goalRev,
            'meet_advisor_on' => $this->q_finPlSo_meetAdvisorOn,
            'dateUpdated' => Carbon::parse($this->q_finPlSo_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateCreated' => Carbon::parse($this->q_finPlSo_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateMarkedAsResolved' => $this->q_finPlSo_dateMarkedAsResolved != '' || $this->q_finPlSo_dateMarkedAsResolved != null ? Carbon::parse($this->q_finPlSo_dateMarkedAsResolved)->format('d, M. Y') : '',
            'remarksOnResolved' => $this->q_finPlSo_remarksOnResolved
        ];
    }
}
