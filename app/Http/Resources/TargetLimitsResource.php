<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TargetLimitsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        if(intval($this->q_targLim_type) == 0){
            $data = [
                'first_name' => "Client",
                'last_name' => "Client",
                'middle_name' => "Client",
            ];
        }else{
            $data = [
                'first_name' => $this->q_famComp_firstName,
                'last_name' => $this->q_famComp_lastName,
                'middle_name' => $this->q_famComp_middleInitial,
            ];
        }
        $monthOnly1 = strtoupper(Carbon::parse($this->q_targLim_dateUpdate)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_targLim_dateCreated)->format('M'));
        $data += [
            'client_id' => $this->q_targLim_clientID,
            'targLimit_id' => $this->q_targLim_id,
            'type' => $this->q_targLim_type,
            'famComp_id' => $this->q_famComp_id,
            'MBL_inPatient' => $this->q_targLim_MBL_inPatient,
            'ABL_inPatient' => $this->q_targLim_ABL_inPatient,
            'LBL_inPatient' => $this->q_targLim_LBL_inPatient,
            'MBL_outPatient' => $this->q_targLim_MBL_outPatient,
            'ABL_outPatient' => $this->q_targLim_ABL_outPatient,
            'LBL_outPatient' => $this->q_targLim_LBL_outPatient,
            'MBL_critIllness' => $this->q_targLim_MBL_critIllness,
            'ABL_critIllness' => $this->q_targLim_ABL_critIllness,
            'LBL_critIllness' => $this->q_targLim_LBL_critIllness,
            'labLimit' => $this->q_targLim_labLimit,
            'hospIncome' => $this->q_targLim_hospIncome,
            'dateUpdated' => Carbon::parse($this->q_targLim_dateUpdate)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateCreated' => Carbon::parse($this->q_targLim_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
        ];
        return $data;
    }
}
