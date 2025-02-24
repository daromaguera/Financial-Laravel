<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class RetirementExpListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_retExpList_dateCreated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_retExp_dateCreated)->format('M'));
        return [
            'retExpList_id' => $this->q_retExpList_id,
            'description' => $this->q_retExpList_description,
            'isOther' => intval($this->q_retExpList_isOther) == 1 ? "Yes" : "No",
            'dateCreated' => Carbon::parse($this->q_retExpList_dateCreated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'Retirement_Expenses' => $this->q_retExp_id ? [
                'dr_id' => $this->q_retExp_id,
                'client_id' => $this->q_retExp_clientID,
                'retExpList_id' => $this->q_retExp_retExpList_id,
                'presentVal_amt_cl' => $this->q_retExp_presentValAmtCL,
                'presentVal_amt_sp' => $this->q_retExp_presentValAmtSP,
                'dateCreated' => Carbon::parse($this->q_retExp_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            ] : []
        ];
    }
}
