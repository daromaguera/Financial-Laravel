<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ClientsAnnualReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $dateTodo = Carbon::parse($this->q_finPlSo_meetAdvisorOn);
        $currentDate = Carbon::now();
        $difference = $dateTodo->diff($currentDate);
        $daysDifference = $difference->days;
        $monthOnly = strtoupper(Carbon::parse($this->q_finPlSo_meetAdvisorOn)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_finPlSo_dateCreated)->format('M'));
        return [
            'fps_id' => $this->q_finPlSo_id,
            'client' => [
                'client_id' => $this->q_clnt_id,
                'First_Name' => $this->q_clnt_f_name,
                'Middle_Name' => $this->q_clnt_m_name,
                'Last_Name' => $this->q_clnt_l_name,
            ],
            'agent' => [
                'agentLN' => $this->q_agnt_l_name,
                'agentMN' => $this->q_agnt_m_name,
                'agentFN' => $this->q_agnt_f_name,
            ],
            'agreed_date_review' => Carbon::parse($this->q_finPlSo_meetAdvisorOn)->format(strcmp($monthOnly,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'total_days_difference' => $daysDifference,
            'days_pending' => (intval($daysDifference / 7) > 0 ? intval($daysDifference / 7) . " Weeks & " : "") . (
                ($dateTodo < Carbon::now()) ? 
                    (($daysDifference % 7) < 2 ? ($daysDifference % 7) . " day Overdue" : ($daysDifference % 7) . " days Overdue") 
                        : 
                    ($daysDifference > 7 ? (($daysDifference % 7) < 2 ? ($daysDifference % 7) . " Day" : ($daysDifference % 7) . " Days") 
                        : 
                    (($daysDifference % 7) < 2 ? ($daysDifference % 7) . " Day" : ($daysDifference % 7) . " Days"))
                ),
            'is_overdue' => ($dateTodo < Carbon::now()) ? 1 : 0,
            'fNA' => $this->q_finPlSo_forTable,
            'dateCreated' => Carbon::parse($this->q_finPlSo_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateMarkedAsResolved' => $this->q_finPlSo_dateMarkedAsResolved != '' || $this->q_finPlSo_dateMarkedAsResolved != null ? Carbon::parse($this->q_finPlSo_dateMarkedAsResolved)->format('d, M. Y') : '',
            'remarksOnResolved' => $this->q_finPlSo_remarksOnResolved
        ];
    }
}
