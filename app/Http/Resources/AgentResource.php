<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class AgentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
        public function toArray($request): array
        {
            date_default_timezone_set('Asia/Manila');
            $monthOnly = strtoupper(Carbon::parse($this->q_agnt_successfulDateSync)->format('M'));
            return [
                'Agent_ID' => $this->q_agnt_id,
                'user_type' => $this->q_agnt_uType,
                'FirstName' => $this->q_agnt_f_name,
                'MiddleName' => $this->q_agnt_m_name,
                'LastName' => $this->q_agnt_l_name,
                'Address' => $this->q_agnt_addrx,
                'Profile_IMG' => $this->q_agnt_profileImage,
                'DateOfSuccessfulSync' => $this->q_agnt_successfulDateSync ? Carbon::parse($this->q_agnt_successfulDateSync)->format(strcmp($monthOnly,'MAY') == 0 ? 'd, M Y' : 'd, M. Y') : '',
                'LastLoggedIn' => $this->q_agnt_lastLoggedIn,
                'DaysHoursMinutesAgoFromLastLoggedIn' => $this->calculateTimeAgo($this->q_agnt_lastLoggedIn),
                'isActive' => $this->q_agnt_isActive,
                'linkLastVisited' => $this->q_agnt_linkLastVisited
            ];
        }

    protected function calculateTimeAgo($lastLoggedIn){
        $diffInMinutes = Carbon::parse($lastLoggedIn)->diffInMinutes(Carbon::now());

        $days = floor($diffInMinutes / (24 * 60));
        $hours = floor(($diffInMinutes % (24 * 60)) / 60);
        $minutes = $diffInMinutes % 60;

        $timeAgo = '';

        if ($days > 0) {
            $timeAgo .= "$days Days, ";
        }

        if ($hours > 0) {
            $timeAgo .= "$hours hour/s and ";
        }
        
        $timeAgo .= "$minutes minutes";

        return $timeAgo;
    }
}
