<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TodosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $dateTodo = Carbon::parse($this->q_tdo_dateTodo);
        $currentDate = Carbon::now();
        $difference = $dateTodo->diff($currentDate);
        $daysDifference = $difference->days;
        $monthOnly = strtoupper(Carbon::parse($this->q_tdo_dateTodo)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_tdo_dateCreated)->format('M'));
        return [
            'todos_id' => $this->q_tdo_id,
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
            'isForClientAgent' => $this->q_tdo_isForClientAgent,
            'description' => $this->q_tdo_descripx,
            'date_todo' => Carbon::parse($this->q_tdo_dateTodo)->format(strcmp($monthOnly,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'date_todo_origin' => $this->q_tdo_dateTodo,
            'total_days_difference' => $daysDifference,
            'days_pending' => ($this->q_tdo_dateTodo < Carbon::now()->format('Y-n-j')) ? ($daysDifference < 2 ? $daysDifference . " day Overdue" : $daysDifference . " days Overdue") : ($daysDifference < 2 ? $daysDifference . " day" : $daysDifference . " days"),
            'is_overdue' => ($this->q_tdo_dateTodo < Carbon::now()->format('Y-n-j')) ? 1 : 0,
            'from_table' => $this->q_tdo_fromTable,
            'is_seen' => $this->q_tdo_isSeen,
            'dateCreated' => Carbon::parse($this->q_tdo_dateCreated)->format(strcmp($monthOnly2, 'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateMarkedAsResolved' => $this->q_tdo_dateMarkedAsResolved != '' || $this->q_tdo_dateMarkedAsResolved != null ? Carbon::parse($this->q_tdo_dateMarkedAsResolved)->format('d, M. Y') : '',
            'remarksOnResolved' => $this->q_tdo_remarksOnResolved
        ];
    }
}
