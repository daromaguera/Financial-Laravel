<?php

namespace App\Http\Controllers;

// use App\Models\User;
use App\Models\Admin;
use App\Models\Agent;
use App\Models\Client;
use App\Models\ErrorLog;
use App\Models\LifeAndHealthInsurance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;

class LoginController extends ExtendedController{
    private $central_domain;
    private $app_name;
    private $app_dk;
    private $fron_url;
    private $linda_url;

    public function __construct()
    {
        $this->app_name = config('app.external.dbph_dm');
        $this->app_dk = config('app.external.dbph_dk');
        $this->central_domain = config('app.external.dbph');
        $this->fron_url = config('app.fron_website.url');
        $this->linda_url = config('app.external.linda');
    }

    public function get_Client_from_Linda($token, $user_id){
        try {
            $url = $this->linda_url . 'getlist?user_id='.$user_id.'&token='.$token.'';
            $ch = curl_init();
            // Set options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false); // Don't include headers in the output
            curl_setopt($ch, CURLOPT_TIMEOUT, 360); // Timeout in seconds

            $result = curl_exec($ch);
            $data = json_decode($result);
            return $data;
        } catch (\Throwable $th) {
            ErrorLog::create([
                'q_errLog_description' => "Unexpected error occurred while syncing information",
                'q_errLog_systemLog' => $th,
                'q_errLog_dateCreated' => date('Y-m-d'),
            ]);
            echo "Unexpected error occurred. Please consult the system administrator. <a href='$this->fron_url' style='color:blue;'>Back to main page</a>";
        }
    }

    public function accept_handshake($token, $scope = null){
        try {
            $verified = $this->verify_handshake($token, $scope); // verifying 
            //dd($verified);
            if($verified)  // success login...
            {
                //Session::put('users',$verified);
                $exists = $this->verify_user_in_local($token, $verified->userid, $verified->account_type);
                if($exists == 0){
                    if($verified->account_type == 'A' || $verified->account_type == 'M' || $verified->account_type == 'S'){
                        $result = Agent::create([
                            'q_agnt_id' => $verified->userid,
                            'q_agnt_token' => $verified->access_token,
                            'q_agnt_f_name' => $verified->first_name,
                            'q_agnt_m_name' => $verified->middle_name,
                            'q_agnt_l_name' => $verified->last_name,
                            'q_agnt_profileImage' => $verified->profile_image,
                            'q_agnt_successfulDateSync' => date('Y-m-d H:i:s'),
                            'q_agnt_lastLoggedIn' => $verified->last_login,
                            'q_agnt_isActive' => $verified->status == "ACTIVE" ? 1 : 0,
                            'q_agnt_uType' => $verified->account_type
                        ]);
                    }
                    // Updated through Mr. Mark and Ma'am Mica's Request...
                    // else if($verified->account_type == 'S'){
                    //     $result = Admin::create([
                    //         'q_ADm_id' => $verified->userid,
                    //         'q_ADm_token' => $verified->access_token,
                    //         'q_ADm_type' => $verified->account_type,
                    //         'q_ADm_fN' => $verified->first_name,
                    //         'q_ADm_mN' => $verified->middle_name,
                    //         'q_ADm_lN' => $verified->last_name,
                    //         'q_ADm_profileImage' => $verified->profile_image,
                    //         'q_ADm_successfulDateSync' => date('Y-m-d H:i:s'),
                    //         'q_ADm_lastLoggedIn' => $verified->last_login,
                    //         'q_ADm_isActive' => $verified->status == "ACTIVE" ? 1 : 0,
                    //     ]);
                    // }
                    // get Advisor's list of Clients (Saving for the first time...)
                    Client::where('q_clnt_agnt_id', $verified->userid)->delete();
                    $data = $this->get_Client_from_Linda($token, $verified->userid);
                    foreach ($data->data as $item) {
                        Client::create([
                            'q_clnt_id' => $item->CLIENTID,
                            'q_clnt_agnt_id' => $item->USERID,
                            'q_clnt_f_name' => $item->FNAME,
                            'q_clnt_m_name' => $item->MNAME,
                            'q_clnt_l_name' => $item->LNAME,
                            'q_clnt_birthDate' => $item->BDAY != "0000-00-00" ? $item->BDAY : null,
                            'q_clnt_gendr' => $item->GENDER,
                            'q_clnt_contNo' => $item->CNUMBER,
                            'q_clnt_emailAddrx' => $item->EMAILADD,
                            'q_clnt_haveChildren' => 0,
                            'q_clnt_shareToSpouse' => 0,
                            'q_clnt_successfulDateSync' => date('Y-m-d H:i:s'),
                            'q_clnt_lastLoggedIn' => date('Y-m-d'),
                            'q_clnt_isActive' => $item->STATUS == 'Active' ? 1 : 0,
                            'q_clnt_addxDate' => date('Y-m-d'),
                            'q_clnt_updxDate' => date('Y-m-d'),
                        ]);
                        // Inserting Policies into Life and Health Insurance
                        if($item->policies){
                            foreach ($item->policies as $polItem) {
                                $policy = LifeAndHealthInsurance::where('q_lifeHealth_clientID',$polItem->CLIENTID)->where('q_lifeHealth_dateUpdated', '<', $polItem->updated)->first();
                                if($policy){
                                    LifeAndHealthInsurance::where('q_lifeHealth_clientID',$polItem->CLIENTID)
                                        ->update([
                                        'q_lifeHealth_clientID' => $polItem->CLIENTID,
                                        'q_lifeHealth_fromAetosAdviser' => 1,
                                        'q_lifeHealth_policyOwner' => 1, // $polItem->insured_name
                                        'q_lifeHealth_dateEffective' => $polItem->EFFECTIVEDATE,
                                        'q_lifeHealth_dateUpdated' => $polItem->updated,
                                        'q_lifeHealth_policyStatus' => $polItem->policy_status,
                                        'q_lifeHealth_policyNumber' => $polItem->policy_no,
                                        'q_lifeHealth_insuranceCompany' => $polItem->insurance_company_name
                                    ]);
                                }
                                $check = 0; 
                                $check = LifeAndHealthInsurance::where('q_lifeHealth_clientID',$polItem->CLIENTID)
                                    ->where('q_lifeHealth_policyNumber', $polItem->policy_no)
                                    ->count();
                                if($check == 0){
                                    LifeAndHealthInsurance::create([
                                        'q_lifeHealth_clientID' => $polItem->CLIENTID,
                                        'q_lifeHealth_fromAetosAdviser' => 1,
                                        'q_lifeHealth_withGuaranteedPayout' => 1,
                                        'q_lifeHealth_policyOwner' => 1, // $polItem->insured_name
                                        'q_lifeHealth_dateEffective' => $polItem->EFFECTIVEDATE,
                                        'q_lifeHealth_dateUpdated' => $polItem->updated,
                                        'q_lifeHealth_policyStatus' => $polItem->policy_status,
                                        'q_lifeHealth_policyNumber' => $polItem->policy_no,
                                        'q_lifeHealth_insuranceCompany' => $polItem->insurance_company_name,
                                        'q_lifeHealth_dateCreated' => date('Y-m-d')
                                    ]);
                                }
                            }
                        }
                    }
                    return \Redirect::to($this->fron_url . 'verify?token='.$verified->access_token.'&id='.$verified->userid); // redirecting to front-end
                }else{
                    if($verified->account_type == 'A' || $verified->account_type == 'M' || $verified->account_type == 'S'){
                        $user = Agent::where('q_agnt_id', $verified->userid)
                        ->update([
                            'q_agnt_token' => $verified->access_token,
                            'q_agnt_lastLoggedIn' => date('Y-m-d'),
                            'q_agnt_isActive' => 1,
                        ]);
                        // get Advisor's list of Clients (S Y N C I N G...)
                        $data = $this->get_Client_from_Linda($token, $verified->userid);
                        //dd($data->data[0]->policies);
                        $i = 0;
                        foreach ($data->data as $item) {
                            $client = Client::where('q_clnt_id',$item->CLIENTID)->where('q_clnt_successfulDateSync', '<', $item->DATEUPDATED)->first();
                            if($client){
                                Client::where('q_clnt_id',$item->CLIENTID)
                                ->update([
                                    'q_clnt_id' => $item->CLIENTID,
                                    'q_clnt_agnt_id' => $item->USERID,
                                    'q_clnt_f_name' => $item->FNAME,
                                    'q_clnt_m_name' => $item->MNAME,
                                    'q_clnt_l_name' => $item->LNAME,
                                    'q_clnt_birthDate' => $item->BDAY != "0000-00-00" ? $item->BDAY : null,
                                    'q_clnt_gendr' => $item->GENDER,
                                    'q_clnt_contNo' => $item->CNUMBER,
                                    'q_clnt_emailAddrx' => $item->EMAILADD,
                                    'q_clnt_haveChildren' => 0,
                                    'q_clnt_shareToSpouse' => 0,
                                    'q_clnt_successfulDateSync' => date('Y-m-d H:i:s'),
                                    'q_clnt_lastLoggedIn' => date('Y-m-d'),
                                    'q_clnt_isActive' => $item->STATUS == 'Active' ? 1 : 0,
                                    'q_clnt_addxDate' => date('Y-m-d'),
                                    'q_clnt_updxDate' => date('Y-m-d'),
                                ]);
                            }else{
                                $count = Client::where('q_clnt_id',$item->CLIENTID)->count();
                                if(!$count){
                                    Client::create([
                                        'q_clnt_id' => $item->CLIENTID,
                                        'q_clnt_agnt_id' => $item->USERID,
                                        'q_clnt_f_name' => $item->FNAME,
                                        'q_clnt_m_name' => $item->MNAME,
                                        'q_clnt_l_name' => $item->LNAME,
                                        'q_clnt_birthDate' => $item->BDAY != "0000-00-00" ? $item->BDAY : null,
                                        'q_clnt_gendr' => $item->GENDER,
                                        'q_clnt_contNo' => $item->CNUMBER,
                                        'q_clnt_emailAddrx' => $item->EMAILADD,
                                        'q_clnt_haveChildren' => 0,
                                        'q_clnt_shareToSpouse' => 0,
                                        'q_clnt_successfulDateSync' => date('Y-m-d H:i:s'),
                                        'q_clnt_lastLoggedIn' => date('Y-m-d'),
                                        'q_clnt_isActive' => $item->STATUS == 'Active' ? 1 : 0,
                                        'q_clnt_addxDate' => date('Y-m-d'),
                                        'q_clnt_updxDate' => date('Y-m-d'),
                                    ]);
                                }
                            }
                            // Inserting Policies into Life and Health Insurance
                            if($item->policies){
                                foreach ($item->policies as $polItem) {
                                    $policy = LifeAndHealthInsurance::where('q_lifeHealth_clientID',$polItem->CLIENTID)->where('q_lifeHealth_dateUpdated', '<', $polItem->updated)->first();
                                    if($policy){
                                        LifeAndHealthInsurance::where('q_lifeHealth_clientID',$polItem->CLIENTID)
                                            ->update([
                                            'q_lifeHealth_clientID' => $polItem->CLIENTID,
                                            'q_lifeHealth_fromAetosAdviser' => 1,
                                            'q_lifeHealth_withGuaranteedPayout' => 1,
                                            'q_lifeHealth_policyOwner' => 1, // $polItem->insured_name
                                            'q_lifeHealth_dateEffective' => $polItem->EFFECTIVEDATE,
                                            'q_lifeHealth_monthYearIssued' => Carbon::parse($polItem->EFFECTIVEDATE)->format('F Y'),
                                            'q_lifeHealth_dateUpdated' => $polItem->updated,
                                            'q_lifeHealth_policyStatus' => $polItem->policy_status,
                                            'q_lifeHealth_policyNumber' => $polItem->policy_no,
                                            'q_lifeHealth_insuranceCompany' => $polItem->insurance_company_name,
                                            'q_lifeHealth_dateCreated' => date('Y-m-d')
                                        ]);
                                    }
                                    $check = 0;
                                    $check = LifeAndHealthInsurance::where('q_lifeHealth_clientID',$polItem->CLIENTID)
                                        ->where('q_lifeHealth_policyNumber', $polItem->policy_no)
                                        ->count();
                                    if($check == 0){
                                        LifeAndHealthInsurance::create([
                                            'q_lifeHealth_clientID' => $polItem->CLIENTID,
                                            'q_lifeHealth_fromAetosAdviser' => 1,
                                            'q_lifeHealth_withGuaranteedPayout' => 1,
                                            'q_lifeHealth_policyOwner' => 1, // $polItem->insured_name
                                            'q_lifeHealth_dateEffective' => $polItem->EFFECTIVEDATE,
                                            'q_lifeHealth_monthYearIssued' => Carbon::parse($polItem->EFFECTIVEDATE)->format('F Y'),
                                            'q_lifeHealth_dateUpdated' => $polItem->updated,
                                            'q_lifeHealth_policyStatus' => $polItem->policy_status,
                                            'q_lifeHealth_policyNumber' => $polItem->policy_no,
                                            'q_lifeHealth_insuranceCompany' => $polItem->insurance_company_name,
                                            'q_lifeHealth_dateCreated' => date('Y-m-d')
                                        ]);
                                    }
                                }
                            }
                            $i++;
                        }
                    }
                    // else if($verified->account_type == 'S'){
                    //     $user = Admin::where('q_ADm_id', $verified->userid)
                    //     ->update([
                    //         'q_ADm_token' => $verified->access_token,
                    //         'q_ADm_lastLoggedIn' => date('Y-m-d'),
                    //         'q_ADm_isActive' => 1,
                    //     ]);
                    // }
                    if($user){
                        return \Redirect::to($this->fron_url . 'verify?token='.$verified->access_token.'&id='.$verified->userid); // redirecting to front-end
                    }else{
                        echo "ERROR: System's process has been INTERRUPTED. <a href='$this->fron_url' style='color:blue;'>Back to main page</a>";
                        // return \Redirect::to($this->central_domain . 'services/login?error=504&dm='.$this->app_name.'&dk='.$this->app_dk.'&req=basic');
                    }
                }
                // Auth::loginUsingId($u->id);
            } else {
                return \Redirect::to($this->central_domain . 'services/login?error=504&dm='.$this->app_name.'&dk='.$this->app_dk.'&req=basic');
            }
        } catch (\Throwable $th) {
            ErrorLog::create([
                'q_errLog_description' => "Unexpected error occurred while verifying information of user",
                'q_errLog_systemLog' => $th,
                'q_errLog_dateCreated' => date('Y-m-d'),
            ]);
            echo $th."<br>Unexpected error occurred. Please consult the system administrator. <a href='$this->fron_url' style='color:blue;'>Back to main page</a>";
        }
    }

    public function verify_handshake($token, $scope){
        try {
            $central_domain = $this->central_domain . "handshake/verify_handshake";

            $scope = ($scope != null ? $scope : "basic");
            $userid = substr($token, 32);
            $token = substr($token, 0, 32);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $central_domain);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'token='.$token.'&userid='.$userid.'&scope='.$scope);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120); // Timeout in seconds

            $headers = array();
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            $data = json_decode($result);

            if(isset($data->error)){
                return false;
            }else{
                if(intval($data->status) == 1){
                    return $data->userdata;
                }else{
                    return false;
                }
            }

            curl_close ($ch);
        } catch (\Throwable $th) {
            ErrorLog::create([
                'q_errLog_description' => "Unexpected error occurred while verifying handshake",
                'q_errLog_systemLog' => $th,
                'q_errLog_dateCreated' => date('Y-m-d'),
            ]);
            echo "Unexpected error occurred. Please consult the system administrator. <a href='$this->fron_url' style='color:blue;'>Back to main page</a>";
        }
    }

    public function verify_user_in_local($token, $userid, $acc_type){
        try {
            if($acc_type == 'A' || $acc_type == 'M' || $acc_type == 'S'){
                return Agent::where('q_agnt_token', $token)->orWhere('q_agnt_id', $userid)->count();
            }else{
                return 0;
            }
            
            // else if($acc_type == 'S'){
            //     return Admin::where('q_ADm_token', $token)->orWhere('q_ADm_id', $userid)->count();
            // }else{
            //     return 0;
            // }
        } catch (\Throwable $th) {
            ErrorLog::create([
                'q_errLog_description' => "Unexpected error occurred while verifying or checking user in 'verify_user_in_local' function",
                'q_errLog_systemLog' => $th,
                'q_errLog_dateCreated' => date('Y-m-d'),
            ]);
            echo "Unexpected error occurred. Please consult the system administrator. <a href='$this->fron_url' style='color:blue;'>Back to main page</a>";
        }
    }

    // public function setCookie($data)
    // {
    //     $cookie = cookie('user', $data, 60); // Set cookie for 60 minutes

    //     return response('Cookie set')->withCookie($cookie);
    // }


    // public function logout()
    // {
    //     Session::flush();
    //     Session::save();
    //     Auth::logout();
    //     return \Redirect::to('/');
    // }

    // public function not_found()
    // {
    //   return view('403');
    // }
}
