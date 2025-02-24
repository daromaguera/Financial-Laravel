<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        date_default_timezone_set('Asia/Manila');
        $monthOnly = strtoupper(Carbon::parse($this->q_ADm_successfulDateSync)->format('M'));
        return [
            'Agent_ID' => $this->q_ADm_id,
            'user_type' => $this->q_ADm_type,
            'FirstName' => $this->q_ADm_fN,
            'MiddleName' => $this->q_ADm_mN,
            'LastName' => $this->q_ADm_lN,
            'Address' => $this->q_ADm_addrx,
            'Profile_IMG' => $this->q_ADm_profileImage,
            'DateOfSuccessfulSync' => $this->q_ADm_successfulDateSync ? Carbon::parse($this->q_ADm_successfulDateSync)->format(strcmp($monthOnly,'MAY') == 0 ? 'd, M Y' : 'd, M. Y') : '',
            'LastLoggedIn' => $this->q_ADm_lastLoggedIn,
            'DaysHoursMinutesAgoFromLastLoggedIn' => $this->calculateTimeAgo($this->q_ADm_lastLoggedIn),
            'isActive' => $this->q_ADm_isActive,
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
