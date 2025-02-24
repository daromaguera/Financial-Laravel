<?php

namespace App\Http\Resources;

use App\Models\FnaCompletion;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        date_default_timezone_set('Asia/Manila');
        $birthDate = Carbon::parse($this->q_clnt_birthDate);
        $age = $birthDate->diffInYears(Carbon::now());
        $monthOnly = strtoupper(Carbon::parse($this->q_clnt_addxDate)->format('M'));
        $sumOfFNA = FnaCompletion::where('q_fnaComp_clientID', $this->q_clnt_id)->sum('q_fnaComp_statusValue');
        return [
            'Client_ID' => $this->q_clnt_id,
            'FinancialNeeds_ID' => $this->q_clnt_fneeds_id,
            'spouse_id' => $this->q_clnt_spouseID,
            'FirstName' => $this->q_clnt_f_name,
            'MiddleName' => $this->q_clnt_m_name,
            'LastName' => $this->q_clnt_l_name,
            'agent_fName' => $this->q_agnt_f_name,
            'agent_mName' => $this->q_agnt_m_name,
            'agent_lName' => $this->q_agnt_l_name,
            'BirthDate' => [  
                'Formatted' => $this->q_clnt_birthDate == null || $this->q_clnt_birthDate == "" ? "" : $birthDate->format('d, M. Y'),
                'Age' => $age,
            ],
            'Gender' => $this->q_clnt_gendr,
            'Contact_Number' => $this->q_clnt_contNo,
            'Email' => $this->q_clnt_emailAddrx,
            'Civil_Status' => $this->q_clnt_civilStatx,
            'have_children' => $this->q_clnt_haveChildren == 0 ? 'No' : 'Yes',
            'isShareToSpouse' => $this->q_clnt_shareToSpouse == 0 ? 'No' : 'Yes',
            'Wedding_Date' => $this->q_clnt_weddDate == null || $this->q_clnt_weddDate == "" ? "" : Carbon::parse($this->q_clnt_weddDate)->format('d, M. Y'),
            'Health_Condition' => $this->q_clnt_healthCondi,
            'Details_Of_Health_Condition' => $this->q_clnt_healthCondiDetail,
            'isTakeRiskAssessment' => $this->q_clnt_takeRiskAssessM == null || $this->q_clnt_takeRiskAssessM == '' ? 'No' : 'Yes',
            'Risk_Capacity' => $this->q_clnt_risk_cap,
            'Risk_Attitude' => $this->q_clnt_risk_attix,
            'Client_DateOfSuccessfulSync' => Carbon::parse($this->q_clnt_successfulDateSync)->format('d, M. Y'),
            'Last_LoggedIn' => Carbon::parse($this->q_clnt_lastLoggedIn)->format('d, M. Y'),
            'DaysHoursMinutesAgoFromLastLoggedIn' => floor(((Carbon::parse($this->q_clnt_lastLoggedIn)->diffInMinutes(Carbon::now())) / 60) / 24) . " Days, " . floor(((Carbon::parse($this->q_clnt_lastLoggedIn)->diffInMinutes(Carbon::now())) % 60) / 24) . " hour/s and " . (Carbon::parse($this->q_clnt_lastLoggedIn)->diffInMinutes(Carbon::now())) % 60 . " minutes",
            'token' => $this->q_clnt_TOKEN,
            'isActive' => $this->q_clnt_isActive,
            'FNAListCompleted' => FnaCompletion::where('q_fnaComp_clientID', $this->q_clnt_id)->get(),
            'completion_status' => $sumOfFNA != null ? number_format(($sumOfFNA / 60) * 100, 0) : 0,
            'Date_Added' => Carbon::parse($this->q_clnt_addxDate)->format(strcmp($monthOnly,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'Date_Updated' => Carbon::parse($this->q_clnt_updxDate)->format('d, M. Y'), 
        ];
    }
}
