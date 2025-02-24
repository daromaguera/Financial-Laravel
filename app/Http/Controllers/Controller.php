<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Client;
use App\Models\SelectedFinancialPriorities;
use App\Models\CashFlowList;
use App\Models\CashFlowData;
use App\Models\CashFlowAnalysis;
use App\Models\Recommendations;
use App\Models\FinancialPriorities;
use App\Models\Heir;
use App\Models\CashAndDeposits;
use App\Models\FamilyComposition;
use App\Models\Receivables;
use App\Models\MutualFundsUITF;
use App\Models\Bonds;
use App\Models\StocksInCompanies;
use App\Models\LifeAndHealthInsurance;
use App\Models\Beneficiaries;
use App\Models\FamilyHomeEstate;
use App\Models\Vehicles;
use App\Models\PersonalAssets;
use App\Models\Liabilities;
use App\Models\DreamsAspirations;
use App\Models\FinancialPlannSol;
use App\Models\Todos;
use App\Models\FPFNA;
use App\Models\DebtsAndFinalList;
use App\Models\DebtsAndFinalExpenses;
use App\Models\LifeInsuranceCoverageList;
use App\Models\ExistingLifeInsuranceCoverage;
use App\Models\RetirePlannFNA;
use App\Models\RetirementExp;
use App\Models\RetirementExpList;
use App\Models\EducPlannFNA;
use App\Models\EducPlannExp;
use App\Models\EducPlannExpList;
use App\Models\SelectedChildEducPlan;
use App\Models\SystemLogs;
use App\Models\Admin;
use App\Models\HealthFundPlannFNA;
use App\Models\HealthCovSumm;
use App\Models\TargetLimits;
use App\Models\FnaCompletion;
use App\Models\AdvActivities;

use App\Http\Resources\AgentResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\SelectedFinancialPriority;
use App\Http\Resources\CashFlowListResource;
use App\Http\Resources\CashFlowDataResource;
use App\Http\Resources\CashFlowAnalysisResource;
use App\Http\Resources\RecommendResource;
use App\Http\Resources\FinancialPrioritiesResource;
use App\Http\Resources\HeirsResource;
use App\Http\Resources\CashAndDepositResource;
use App\Http\Resources\ReceivableResource;
use App\Http\Resources\MutualFundsUITFResource;
use App\Http\Resources\BondsResource;
use App\Http\Resources\StockInCompaniesResource;
use App\Http\Resources\LifeAndHealthInsuranceResource;
use App\Http\Resources\HomeEstateResource;
use App\Http\Resources\VehiclesResource;
use App\Http\Resources\PersonalAssetsResource;
use App\Http\Resources\LiabilitiesResource;
use App\Http\Resources\DreamsAspirationsResource;
use App\Http\Resources\FinancialPlannSolResource;
use App\Http\Resources\TodosResource;
use App\Http\Resources\FPFNAResource;
use App\Http\Resources\DebtsAndFinalListResource;
use App\Http\Resources\LifeInsuranceCoverageListResource;
use App\Http\Resources\DebtsAndFinalExpResource;
use App\Http\Resources\AdminResource;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    private $central_domain;
    private $app_name;
    private $app_dk;
    private $fron_url;
    private $linda_url;
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    const PER_PAGE = 10;
    public $unexpectedError = '', $successOperation = '', $interruptedError = '', $similarInfoDetErr = '', $consultAdminMsg = '', $updateOfRecordDiscge = '';
    public function __construct(){
        $this->interruptedError = 'The Server has been interrupted. Please consult the server administrator if this error will persist in the future.';
        $this->unexpectedError = 'Unexpected error occurred. Please consult the server administrator if this error will persist in the future.';
        $this->successOperation = 'Successful server operation';
        $this->similarInfoDetErr = 'Similar information has been detected. Please try again.';
        $this->consultAdminMsg = "Unnecessary or similar data can't be made. Consult the system administrator for details or refer to the documentation.";
        $this->updateOfRecordDiscge = "Updating of this record is temporarily discourage. Consult the system administrator for details.";
        $this->app_name = config('app.external.dbph_dm');
        $this->app_dk = config('app.external.dbph_dk');
        $this->central_domain = config('app.external.dbph');
        $this->fron_url = config('app.fron_website.url');
        $this->linda_url = config('app.external.linda');
    }


    // Other useful functions... **********************************************************************************************************
    // ************************************************************************************************************************************
    // ************************************************************************************************************************************
    // **************************************************************************************************************************
    // custom Reply...
    public function syncData($op, $request){
        try {
            if($op == 'Client'){
                $data = $this->get_Client_from_Linda($request['token'], $request['user_id']);
                $i = 0;
                foreach ($data->data as $item) {
                    $client = Client::where('q_clnt_id',$item->CLIENTID)->where('q_clnt_agnt_id',$request['user_id'])->where('q_clnt_successfulDateSync', '<', $item->DATEUPDATED)->first();
                    if($client){
                        Client::where('q_clnt_id',$item->CLIENTID)
                        ->where('q_clnt_agnt_id',$request['user_id'])
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
                return 1;
            }
        } catch (\Throwable $th) {
            return 0;
        }
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
    public function customSystemReply($type, $subject, $op){ // op for operation
        if($type == 'success') {
            $cpyOP = $op == 'delete' ? 'deleted' : null;
            $cpyOP = !$cpyOP && $op == 'edit' ? 'modified' : $cpyOP;
            $cpyOP = !$cpyOP && $op == 'save' ? 'added' : $cpyOP;
            return 'Server Response No. '.date('YmdHis').': An '.$subject.' has been successfully '.$cpyOP.'!';
        }else if($type == 'error'){
            $cpyOP = $op == 'delete' ? 'deletion attempt' : null;
            $cpyOP = !$cpyOP && $op == 'edit' ? 'modification attempt' : $cpyOP;
            $cpyOP = !$cpyOP && $op == 'save' ? 'addition attempt' : $cpyOP;
            return 'Server Response No. '.date('YmdHis').': Unsuccessful '.$cpyOP.' on '.$subject.'!';
        }
    }
    public function throwErrorCustom($message, $http){
        $data = [
            'http' => $http,
            'message' => $message,
        ];
        return $this->customJsonFormatter('', $data, 'Cash_Flow_Data');
    }
    public function flattenData($data){
        $flattenedData = collect($data)->flatMap(function ($values) {
            return $values;
        })->toArray();
        return $flattenedData;
    }
    public function thrownErrorInterrupted($message, $http){
        return [
            'http' => $http,
            'message' => $message,
        ];
    }
    public function thrownErrorInterruptedHasSuccess($message, $http, $hasSuccess){
        return [
            'http' => $http,
            'message' => $message,
            'hasSuccess' => $hasSuccess
        ];
    }
    public function unexpectedError($message, $http){
        return [
            'http' => $http,
            'message' => $message
        ];
    }
    public function unexpectedErrorHasSuccess($message, $http, $hasSuccess){
        return [
            'http' => $http,
            'message' => $message,
            'hasSuccess' => $hasSuccess
        ];
    }
    public function getSuccessOperation($http, $message, $hasSuccess, $other){
        return [
            'http' => $http,
            'message' => $message,
            'hasSuccess' => $hasSuccess,
            'extData' => $other
        ];
    }
    public function recordSystemLogs($text){
        $result = SystemLogs::create([
            'q_SysLogs_logDescription' => $text,
            'q_SysLogs_dateCreated' => date('Y-m-d'),
        ]);
        return $result->id;
    }
    public function advActivityLog($agentID, $clientID, $text){
        $result = AdvActivities::create([
            'q_advAct_agentID' => $agentID,
            'q_advAct_clientID' => $clientID,
            'q_advAct_actDescription' => $text,
            'q_advAct_dateCreated' => date('Y-m-d'),
        ]);
        return $result->id;
    }
    public function verifyUser($request, $position){ // Validate any user type first before doing any transactions...
        try {
            if($position != 0){ // for multidimensional request
                $data = null;
                $data = Agent::where('q_agnt_id', $request[$position]['web_user'][0][0])->where('q_agnt_token', $request[$position]['web_user'][0][1])->get();
                $userID = $request[$position]['web_user'][0][0];
                $dataResult = [
                    'userID' => $userID['user_id'],
                    'userCompleteName' => $data[0]['q_agnt_f_name'] ." ". $data[0]['q_agnt_l_name'],
                ];
                if($data){
                    return $dataResult;
                }else{
                    return 0;
                }
            }else{ // for default request
                $result = ["success" => 0, "message" => "Invalid User", "user_info" => null, "user_type" => null];
                $user = AgentResource::collection(Agent::where('q_agnt_token', $request['user_token'])->where('q_agnt_id', $request['user_id'])->paginate(1,['*'],'1',1));
                if(!$user->isEmpty()){
                    $result = [
                        "success" => 1,
                        "message" => "Successful Server Operation",
                        "user_info" => $user,
                    ];
                    return $result;
                } else {
                    $user = AdminResource::collection(Admin::where('q_ADm_token', $request['user_token'])->where('q_ADm_id', $request['user_id'])->paginate(1,['*'],'1',1));
                    if(!$user->isEmpty()){
                        $result = [
                            "success" => 1,
                            "message" => "Successful Server Operation",
                            "user_info" => $user,
                        ];
                        return $result;
                    }else{
                        return $result;
                    }
                }
            }
            
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public function returnZeroAndOne($result){
        if($result){ return 1;
        }else{ return 0; }
    }

    // custom want field...
    public function wantField($request, $op){
        $wantField = '';
        if($op == 'agents'){
            if($request['wantField'] == 'first_name'){ $wantField = 'q_agnt_f_name'; }
            else if($request['wantField'] == 'last_name'){ $wantField = 'q_agnt_l_name'; }
            else if($request['wantField'] == 'address'){ $wantField = 'q_agnt_addrx'; }
            else if($request['wantField'] == 'last_successfulsync'){ $wantField = 'q_agnt_successfulDateSync'; }
            else{ return ''; }
        }else if($op == 'clients'){
            if($request['wantField'] == 'first_name'){ $wantField = 'q_clnt_f_name'; }
            else if($request['wantField'] == 'last_name'){ $wantField = 'q_clnt_l_name'; }
            else if($request['wantField'] == 'gender'){ $wantField = 'q_clnt_gendr'; }
            else if($request['wantField'] == 'contact_number'){ $wantField = 'q_clnt_contNo'; }
            else if($request['wantField'] == 'email_address'){ $wantField = 'q_clnt_emailAddrx'; }
            else if($request['wantField'] == 'civil_status'){ $wantField = 'q_clnt_civilStatx'; }
            else if($request['wantField'] == 'details_of_health_condition'){ $wantField = 'q_clnt_healthCondiDetail'; }
            else if($request['wantField'] == 'date_of_successful_sync'){ $wantField = 'q_clnt_successfulDateSync'; }
            else if($request['wantField'] == 'wedding_date'){ $wantField = 'q_clnt_weddDate'; }
            else{ return ''; }
        }
        return $wantField;
    }

    // Data Evaluation and Validation
    public function evaluateAndValidate($elements, $table, $option, $method, $other){
        $collectedErrors = array();
        $i = 0; $counter = 0;
        foreach ($elements['data'] as $index => $data) {
            $flattenedData = $this->flattenData($data);  // This code flattens the nested arrays in each set of data before running the validation. Adjust the validation rules and custom error messages based on your actual field names and requirements.
            $validated = null;
            $validated = $this->validateFields($table, $method, $flattenedData, $other);
            if(!$validated['validate']){
                $str = '';
                if($option == 'heirs'){ $str = 'Heirs'; }
                else if($option == 'selected_financial_priorities'){ $str = 'Selected Financial Priority'; }
                else if($option == 'cash_and_deposits'){ $str = 'Cash and Deposits'; }
                else if($option == 'heirsInCashAndDeposits'){ $str = 'Heirs for Cash and Deposits'; }
                else if($option == 'famComp'){ $str = 'Family Composition'; }
                else if($option == 'receivables'){ $str = 'Receivables'; }
                else if($option == 'heirsInReceivables'){ $str = 'Heirs for Receivables'; }
                else if($option == 'Mutual_Funds'){ $str = 'Mutual Funds or UITF'; }
                else if($option == 'heirsInMutualFund'){ $str = 'Heirs for Mutual Funds or UITF'; }
                else if($option == 'Bonds'){ $str = 'Financial Asset - Bonds'; }
                else if($option == 'heirsInBonds'){ $str = 'Heirs for Financial Asset - Bonds'; }
                else if($option == 'StockInCompanies'){ $str = 'Financial Asset - Stocks in Companies'; }
                else if($option == 'heirsInStocksInCompanies'){ $str = 'Heirs for Financial Asset - Stocks in Companies'; }
                else if($option == 'lifeAndHealthInsurance'){ $str = 'Financial Asset - Family Life and Health Insurance'; }
                else if($option == 'beneInLifeHealthInsurance'){ $str = 'Heirs for Financial Asset - Family Life and Health Insurance'; }
                else if($option == 'FamilyHomeRealEstate'){ $str = 'Family Home or Real Estate'; }
                else if($option == 'heirsFamilyHomeRealEstate'){ $str = 'Heirs for Family Home or Real Estate'; }         
                else if($option == 'Vehicles'){ $str = 'Vehicle'; }
                else if($option == 'heirsInVehicles'){ $str = 'Heirs for Vehicle'; }
                else if($option == 'PersonalAssets'){ $str = 'Personal Assets'; }
                else if($option == 'heirsInPersonalAssets'){ $str = 'Heirs for Personal Assets'; }
                else if($option == 'Liabilities'){ $str = 'Liabilities'; }
                else if($option == 'DreamsAndAspiration'){ $str = 'Dreams and Aspirations'; }
                else if($option == 'PlanningSolutions'){ $str = 'Financial Planning Solutions'; }
                else if($option == 'PlanSol_todos'){ $str = 'Todos for Financial Planning Solutions'; }
                else if($option == 'saveNewFamProFNA'){ $str = 'Financial Protection - FNA'; }
                else if($option == 'DebtFinalExpenses'){ $str = 'Debts and Final Expenses for Financial Protection - FNA'; }
                else if($option == 'ExistLifeInsureCov'){ $str = 'Existing Life Insurance Coverage for Financial Protection - FNA'; }
                else if($option == 'saveNewRetirementPlanFNA'){ $str = 'Retirement Planning - FNA'; }
                else if($option == 'retirementExpenses'){ $str = 'Retirement Expenses for Retirement Planning - FNA'; }
                else if($option == 'educPlannFNA'){ $str = 'Education Planning - FNA'; }
                else if($option == 'childrenSelected'){ $str = 'Children for Education Planning - FNA'; }
                else if($option == 'EducationPlannExp'){ $str = 'Education Planning Expenses for Education Planning - FNA'; }
                else if($option == 'healthFundPlannFNA'){ $str = 'Health Fund Planning - FNA'; }
                else if($option == 'healthCovSumm'){ $str = 'Health Coverage Summary for Health Fund Planning FNA'; }
                else if($option == 'targetLimits'){ $str = 'Target Limits for Health Fund Planning FNA'; }
                else if($option == 'educFundAllocated'){ $str = 'Fund Allocation for Education Planning FNA'; }
                else if($option == 'healthCovSum'){ $str = 'Health Coverage Summary for Health FNA'; }
                $collectedErrors[$i] = ['Error in set No. ' . $counter + 1 . ' from the submitted '.$str.'' => $validated['errors']->errors()];
                $i++;
            }
            $counter++;
        }
        return $collectedErrors;
    }

    // custom Json formatting...
    public function customJsonFormatter($data, $response, $table){
        $finalData = ['message' => $response['message']];
        $finalData += [ $table => $data ];
        if($response['http'] == 200 && $response['meta']){
            $finalData += ['meta' => $response['meta']];
        }
        return response()->json($finalData, $response['http']); // data, status code
    }
    public function customJsonFormatterIndividualClient($data, $response, $table){
        $finalData = ['message' => $response['message']];
        $finalData += [ $table => $data ];
        if($response['http'] == 200 && $response['meta']){
            $finalData += ['meta' => $response['meta']];
        }
        // return response()->json($finalData, $response['http']); // data, status code
        $responseObject = new Response(json_encode($finalData), $response['http']);
        $responseObject->header('Content-Type', 'application/json');

        return $responseObject;
    }


    // Validators **************************************************************************************************************************
    // ****************************************************************************************************************************************
    // ****************************************************************************************************************************************
    // ********************************************************************************************************************
    public function validateFields($op, $method, $request, $op2){
        $validator = null;
        if($op == 'validateGetRequest'){
            $validator = Validator::make($request->all(), [  // use ->all() for legitimate Request  
                'client_id' => 'required',
                'user_id' => 'required',
                'user_token' => 'required',
            ], [
                'client_id.required' => '\'client_id\' is required',
                'user_id.required' => '\'user_id\' is required',
                'user_token.required' => '\'user_token\' is required',
            ]);
        }else if($op == 'selected_financial_priorities'){
            if($method == 'post'){
                $validator = Validator::make($request, [
                    'sfp_id' => 'required',
                    'client_id' => 'required',
                    'financial_priority_list_id' => 'required',
                    'rankNumber' => 'required',
                ], [
                    'sfp_id.required' => '\'sfp_id\' in a JSON object named \'data\' is required',
                    'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                    'financial_priority_list_id.required' => '\'financial_priority_list_id\' in a JSON object named \'data\' is required',
                    'rankNumber.required' => '\'rankNumber\' in a JSON object named \'data\' is required',
                ]);
            }else if($method == 'put'){
                $validator = Validator::make($request, [
                    'client_id' => 'required',
                    'rank_number' => 'required',
                    'sfp_id' => 'required',
                    'priority_list_id' => 'required',
                ], [
                    'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                    'rank_number.required' => '\'rank_number\' in a JSON object named \'data\' is required',
                    'sfp_id.required' => '\'sfp_id\' in a JSON object named \'data\' is required',
                    'priority_list_id.required' => '\'priority_list_id\' in a JSON object named \'data\' is required',
                ]);
            }
        }else if($op == 'agent' || $op == 'client'){
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ], [
                'id.required' => '\'id\' field is required',
            ]);
        }else if($op == 'cash_flow_list'){
            $validator = Validator::make($request->all(), [
                'cashFlowType' => 'required',
                'client_id' => 'required',
            ], [
                'cashFlowType.required' => '\'cashFlowType\' field is required. Use the following value: 0 for Inflow, and 1 for Outflow',
                'client_id.required' => '\'client_id\' field is required.',
            ]);
        }else if($op == 'AgentSearch'){
            $validator = Validator::make($request->all(), [
                'dataToSearch' => 'required',
            ], [
                'dataToSearch.required' => '\'dataToSearch\' field is required.',
            ]);
        }else if($op == 'AgentOnField'){
            $validator = Validator::make($request->all(), [
                'dataToSearch' => 'required',
                'wantField' => 'required',
            ], [
                'dataToSearch.required' => '\'dataToSearch\' field is required.',
                'wantField.required' => '\'wantField\' field is required. Also, this field requires a value indicator on which field to search, such as \'first_name\', \'last_name\', \'address\', or \'last_successfulsync\'.',
            ]);
        }else if($op == 'ClientOnField'){
            $validator = Validator::make($request->all(), [
                'agent_id' => 'required',
                'dataToSearch' => 'required',
                'wantField' => 'required',
            ], [
                'agent_id.required' => '\'agent_id\' field is required.',
                'dataToSearch.required' => '\'dataToSearch\' field is required.',
                'wantField.required' => '\'wantField\' field is required. Also, this field requires a value indicator on which field to search, such as \'first_name\', \'last_name\', \'gender\', \'contact_number\', \'email_address\', \'civil_status\', \'details_of_health_condition\', \'date_of_successful_sync\', or \'wedding_date\' as value for \'wantField\' key.',
            ]);
        }else if($op == 'CashFlowAnalysisInflow'){
            $validator = Validator::make($request, [
                'cfl_id' => 'required',
                'cfd_id' => 'required',
                'client_id' => 'required',
                'cfda_client_amt' => 'required',
                'cfda_spouse_amt' => 'required',
            ], [
                'cfl_id.required' => '\'cfl_id\' in a JSON object named \'data\' is required',
                'cfd_id.required' => '\'cfd_id\' in a JSON object named \'data\' is required',
                'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                'cfda_client_amt.required' => '\'cfda_client_amt\' in a JSON object named \'data\' is required',
                'cfda_spouse_amt.required' => '\'cfda_spouse_amt\' in a JSON object named \'data\' is required',
            ]);
        }else if($op == 'CashFlowAnalysisOutflow'){
            $validator = Validator::make($request, [
                'cfl_id' => 'required',
                'cfd_id' => 'required',
                'client_id' => 'required',
                'cfda_client_amount_expense' => 'required',
                'cfda_spouse_amount_expense' => 'required',
                'target_retirementAmntPercent' => 'required',
            ], [
                'cfl_id.required' => '\'cfl_id\' in a JSON object named \'data\' is required',
                'cfd_id.required' => '\'cfd_id\' in a JSON object named \'data\' is required',
                'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                'cfda_client_amount_expense.required' => '\'cfda_client_amount_expense\' in a JSON object named \'data\' is required',
                'cfda_spouse_amount_expense.required' => '\'cfda_spouse_amount_expense\' in a JSON object named \'data\' is required',
                'target_retirementAmntPercent.required' => '\'target_retirementAmntPercent\' in a JSON object named \'data\' is required',
            ]);
        }else if($op == 'CashFlowAnalysisOutflowWithBudget'){
            $validator = Validator::make($request, [
                'cfl_id' => 'required',
                'cfd_id' => 'required',
                'client_id' => 'required',
                'cfdb_clientAmt' => 'required',
                'cfdb_spouseAmt' => 'required',
            ], [
                'cfl_id.required' => '\'cfl_id\' in a JSON object named \'data\' is required',
                'cfd_id.required' => '\'cfd_id\' in a JSON object named \'data\' is required',
                'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                'cfdb_clientAmt.required' => '\'cfdb_clientAmt\' in a JSON object named \'data\' is required',
                'cfdb_spouseAmt.required' => '\'cfdb_spouseAmt\' in a JSON object named \'data\' is required',
            ]);
        }else if($op == 'CashFlowRecommendations'){
            $validator = Validator::make($request, [
                'recommendation_id' => 'required',
            ], [
                'recommendation_id.required' => '\'recommendation_id\' in a JSON object named \'recommendations\' is required',
            ]);
        }else if($op == 'cash_and_deposits'){
            if($method == 'post'){
                $validator = Validator::make($request, [  // use ->all() for legitimate Request
                    'part' => 'required',
                    'cad_id' => 'required',
                    'client_id' => 'required',
                    'bank' => 'required',
                    'account_description' => 'required',
                    'type_of_account' => 'required',
                    'estimated_value' => 'required',
                    'purpose' => 'required',
                    'with_guaranteed_payout' => 'required',
                    'exclusive_conjugal' => 'required',
                    'share_self' => 'required',
                ], [
                    'part.required' => '\'part\' in a JSON object named \'data\' is required',
                    'cad_id.required' => '\'cad_id\' in a JSON object named \'data\' is required',
                    'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                    'bank.required' => '\'bank\' in a JSON object named \'data\' is required',
                    'account_description.required' => '\'account_description\' in a JSON object named \'data\' is required',
                    'type_of_account.required' => '\'type_of_account\' in a JSON object named \'data\' is required',
                    'estimated_value.required' => '\'estimated_value\' in a JSON object named \'data\' is required',
                    'purpose.required' => '\'purpose\' in a JSON object named \'data\' is required',
                    'with_guaranteed_payout.required' => '\'with_guaranteed_payout\' in a JSON object named \'data\' is required',
                    'exclusive_conjugal.required' => '\'exclusive_conjugal\' in a JSON object named \'data\' is required',
                    'share_self.required' => '\'share_self\' in a JSON object named \'data\' is required',
                ]);
            }
        }else if($op == 'family_composition'){
            if($method == 'post'){
                $validator = Validator::make($request, [  // use ->all() for legitimate Request
                    'fc_id' => 'required',
                    'client_id' => 'required',
                    'first_name' => 'required',
                    //'last_name' => 'required',
                    //'type' => 'required',
                    //'birthday' => 'required',
                    'health_condition' => 'required',
                ], [
                    'fc_id.required' => '\'fc_id\' in a JSON object named \'data\' is required',
                    'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                    'first_name.required' => '\'first_name\' in a JSON object named \'data\' is required',
                    //'last_name.required' => '\'last_name\' in a JSON object named \'data\' is required',
                    //'type.required' => '\'type\' in a JSON object named \'data\' is required',
                    //'birthday.required' => '\'birthday\' in a JSON object named \'data\' is required',
                    'health_condition.required' => '\'health_condition\' in a JSON object named \'data\' is required',
                ]);
            }
        }else if($op == 'heirs'){
            $validator = Validator::make($request, [  // use ->all() for legitimate Request
                'part' => 'required',
                'heir_id' => 'required',
                'famComp_id' => 'required',
                'table_ID' => 'required',
                'from_table' => 'required',
                'indicated_percent' => 'required',
            ], [
                'part.required' => '\'part\' in a JSON object named \'heirs\' is required',
                'heir_id.required' => '\'heir_id\' in a JSON object named \'heirs\' is required',
                'famComp_id.required' => '\'famComp_id\' in a JSON object named \'heirs\' is required',
                'table_ID.required' => '\'table_ID\' in a JSON object named \'heirs\' is required',
                'from_table.required' => '\'from_table\' in a JSON object named \'heirs\' is required',
                'indicated_percent.required' => '\'indicated_percent\' in a JSON object named \'heirs\' is required',
            ]);
        }else if($op == 'receivables'){
            if($method == 'post'){
                $validator = Validator::make($request, [  // use ->all() for legitimate Request
                    'part' => 'required',
                    'receivables_id' => 'required',
                    'client_id' => 'required',
                    'name_of_debtor' => 'required',
                    'loan_purpose' => 'required',
                    'estimated_value' => 'required',
                    'percentage_collectibility' => 'required',
                    'exclusive_conjugal' => 'required',
                    'share_self' => 'required',
                    'with_cli' => 'required',
                ], [
                    'part.required' => '\'part\' in a JSON object named \'data\' is required',
                    'receivables_id.required' => '\'receivables_id\' in a JSON object named \'data\' is required',
                    'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                    'name_of_debtor.required' => '\'name_of_debtor\' in a JSON object named \'data\' is required',
                    'loan_purpose.required' => '\'loan_purpose\' in a JSON object named \'data\' is required',
                    'estimated_value.required' => '\'estimated_value\' in a JSON object named \'data\' is required',
                    'percentage_collectibility.required' => '\'percentage_collectibility\' in a JSON object named \'data\' is required',
                    'exclusive_conjugal.required' => '\'exclusive_conjugal\' in a JSON object named \'data\' is required',
                    'share_self.required' => '\'share_self\' in a JSON object named \'data\' is required',
                    'with_cli.required' => '\'with_cli\' in a JSON object named \'data\' is required',
                ]);
            }
        }else if($op == 'Mutual_Funds'){
            if($method == 'post'){
                $validator = Validator::make($request, [  // use ->all() for legitimate Request
                    'part' => 'required',
                    'mfuitf_id' => 'required',
                    'client_id' => 'required',
                    'company' => 'required',
                    'no_of_units' => 'required',
                    'current_value' => 'required',
                    'estimated_value' => 'required',
                    'purpose' => 'required',
                    'with_guaranteed_payout' => 'required',
                    'exclusive_conjugal' => 'required',
                    'share_self' => 'required',
                ], [
                    'part.required' => '\'part\' in a JSON object named \'data\' is required',
                    'mfuitf_id.required' => '\'mfuitf_id\' in a JSON object named \'data\' is required',
                    'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                    'company.required' => '\'company\' in a JSON object named \'data\' is required',
                    'no_of_units.required' => '\'no_of_units\' in a JSON object named \'data\' is required',
                    'current_value.required' => '\'current_value\' in a JSON object named \'data\' is required',
                    'estimated_value.required' => '\'estimated_value\' in a JSON object named \'data\' is required',
                    'purpose.required' => '\'purpose\' in a JSON object named \'data\' is required',
                    'with_guaranteed_payout.required' => '\'with_guaranteed_payout\' in a JSON object named \'data\' is required',
                    'exclusive_conjugal.required' => '\'exclusive_conjugal\' in a JSON object named \'data\' is required',
                    'share_self.required' => '\'share_self\' in a JSON object named \'data\' is required',
                ]);
            }
        }else if($op == 'Bonds'){
            if($method == 'post'){
                $validator = Validator::make($request, [  // use ->all() for legitimate Request
                    'part' => 'required',
                    'bond_id' => 'required',
                    'client_id' => 'required',
                    'bonds_issuer' => 'required',
                    'estimated_value' => 'required',
                    'purpose' => 'required',
                    'exclusive_conjugal' => 'required',
                    'share_self' => 'required',
                ], [
                    'part.required' => '\'part\' in a JSON object named \'data\' is required',
                    'bond_id.required' => '\'bond_id\' in a JSON object named \'data\' is required',
                    'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                    'bonds_issuer.required' => '\'bonds_issuer\' in a JSON object named \'data\' is required',
                    'estimated_value.required' => '\'estimated_value\' in a JSON object named \'data\' is required',
                    'purpose.required' => '\'purpose\' in a JSON object named \'data\' is required',
                    'exclusive_conjugal.required' => '\'exclusive_conjugal\' in a JSON object named \'data\' is required',
                    'share_self.required' => '\'share_self\' in a JSON object named \'data\' is required',
                ]);
            }
        }else if($op == 'StockInCompanies'){
            if($method == 'post'){
                try {
                    if(intval($request['exclusive_conjugal']) == 1){
                        $validator = Validator::make($request, [  // use ->all() for legitimate Request
                            'part' => 'required',
                            'sic_id' => 'required',
                            'client_id' => 'required',
                            'company_alias' => 'required',
                            'no_of_shares' => 'required',
                            'current_book_value' => 'required',
                            'estimated_value' => 'required',
                            'purpose' => 'required',
                            'exclusive_conjugal' => 'required',
                            'share_self' => 'required',
                        ], [
                            'part.required' => '\'part\' in a JSON object named \'data\' is required',
                            'sic_id.required' => '\'sic_id\' in a JSON object named \'data\' is required',
                            'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                            'company_alias.required' => '\'company_alias\' in a JSON object named \'data\' is required',
                            'no_of_shares.required' => '\'no_of_shares\' in a JSON object named \'data\' is required',
                            'current_book_value.required' => '\'current_book_value\' in a JSON object named \'data\' is required',
                            'estimated_value.required' => '\'estimated_value\' in a JSON object named \'data\' is required',
                            'purpose.required' => '\'purpose\' in a JSON object named \'data\' is required',
                            'exclusive_conjugal.required' => '\'exclusive_conjugal\' in a JSON object named \'data\' is required',
                            'share_self.required' => '\'share_self\' in a JSON object named \'data\' is required',
                        ]);
                    }else if(intval($request['exclusive_conjugal']) == 2){
                        $validator = Validator::make($request, [  // use ->all() for legitimate Request
                            'part' => 'required',
                            'sic_id' => 'required',
                            'client_id' => 'required',
                            'company_alias' => 'required',
                            'no_of_shares' => 'required',
                            'current_book_value' => 'required',
                            'estimated_value' => 'required',
                            'purpose' => 'required',
                            'exclusive_conjugal' => 'required',
                            'share_self' => 'required',
                            'share_spouse' => 'required',
                        ], [
                            'part.required' => '\'part\' in a JSON object named \'data\' is required',
                            'sic_id.required' => '\'sic_id\' in a JSON object named \'data\' is required',
                            'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                            'company_alias.required' => '\'company_alias\' in a JSON object named \'data\' is required',
                            'no_of_shares.required' => '\'no_of_shares\' in a JSON object named \'data\' is required',
                            'current_book_value.required' => '\'current_book_value\' in a JSON object named \'data\' is required',
                            'estimated_value.required' => '\'estimated_value\' in a JSON object named \'data\' is required',
                            'purpose.required' => '\'purpose\' in a JSON object named \'data\' is required',
                            'exclusive_conjugal.required' => '\'exclusive_conjugal\' in a JSON object named \'data\' is required',
                            'share_self.required' => '\'share_self\' in a JSON object named \'data\' is required',
                            'share_spouse.required' => '\'share_spouse\' in a JSON object named \'data\' is required',
                        ]);
                    }
                } catch (\Throwable $th) {
                    $validator = Validator::make($request, [  // use ->all() for legitimate Request
                        'part' => 'required',
                        'sic_id' => 'required',
                        'client_id' => 'required',
                        'company_alias' => 'required',
                        'no_of_shares' => 'required',
                        'current_book_value' => 'required',
                        'estimated_value' => 'required',
                        'purpose' => 'required',
                        'exclusive_conjugal' => 'required',
                        'share_self' => 'required',
                        'share_spouse' => 'required',
                    ], [
                        'part.required' => '\'part\' in a JSON object named \'data\' is required',
                        'sic_id.required' => '\'sic_id\' in a JSON object named \'data\' is required',
                        'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                        'company_alias.required' => '\'company_alias\' in a JSON object named \'data\' is required',
                        'no_of_shares.required' => '\'no_of_shares\' in a JSON object named \'data\' is required',
                        'current_book_value.required' => '\'current_book_value\' in a JSON object named \'data\' is required',
                        'estimated_value.required' => '\'estimated_value\' in a JSON object named \'data\' is required',
                        'purpose.required' => '\'purpose\' in a JSON object named \'data\' is required',
                        'exclusive_conjugal.required' => '\'exclusive_conjugal\' in a JSON object named \'data\' is required',
                        'share_self.required' => '\'share_self\' in a JSON object named \'data\' is required',
                        'share_spouse.required' => '\'share_spouse\' in a JSON object named \'data\' is required',
                    ]);
                }
            }
        }else if($op == 'lifeAndHealthInsurance'){
            if($method == 'post'){
                $validator = Validator::make($request, [  // use ->all() for legitimate Request
                    'client_id' => 'required',
                    'insurance_company' => 'required',
                    'policy_owner' => 'required',
                    'policy_number' => 'required',
                    'month_year_issued' => 'required',
                    'type_of_policy' => 'required',
                    'insured' => 'required',
                    'purpose' => 'required',
                    'with_guaranteed_payout' => 'required',
                    'faceamount_fpcf' => 'required',
                    'faceamount_etax' => 'required',
                    'faceamount_edistribution' => 'required',
                    'faceamount_total' => 'required',
                    'current_account_value' => 'required',
                ], [
                    'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                    'insurance_company.required' => '\'insurance_company\' in a JSON object named \'data\' is required',
                    'policy_owner.required' => '\'policy_owner\' in a JSON object named \'data\' is required',
                    'policy_number.required' => '\'policy_number\' in a JSON object named \'data\' is required',
                    'month_year_issued.required' => '\'month_year_issued\' in a JSON object named \'data\' is required',
                    'type_of_policy.required' => '\'type_of_policy\' in a JSON object named \'data\' is required',
                    'insured.required' => '\'insured\' in a JSON object named \'data\' is required',
                    'purpose.required' => '\'purpose\' in a JSON object named \'data\' is required',
                    'with_guaranteed_payout.required' => '\'with_guaranteed_payout\' in a JSON object named \'data\' is required',
                    'faceamount_fpcf.required' => '\'faceamount_fpcf\' in a JSON object named \'data\' is required',
                    'faceamount_etax.required' => '\'faceamount_etax\' in a JSON object named \'data\' is required',
                    'faceamount_edistribution.required' => '\'faceamount_edistribution\' in a JSON object named \'data\' is required',
                    'faceamount_total.required' => '\'faceamount_total\' in a JSON object named \'data\' is required',
                    'current_account_value.required' => '\'current_account_value\' in a JSON object named \'data\' is required',
                ]);
            }
        }else if($op == 'beneficiaries'){
            if($method == 'post'){
                $validator = Validator::make($request, [  // use ->all() for legitimate Request
                    'full_name' => 'required|min:4|max:60',
                    'percent_share' => 'required|numeric|integer|min:1|max:100',
                    'designation' => 'required',
                    'priority' => 'required',
                ], [
                    'full_name.required' => '\'full_name\' in a JSON object named \'data\' is required',
                    'full_name.min' => '\'full_name\' in a JSON object named \'data\' is a minimum of 4 characters',
                    'full_name.max' => '\'full_name\' in a JSON object named \'data\' is a maximum of 60 characters',
                    'percent_share.required' => '\'percent_share\' in a JSON object named \'data\' is required',
                    'percent_share.numeric' => '\'percent_share\' in a JSON object named \'data\' requires numeric format',
                    'percent_share.integer' => '\'percent_share\' in a JSON object named \'data\' requires integer format',
                    'percent_share.min' => '\'percent_share\' in a JSON object named \'data\' requires a minimum of 1%',
                    'percent_share.max' => '\'percent_share\' in a JSON object named \'data\' requires a maximum of 100%',
                    'designation.required' => '\'designation\' in a JSON object named \'data\' is required',
                    'priority.required' => '\'priority\' in a JSON object named \'data\' is required',
                ]);
            }else if($method == 'get'){
                $validator = Validator::make($request->all(), [  // use ->all() for legitimate Request
                    'client_id' => 'required',
                ], [
                    'client_id.required' => '\'client_id\' is required',
                ]);
            }
        }else if($op == 'updateClient'){
            if($method == 'put'){
                $validator = Validator::make($request->all(), [  // use ->all() for legitimate Request
                    'client_id' => 'required',
                    'civil_status' => 'required',
                ], [        
                    'client_id.required' => '\'client_id\' field is required.',
                    'civil_status.required' => '\'civil_status\' field is required.',
                ]);
            }else if($method == 'post' && $request['type'] == 2){
                $validator = Validator::make($request, [  // use ->all() for legitimate Request
                    'client_id' => 'required',
                    'first_name' => 'required',
                    'health_condition' => 'required',
                    'withWithout_children' => 'required',
                    'status' => 'required',
                ], [        
                    'client_id.required' => '\'client_id\' field is required.',
                    'first_name.required' => '\'first_name\' field is required.',
                    'health_condition.required' => '\'health_condition\' field is required.',
                    'withWithout_children.required' => '\'withWithout_children\' field is required.',
                    'status.required' => '\'status\' field is required.',
                ]);
            }else if($method == 'post' && $request['type'] != 2){
                $validator = Validator::make($request, [  // use ->all() for legitimate Request
                    'client_id' => 'required',
                    'first_name' => 'required',
                    'health_condition' => 'required',
                ], [        
                    'client_id.required' => '\'client_id\' field is required.',
                    'first_name.required' => '\'first_name\' field is required.',
                    'health_condition.required' => '\'health_condition\' field is required.',
                ]);
            }
        }else if($op == 'clientIsActive'){
            $validator = Validator::make($request->all(), [  // use ->all() for legitimate Request  
                'client_id' => 'required',
            ], [
                'client_id.required' => '\'client_id\' is required',
            ]);
        }else if($op == 'FamilyHomeRealEstate'){
            if($method == 'post'){
                if(intval($op2['isHome']) == 1){
                    try {
                        if(intval($request['exclusive_conjugal']) == 2){
                            $validator = Validator::make($request, [  // use ->all() for legitimate Request
                                'part' => 'required',
                                'client_id' => 'required',
                                'location' => 'required',
                                'estimated_value' => 'required',
                                'share_self' => 'required',
                                'share_spouse' => 'required',
                            ], [
                                'part.required' => '\'part\' in a JSON object named \'data\' is required',
                                'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                                'location.required' => '\'location\' in a JSON object named \'data\' is required',
                                'estimated_value.required' => '\'estimated_value\' in a JSON object named \'data\' is required',
                                'share_self.required' => '\'share_self\' in a JSON object named \'data\' is required',
                                'share_spouse.required' => '\'share_spouse\' in a JSON object named \'data\' is required',
                            ]);
                        }else if(intval($request['exclusive_conjugal']) == 1){
                            $validator = Validator::make($request, [  // use ->all() for legitimate Request
                                'part' => 'required',
                                'client_id' => 'required',
                                'location' => 'required',
                                'estimated_value' => 'required',
                                'share_self' => 'required',
                            ], [
                                'part.required' => '\'part\' in a JSON object named \'data\' is required',
                                'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                                'location.required' => '\'location\' in a JSON object named \'data\' is required',
                                'estimated_value.required' => '\'estimated_value\' in a JSON object named \'data\' is required',
                                'share_self.required' => '\'share_self\' in a JSON object named \'data\' is required',   
                            ]);
                        }
                    } catch (\Throwable $th) {
                        $validator = Validator::make($request, [  // use ->all() for legitimate Request
                            'part' => 'required',
                            'client_id' => 'required',
                            'location' => 'required',
                            'estimated_value' => 'required',
                            'share_self' => 'required',
                            'share_spouse' => 'required',
                        ], [
                            'part.required' => '\'part\' in a JSON object named \'data\' is required',
                            'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                            'location.required' => '\'location\' in a JSON object named \'data\' is required',
                            'estimated_value.required' => '\'estimated_value\' in a JSON object named \'data\' is required',
                            'share_self.required' => '\'share_self\' in a JSON object named \'data\' is required',
                            'share_spouse.required' => '\'share_spouse\' in a JSON object named \'data\' is required',
                        ]);
                    }
                }else if(intval($op2['isHome']) == 2){
                    try {
                        if(intval($request['exclusive_conjugal']) == 2){
                            $validator = Validator::make($request, [  // use ->all() for legitimate Request
                                'part' => 'required',
                                'client_id' => 'required',
                                'location' => 'required',
                                'estimated_value' => 'required',
                                'purpose' => 'required',
                                'share_self' => 'required',
                                'share_spouse' => 'required',
                            ], [
                                'part.required' => '\'part\' in a JSON object named \'data\' is required',
                                'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                                'location.required' => '\'location\' in a JSON object named \'data\' is required',
                                'estimated_value.required' => '\'estimated_value\' in a JSON object named \'data\' is required',
                                'purpose.required' => '\'purpose\' in a JSON object named \'data\' is required',
                                'share_self.required' => '\'share_self\' in a JSON object named \'data\' is required',
                                'share_spouse.required' => '\'share_spouse\' in a JSON object named \'data\' is required',
                            ]);
                        }else if(intval($request['exclusive_conjugal']) == 1){
                            $validator = Validator::make($request, [  // use ->all() for legitimate Request
                                'part' => 'required',
                                'client_id' => 'required',
                                'location' => 'required',
                                'estimated_value' => 'required',
                                'purpose' => 'required',
                                'share_self' => 'required',
                            ], [
                                'part.required' => '\'part\' in a JSON object named \'data\' is required',
                                'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                                'location.required' => '\'location\' in a JSON object named \'data\' is required',
                                'estimated_value.required' => '\'estimated_value\' in a JSON object named \'data\' is required',
                                'purpose.required' => '\'purpose\' in a JSON object named \'data\' is required',
                                'share_self.required' => '\'share_self\' in a JSON object named \'data\' is required',
                            ]);
                        }
                    } catch (\Throwable $th) {
                        $validator = Validator::make($request, [  // use ->all() for legitimate Request
                            'part' => 'required',
                            'client_id' => 'required',
                            'location' => 'required',
                            'estimated_value' => 'required',
                            'purpose' => 'required',
                            'share_self' => 'required',
                            'share_spouse' => 'required',
                        ], [
                            'part.required' => '\'part\' in a JSON object named \'data\' is required',
                            'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                            'location.required' => '\'location\' in a JSON object named \'data\' is required',
                            'estimated_value.required' => '\'estimated_value\' in a JSON object named \'data\' is required',
                            'purpose.required' => '\'purpose\' in a JSON object named \'data\' is required',
                            'share_self.required' => '\'share_self\' in a JSON object named \'data\' is required',
                            'share_spouse.required' => '\'share_spouse\' in a JSON object named \'data\' is required',
                        ]);
                    }
                }
            }
        }else if($op == 'Vehicles'){
            if($method == 'post'){
                if(intval($request['exclusive_conjugal']) == 1){
                    $validator = Validator::make($request, [  // use ->all() for legitimate Request
                        'part' => 'required',
                        'share_self' => 'required',
                    ], [
                        'part.required' => '\'part\' in a JSON object named \'data\' is required',
                        'share_self.required' => '\'share_self\' in a JSON object named \'data\' is required',
                    ]);
                }else if(intval($request['exclusive_conjugal']) == 2){
                    $validator = Validator::make($request, [  // use ->all() for legitimate Request
                        'part' => 'required',
                        'client_id' => 'required',
                        'share_self' => 'required',
                        'share_spouse' => 'required',
                    ], [
                        'part.required' => '\'part\' in a JSON object named \'data\' is required',
                        'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                        'share_self.required' => '\'share_self\' in a JSON object named \'data\' is required',
                        'share_spouse.required' => '\'share_spouse\' in a JSON object named \'data\' is required',
                    ]);
                }
            }
        }else if($op == 'PersonalAssets'){
            if($method == 'post'){
                if(intval($request['exclusive_conjugal']) == 1){
                    $validator = Validator::make($request, [  // use ->all() for legitimate Request
                        'part' => 'required',
                        'pa_id' => 'required',
                        'client_id' => 'required',
                        'estimated_value' => 'required',
                        'purpose' => 'required',
                        'with_guaranteed_payout' => 'required',
                        'share_self' => 'required',
                    ], [
                        'part.required' => '\'part\' in a JSON object named \'data\' is required',
                        'pa_id.required' => '\'pa_id\' in a JSON object named \'data\' is required',
                        'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                        'estimated_value.required' => '\'estimated_value\' in a JSON object named \'data\' is required',
                        'purpose.required' => '\'purpose\' in a JSON object named \'data\' is required',
                        'with_guaranteed_payout.required' => '\'with_guaranteed_payout\' in a JSON object named \'data\' is required',
                        'share_self.required' => '\'share_self\' in a JSON object named \'data\' is required',
                    ]);
                }else if(intval($request['exclusive_conjugal']) == 2){
                    $validator = Validator::make($request, [  // use ->all() for legitimate Request
                        'part' => 'required',
                        'pa_id' => 'required',
                        'client_id' => 'required',
                        'estimated_value' => 'required',
                        'purpose' => 'required',
                        'with_guaranteed_payout' => 'required',
                        'share_self' => 'required',
                        'share_spouse' => 'required',
                    ], [
                        'part.required' => '\'part\' in a JSON object named \'data\' is required',
                        'pa_id.required' => '\'pa_id\' in a JSON object named \'data\' is required',
                        'client_id.required' => '\'client_id\' in a JSON object named \'data\' is required',
                        'estimated_value.required' => '\'estimated_value\' in a JSON object named \'data\' is required',
                        'purpose.required' => '\'purpose\' in a JSON object named \'data\' is required',
                        'with_guaranteed_payout.required' => '\'with_guaranteed_payout\' in a JSON object named \'data\' is required',
                        'share_self.required' => '\'share_self\' in a JSON object named \'data\' is required',
                        'share_spouse.required' => '\'share_spouse\' in a JSON object named \'data\' is required',
                    ]);
                }
            }else if($method == 'get'){

            }
        }else if($op == 'famCompReq_checkCID' || $op == 'Lia_checkCID' || $op == 'PA_checkID' || $op == 'Vehicles_checkCID' || $op == 'LAHI_checkCID' || $op == 'SIC_checkCID' || $op == 'Bonds_checkCID' || $op == 'MF_checkCID' || $op == 'R_checkCID' || $op == 'SFP_checkCID' || $op == 'FC_checkCID' || $op == 'CAD_checkCID'){
            $validator = Validator::make($request->all(), [  // use ->all() for legitimate Request  
                'client_id' => 'required',
            ], [
                'client_id.required' => '\'client_id\' is required',
            ]);
        }else if($op == 'individualClient'){
            $validator = Validator::make($request->all(), [  // use ->all() for legitimate Request  
                'client_id' => 'required',
                'agent_id' => 'required',
            ], [
                'client_id.required' => '\'client_id\' is required',
                'agent_id.required' => '\'agent_id\' is required. Clients are managed by specific Agent.',
            ]);
        }else if($op == 'FamilyHomesRealEstate'){
            $validator = Validator::make($request->all(), [  // use ->all() for legitimate Request  
                'client_id' => 'required',
            ], [
                'client_id.required' => '\'client_id\' is required',
            ]);
        }else if($op == 'Liabilities'){
            $validator = Validator::make($request, [  // use ->all() for legitimate Request  
                'client_id' => 'required',
                'type_of_liability' => 'required',
            ], [
                'client_id.required' => '\'client_id\' is required',
                'type_of_liability.required' => '\'type_of_liability\' is required',
            ]);
        }else if($op == 'Client_checkAgentID'){
            $validator = Validator::make($request->all(), [  // use ->all() for legitimate Request  
                'agent_id' => 'required',
            ], [
                'agent_id.required' => '\'agent_id\' is required. Clients are manage by specific agent.',
            ]);
        }else if($op == 'ClientSearch'){
            $validator = Validator::make($request->all(), [
                'agent_id' => 'required',
                'dataToSearch' => 'required',
            ], [
                'agent_id.required' => '\'agent_id\' field is required.',
                'dataToSearch.required' => '\'dataToSearch\' field is required.',
            ]);
        }else if($op == 'DreamsAndAspiration'){
            $validator = Validator::make($request, [ // use ->all() for legitimate Request  
                'client_id' => 'required',
                'goals' => 'required',
                'target_amount' => 'required',
                'timeline' => 'required',
            ], [
                'client_id.required' => '\'client_id\' field is required.',
                'goals.required' => '\'goals\' field is required.',
                'target_amount.required' => '\'target_amount\' field is required.',
                'timeline.required' => '\'timeline\' field is required.',
            ]);
        }else if($op == 'PlanningSolutions'){
            $validator = Validator::make($request, [ // use ->all() for legitimate Request  
                'monthy_budget1' => 'required',
                'monthy_budget2' => 'required',
                'actual_net_cash_flow1' => 'required',
                'actual_net_cash_flow2' => 'required',
            ], [
                'monthy_budget1.required' => '\'monthy_budget1\' field is required.',
                'monthy_budget2.required' => '\'monthy_budget2\' field is required.',
                'actual_net_cash_flow1.required' => '\'actual_net_cash_flow1\' field is required.',
                'actual_net_cash_flow2.required' => '\'actual_net_cash_flow2\' field is required.',
            ]);
        }else if($op == 'PlanSol_todos'){
            $validator = Validator::make($request, [ // use ->all() for legitimate Request  
                'todo' => 'required',
                'date_todo' => 'required',
            ], [
                'todo.required' => '\'todo\' field is required.',
                'date_todo.required' => '\'date_todo\' field is required.',
            ]);
        }else if($op == 'saveNewFamProFNA'){
            $validator = Validator::make($request, [ // use ->all() for legitimate Request
                'selected_financial_priority_id' => 'required',
                'selected_financial_priority_rank' => 'required',
                'average_infla_rate' => 'required',
                'annual_outflows_cl' => 'required',
                'annual_outflows_sp' => 'required',
                'years_family_support' => 'required',
                'annual_support_from_cl' => 'required',
                'annual_support_from_sp' => 'required',
                'years_support_cl' => 'required',
                'years_support_sp' => 'required',
                'addx_life_insurance_cl' => 'required',
                'addx_life_insurance_sp' => 'required'
            ], [
                'selected_financial_priority_id.required' => '\'selected_financial_priority_id\' field is required.',
                'selected_financial_priority_rank.required' => '\'selected_financial_priority_rank\' field is required.',
                'average_infla_rate.required' => '\'average_infla_rate\' field is required.',
                'annual_outflows_cl.required' => '\'annual_outflows_cl\' field is required.',
                'annual_outflows_sp.required' => '\'annual_outflows_sp\' field is required.',
                'years_family_support.required' => '\'years_family_support\' field is required.',
                'annual_support_from_cl.required' => '\'annual_support_from_cl\' field is required.',
                'annual_support_from_sp.required' => '\'annual_support_from_sp\' field is required.',
                'years_support_cl.required' => '\'years_support_cl\' field is required.',
                'years_support_sp.required' => '\'years_support_sp\' field is required.',
                'addx_life_insurance_cl.required' => '\'addx_life_insurance_cl\' field is required.',
                'addx_life_insurance_sp.required' => '\'addx_life_insurance_sp\' field is required.',
            ]);
        }else if($op == 'DebtFinalExpenses'){
            $validator = Validator::make($request, [ // use ->all() for legitimate Request  
                'debFin_id' => 'required',
                'client_id' => 'required',
                'debFinList_id' => 'required',
                'amount_on_client' => 'required',
                'amount_on_spouse' => 'required'
            ], [
                'debFin_id.required' => '\'debFin_id\' field is required.',
                'client_id.required' => '\'client_id\' field is required.',
                'debFinList_id.required' => '\'debFinList_id\' field is required.',
                'amount_on_client.required' => '\'amount_on_client\' field is required.',
                'amount_on_spouse.required' => '\'amount_on_spouse\' field is required.',
            ]);
        }else if($op == 'ExistLifeInsureCov'){
            $validator = Validator::make($request, [ // use ->all() for legitimate Request  
                'exLifeInsCov_id' => 'required',
                'client_id' => 'required',
                'exLifeInsCovList_id' => 'required',
                'amount_on_client' => 'required',
                'amount_on_spouse' => 'required'
            ], [
                'exLifeInsCov_id.required' => '\'exLifeInsCov_id\' field is required.',
                'client_id.required' => '\'client_id\' field is required.',
                'exLifeInsCovList_id.required' => '\'exLifeInsCovList_id\' field is required.',
                'amount_on_client.required' => '\'amount_on_client\' field is required.',
                'amount_on_spouse.required' => '\'amount_on_spouse\' field is required.',
            ]);
        }else if($op == 'saveNewRetirementPlanFNA'){
            $validator = Validator::make($request, [ // use ->all() for legitimate Request  
                'retPFNA_id' => 'required',
                'reason_retirement_plann' => 'required',
                'how_retirement_looks' => 'required',
            ], [
                'retPFNA_id.required' => '\'retPFNA_id\' field is required.',
                'client_id.required' => '\'client_id\' field is required.',
                'reason_retirement_plann.required' => '\'reason_retirement_plann\' field is required.',
                'how_retirement_looks.required' => '\'how_retirement_looks\' field is required.',
            ]);
        }else if($op == 'retirementExpenses'){
            $validator = Validator::make($request, [ // use ->all() for legitimate Request  
                'dr_id' => 'required',
                'client_id' => 'required',
                'presentVal_amt_cl' => 'required',
                'presentVal_amt_sp' => 'required',
            ], [
                'dr_id.required' => '\'dr_id\' field is required.',
                'client_id.required' => '\'client_id\' field is required.',
                'presentVal_amt_cl.required' => '\'presentVal_amt_cl\' field is required.',
                'presentVal_amt_sp.required' => '\'presentVal_amt_sp\' field is required.',
            ]);
        }else if($op == 'educPlannFNA'){
            $validator = Validator::make($request, [ // use ->all() for legitimate Request
                'reason_educPlan_important' => 'required',
                'dreams_for_children' => 'required',
            ], [
                'reason_educPlan_important.required' => '\'reason_educPlan_important\' field is required.',
                'dreams_for_children.required' => '\'dreams_for_children\' field is required.',
            ]);
        }else if($op == 'childrenSelected'){
            $validator = Validator::make($request, [ // use ->all() for legitimate Request  
                'famComp_id' => 'required',
                'child_birthday' => 'required',
                'age_for_college' => 'required',
            ], [
                'famComp_id.required' => '\'famComp_id\' field is required.',
                'child_birthday.required' => '\'child_birthday\' field is required.',
                'age_for_college.required' => '\'age_for_college\' field is required.',
            ]);
        }else if($op == 'EducationPlannExp'){
            $validator = Validator::make($request, [ // use ->all() for legitimate Request  
                'familyComp_id' => 'required',
                'presentVal_amt' => 'required',
                'fundNeeded_futureVal_amt' => 'required',
            ], [
                'familyComp_id.required' => '\'familyComp_id\' field is required.',
                'presentVal_amt.required' => '\'presentVal_amt\' field is required.',
                'fundNeeded_futureVal_amt.required' => '\'fundNeeded_futureVal_amt\' field is required.',
            ]);
        }else if($op == 'healthFundPlannFNA'){
            $validator = Validator::make($request, [ // use ->all() for legitimate Request  
                'reason_health_fund' => 'required',
                'financialSit_with_illMember' => 'required',
                'financial_impact' => 'required',
            ], [
                'reason_health_fund.required' => '\'reason_health_fund\' field is required.',
                'financialSit_with_illMember.required' => '\'financialSit_with_illMember\' field is required.',
                'financial_impact.required' => '\'financial_impact\' field is required.',
            ]);
        }else if($op == 'healthCovSumm'){
            $validator = Validator::make($request, [ // use ->all() for legitimate Request  
                'healthCovSum_id' => 'required',
                'type' => 'required',
                'famComp_id' => 'required',
                'policyRef_no' => 'required',
                'origin' => 'required',
            ], [
                'healthCovSum_id.required' => '\'healthCovSum_id\' field is required.',
                'type.required' => '\'type\' field is required.',
                'famComp_id.required' => '\'famComp_id\' field is required.',
                'policyRef_no.required' => '\'policyRef_no\' field is required.',
                'origin.required' => '\'origin\' field is required.',
            ]);
        }else if($op == 'targetLimits'){
            $validator = Validator::make($request, [ // use ->all() for legitimate Request  
                'type' => 'required',
                'famComp_id' => 'required',
            ], [
                'type.required' => '\'type\' field is required.',
                'famComp_id.required' => '\'famComp_id\' field is required.',
            ]);
        }else if($op == 'logoutUser'){
            $validator = Validator::make($request->all(), [  // use ->all() for legitimate Request  
                'client_id' => 'required',
                'token' => 'required',
            ], [
                'client_id.required' => '\'client_id\' is required',
                'token.required' => '\'token\' is required',
            ]);
        }else if($op == 'newChildForFamComp'){
            $validator = Validator::make($request->all(), [  // use ->all() for legitimate Request  
                'client_id' => 'required',
                'user_id' => 'required',
                'user_token' => 'required',
                'fn' => 'required',
                'fs' => 'required',
                'bd' => 'required',
                'hc' => 'required',
                'status' => 'required',
            ], [
                'client_id.required' => '\'client_id\' is required',
                'user_id.required' => '\'user_id\' or ID of Agent is required',
                'user_token.required' => '\'user_token\' is required',
                'fn.required' => '\'First Name (fn)\' is required',
                'fs.required' => '\'Family Support (fs)\' is required',
                'bd.required' => '\'Birthday (bd)\' is required',
                'hc.required' => '\'Health Condition (hc)\' is required',
                'status.required' => '\'Status\' is required',
            ]);
        }else if($op == 'educFundAllocated'){
            $validator = Validator::make($request, [  // use ->all() for legitimate Request  
                'table_id' => 'required',
                'projValEducAge' => 'required',
                'from_table' => 'required',
            ], [
                'table_id.required' => '\'table_id\' is required',
                'projValEducAge.required' => '\'projValEducAge\' is required',
                'from_table.required' => '\'from_table\' is required',
            ]);
        }else if($op == 'educFundGuaranteedPaySched'){
            $validator = Validator::make($request, [  // use ->all() for legitimate Request  
                'table_id' => 'required',
                'policyNo' => 'required',
                'regPayoutAmt' => 'required',
                'ageStartPayout' => 'required',
                'startYearForPayout' => 'required',
                'freqOfPayout' => 'required',
                'ageChildForLastPayout' => 'required',
                'endYearForPayout' => 'required',
                'from_table' => 'required',
            ], [
                'table_id.required' => '\'table_id\' is required',
                'policyNo.required' => '\'policyNo\' is required',
                'regPayoutAmt.required' => '\'regPayoutAmt\' is required',
                'ageStartPayout.required' => '\'ageStartPayout\' is required',
                'startYearForPayout.required' => '\'startYearForPayout\' is required',
                'freqOfPayout.required' => '\'freqOfPayout\' is required',
                'ageChildForLastPayout.required' => '\'ageChildForLastPayout\' is required',
                'endYearForPayout.required' => '\'endYearForPayout\' is required',
                'from_table.required' => '\'from_table\' is required',
            ]);
        }else if($op == 'healthCovSum'){
            $validator = Validator::make($request->all(), [  // use ->all() for legitimate Request  
                'type' => 'required',
                'famComp_id' => 'required',
                'policyRef_no' => 'required',
                'origin' => 'required',
            ], [
                'type.required' => '\'type\' is required',
                'famComp_id.required' => '\'famComp_id\' is required',
                'policyRef_no.required' => '\'policyRef_no\' is required',
                'origin.required' => '\'origin\' is required',
            ]);
        }
        if ($validator->fails()) {
            return [
                'validate' => 0,
                'errors' => $validator
            ];
        }else if($validator == null){
            return [
                'validate' => 0,
                'errors' => $this->interruptedError
            ];
        }
        return ['validate' => 1]; 
    }
    public function validateUserAndRequest($request){
        $result = array();
        // Field validation... This section requires a request to include client_id, user_id, and a token.
        $validated = $this->validateFields('validateGetRequest', 'get', $request, null);
        if(!$validated['validate']){
            $result = [
                'success' => 0,
                'message' => $validated['errors']->errors()
            ];
            return $result;
        }
        // User/Requestor validation... This section validates/verifies the requestor using user_id and token.
        $verified = $this->verifyUser($request, 0);
        if(!$verified["success"]){
            $result = [
                'success' => 0,
                'message' => $this->throwErrorCustom('Unauthorized user.', 401)
            ];
        }else{
            $result = [
                'success' => 1,
                'message' => $verified
            ];
        }
        return $result;
    }

    // Inserting on the DB
    public function insertIntoDB($table, $flattenedData, $request, $other){
        try {
            if($table == 'SelectedFinancialPriorities'){
                $result = SelectedFinancialPriorities::create([
                    'q_sfp_clnt_id' => $flattenedData['client_id'],
                    'q_sfp_fp_id' => $flattenedData['financial_priority_list_id'],
                    'q_sfp_rank' => $flattenedData['rankNumber'],
                    'q_sfp_reason' => $flattenedData['reason'] !== "" ? $flattenedData['reason'] : null,
                    'q_sfp_dateCreated' => date('Y-m-d'),
                ]);
                if($result){
                    $cntClient = FnaCompletion::where('q_fnaComp_clientID', $flattenedData['client_id'])->where('q_fnaComp_FNA', "Financial Priorities")->count();
                    if($cntClient == 0){
                        FnaCompletion::create([
                            'q_fnaComp_clientID' => $flattenedData['client_id'],
                            'q_fnaComp_FNA' => "Financial Priorities",
                            'q_fnaComp_statusValue' => 10,
                            'q_fnaComp_dateCreated' => date('Y-m-d')
                        ]);
                    }
                }
            }else if($table == 'CashFlowData'){
                if($request['flowType'] == 0){
                    $result = CashFlowData::create([
                        'q_cfd_cfl_id' => $other ? $other : $flattenedData['cfl_id'],
                        'q_cfd_clnt_id' => $flattenedData['client_id'],
                        'q_cfd_isNeedsForClient' => 0,
                        'q_cfd_cfda_clientAmt' => $flattenedData['cfda_client_amt'],
                        'q_cfd_isNeedsForSpouse' => 0,
                        'q_cfd_cfda_spouseAmt' => $flattenedData['cfda_spouse_amt'],
                        'q_cfd_dateUpdated' => date('Y-m-d'),
                        'q_cfd_dateCreated' => date('Y-m-d'),
                    ]);
                    return $result->id;
                }else if($request['flowType'] == 1){
                    $result = CashFlowData::create([
                        'q_cfd_cfl_id' => $other ? $other : $flattenedData['cfl_id'],
                        'q_cfd_clnt_id' => $flattenedData['client_id'],
                        'q_cfd_isNeedsForClient' => 0,
                        'q_cfd_cfda_clientAmtExpense' => $flattenedData['cfda_client_amount_expense'],
                        'q_cfd_isNeedsForSpouse' => 0,
                        'q_cfd_cfda_spouseAmtExpense' => $flattenedData['cfda_spouse_amount_expense'],
                        'q_cfd_targetRetireAmtInPercent' => $flattenedData['target_retirementAmntPercent'],
                        'q_cfd_dateUpdated' => date('Y-m-d'),
                        'q_cfd_dateCreated' => date('Y-m-d'),
                    ]);
                    if($result){
                        $cntClient = FnaCompletion::where('q_fnaComp_clientID', $flattenedData['client_id'])->where('q_fnaComp_FNA', "Cash Flow")->count();
                        if($cntClient == 0){
                            FnaCompletion::create([
                                'q_fnaComp_clientID' => $flattenedData['client_id'],
                                'q_fnaComp_FNA' => "Cash Flow",
                                'q_fnaComp_statusValue' => 10,
                                'q_fnaComp_dateCreated' => date('Y-m-d')
                            ]);
                        }
                        return $result->id;
                    }
                    return 0;
                }else if($request['flowType'] == 2){
                    $result = CashFlowData::create([
                        'q_cfd_cfl_id' => $other ? $other : $flattenedData['cfl_id'],
                        'q_cfd_clnt_id' => $flattenedData['client_id'],
                        'q_cfd_isNeedsForClient' => $flattenedData['isNeedClient'],
                        'q_cfd_cfda_clientAmtExpense' => $flattenedData['cfda_client_amount_expense'],
                        'q_cfd_isNeedsForSpouse' => $flattenedData['isNeedSpouse'],
                        'q_cfd_cfda_spouseAmtExpense' => $flattenedData['cfda_spouse_amount_expense'],
                        'q_cfd_cfdb_clientAmt' => $flattenedData['cfdb_clientAmt'],
                        'q_cfd_cfdb_spouseAmt' => $flattenedData['cfdb_spouseAmt'],
                        'q_cfd_dateUpdated' => date('Y-m-d'),
                        'q_cfd_dateCreated' => date('Y-m-d'),
                    ]);
                    if($result){
                        $cntClient = FnaCompletion::where('q_fnaComp_clientID', $flattenedData['client_id'])->where('q_fnaComp_FNA', "Cash Flow")->count();
                        if($cntClient == 0){
                            FnaCompletion::create([
                                'q_fnaComp_clientID' => $flattenedData['client_id'],
                                'q_fnaComp_FNA' => "Cash Flow",
                                'q_fnaComp_statusValue' => 10,
                                'q_fnaComp_dateCreated' => date('Y-m-d')
                            ]);
                        }
                        return $result->id;
                    }
                    return 0;
                }
                return 0;
            }else if($table == 'Recommendations'){
                Recommendations::create([
                    'q_recommx_cfa_id' => $other['q_cfa_id'],
                    'q_recommx_recommxDetails' => $flattenedData['description'],
                    'q_recommx_isInflowOutflow' => $request['isInflowOutflow'] == 0 ? 1 : 2,
                    'q_recommx_dateCreated' => date('Y-m-d'),
                ]);
                return 1;
            }else if($table == 'CashFlowAnalysis'){
                if($request['flowType'] == 0){
                    $result = CashFlowAnalysis::create([
                        'q_cfa_clnt_id' => $request['client_id'],
                        'q_cfa_targetCashInF_client' => $request['client'],
                        'q_cfa_targetCashInF_spouse' => $request['spouse'],
                        'q_cfa_dateUpdated' => date('Y-m-d'),
                        'q_cfa_dateCreated' => date('Y-m-d'),
                    ]);
                    return $result->id;
                }else if($request['flowType'] == 1){
                    CashFlowAnalysis::create([
                        'q_cfa_clnt_id' => $request['client_id'],
                        'q_cfa_targetCashOutF_client' => $request['clientExpenses'],
                        'q_cfa_targetCashOutF_spouse' => $request['spouseExpenses'],
                        'q_cfa_clientShareRFN' => $request['clientshare_rfn'],
                        'q_cfa_spouseShareRFN' => $request['spouseshare_rfn'],
                        'q_cfa_reduceCFAttempt' => $request['reduce_cf_attempt'],
                        'q_cfa_dateUpdated' => date('Y-m-d'),
                        'q_cfa_dateCreated' => date('Y-m-d'),
                    ]);
                    return 1;
                }else if($request['flowType'] == 2){
                    CashFlowAnalysis::create([
                        'q_cfa_clnt_id' => $request['client_id'],
                        'q_cfa_expectedSavings' => $request['expected_savings'],
                        'q_cfa_goesWell' => $request['goes_well'],
                        'q_cfa_dateUpdated' => date('Y-m-d'),
                        'q_cfa_dateCreated' => date('Y-m-d'),
                    ]);
                    return 1;
                }
                return 0;
            }else if($table == 'Heirs'){
                $err = 0;
                foreach ($request['heirs'] as $index => $data) {
                    $flattenedData_v2 = $this->flattenData($data);
                    if(intval($flattenedData_v2['part']) == intval($other)){
                        $result = Heir::create([
                            'q_heir_famComp_id' => $flattenedData_v2['famComp_id'],
                            'q_heir_tableID' => $flattenedData,
                            'q_heir_fromTable' => $flattenedData_v2['from_table'],
                            'q_heir_indicatedPercentage' => $flattenedData_v2['indicated_percent'],
                        ]);
                        if(!$result){
                            $err = 1;
                            break;
                        }
                    }
                }
                if($err){
                    return 0;
                }
                return 1;
            }else if($table == 'CashAndDeposits'){
                $result = CashAndDeposits::create([
                    'q_cad_clientID' => $flattenedData['client_id'],
                    'q_cad_bank' => $flattenedData['bank'],
                    'q_cad_accountDescription' => $flattenedData['account_description'],
                    'q_cad_typeOfAccount' => $flattenedData['type_of_account'],
                    'q_cad_estimatedValue' => $flattenedData['estimated_value'],
                    'q_cad_purpose' => $flattenedData['purpose'],
                    'q_cad_withGuaranteedPayout' => $flattenedData['with_guaranteed_payout'],
                    'q_cad_exclusiveConjugal' => $flattenedData['exclusive_conjugal'],
                    'q_cad_shareSelf' => $flattenedData['share_self'],
                    'q_cad_shareSpouse' => $flattenedData['share_spouse'],
                    'q_cad_dateUpdated' => date('Y-m-d'),
                    'q_cad_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'family_composition'){
                $result = FamilyComposition::create([
                    'q_famComp_clientID' => $flattenedData['client_id'],
                    'q_famComp_firstName' => strtoupper($flattenedData['first_name']),
                    'q_famComp_lastName' => strtoupper($flattenedData['last_name']),
                    'q_famComp_middleInitial' => strtoupper(Str::substr($flattenedData['middle_initial'], 0, 1)),
                    'q_famComp_compType' => $flattenedData['type'],
                    'q_famComp_withWithoutChildren' => $flattenedData['withWithout_children'],
                    'q_famComp_dateMarried' => $flattenedData['date_married'],
                    'q_famComp_birthDay' => $flattenedData['birthday'],
                    'q_famComp_healthCondition' => $flattenedData['health_condition'],
                    'q_famComp_status' => $flattenedData['status'],
                    'q_famComp_revocableLiving' => $flattenedData['revocable_living'],
                    'q_famComp_revocableLast' => $flattenedData['revocable_last'],
                    'q_famComp_dateUpdated' => date('Y-m-d'),
                    'q_famComp_dateCreated' => date('Y-m-d')
                ]);
                if($result){
                    if($flattenedData['type'] == 2 || $flattenedData['type'] == "2" || $flattenedData['type'] == '2'){
                        Client::where('q_clnt_id', $flattenedData['client_id'])->where('q_clnt_haveChildren', '!=',1)->update(['q_clnt_haveChildren' => 1]);
                    }
                    $cntClient = FnaCompletion::where('q_fnaComp_clientID', $flattenedData['client_id'])->where('q_fnaComp_FNA', "Family Composition")->count();
                    if($cntClient == 0){
                        FnaCompletion::create([
                            'q_fnaComp_clientID' => $flattenedData['client_id'],
                            'q_fnaComp_FNA' => "Family Composition",
                            'q_fnaComp_statusValue' => 10,
                            'q_fnaComp_dateCreated' => date('Y-m-d')
                        ]);
                    }
                }
            }else if($table == 'Receivables'){
                $result = Receivables::create([
                    'q_rec_clientID' => $flattenedData['client_id'],
                    'q_rec_debtorName' => $flattenedData['name_of_debtor'],
                    'q_rec_loanPurpose' => $flattenedData['loan_purpose'],
                    'q_rec_estimatedValue' => $flattenedData['estimated_value'],
                    'q_rec_percentCollectability' => $flattenedData['percentage_collectibility'],
                    'q_rec_exclusiveConjugal' => $flattenedData['exclusive_conjugal'],
                    'q_rec_shareSelf' => $flattenedData['share_self'],
                    'q_rec_shareSpouse' => $flattenedData['share_spouse'],
                    'q_rec_withCli' => $flattenedData['with_cli'],
                    'q_rec_renewalMonth' => $flattenedData['renewal_month'],
                    'q_rec_dateUpdated' => date('Y-m-d'),
                    'q_rec_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'MutualFunds'){
                $result = MutualFundsUITF::create([
                    'q_uitf_clientID' => $flattenedData['client_id'],
                    'q_uitf_accNo' => $flattenedData['policyNo'],
                    'q_uitf_company' => $flattenedData['company'],
                    'q_uitf_noOfUnits' => $flattenedData['no_of_units'],
                    'q_uitf_currentValuePerUnits' => $flattenedData['current_value'],
                    'q_uitf_estimatedValue' => $flattenedData['estimated_value'],
                    'q_uitf_purpose' => $flattenedData['purpose'],
                    'q_uitf_withGuaranteedPayout' => $flattenedData['with_guaranteed_payout'],
                    'q_uitf_exclusiveConjugal' => $flattenedData['exclusive_conjugal'],
                    'q_uitf_shareSelf' => $flattenedData['share_self'],
                    'q_uitf_shareSpouse' => $flattenedData['share_spouse'],
                    'q_uitf_dateUpdated' => date('Y-m-d'),
                    'q_uitf_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'Bonds'){
                $result = Bonds::create([
                    'q_bond_clientID' => $flattenedData['client_id'],
                    'q_bond_accNo' => $flattenedData['policyNo'],
                    'q_bond_clientID' => $flattenedData['client_id'],
                    'q_bond_issuer' => $flattenedData['bonds_issuer'],
                    'q_bond_maturityDate' => $flattenedData['maturity_date'],
                    'q_bond_perValue' => $flattenedData['par_value'],
                    'q_bond_estimatedValue' => $flattenedData['estimated_value'],
                    'q_bond_purpose' => $flattenedData['purpose'],
                    'q_bond_withGuaranteedPayout' => $flattenedData['with_guaranteed_payout'],
                    'q_bond_exclusiveConjugal' => $flattenedData['exclusive_conjugal'],
                    'q_bond_shareSelf' => $flattenedData['share_self'],
                    'q_bond_shareSpouse' => $flattenedData['share_spouse'],
                    'q_bond_dateUpdated' => date('Y-m-d'),
                    'q_bond_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'StockInCompanies'){
                $result = StocksInCompanies::create([
                    'q_stoComp_clientID' => $flattenedData['client_id'],
                    'q_stoComp_companyAlias' => $flattenedData['company_alias'],
                    'q_stoComp_accNo' => $flattenedData['policyNo'],
                    'q_stoComp_noOfShares' => $flattenedData['no_of_shares'],
                    'q_stoComp_currentBookValueShare' => $flattenedData['current_book_value'],
                    'q_stoComp_estimatedValue' => $flattenedData['estimated_value'],
                    'q_stoComp_purpose' => $flattenedData['purpose'],
                    'q_stoComp_exclusiveConjugal' => $flattenedData['exclusive_conjugal'],
                    'q_stoComp_shareSelf' => $flattenedData['share_self'],
                    'q_stoComp_shareSpouse' => $flattenedData['share_spouse'],
                    'q_stoComp_isListed' => $request['isListed'],
                    'q_stoComp_dateUpdated' => date('Y-m-d'),
                    'q_stoComp_dateCreated' => date('Y-m-d'),
                ]);
                return $result->id;
            }else if($table == 'LifeAndHealthInsurance'){
                try {
                    $result = LifeAndHealthInsurance::create([
                        'q_lifeHealth_clientID' => $flattenedData['client_id'],
                        'q_lifeHealth_fromAetosAdviser' => $request['fromAdviser'],
                        'q_lifeHealth_insuranceCompany' => $flattenedData['insurance_company'],
                        'q_lifeHealth_policyOwner' => $flattenedData['policy_owner'],
                        'q_lifeHealth_policyNumber' => $flattenedData['policy_number'],
                        'q_lifeHealth_typeOfPolicy' => $flattenedData['type_of_policy'],
                        'q_lifeHealth_monthYearIssued' => $flattenedData['month_year_issued'],
                        'q_lifeHealth_insured' => $flattenedData['insured'],
                        'q_lifeHealth_purpose' => $flattenedData['purpose'],
                        'q_lifeHealth_withGuaranteedPayout' => $flattenedData['with_guaranteed_payout'],
                        'q_lifeHealth_faceAmountFamilyProtection' => $flattenedData['faceamount_fpcf'],
                        'q_lifeHealth_faceAmountEstateTax' => $flattenedData['faceamount_etax'],
                        'q_lifeHealth_faceAmountEstateDistribution' => $flattenedData['faceamount_edistribution'],
                        'q_lifeHealth_faceAmount' => $flattenedData['faceamount_total'],
                        'q_lifeHealth_currentFundValueEstimated' => $flattenedData['current_account_value'],
                        'q_lifeHealth_dateEffective' => date('Y-m-d'),
                        'q_lifeHealth_dateUpdated' => date('Y-m-d'),
                        'q_lifeHealth_dateCreated' => date('Y-m-d'),
                    ]);
                    return $result->id;
                } catch (\Throwable $th) {
                    return $th;
                }
            }else if($table == 'Beneficiaries'){
                $collectedErrorsAndSuccess = array();
                $success = 0; $i = 0; $counter = 0;
                foreach ($request['beneficiaries'] as $index => $data) {
                    $flattenedData_v2 = $this->flattenData($data);
                    $countBen = 0;
                    $countBen = $this->checkAndCount('CountBeneficiaries', $flattenedData_v2, null, $flattenedData);
                    if($countBen == 0){
                        $result = Beneficiaries::create([
                            'q_benex_lifeHeath_id' => $flattenedData,
                            'q_benex_fullName' => $flattenedData_v2['full_name'],
                            'q_benex_percentShare' => $flattenedData_v2['percent_share'],
                            'q_benex_designation' => $flattenedData_v2['designation'],
                            'q_benex_priority' => $flattenedData_v2['priority'],
                            'q_benex_dateUpdated' => date('Y-m-d'),  
                            'q_benex_dateCreated' => date('Y-m-d'),
                        ]);
                        $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Beneficiaries has been successfully added for Life and Health Insurance."];
                        $success++;
                    }else{
                        $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => " A new Beneficiaries cannot be made. Consult the system administrator." ];          
                    }
                    $i++;
                    $counter++;
                }
                return $collectedErrorsAndSuccess;
            }else if($table == 'FamilyHomeRealEstate'){
                $result = FamilyHomeEstate::create([
                    'q_homeEstate_clientID' => $flattenedData['client_id'],
                    'q_homeEstate_tctNumber' => $flattenedData['tct_cct_number'],
                    'q_homeEstate_cityMunLocation' => $flattenedData['location'],
                    'q_homeEstate_areaSQM' => $flattenedData['area_sqm'],
                    'q_homeEstate_zoneValueEstimate' => $flattenedData['bir_zonal_value'],
                    'q_homeEstate_estimatedValue' => $flattenedData['estimated_value'],
                    'q_homeEstate_exclusiveConjugal' => $flattenedData['exclusive_conjugal'],
                    'q_homeEstate_purpose' => $flattenedData['purpose'],
                    'q_homeEstate_withGuaranteedPayout' => $flattenedData['with_guaranteed_payout'],
                    'q_homeEstate_shareSelf' => $flattenedData['share_self'],
                    'q_homeEstate_shareSpouse' => $flattenedData['share_spouse'],
                    'q_homeEstate_withPropertyInsurance' => $flattenedData['with_property_insurance'],
                    'q_homeEstate_renewalMonth' => $flattenedData['renewalMonth'],
                    'q_homeEstate_isHome' => $request['isHome'],
                    'q_homeEstate_dateUpdated' => date('Y-m-d'),
                    'q_homeEstate_dateCreated' => date('Y-m-d'),
                ]);
                return $result->id;
            }else if($table == 'Vehicles'){
                $result = Vehicles::create([
                    'q_vehicle_clientID' => $flattenedData['client_id'],
                    'q_vehicle_plateNo' => $flattenedData['plate_no'],
                    'q_vehicle_accNo' => $flattenedData['policyNo'],
                    'q_vehicle_type' => $flattenedData['vehicle_type'],
                    'q_vehicle_estimatedValue' => $flattenedData['estimated_value'],
                    'q_vehicle_exclusiveConjugal' => $flattenedData['exclusive_conjugal'],
                    'q_vehicle_shareSelf' => $flattenedData['share_self'],
                    'q_vehicle_shareSpouse' => $flattenedData['share_spouse'],
                    'q_vehicle_withInsurance' => $flattenedData['with_vehicle_insurance'],
                    'q_vehicle_renewalMonth' => $flattenedData['renewalMonth'],
                    'q_vehicle_dateUpdated' => date('Y-m-d'),
                    'q_vehicle_dateCreated' => date('Y-m-d'),
                ]);
                return $result->id;
            }else if($table == 'PersonalAssets'){
                $result = PersonalAssets::create([
                    'q_perAs_clientID' => $flattenedData['client_id'],
                    'q_perAs_accNo' => $flattenedData['policyNo'],
                    'q_perAs_item' => $flattenedData['item_name'],
                    'q_perAs_estimatedValue' => $flattenedData['estimated_value'],
                    'q_perAs_purpose' => $flattenedData['purpose'],
                    'q_perAs_withGuaranteedPayout' => $flattenedData['with_guaranteed_payout'],
                    'q_perAs_exclusiveConjugal' => $flattenedData['exclusive_conjugal'],
                    'q_perAs_shareSelf' => $flattenedData['share_self'],
                    'q_perAs_shareSpouse' => $flattenedData['share_spouse'],
                    'q_perAs_dateUpdated' => date('Y-m-d'),
                    'q_perAs_dateCreated' => date('Y-m-d'),
                ]);
                return $result->id;
            }else if($table == 'Liabilities'){
                $result = Liabilities::create([
                    'q_lia_clientID' => $flattenedData['client_id'],
                    'q_lia_creditorName' => $flattenedData['name_of_creditor'],
                    'q_lia_type' => $flattenedData['type_of_liability'],
                    'q_lia_totalUnpaidAmt' => $flattenedData['total_unpaid_amount'],
                    'q_lia_annualInterestRate' => $flattenedData['annual_interest_rate'],
                    'q_lia_amtOfMRI' => $flattenedData['amount_of_mri'],
                    'q_lia_uncovered' => $flattenedData['amount_uncovered'],
                    'q_lia_exclusiveConjugal' => $flattenedData['exclusive_conjugal'],
                    'q_lia_shareSelf' => $flattenedData['share_self'],
                    'q_lia_shareSpouse' => $flattenedData['share_spouse'],
                    'q_lia_dateUpdated' => date('Y-m-d'),
                    'q_lia_dateCreated' => date('Y-m-d'),
                ]);
                if($result){
                    $cntClient = FnaCompletion::where('q_fnaComp_clientID', $flattenedData['client_id'])->where('q_fnaComp_FNA', "Networth Inventory")->count();
                    if($cntClient == 0){
                        FnaCompletion::create([
                            'q_fnaComp_clientID' => $flattenedData['client_id'],
                            'q_fnaComp_FNA' => "Networth Inventory",
                            'q_fnaComp_statusValue' => 10,
                            'q_fnaComp_dateCreated' => date('Y-m-d')
                        ]);
                    }
                }
                return $result->id;
            }else if($table == 'DreamsAndAspiration'){
                $result = DreamsAspirations::create([
                    'q_dreAsp_client_id' => $flattenedData['client_id'],
                    'q_dreAsp_goals' => $flattenedData['goals'],
                    'q_dreAsp_otherGoals' => $flattenedData['other_goals'],
                    'q_dreAsp_typeTargetAmount' => $flattenedData['target_amount'],
                    'q_dreAsp_timeline' => $flattenedData['timeline'],
                    'q_dreAsp_dateUpdated' => date('Y-m-d'),
                    'q_dreAsp_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'New_CashFlowList'){
                $res = CashFlowList::where('q_cfl_type',$request['flowType'] !== 2 ? $request['flowType'] : 1)->orderBy('q_cfl_order','DESC')->first();
                $result = CashFlowList::create([
                    'q_cfl_descripx' => $flattenedData['cfl_description'],
                    'q_cfl_type' => $request['flowType'] !== 2 ? $request['flowType'] : 1,
                    'q_cfl_isOther' => 1,
                    'q_cfl_order' => intval($res->q_cfl_order) + 1,
                    'q_cfl_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'PlanningSolutions'){
                $result = FinancialPlannSol::create([
                    'q_finPlSo_clientID' => $request['client_id'],
                    'q_finPlSo_forTable' => $request['fromTable'],
                    'q_finPlSo_monthlyBud1' => $flattenedData['monthy_budget1'],
                    'q_finPlSo_monthlyBud2' => $flattenedData['monthy_budget2'],
                    'q_finPlSo_actNetCashflow1' => $flattenedData['actual_net_cash_flow1'],
                    'q_finPlSo_actNetCashflow2' => $flattenedData['actual_net_cash_flow2'],
                    'q_finPlSo_advisorSuggestion' => $flattenedData['advisor_suggestion'],
                    'q_finPlSo_modePayment' => $flattenedData['modePayment'],
                    'q_finPlSo_formPayment' => $flattenedData['formPayment'],
                    'q_finPlSo_status' => $flattenedData['status'],
                    'q_finPlSo_goalRev' => $flattenedData['goal_review'],
                    'q_finPlSo_meetAdvisorOn' => $flattenedData['meet_advisor_on'],
                    'q_finPlSo_dateUpdated' => date('Y-m-d'),
                    'q_finPlSo_dateCreated' => date('Y-m-d'),
                ]);
                return $result->id;
            }else if($table == 'PlanSol_Todos'){
                $result = Todos::create([
                    'q_tdo_clientID' => $request['client_id'],
                    'q_tdo_agentID' => $request['userID'],
                    'q_tdo_isForClientAgent' => $flattenedData['todos_forClientAgent'],
                    'q_tdo_descripx' => $flattenedData['todo'],
                    'q_tdo_dateTodo' => $flattenedData['date_todo'],
                    'q_tdo_fromTable' => $request['fromTable'],
                    'q_tdo_isSeen' => 0,
                    'q_tdo_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'saveNewFamProFNA'){
                $result = FPFNA::create([
                    'q_fpfna_clientID' => $request['client_id'],
                    'q_fpfna_finImpDeceased' => $flattenedData['financial_impact_deceased'],
                    'q_fpfna_avgInflaRate' => $flattenedData['average_infla_rate'],
                    'q_fpfna_annOutflowsCL' => $flattenedData['annual_outflows_cl'],
                    'q_fpfna_annOutflowsSP' => $flattenedData['annual_outflows_sp'],
                    'q_fpfna_yearsFamSupp' => $flattenedData['years_family_support'],
                    'q_fpfna_annSuppFromCL' => $flattenedData['annual_support_from_cl'],
                    'q_fpfna_annSuppFromSP' => $flattenedData['annual_support_from_sp'],
                    'q_fpfna_yearsSuppCL' => $flattenedData['years_support_cl'],
                    'q_fpfna_yearsSuppSP' => $flattenedData['years_support_sp'],
                    'q_fpfna_addxLifeInsuCL' => $flattenedData['addx_life_insurance_cl'],
                    'q_fpfna_addxLifeInsuSP' => $flattenedData['addx_life_insurance_sp'],
                    'q_fpfna_dateUpdated' => date('Y-m-d'),
                    'q_fpfna_dateCreated' => date('Y-m-d'),
                ]);
                if($flattenedData['selected_financial_priority_id'] !== 0){
                    $resultSFP = SelectedFinancialPriorities::where('q_sfp_clnt_id',$request['client_id'])->where('q_sfp_id',$flattenedData['selected_financial_priority_id'])
                        ->update([
                        'q_sfp_rank' => $flattenedData['selected_financial_priority_rank'],
                        'q_sfp_reason' => $flattenedData['reason_family_protect_important'],
                    ]);
                }else{
                    $resultSFP = SelectedFinancialPriorities::create([
                        'q_sfp_clnt_id' => $request['client_id'],
                        'q_sfp_fp_id' => intval($flattenedData['selected_financial_priority_rank']) == 0 ? 1 : intval($flattenedData['selected_financial_priority_rank']),
                        'q_sfp_rank' => $flattenedData['selected_financial_priority_rank'],
                        'q_sfp_reason' => $flattenedData['reason_family_protect_important'],
                        'q_sfp_dateCreated' => date('Y-m-d'),                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
                    ]);
                }
            }else if($table == 'DebtsAndFinalExpenses'){
                $result = DebtsAndFinalExpenses::create([
                    'q_debtFinExp_client_id' => $flattenedData['client_id'],
                    'q_debtFinExp_debFinList_id' => $other ? $other : $flattenedData['debFinList_id'],
                    'q_debtFinExp_amount_on_client' => $flattenedData['amount_on_client'],
                    'q_debtFinExp_amount_on_spouse' => $flattenedData['amount_on_spouse'],
                    'q_debtFinExp_dateUpdated' => date('Y-m-d'),
                    'q_debtFinExp_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'DebtsAndFinalList'){
                $orderNo = DebtsAndFinalList::orderBy('q_debtFin_order', 'DESC')->first();
                $result = DebtsAndFinalList::create([
                    'q_debtFin_debFinList_desc' => $flattenedData['debFinList_description'],
                    'q_debtFin_isOtherCreated' => 1,
                    'q_debtFin_order' => ((int) $orderNo->q_debtFin_order) + 1,
                    'q_debtFin_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'LifeInsuranceCovList'){
                $orderNo = LifeInsuranceCoverageList::orderBy('q_lifeInsCovList_order', 'DESC')->first();
                $result = LifeInsuranceCoverageList::create([
                    'q_lifeInsCovList_debFinListDesc' => $flattenedData['exLifeInsCovList_description'],
                    'q_lifeInsCovList_isOtherCreated' => 1,
                    'q_lifeInsCovList_order' => ((int) $orderNo->q_lifeInsCovList_order) + 1,
                    'q_lifeInsCovList_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'ExistLifeInsureCov'){
                $result = ExistingLifeInsuranceCoverage::create([
                    'q_exLifeInsCov_clientID' => $flattenedData['client_id'],
                    'q_exLifeInsCov_listID' => $other ? $other : $flattenedData['exLifeInsCovList_id'],
                    'q_exLifeInsCov_amtClient' => $flattenedData['amount_on_client'],
                    'q_exLifeInsCov_amtSpouse' => $flattenedData['amount_on_spouse'],
                    'q_exLifeInsCov_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'RetirementPlanFNA'){
                $result = RetirePlannFNA::create([
                    'q_retPFNA_clientID' => $request['client_id'],
                    'q_retPFNAa_resRetPlann' => $flattenedData['reason_retirement_plann'],
                    'q_retPFNA_howRetLooks' => $flattenedData['how_retirement_looks'],
                    'q_retPFNA_currAgeCL' => $flattenedData['current_age_cl'],
                    'q_retPFNA_currAgeSP' => $flattenedData['current_age_sp'],
                    'q_retPFNA_ageRetCL' => $flattenedData['age_retirement_cl'],
                    'q_retPFNA_ageRetSP' => $flattenedData['age_retirement_sp'],
                    'q_retPFNA_lifeSpanCL' => $flattenedData['life_span_cl'],
                    'q_retPFNA_lifeSpanSP' => $flattenedData['life_span_sp'],
                    'q_retPFNA_avgInfaRate' => $flattenedData['avg_inflation_rate'],
                    'q_retPFNA_intRetirement' => $flattenedData['interest_retirement'],
                    'q_retPFNA_sssAnnualCL' => $flattenedData['sss_anual_cl'],
                    'q_retPFNA_sssAnnualSP' => $flattenedData['sss_anual_sp'],
                    'q_retPFNA_yrsSSSBenefitCL' => $flattenedData['yrs_sss_benefit_cl'],
                    'q_retPFNA_yrsSSSBenefitSP' => $flattenedData['yrs_sss_benefit_sp'],
                    'q_retPFNA_companyBenefitRetCL' => $flattenedData['comp_benefit_ret_cl'],
                    'q_retPFNA_companyBenefitRetSP' => $flattenedData['comp_benefit_ret_sp'],
                    'q_retPFNA_yrsCompanyBenefitCL' => $flattenedData['yrs_comp_benefit_cl'],
                    'q_retPFNA_yrsCompanyBenefitSP' => $flattenedData['yrs_comp_benefit_sp'],
                    'q_retPFNA_dateUpdated' => date('Y-m-d'),
                    'q_retPFNA_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'RetirementExpensesList'){
                $result = RetirementExpList::create([
                    'q_retExpList_description' => $flattenedData['retExpList_description'],
                    'q_retExpList_isOther' => 1,
                    'q_retExpList_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'RetirementExpenses'){
                $result = RetirementExp::create([
                    'q_retExp_clientID' => $flattenedData['client_id'],
                    'q_retExp_retExpList_id' => $other ? $other : $flattenedData['retExpList_id'],
                    'q_retExp_presentValAmtCL' => $flattenedData['presentVal_amt_cl'],
                    'q_retExp_presentValAmtSP' => $flattenedData['presentVal_amt_sp'],
                    'q_retExp_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'EducationPlanFNA'){
                $result = EducPlannFNA::create([
                    'q_educPFNA_clientID' => $request['client_id'],
                    'q_educPFNA_resEducPlannImp' => $flattenedData['reason_educPlan_important'],
                    'q_educPFNA_dreamsForChildren' => $flattenedData['dreams_for_children'] == '' || $flattenedData['dreams_for_children'] == null ? '' : $flattenedData['dreams_for_children'],
                    'q_educPFNA_dateUpdated' => date('Y-m-d'),
                    'q_educPFNA_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'EducPlanExpensesList'){
                $result = EducPlannExpList::create([
                    'q_educPExpList_description' => $flattenedData['educPlanExpList_description'],
                    'q_educPExpList_isOther' => 1,
                    'q_educPExpList_order' => 1,
                    'q_educPExpList_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'EducPlanExpenses'){
                $result = EducPlannExp::create([
                    'q_educPExp_famComp_id' => $flattenedData['familyComp_id'],
                    'q_educPExp_educPExpList_id' => $other ? $other : $flattenedData['educPlanExpList_id'],
                    'q_educPExp_presentValAmt' => $flattenedData['presentVal_amt'],
                    'q_educPExp_avgInflationRate' => $flattenedData['avg_inflation_rate'],
                    'q_educPExp_futureNeededValAmt' => $flattenedData['fundNeeded_futureVal_amt'],
                    'q_educPExp_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'ChildrenEPFNA'){
                $result = SelectedChildEducPlan::create([
                    'q_selChildEduP_famComp_id' => $flattenedData['famComp_id'],
                    'q_selChildEduP_desiredSchool' => $flattenedData['desired_school'],
                    'q_selChildEduP_childAgeCollege' => $flattenedData['age_for_college'],
                    'q_selChildEduP_totalEducFundNeeded' => $flattenedData['total_educ_fund_needed'],
                    'q_selChildEduP_investmentAlloc' => $flattenedData['investment_alloc'],
                    'q_selChildEduP_dateUpdated' => date('Y-m-d'),
                    'q_selChildEduP_dateCreated' => date('Y-m-d'),
                ]);
                FamilyComposition::where('q_famComp_id', $flattenedData['famComp_id'])
                    ->update([
                    'q_famComp_birthDay' => $flattenedData['child_birthday'],
                    'q_famComp_dateUpdated' => date('Y-m-d'),
                ]);
            }else if($table == 'cHealthFundPlannFNA'){
                $result = HealthFundPlannFNA::create([
                    'q_healthFP_clientID' => $request['client_id'],
                    'q_healthFP_resHealthFund' => $flattenedData['reason_health_fund'],
                    'q_healthFP_finSitWithIllMember' => $flattenedData['financialSit_with_illMember'],
                    'q_healthFP_finImpact' => $flattenedData['financial_impact'],
                    'q_healthFP_dateUpdated' => date('Y-m-d'),
                    'q_healthFP_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'insHealthCovSummary'){
                $result = HealthCovSumm::create([
                    'q_healthCovSum_famCompID' => $flattenedData['famComp_id'],
                    'q_healthCovSum_type' => $flattenedData['type'],
                    'q_healthCovSum_policyRefNo' => $flattenedData['policyRef_no'],
                    'q_healthCovSum_origin' => $flattenedData['origin'],
                    'q_healthCovSum_amtInPatient' => $flattenedData['amt_in_patient'],
                    'q_healthCovSum_opInPatient' => $flattenedData['op_in_patient'],
                    'q_healthCovSum_amtOutPatient' => $flattenedData['amt_out_patient'],
                    'q_healthCovSum_opOutPatient' => $flattenedData['op_out_patient'],
                    'q_healthCovSum_amtCritIllLim' => $flattenedData['amt_critical_illness_limit'],
                    'q_healthCovSum_opCritIllLim' => $flattenedData['op_critical_illness_limit'],
                    'q_healthCovSum_amtLabLim' => $flattenedData['amt_lab_limit'],
                    'q_healthCovSum_amtHospIncome' => $flattenedData['amt_hosp_income'],
                    'q_healthCovSum_notes' => $flattenedData['notes'],
                    'q_healthCovSum_dateUpdated' => date('Y-m-d'),
                    'q_healthCovSum_dateCreated' => date('Y-m-d'),
                ]);
            }else if($table == 'cTargetLimits'){
                $result = TargetLimits::create([
                    'q_targLim_clientID' => $request['client_id'],
                    'q_targLim_famCompID' => $flattenedData['famComp_id'],
                    'q_targLim_type' => $flattenedData['type'],
                    'q_targLim_MBL_inPatient' => $flattenedData['MBL_inPatient'],
                    'q_targLim_ABL_inPatient' => $flattenedData['ABL_inPatient'],
                    'q_targLim_LBL_inPatient' => $flattenedData['LBL_inPatient'],
                    'q_targLim_MBL_outPatient' => $flattenedData['MBL_outPatient'],
                    'q_targLim_ABL_outPatient' => $flattenedData['ABL_outPatient'],
                    'q_targLim_LBL_outPatient' => $flattenedData['LBL_outPatient'],
                    'q_targLim_MBL_critIllness' => $flattenedData['MBL_critIllness'],
                    'q_targLim_ABL_critIllness' => $flattenedData['ABL_critIllness'],
                    'q_targLim_LBL_critIllness' => $flattenedData['LBL_critIllness'],
                    'q_targLim_labLimit' => $flattenedData['labLimit'],
                    'q_targLim_hospIncome' => $flattenedData['hospIncome'],
                    'q_targLim_dateUpdate' => date('Y-m-d'),
                    'q_targLim_dateCreated' => date('Y-m-d'),
                ]);
            }
            return $result->id;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    // Updating on the DB
    public function updateIntoDB($op, $flattenedData, $request, $other){
        try {
            $result = null;
            if($op == 'SelectedFinancialPriorities'){
                return SelectedFinancialPriorities::where('q_sfp_id', $flattenedData['sfp_id'])
                    ->update([
                        'q_sfp_fp_id' => $flattenedData['financial_priority_list_id'],
                        'q_sfp_rank' => $flattenedData['rankNumber'],
                        'q_sfp_reason' => $flattenedData['reason'],
                    ]);
            }else if($op == 'CashFlowData'){
                $result = CashFlowData::where('q_cfd_id', $flattenedData['cfd_id'])->where('q_cfd_clnt_id', $flattenedData['client_id'])
                    ->update([
                        'q_cfd_cfda_clientAmt' => $flattenedData['cfda_client_amt'],
                        'q_cfd_cfda_spouseAmt' => $flattenedData['cfda_spouse_amt'],
                        'q_cfd_dateUpdated' => date('Y-m-d'),
                    ]);
            }else if($op == 'CashFlowData_v2'){
                $result = CashFlowData::where('q_cfd_id', $flattenedData['cfd_id'])->where('q_cfd_clnt_id', $flattenedData['client_id'])
                    ->update([
                        'q_cfd_cfda_clientAmtExpense' => $flattenedData['cfda_client_amount_expense'],
                        'q_cfd_cfda_spouseAmtExpense' => $flattenedData['cfda_spouse_amount_expense'],
                        'q_cfd_targetRetireAmtInPercent' => $flattenedData['target_retirementAmntPercent'],
                        'q_cfd_dateUpdated' => date('Y-m-d'),
                    ]);
                $cntClient = FnaCompletion::where('q_fnaComp_clientID', $flattenedData['client_id'])->where('q_fnaComp_FNA', "Cash Flow")->count();
                    if($cntClient == 0){
                        FnaCompletion::create([
                            'q_fnaComp_clientID' => $flattenedData['client_id'],
                            'q_fnaComp_FNA' => "Cash Flow",
                            'q_fnaComp_statusValue' => 10,
                            'q_fnaComp_dateCreated' => date('Y-m-d')
                        ]);
                    }
            }else if($op == 'CashFlowData_v3'){
                $result = CashFlowData::where('q_cfd_id', $flattenedData['cfd_id'])->where('q_cfd_clnt_id', $flattenedData['client_id'])
                    ->update([
                        'q_cfd_isNeedsForClient' => $flattenedData['isNeedClient'],
                        'q_cfd_cfda_clientAmtExpense' => $flattenedData['cfda_client_amount_expense'],
                        'q_cfd_isNeedsForSpouse' => $flattenedData['isNeedSpouse'],
                        'q_cfd_cfda_spouseAmtExpense' => $flattenedData['cfda_spouse_amount_expense'],
                        'q_cfd_cfdb_clientAmt' => $flattenedData['cfdb_clientAmt'],
                        'q_cfd_cfdb_spouseAmt' => $flattenedData['cfdb_spouseAmt'],
                        'q_cfd_dateUpdated' => date('Y-m-d'),
                    ]);
            }else if($op == 'Recommendations'){
                return Recommendations::where('q_recommx_id', $flattenedData['recommendation_id'])
                    ->update([
                        'q_recommx_recommxDetails' => $flattenedData['description'],
                    ]);
            }else if($op == 'Heir'){
                return Heir::where('q_heir_id', $flattenedData['heir_id'])
                    ->update([
                        'q_heir_clientID' => $flattenedData['client_ID'],
                        'q_heir_tableID' => $flattenedData['table_ID'],
                        'q_heir_fromTable' => $flattenedData['from_table'],
                        'q_heir_fullName' => $flattenedData['heir_fullName'],
                        'q_heir_indicatedPercentage' => $flattenedData['indicated_percent'],
                    ]);
            }else if($op == 'updateClientIsActive'){
                return Client::where('q_clnt_id', $request['client_id'])
                    ->update([
                        'q_clnt_isActive' => $request['changeStatusTo']
                    ]);
            }else if($op == 'updateClient'){
                $result = Client::where('q_clnt_id', $request['client_id'])
                    ->update([
                        'q_clnt_weddDate' => $request['wedding_date'],
                        'q_clnt_healthCondi' => $request['health_condition'],
                        'q_clnt_healthCondiDetail' => $request['health_conditionDetail'],
                        'q_clnt_risk_cap' => $request['risk_capacity'],
                        'q_clnt_risk_attix' => $request['risk_attitude'],
                        'q_clnt_emailAddrx' => $request['email'],
                        'q_clnt_contNo' => $request['contact_number'],
                        'q_clnt_civilStatx' => $request['civil_status'],
                    ]);
                if($result){
                    $cntClient = FnaCompletion::where('q_fnaComp_clientID', $request['client_id'])->where('q_fnaComp_FNA', "Financial Plan Presentation")->count();
                    if($cntClient == 0){
                        FnaCompletion::create([
                            'q_fnaComp_clientID' => $request['client_id'],
                            'q_fnaComp_FNA' => "Financial Plan Presentation",
                            'q_fnaComp_statusValue' => 10,
                            'q_fnaComp_dateCreated' => date('Y-m-d')
                        ]);
                    }
                }
            }else if($op == 'StockInCompanies'){
                $result = StocksInCompanies::where('q_stoComp_id', $flattenedData['sic_id'])
                ->update([
                    'q_stoComp_clientID' => $flattenedData['client_id'],
                    'q_stoComp_companyAlias' => $flattenedData['company_alias'],
                    'q_stoComp_noOfShares' => $flattenedData['no_of_shares'],
                    'q_stoComp_currentBookValueShare' => $flattenedData['current_book_value'],
                    'q_stoComp_estimatedValue' => $flattenedData['estimated_value'],
                    'q_stoComp_purpose' => $flattenedData['purpose'],
                    'q_stoComp_exclusiveConjugal' => $flattenedData['exclusive_conjugal'],
                    'q_stoComp_shareSelf' => $flattenedData['share_self'],
                    'q_stoComp_shareSpouse' => $flattenedData['share_spouse'],
                    'q_stoComp_isListed' => $request['isListed'],
                    'q_stoComp_dateUpdated' => date('Y-m-d'),
                ]);
            }else if($op == 'Bonds'){
                $result = Bonds::where('q_bond_id', $flattenedData['bond_id'])
                    ->update([
                    'q_bond_clientID' => $flattenedData['client_id'],
                    'q_bond_accNo' => $flattenedData['policyNo'],
                    'q_bond_issuer' => $flattenedData['bonds_issuer'],
                    'q_bond_maturityDate' => $flattenedData['maturity_date'],
                    'q_bond_perValue' => $flattenedData['par_value'],
                    'q_bond_estimatedValue' => $flattenedData['estimated_value'],
                    'q_bond_purpose' => $flattenedData['purpose'],
                    'q_bond_withGuaranteedPayout' => $flattenedData['with_guaranteed_payout'],
                    'q_bond_exclusiveConjugal' => $flattenedData['exclusive_conjugal'],
                    'q_bond_shareSelf' => $flattenedData['share_self'],
                    'q_bond_shareSpouse' => $flattenedData['share_spouse'],
                    'q_bond_dateUpdated' => date('Y-m-d'),
                ]);
            }else if($op == 'MutualFunds'){
                $result = MutualFundsUITF::where('q_uitf_id', $flattenedData['mfuitf_id'])
                    ->update([
                    'q_uitf_clientID' => $flattenedData['client_id'],
                    'q_uitf_accNo' => $flattenedData['policyNo'],
                    'q_uitf_company' => $flattenedData['company'],
                    'q_uitf_noOfUnits' => $flattenedData['no_of_units'],
                    'q_uitf_currentValuePerUnits' => $flattenedData['current_value'],
                    'q_uitf_estimatedValue' => $flattenedData['estimated_value'],
                    'q_uitf_purpose' => $flattenedData['purpose'],
                    'q_uitf_withGuaranteedPayout' => $flattenedData['with_guaranteed_payout'],
                    'q_uitf_exclusiveConjugal' => $flattenedData['exclusive_conjugal'],
                    'q_uitf_shareSelf' => $flattenedData['share_self'],
                    'q_uitf_shareSpouse' => $flattenedData['share_spouse'],
                    'q_uitf_dateUpdated' => date('Y-m-d'),
                ]);
            }else if($op == 'Receivables'){
                $result = Receivables::where('q_rec_id', $flattenedData['receivables_id'])
                    ->update([
                    'q_rec_clientID' => $flattenedData['client_id'],
                    'q_rec_debtorName' => $flattenedData['name_of_debtor'],
                    'q_rec_loanPurpose' => $flattenedData['loan_purpose'],
                    'q_rec_estimatedValue' => $flattenedData['estimated_value'],
                    'q_rec_percentCollectability' => $flattenedData['percentage_collectibility'],
                    'q_rec_exclusiveConjugal' => $flattenedData['exclusive_conjugal'],
                    'q_rec_shareSelf' => $flattenedData['share_self'],
                    'q_rec_shareSpouse' => $flattenedData['share_spouse'],
                    'q_rec_withCli' => $flattenedData['with_cli'],
                    'q_rec_renewalMonth' => $flattenedData['renewal_month'],
                    'q_rec_dateUpdated' => date('Y-m-d'),
                ]);
            }else if($op == 'family_composition'){
                $result = FamilyComposition::where('q_famComp_id', $flattenedData['fc_id'])
                    ->update([
                    'q_famComp_clientID' => $flattenedData['client_id'],
                    'q_famComp_firstName' => $flattenedData['first_name'],
                    'q_famComp_lastName' => $flattenedData['last_name'],
                    'q_famComp_middleInitial' => $flattenedData['middle_initial'],
                    'q_famComp_compType' => $flattenedData['type'],
                    'q_famComp_withWithoutChildren' => $flattenedData['withWithout_children'],
                    'q_famComp_dateMarried' => $flattenedData['date_married'],
                    'q_famComp_birthDay' => $flattenedData['birthday'],
                    'q_famComp_healthCondition' => $flattenedData['health_condition'],
                    'q_famComp_status' => $flattenedData['status'],
                    'q_famComp_revocableLiving' => $flattenedData['revocable_living'],
                    'q_famComp_revocableLast' => $flattenedData['revocable_last'],
                    'q_famComp_dateUpdated' => date('Y-m-d'),
                ]);
                if($flattenedData['type'] == 2){
                    Client::where('q_clnt_id', $flattenedData['client_id'])->update(['q_clnt_haveChildren' => 1]);
                }
            }else if($op == 'CashAndDeposits'){
                $result = CashAndDeposits::where('q_cad_id', $flattenedData['cad_id'])
                    ->update([
                    'q_cad_clientID' => $flattenedData['client_id'],
                    'q_cad_bank' => $flattenedData['bank'],
                    'q_cad_accountDescription' => $flattenedData['account_description'],
                    'q_cad_typeOfAccount' => $flattenedData['type_of_account'],
                    'q_cad_estimatedValue' => $flattenedData['estimated_value'],
                    'q_cad_purpose' => $flattenedData['purpose'],
                    'q_cad_withGuaranteedPayout' => $flattenedData['with_guaranteed_payout'],
                    'q_cad_exclusiveConjugal' => $flattenedData['exclusive_conjugal'],
                    'q_cad_shareSelf' => $flattenedData['share_self'],
                    'q_cad_shareSpouse' => $flattenedData['share_spouse'],
                    'q_cad_dateUpdated' => date('Y-m-d'),
                ]);
            }else if($op == 'LifeAndHealthInsurance'){
                $result = LifeAndHealthInsurance::where('q_lifeHealth_id', $flattenedData['flahi_id'])
                    ->update([
                    'q_lifeHealth_clientID' => $flattenedData['client_id'],
                    'q_lifeHealth_fromAetosAdviser' => $request['fromAdviser'],
                    'q_lifeHealth_insuranceCompany' => $flattenedData['insurance_company'],
                    'q_lifeHealth_policyOwner' => $flattenedData['policy_owner'],
                    'q_lifeHealth_policyNumber' => $flattenedData['policy_number'],
                    'q_lifeHealth_typeOfPolicy' => $flattenedData['type_of_policy'],
                    'q_lifeHealth_monthYearIssued' => $flattenedData['month_year_issued'],
                    'q_lifeHealth_insured' => $flattenedData['insured'],
                    'q_lifeHealth_purpose' => $flattenedData['purpose'],
                    'q_lifeHealth_withGuaranteedPayout' => $flattenedData['with_guaranteed_payout'],
                    'q_lifeHealth_faceAmountFamilyProtection' => $flattenedData['faceamount_fpcf'],
                    'q_lifeHealth_faceAmountEstateTax' => $flattenedData['faceamount_etax'],
                    'q_lifeHealth_faceAmountEstateDistribution' => $flattenedData['faceamount_edistribution'],
                    'q_lifeHealth_faceAmount' => $flattenedData['faceamount_total'],
                    'q_lifeHealth_currentFundValueEstimated' => $flattenedData['current_account_value'],
                    'q_lifeHealth_dateUpdated' => date('Y-m-d'),
                ]);
            }else if($op == 'FamilyHomeRealEstate'){
                $result = FamilyHomeEstate::where('q_homeEstate_id', $flattenedData['fh_id'])
                    ->update([
                    'q_homeEstate_clientID' => $flattenedData['client_id'],
                    'q_homeEstate_tctNumber' => $flattenedData['tct_cct_number'],
                    'q_homeEstate_cityMunLocation' => $flattenedData['location'],
                    'q_homeEstate_areaSQM' => $flattenedData['area_sqm'],
                    'q_homeEstate_zoneValueEstimate' => $flattenedData['bir_zonal_value'],
                    'q_homeEstate_estimatedValue' => $flattenedData['estimated_value'],
                    'q_homeEstate_exclusiveConjugal' => $flattenedData['exclusive_conjugal'],
                    'q_homeEstate_purpose' => $flattenedData['purpose'],
                    'q_homeEstate_withGuaranteedPayout' => $flattenedData['with_guaranteed_payout'],
                    'q_homeEstate_shareSelf' => $flattenedData['share_self'],
                    'q_homeEstate_shareSpouse' => $flattenedData['share_spouse'],
                    'q_homeEstate_withPropertyInsurance' => $flattenedData['with_property_insurance'],
                    'q_homeEstate_renewalMonth' => $flattenedData['renewalMonth'],
                    'q_homeEstate_isHome' => $request['isHome'],
                    'q_homeEstate_dateUpdated' => date('Y-m-d'),
                ]);
            }else if($op == 'Vehicles'){
                $result = Vehicles::where('q_vehicle_id', $flattenedData['vehicles_id'])
                    ->update([
                    'q_vehicle_clientID' => $flattenedData['client_id'],
                    'q_vehicle_plateNo' => $flattenedData['plate_no'],
                    'q_vehicle_type' => $flattenedData['vehicle_type'],
                    'q_vehicle_estimatedValue' => $flattenedData['estimated_value'],
                    'q_vehicle_exclusiveConjugal' => $flattenedData['exclusive_conjugal'],
                    'q_vehicle_shareSelf' => $flattenedData['share_self'],
                    'q_vehicle_shareSpouse' => $flattenedData['share_spouse'],
                    'q_vehicle_withInsurance' => $flattenedData['with_vehicle_insurance'],
                    'q_vehicle_renewalMonth' => $flattenedData['renewalMonth'],
                    'q_vehicle_dateUpdated' => date('Y-m-d'),
                ]);
            }else if($op == 'PersonalAssets'){
                $result = PersonalAssets::where('q_perAs_id', $flattenedData['pa_id'])
                    ->update([
                    'q_perAs_clientID' => $flattenedData['client_id'],
                    'q_perAs_item' => $flattenedData['item_name'],
                    'q_perAs_estimatedValue' => $flattenedData['estimated_value'],
                    'q_perAs_purpose' => $flattenedData['purpose'],
                    'q_perAs_withGuaranteedPayout' => $flattenedData['with_guaranteed_payout'],
                    'q_perAs_exclusiveConjugal' => $flattenedData['exclusive_conjugal'],
                    'q_perAs_shareSelf' => $flattenedData['share_self'],
                    'q_perAs_shareSpouse' => $flattenedData['share_spouse'],
                    'q_perAs_dateUpdated' => date('Y-m-d'),
                ]);
            }else if($op == 'Liabilities'){
                $result = Liabilities::where('q_lia_id', $flattenedData['liabilities_id'])
                    ->update([
                    'q_lia_clientID' => $flattenedData['client_id'],
                    'q_lia_creditorName' => $flattenedData['name_of_creditor'],
                    'q_lia_type' => $flattenedData['type_of_liability'],
                    'q_lia_totalUnpaidAmt' => $flattenedData['total_unpaid_amount'],
                    'q_lia_annualInterestRate' => $flattenedData['annual_interest_rate'],
                    'q_lia_amtOfMRI' => $flattenedData['amount_of_mri'],
                    'q_lia_uncovered' => $flattenedData['amount_uncovered'],
                    'q_lia_exclusiveConjugal' => $flattenedData['exclusive_conjugal'],
                    'q_lia_shareSelf' => $flattenedData['share_self'],
                    'q_lia_shareSpouse' => $flattenedData['share_spouse'],
                    'q_lia_dateUpdated' => date('Y-m-d'),
                ]);
            }else if($op == 'DreamsAndAspiration'){
                $result = DreamsAspirations::where('q_dreAsp_id', $flattenedData['dreasp_id'])
                    ->update([
                    'q_dreAsp_client_id' => $flattenedData['client_id'],
                    'q_dreAsp_goals' => $flattenedData['goals'],
                    'q_dreAsp_otherGoals' => $flattenedData['other_goals'],
                    'q_dreAsp_typeTargetAmount' => $flattenedData['target_amount'],
                    'q_dreAsp_timeline' => $flattenedData['timeline'],
                    'q_dreAsp_dateUpdated' => date('Y-m-d'),
                ]);
            }else if($op == 'PlanningSolutions'){
                $result = FinancialPlannSol::where('q_finPlSo_id', $flattenedData['plansol_id'])
                    ->update([
                    'q_finPlSo_clientID' => $request['client_id'],
                    'q_finPlSo_forTable' => $request['fromTable'],
                    'q_finPlSo_monthlyBud1' => $flattenedData['monthy_budget1'],
                    'q_finPlSo_monthlyBud2' => $flattenedData['monthy_budget2'],
                    'q_finPlSo_actNetCashflow1' => $flattenedData['actual_net_cash_flow1'],
                    'q_finPlSo_actNetCashflow2' => $flattenedData['actual_net_cash_flow2'],
                    'q_finPlSo_advisorSuggestion' => $flattenedData['advisor_suggestion'],
                    'q_finPlSo_modePayment' => $flattenedData['modePayment'],
                    'q_finPlSo_formPayment' => $flattenedData['formPayment'],
                    'q_finPlSo_status' => $flattenedData['status'],
                    'q_finPlSo_goalRev' => $flattenedData['goal_review'],
                    'q_finPlSo_meetAdvisorOn' => $flattenedData['meet_advisor_on'],
                    'q_finPlSo_dateUpdated' => date('Y-m-d'),

                ]);
            }else if($op == 'saveNewFamProFNA'){
                $result = FPFNA::where('q_fpfna_id', $flattenedData['fpfna_id'])
                    ->update([
                    'q_fpfna_finImpDeceased' => $flattenedData['financial_impact_deceased'],
                    'q_fpfna_avgInflaRate' => $flattenedData['average_infla_rate'],
                    'q_fpfna_annOutflowsCL' => $flattenedData['annual_outflows_cl'],
                    'q_fpfna_annOutflowsSP' => $flattenedData['annual_outflows_sp'],
                    'q_fpfna_yearsFamSupp' => $flattenedData['years_family_support'],
                    'q_fpfna_annSuppFromCL' => $flattenedData['annual_support_from_cl'],
                    'q_fpfna_annSuppFromSP' => $flattenedData['annual_support_from_sp'],
                    'q_fpfna_yearsSuppCL' => $flattenedData['years_support_cl'],
                    'q_fpfna_yearsSuppSP' => $flattenedData['years_support_sp'],
                    'q_fpfna_addxLifeInsuCL' => $flattenedData['addx_life_insurance_cl'],
                    'q_fpfna_addxLifeInsuSP' => $flattenedData['addx_life_insurance_sp'],
                    'q_fpfna_dateUpdated' => date('Y-m-d'),
                ]);
                if(intval($flattenedData['selected_financial_priority_id']) !== 0){
                    $resultSFP = SelectedFinancialPriorities::where('q_sfp_clnt_id',$request['client_id'])->where('q_sfp_id',$flattenedData['selected_financial_priority_id'])
                        ->update([
                        'q_sfp_rank' => $flattenedData['selected_financial_priority_rank'],
                        'q_sfp_reason' => $flattenedData['reason_family_protect_important'],
                    ]);
                }else{
                    $resultSFP = SelectedFinancialPriorities::create([
                        'q_sfp_clnt_id' => $request['client_id'],
                        'q_sfp_fp_id' => intval($flattenedData['selected_financial_priority_rank']) == 0 ? 1 : intval($flattenedData['selected_financial_priority_rank']),
                        'q_sfp_rank' => $flattenedData['selected_financial_priority_rank'],
                        'q_sfp_reason' => $flattenedData['reason_family_protect_important'],
                        'q_sfp_dateCreated' => date('Y-m-d'),                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
                    ]);
                }
            }else if($op == 'RetirementPlanFNA'){
                $result = RetirePlannFNA::where('q_retPFNA_id', $flattenedData['retPFNA_id'])
                    ->update([
                    'q_retPFNA_clientID' => $request['client_id'],
                    'q_retPFNAa_resRetPlann' => $flattenedData['reason_retirement_plann'],
                    'q_retPFNA_howRetLooks' => $flattenedData['how_retirement_looks'],
                    'q_retPFNA_currAgeCL' => $flattenedData['current_age_cl'],
                    'q_retPFNA_currAgeSP' => $flattenedData['current_age_sp'],
                    'q_retPFNA_ageRetCL' => $flattenedData['age_retirement_cl'],
                    'q_retPFNA_ageRetSP' => $flattenedData['age_retirement_sp'],
                    'q_retPFNA_lifeSpanCL' => $flattenedData['life_span_cl'],
                    'q_retPFNA_lifeSpanSP' => $flattenedData['life_span_sp'],
                    'q_retPFNA_avgInfaRate' => $flattenedData['avg_inflation_rate'],
                    'q_retPFNA_intRetirement' => $flattenedData['interest_retirement'],
                    'q_retPFNA_sssAnnualCL' => $flattenedData['sss_anual_cl'],
                    'q_retPFNA_sssAnnualSP' => $flattenedData['sss_anual_sp'],
                    'q_retPFNA_yrsSSSBenefitCL' => $flattenedData['yrs_sss_benefit_cl'],
                    'q_retPFNA_yrsSSSBenefitSP' => $flattenedData['yrs_sss_benefit_sp'],
                    'q_retPFNA_companyBenefitRetCL' => $flattenedData['comp_benefit_ret_cl'],
                    'q_retPFNA_companyBenefitRetSP' => $flattenedData['comp_benefit_ret_sp'],
                    'q_retPFNA_yrsCompanyBenefitCL' => $flattenedData['yrs_comp_benefit_cl'],
                    'q_retPFNA_yrsCompanyBenefitSP' => $flattenedData['yrs_comp_benefit_sp'],
                    'q_retPFNA_dateUpdated' => date('Y-m-d'),
                ]);
            }else if($op == 'EducationPlanFNA'){
                $result = EducPlannFNA::where('q_educPFNA_id', $flattenedData['educPlanFNA_id'])
                    ->update([
                    'q_educPFNA_clientID' => $request['client_id'],
                    'q_educPFNA_resEducPlannImp' => $flattenedData['reason_educPlan_important'],
                    'q_educPFNA_dreamsForChildren' => $flattenedData['dreams_for_children'] == '' || $flattenedData['dreams_for_children'] == null ? '' : $flattenedData['dreams_for_children'],
                    'q_educPFNA_dateUpdated' => date('Y-m-d'),
                ]);
            }else if($op == 'ChildrenEPFNA'){
                $result = SelectedChildEducPlan::where('q_selChildEduP_famComp_id', $flattenedData['famComp_id'])
                    ->update([
                    'q_selChildEduP_famComp_id' => $flattenedData['famComp_id'],
                    'q_selChildEduP_desiredSchool' => $flattenedData['desired_school'],
                    'q_selChildEduP_childAgeCollege' => $flattenedData['age_for_college'],
                    'q_selChildEduP_totalEducFundNeeded' => $flattenedData['total_educ_fund_needed'],
                    'q_selChildEduP_investmentAlloc' => $flattenedData['investment_alloc'],
                    'q_selChildEduP_dateUpdated' => date('Y-m-d'),
                ]);
                FamilyComposition::where('q_famComp_id', $flattenedData['famComp_id'])
                    ->update([
                    'q_famComp_birthDay' => $flattenedData['child_birthday'],
                    'q_famComp_dateUpdated' => date('Y-m-d'),
                ]);
            }else if($op == 'EducPlanExpenses'){
                $result = EducPlannExp::where('q_educPExp_id', $flattenedData['educPlanExp_id'])
                    ->update([
                    'q_educPExp_famComp_id' => $flattenedData['familyComp_id'],
                    'q_educPExp_educPExpList_id' => $other ? $other : $flattenedData['educPlanExpList_id'],
                    'q_educPExp_presentValAmt' => $flattenedData['presentVal_amt'],
                    'q_educPExp_avgInflationRate' => $flattenedData['avg_inflation_rate'],
                    'q_educPExp_futureNeededValAmt' => $flattenedData['fundNeeded_futureVal_amt'],
                ]);
            }else if($op == 'RetirementExpenses'){
                $result = RetirementExp::where('q_retExp_id', $flattenedData['dr_id'])
                    ->update([
                    'q_retExp_clientID' => $flattenedData['client_id'],
                    'q_retExp_retExpList_id' => $other ? $other : $flattenedData['retExpList_id'],
                    'q_retExp_presentValAmtCL' => $flattenedData['presentVal_amt_cl'],
                    'q_retExp_presentValAmtSP' => $flattenedData['presentVal_amt_sp'],
                ]);
            }else if($op == 'ExistLifeInsureCov'){
                $result = ExistingLifeInsuranceCoverage::where('q_exLifeInsCov_id', $flattenedData['exLifeInsCov_id'])
                    ->update([
                    'q_exLifeInsCov_clientID' => $flattenedData['client_id'],
                    'q_exLifeInsCov_listID' => $other ? $other : $flattenedData['exLifeInsCovList_id'],
                    'q_exLifeInsCov_amtClient' => $flattenedData['amount_on_client'],
                    'q_exLifeInsCov_amtSpouse' => $flattenedData['amount_on_spouse'],
                ]);
            }else if($op == 'cHealthFundPlannFNA'){
                $result = HealthFundPlannFNA::where('q_healthFP_id', $flattenedData['healthFP_id'])
                    ->update([
                    'q_healthFP_clientID' => $request['client_id'],
                    'q_healthFP_resHealthFund' => $flattenedData['reason_health_fund'],
                    'q_healthFP_finSitWithIllMember' => $flattenedData['financialSit_with_illMember'],
                    'q_healthFP_finImpact' => $flattenedData['financial_impact'],
                    'q_healthFP_dateUpdated' => date('Y-m-d'),
                ]);
            }else if($op == 'upsHealthCovSummary'){
                $result = HealthCovSumm::where('q_healthCovSum_id', $flattenedData['healthCovSum_id'])
                    ->update([
                    'q_healthCovSum_famCompID' => $flattenedData['famComp_id'],
                    'q_healthCovSum_type' => $flattenedData['type'],
                    'q_healthCovSum_policyRefNo' => $flattenedData['policyRef_no'],
                    'q_healthCovSum_origin' => $flattenedData['origin'],
                    'q_healthCovSum_amtInPatient' => $flattenedData['amt_in_patient'],
                    'q_healthCovSum_opInPatient' => $flattenedData['op_in_patient'],
                    'q_healthCovSum_amtOutPatient' => $flattenedData['amt_out_patient'],
                    'q_healthCovSum_opOutPatient' => $flattenedData['op_out_patient'],
                    'q_healthCovSum_amtCritIllLim' => $flattenedData['amt_critical_illness_limit'],
                    'q_healthCovSum_opCritIllLim' => $flattenedData['op_critical_illness_limit'],
                    'q_healthCovSum_amtLabLim' => $flattenedData['amt_lab_limit'],
                    'q_healthCovSum_amtHospIncome' => $flattenedData['amt_hosp_income'],
                    'q_healthCovSum_notes' => $flattenedData['notes'],
                    'q_healthCovSum_dateUpdated' => date('Y-m-d'),
                ]);
            }else if($op == 'updTargetLimits'){
                $result = TargetLimits::where('q_targLim_id', $flattenedData['TL_id'])
                    ->update([
                    'q_targLim_famCompID' => $flattenedData['famComp_id'],
                    'q_targLim_type' => $flattenedData['type'],
                    'q_targLim_MBL_inPatient' => $flattenedData['MBL_inPatient'],
                    'q_targLim_ABL_inPatient' => $flattenedData['ABL_inPatient'],
                    'q_targLim_LBL_inPatient' => $flattenedData['LBL_inPatient'],
                    'q_targLim_MBL_outPatient' => $flattenedData['MBL_outPatient'],
                    'q_targLim_ABL_outPatient' => $flattenedData['ABL_outPatient'],
                    'q_targLim_LBL_outPatient' => $flattenedData['LBL_outPatient'],
                    'q_targLim_MBL_critIllness' => $flattenedData['MBL_critIllness'],
                    'q_targLim_ABL_critIllness' => $flattenedData['ABL_critIllness'],
                    'q_targLim_LBL_critIllness' => $flattenedData['LBL_critIllness'],
                    'q_targLim_labLimit' => $flattenedData['labLimit'],
                    'q_targLim_hospIncome' => $flattenedData['hospIncome'],
                    'q_targLim_dateUpdate' => date('Y-m-d'),
                ]);
            }else if($op == 'visitedLinkOfAgent'){
                $result = Agent::where('q_agnt_id', $request['user_id'])
                    ->update([
                    'q_agnt_linkLastVisited' => $request['linkLastVisited'] == "null" || $request['linkLastVisited'] == 'null' || $request['linkLastVisited'] == '' || $request['linkLastVisited'] == null ? null : $request['linkLastVisited']
                ]);
                return $result;
            }else if($op == 'updateToDoToResolved'){
                $result = Todos::where('q_tdo_id', $request['toDoID'])
                    ->update([
                        'q_tdo_dateMarkedAsResolved' => date('Y-m-d'),
                        'q_tdo_remarksOnResolved' => $request['remarksOnResolve'],
                ]);
                return $result;
            }else if($op == 'updatePlanSolToResolved'){
                $result = FinancialPlannSol::where('q_finPlSo_id', $request['finPlSo_id'])
                    ->update([
                        'q_finPlSo_dateMarkedAsResolved' => date('Y-m-d'),
                        'q_finPlSo_remarksOnResolved' => $request['remarksOnResolve'],
                ]);
                return $result;
            }else if($op == 'fundAllocatedEducPlanningFNA'){
                $result = null;
                if($flattenedData['from_table'] == "Cash_And_Deposit"){
                    $result = CashAndDeposits::where('q_cad_id', $flattenedData['table_id'])
                        ->update([
                            'q_cad_accNo' => $flattenedData['policyNo'],
                            'q_cad_insuProd' => $flattenedData['insuProd'],
                            'q_cad_projRate' => $flattenedData['projRate'],
                            'q_cad_projValEducAge' => $flattenedData['projValEducAge'],
                        ]);
                }else if($flattenedData['from_table'] == "MFUITF"){
                    $result = MutualFundsUITF::where('q_uitf_id', $flattenedData['table_id'])
                        ->update([
                            'q_uitf_accNo' => $flattenedData['policyNo'],
                            'q_uitf_insuProd' => $flattenedData['insuProd'],
                            'q_uitf_projRate' => $flattenedData['projRate'],
                            'q_uitf_projValEducAge' => $flattenedData['projValEducAge'],
                        ]);
                }else if($flattenedData['from_table'] == "Bonds"){
                    $result = Bonds::where('q_bond_id', $flattenedData['table_id'])
                        ->update([
                            'q_bond_accNo' => $flattenedData['policyNo'],
                            'q_bond_insuProd' => $flattenedData['insuProd'],
                            'q_bond_projRate' => $flattenedData['projRate'],
                            'q_bond_projValEducAge' => $flattenedData['projValEducAge'],
                        ]);
                }else if($flattenedData['from_table'] == "Life_And_Health_Insurance_From_Aetos" || $flattenedData['from_table'] == "Life_And_Health_Insurance"){
                    $result = LifeAndHealthInsurance::where('q_lifeHealth_id', $flattenedData['table_id'])
                        ->update([
                            'q_lifeHealth_accNo' => $flattenedData['policyNo'],
                            'q_lifeHealth_insuProd' => $flattenedData['insuProd'],
                            'q_lifeHealth_projRate' => $flattenedData['projRate'],
                            'q_lifeHealth_projValEducAge' => $flattenedData['projValEducAge'],
                        ]);
                }else if($flattenedData['from_table'] == "Stock_In_Listed_Companies" || $flattenedData['from_table'] == "Stock_In_NonListed_Companies"){
                    $result = StocksInCompanies::where('q_stoComp_id', $flattenedData['table_id'])
                        ->update([
                            'q_stoComp_accNo' => $flattenedData['policyNo'],
                            'q_stoComp_insuProd' => $flattenedData['insuProd'],
                            'q_stoComp_projRate' => $flattenedData['projRate'],
                            'q_stoComp_projValEducAge' => $flattenedData['projValEducAge'],
                        ]);
                }else if($flattenedData['from_table'] == "Family_Home" || $flattenedData['from_table'] == "Real_Estate"){
                    $result = FamilyHomeEstate::where('q_homeEstate_id', $flattenedData['table_id'])
                        ->update([
                            'q_homeEstate_accNo' => $flattenedData['policyNo'],
                            'q_homeEstate_insuProd' => $flattenedData['insuProd'],
                            'q_homeEstate_projRate' => $flattenedData['projRate'],
                            'q_homeEstate_projValEducAge' => $flattenedData['projValEducAge'],
                        ]);
                }else if($flattenedData['from_table'] == "Personal_Assets"){
                    $result = PersonalAssets::where('q_perAs_id', $flattenedData['table_id'])
                        ->update([
                            'q_perAs_accNo' => $flattenedData['policyNo'],
                            'q_perAs_insuProd' => $flattenedData['insuProd'],
                            'q_perAs_projRate' => $flattenedData['projRate'],
                            'q_perAs_projValEducAge' => $flattenedData['projValEducAge'],
                        ]);
                }
                return $result;
            }else if($op == 'educFundGuaranteedPaySched'){
                $result = null;
                if($flattenedData['from_table'] == "Cash_And_Deposit"){
                    $result = CashAndDeposits::where('q_cad_id', $flattenedData['table_id'])
                        ->update([
                            'q_cad_accNo' => $flattenedData['policyNo'],
                            'q_cad_regPayoutAmt' => $flattenedData['regPayoutAmt'],
                            'q_cad_ageStartPayout' => $flattenedData['ageStartPayout'],
                            'q_cad_startYearForPayout' => $flattenedData['startYearForPayout'],
                            'q_cad_freqOfPayout' => $flattenedData['freqOfPayout'],
                            'q_cad_ageChildForLastPayout' => $flattenedData['ageChildForLastPayout'],
                            'q_cad_endYearForPayout' => $flattenedData['endYearForPayout'],
                        ]);
                }else if($flattenedData['from_table'] == "MFUITF"){
                    $result = MutualFundsUITF::where('q_uitf_id', $flattenedData['table_id'])
                        ->update([
                            'q_uitf_accNo' => $flattenedData['policyNo'],
                            'q_uitf_regPayoutAmt' => $flattenedData['regPayoutAmt'],
                            'q_uitf_ageStartPayout' => $flattenedData['ageStartPayout'],
                            'q_uitf_startYearForPayout' => $flattenedData['startYearForPayout'],
                            'q_uitf_freqOfPayout' => $flattenedData['freqOfPayout'],
                            'q_uitf_ageChildForLastPayout' => $flattenedData['ageChildForLastPayout'],
                            'q_uitf_endYearForPayout' => $flattenedData['endYearForPayout'],
                        ]);
                }else if($flattenedData['from_table'] == "Bonds"){
                    $result = Bonds::where('q_bond_id', $flattenedData['table_id'])
                        ->update([
                            'q_bond_accNo' => $flattenedData['policyNo'],
                            'q_bond_regPayoutAmt' => $flattenedData['regPayoutAmt'],
                            'q_bond_ageStartPayout' => $flattenedData['ageStartPayout'],
                            'q_bond_startYearForPayout' => $flattenedData['startYearForPayout'],
                            'q_bond_freqOfPayout' => $flattenedData['freqOfPayout'],
                            'q_bond_ageChildForLastPayout' => $flattenedData['ageChildForLastPayout'],
                            'q_bond_endYearForPayout' => $flattenedData['endYearForPayout'],
                        ]);
                }else if($flattenedData['from_table'] == "Life_And_Health_Insurance_From_Aetos" || $flattenedData['from_table'] == "Life_And_Health_Insurance"){
                    $result = LifeAndHealthInsurance::where('q_lifeHealth_id', $flattenedData['table_id'])
                        ->update([
                            'q_lifeHealth_accNo' => $flattenedData['policyNo'],
                            'q_lifeHealth_regPayoutAmt' => $flattenedData['regPayoutAmt'],
                            'q_lifeHealth_ageStartPayout' => $flattenedData['ageStartPayout'],
                            'q_lifeHealth_startYearForPayout' => $flattenedData['startYearForPayout'],
                            'q_lifeHealth_freqOfPayout' => $flattenedData['freqOfPayout'],
                            'q_lifeHealth_ageChildForLastPayout' => $flattenedData['ageChildForLastPayout'],
                            'q_lifeHealth_endYearForPayout' => $flattenedData['endYearForPayout'],
                        ]);
                }else if($flattenedData['from_table'] == "Stock_In_Listed_Companies" || $flattenedData['from_table'] == "Stock_In_NonListed_Companies"){
                    $result = StocksInCompanies::where('q_stoComp_id', $flattenedData['table_id'])
                        ->update([
                            'q_stoComp_accNo' => $flattenedData['policyNo'],
                            'q_stoComp_regPayoutAmt' => $flattenedData['regPayoutAmt'],
                            'q_stoComp_ageStartPayout' => $flattenedData['ageStartPayout'],
                            'q_stoComp_startYearForPayout' => $flattenedData['startYearForPayout'],
                            'q_stoComp_freqOfPayout' => $flattenedData['freqOfPayout'],
                            'q_stoComp_ageChildForLastPayout' => $flattenedData['ageChildForLastPayout'],
                            'q_stoComp_endYearForPayout' => $flattenedData['endYearForPayout'],
                        ]);
                }else if($flattenedData['from_table'] == "Family_Home" || $flattenedData['from_table'] == "Real_Estate"){
                    $result = FamilyHomeEstate::where('q_homeEstate_id', $flattenedData['table_id'])
                        ->update([
                            'q_homeEstate_accNo' => $flattenedData['policyNo'],
                            'q_homeEstate_regPayoutAmt' => $flattenedData['regPayoutAmt'],
                            'q_homeEstate_ageStartPayout' => $flattenedData['ageStartPayout'],
                            'q_homeEstate_startYearForPayout' => $flattenedData['startYearForPayout'],
                            'q_homeEstate_freqOfPayout' => $flattenedData['freqOfPayout'],
                            'q_homeEstate_ageChildForLastPayout' => $flattenedData['ageChildForLastPayout'],
                            'q_homeEstate_endYearForPayout' => $flattenedData['endYearForPayout'],
                        ]);
                }else if($flattenedData['from_table'] == "Personal_Assets"){
                    $result = PersonalAssets::where('q_perAs_id', $flattenedData['table_id'])
                        ->update([
                            'q_perAs_accNo' => $flattenedData['policyNo'],
                            'q_perAs_regPayoutAmt' => $flattenedData['regPayoutAmt'],
                            'q_perAs_ageStartPayout' => $flattenedData['ageStartPayout'],
                            'q_perAs_startYearForPayout' => $flattenedData['startYearForPayout'],
                            'q_perAs_freqOfPayout' => $flattenedData['freqOfPayout'],
                            'q_perAs_ageChildForLastPayout' => $flattenedData['ageChildForLastPayout'],
                            'q_perAs_endYearForPayout' => $flattenedData['endYearForPayout'],
                        ]);
                }
                return $result;
            }else if($op == 'PlanSol_Todos'){
                $result = Todos::where('q_tdo_id', $flattenedData['todos_id'])
                ->update([
                    'q_tdo_descripx' => $flattenedData['todo'],
                    'q_tdo_dateTodo' => $flattenedData['date_todo'],
                ]);
            }else if($op == 'Multi_Heirs'){
                $err = 0;$success = 0;
                foreach ($request['heirs'] as $index => $data) {
                    $flattenedData_v2 = $this->flattenData($data);
                    if(intval($flattenedData_v2['part']) == intval($other)){
                        $count = 0;
                        $count = Heir::where('q_heir_id', intval($flattenedData_v2['heir_id']))->count();
                        if($count > 0){
                            $result = Heir::where('q_heir_id', intval($flattenedData_v2['heir_id']))
                            ->update([
                                'q_heir_famComp_id' => $flattenedData_v2['famComp_id'],
                                'q_heir_tableID' => $flattenedData_v2['table_ID'],
                                'q_heir_fromTable' => $flattenedData_v2['from_table'],
                                'q_heir_indicatedPercentage' => $flattenedData_v2['indicated_percent'],
                            ]);
                            $success = 1;
                        }else{
                            $result = Heir::create([
                                'q_heir_famComp_id' => $flattenedData_v2['famComp_id'],
                                'q_heir_tableID' => $flattenedData_v2['table_ID'],
                                'q_heir_fromTable' => $flattenedData_v2['from_table'],
                                'q_heir_indicatedPercentage' => $flattenedData_v2['indicated_percent'],
                            ]);
                            $success = 1;
                        }
                    }
                }
                if($success == 1){
                    return 1;
                }else{
                    return 0;
                }
            }else if($op == 'Beneficiaries'){
                $collectedErrorsAndSuccess = array();
                $success = 0; $i = 0; $counter = 0;
                foreach ($request['beneficiaries'] as $index => $data) {
                    $flattenedData_v2 = $this->flattenData($data);
                    $countBen = 0;
                    $countBen = $this->checkAndCount('CountBeneficiaries', $flattenedData_v2, null, $flattenedData);
                    if($countBen == 0){
                        $result = Beneficiaries::create([
                            'q_benex_lifeHeath_id' => $flattenedData,
                            'q_benex_fullName' => $flattenedData_v2['full_name'],
                            'q_benex_percentShare' => $flattenedData_v2['percent_share'],
                            'q_benex_designation' => $flattenedData_v2['designation'],
                            'q_benex_priority' => $flattenedData_v2['priority'],
                            'q_benex_dateUpdated' => date('Y-m-d'),  
                            'q_benex_dateCreated' => date('Y-m-d'),
                        ]);
                        if($result){
                            $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Beneficiaries has been successfully ADDED for Life and Health Insurance."];
                            $success++;
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => "Something went wrong while INSERTING new FLAHI. Consult the system administrator"];
                        }
                    }else{
                        $result = Beneficiaries::where('q_benex_id', $flattenedData_v2['beneficiaries_id'])
                        ->update([
                            'q_benex_fullName' => $flattenedData_v2['full_name'],
                            'q_benex_percentShare' => $flattenedData_v2['percent_share'],
                            'q_benex_designation' => $flattenedData_v2['designation'],
                            'q_benex_priority' => $flattenedData_v2['priority'],
                            'q_benex_dateUpdated' => date('Y-m-d')
                        ]);
                        if($result){
                            $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Beneficiaries has been successfully UPDATED for Life and Health Insurance."];
                            $success++; 
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => "Something went wrong while UPDATING old FLAHI. Consult the system administrator"];
                        }
                    }
                    $i++;
                    $counter++;
                }
                return $collectedErrorsAndSuccess;
            }
            return $this->returnZeroAndOne($result);
        } catch (\Throwable $th) {
            return 0;
        }
    }

    // Check and Count
    public function checkAndCount($table, $flattenedData, $request, $other){
        try {
            if($table == 'SelectedFinancialPriorities'){
                return SelectedFinancialPriorities::where('q_sfp_clnt_id', $flattenedData['client_id'])
                    ->where('q_sfp_fp_id', $flattenedData['financial_priority_list_id'])
                    ->count();
            }else if($table == 'SelectedFinancialPriorities_V2'){
                return SelectedFinancialPriorities::where('q_sfp_clnt_id', $flattenedData['client_id'])
                    ->where('q_sfp_rank', $flattenedData['rankNumber'])
                    ->count();
            }else if($table == 'SelectedFinancialPriorities_V3'){
                return SelectedFinancialPriorities::where('q_sfp_id', $flattenedData['sfp_id'])
                    ->where('q_sfp_fp_id', $flattenedData['financial_priority_list_id'])
                    ->where('q_sfp_rank', $flattenedData['rankNumber'])
                    ->where('q_sfp_reason', $flattenedData['reason'])
                    ->count();
            }else if($table == 'CashFlowData'){
                return CashFlowData::where('q_cfd_id', $flattenedData['cfd_id'])->where('q_cfd_clnt_id', $flattenedData['client_id'])->count();
            }else if($table == 'CashFlowData_V2'){
                return CashFlowData::where('q_cfd_id', $flattenedData['cfd_id'])
                    ->where('q_cfd_cfda_clientAmt', $flattenedData['cfda_client_amt'])
                    ->where('q_cfd_cfda_spouseAmt', $flattenedData['cfda_spouse_amt'])
                    ->count();
            }else if($table == 'CashFlowData_V3'){
                return CashFlowData::where('q_cfd_id', $flattenedData['cfd_id'])
                    ->where('q_cfd_cfda_clientAmtExpense', $flattenedData['cfda_client_amount_expense'])
                    ->where('q_cfd_cfda_spouseAmtExpense', $flattenedData['cfda_spouse_amount_expense'])
                    ->where('q_cfd_targetRetireAmtInPercent', $flattenedData['cfda_spouse_amount_expense'])
                    ->count();
            }else if($table == 'CashFlowData_V4'){
                return CashFlowData::where('q_cfd_id', $flattenedData['cfd_id'])
                    ->where('q_cfd_cfdb_clientAmt', $flattenedData['cfdb_clientAmt'])
                    ->where('q_cfd_cfdb_spouseAmt', $flattenedData['cfdb_spouseAmt'])
                    ->count();
            }else if($table == 'CashFlowData_V5'){
                return CashFlowData::where('q_cfd_cfl_id', $flattenedData['cfl_id'])
                    ->where('q_cfd_clnt_id', $flattenedData['client_id'])
                    ->count();
            }else if($table == 'Recommendations'){
                return Recommendations::where('q_recommx_id', $flattenedData['recommendation_id'])->count();
            }else if($table == 'Recommendations_V2'){
                return Recommendations::where('q_recommx_id', $flattenedData['recommendation_id'])
                    ->where('q_recommx_isInflowOutflow', $request['isInflowOutflow'])
                    ->where('q_recommx_recommxDetails', $flattenedData['description'])
                    ->count();
            }else if($table == 'Recommendations_V4'){
                return Recommendations::where('q_recommx_cfa_id', $other['q_cfa_id'])
                    ->where('q_recommx_recommxDetails', $flattenedData['description'])
                    ->where('q_recommx_isInflowOutflow', $request['isInflowOutflow'])
                    ->count();
            }else if($table == 'CashFlowAnalysis'){
                return CashFlowAnalysis::where('q_cfa_clnt_id', $request['client_id'])->count();
            }else if($table == 'CashFlowAnalysis_V2'){
                return CashFlowAnalysis::where('q_cfa_clnt_id', $request['client_id'])
                    ->where('q_cfa_targetCashInF_client', $request['client'])
                    ->where('q_cfa_targetCashInF_spouse', $request['spouse'])
                    ->count();
            }else if($table == 'Heir'){
                return Heir::where('q_heir_id', $flattenedData['heir_id'])->count();
            }else if($table == 'Heir_V2'){
                return Heir::where('q_heir_clientID', $flattenedData['client_ID'])
                    ->where('q_heir_fullName', $flattenedData['heir_fullName'])
                    ->count();
            }else if($table == 'Heir_V3'){
                return Heir::where('q_heir_clientID', $flattenedData['client_ID'])
                ->where('q_heir_tableID', $flattenedData['table_ID'])
                ->where('q_heir_fromTable', $flattenedData['from_table'])
                ->where('q_heir_fullName', $flattenedData['heir_fullName'])
                ->where('q_heir_indicatedPercentage', $flattenedData['indicated_percent'])
                ->count();
            }else if($table == 'CashAndDeposits'){
                return CashAndDeposits::where('q_cad_id', $flattenedData['cad_id'])->count();
            }else if($table == 'CashAndDeposits_V2'){
                return CashAndDeposits::where('q_cad_clientID', $flattenedData['client_id'])
                    ->where('q_cad_bank', $flattenedData['bank'])
                    ->where('q_cad_accountDescription', $flattenedData['account_description'])
                    ->where('q_cad_typeOfAccount', $flattenedData['type_of_account'])
                    ->where('q_cad_estimatedValue', $flattenedData['estimated_value'])
                    ->where('q_cad_purpose', $flattenedData['purpose'])
                    ->where('q_cad_withGuaranteedPayout', $flattenedData['with_guaranteed_payout'])
                    ->where('q_cad_exclusiveConjugal', $flattenedData['exclusive_conjugal'])
                    ->where('q_cad_shareSelf', $flattenedData['share_self'])
                    ->where('q_cad_shareSpouse', $flattenedData['share_spouse'])
                    ->count();
            }else if($table == 'CashAndDeposits_V3'){
                return CashAndDeposits::where('q_cad_id', $flattenedData['cad_id'])
                    ->where('q_cad_clientID', $flattenedData['client_id'])
                    ->where('q_cad_bank', $flattenedData['bank'])
                    ->where('q_cad_accountDescription', $flattenedData['account_description'])
                    ->where('q_cad_typeOfAccount', $flattenedData['type_of_account'])
                    ->where('q_cad_estimatedValue', $flattenedData['estimated_value'])
                    ->where('q_cad_purpose', $flattenedData['purpose'])
                    ->where('q_cad_withGuaranteedPayout', $flattenedData['with_guaranteed_payout'])
                    ->where('q_cad_exclusiveConjugal', $flattenedData['exclusive_conjugal'])
                    ->where('q_cad_shareSelf', $flattenedData['share_self'])
                    ->where('q_cad_shareSpouse', $flattenedData['share_spouse'])
                    ->count();
            }else if($table == 'SelectedFinancialPriorities'){
                return SelectedFinancialPriorities::where('q_sfp_id', $flattenedData['sfp_id'])->where('q_sfp_rank', $flattenedData['rank_number'])->count();
            }else if($table == 'SelectedFinancialPriorities_V2'){
                return SelectedFinancialPriorities::where('q_sfp_id', $flattenedData['sfp_id'])->where('q_sfp_fp_id', $flattenedData['priority_list_id'])->count();
            }else if($table == 'SelectedFinancialPriorities_V3'){
                return SelectedFinancialPriorities::where('q_sfp_id', $flattenedData['sfp_id'])->where('q_sfp_reason', $flattenedData['reason'])->count();
            }else if($table == 'Family_Composition'){
                return FamilyComposition::where('q_famComp_id', $flattenedData['fc_id'])->count();
            }else if($table == 'Family_Composition_v2'){
                return FamilyComposition::where('q_famComp_clientID', $flattenedData['client_id'])
                    ->where(DB::raw('UPPER(q_famComp_firstName)'), strtoupper($flattenedData['first_name']))
                    ->where(DB::raw('UPPER(q_famComp_lastName)'), strtoupper($flattenedData['last_name']))
                    ->where(DB::raw('UPPER(q_famComp_middleInitial)'), strtoupper($flattenedData['middle_initial']))
                    ->whereDate('q_famComp_birthDay', $flattenedData['birthday']) // 1989-05-09
                    ->where('q_famComp_compType', $flattenedData['type'])
                    ->count();
            }else if($table == 'Receivables'){
                return Receivables::where('q_rec_id', $flattenedData['receivables_id'])->count();
            }else if($table == 'Receivables_V2'){
                return Receivables::where('q_rec_clientID', $flattenedData['client_id'])
                    ->where('q_rec_debtorName', $flattenedData['name_of_debtor'])
                    ->where('q_rec_loanPurpose', $flattenedData['loan_purpose'])
                    ->where('q_rec_estimatedValue', $flattenedData['estimated_value'])
                    ->where('q_rec_percentCollectability', $flattenedData['percentage_collectibility'])
                    ->where('q_rec_exclusiveConjugal', $flattenedData['exclusive_conjugal'])
                    ->where('q_rec_shareSelf', $flattenedData['share_self'])
                    ->where('q_rec_shareSpouse', $flattenedData['share_spouse'])
                    ->where('q_rec_withCli', $flattenedData['with_cli'])
                    ->where('q_rec_renewalMonth', $flattenedData['renewal_month'])
                    ->count();
            }else if($table == 'MutualFunds'){
                return MutualFundsUITF::where('q_uitf_id', $flattenedData['mfuitf_id'])->count();
            }else if($table == 'MutualFunds_V2'){
                return MutualFundsUITF::where('q_uitf_clientID', $flattenedData['client_id'])
                    ->where('q_uitf_accNo', $flattenedData['policyNo'])
                    ->where('q_uitf_company', $flattenedData['company'])
                    ->where('q_uitf_noOfUnits', $flattenedData['no_of_units'])
                    ->where('q_uitf_currentValuePerUnits', $flattenedData['current_value'])
                    ->where('q_uitf_estimatedValue', $flattenedData['estimated_value'])
                    ->where('q_uitf_purpose', $flattenedData['purpose'])
                    ->where('q_uitf_withGuaranteedPayout', $flattenedData['with_guaranteed_payout'])
                    ->where('q_uitf_exclusiveConjugal', $flattenedData['exclusive_conjugal'])
                    ->where('q_uitf_shareSelf', $flattenedData['share_self'])
                    ->where('q_uitf_shareSpouse', $flattenedData['share_spouse'])
                    ->count();
            }else if($table == 'Bonds'){
                return Bonds::where('q_bond_id', $request['bond_id'])->count();
            }else if($table == 'Bonds_V2'){
                return Bonds::where('q_bond_clientID', $flattenedData['client_id'])
                    ->where('q_bond_issuer', $flattenedData['bonds_issuer'])
                    ->where('q_bond_maturityDate', $flattenedData['maturity_date'])
                    ->where('q_bond_perValue', $flattenedData['par_value'])
                    ->where('q_bond_estimatedValue', $flattenedData['estimated_value'])
                    ->where('q_bond_purpose', $flattenedData['purpose'])
                    ->where('q_bond_withGuaranteedPayout', $flattenedData['with_guaranteed_payout'])
                    ->where('q_bond_exclusiveConjugal', $flattenedData['exclusive_conjugal'])
                    ->where('q_bond_shareSelf', $flattenedData['share_self'])
                    ->where('q_bond_shareSpouse', $flattenedData['share_spouse'])
                    ->count();
            }else if($table == 'StockInCompanies'){
                return StocksInCompanies::where('q_stoComp_id', $flattenedData['sic_id'])->count();
            }else if($table == 'StockInCompanies_V2'){
                return StocksInCompanies::where('q_stoComp_clientID', $flattenedData['client_id'])
                    ->where('q_stoComp_companyAlias', $flattenedData['company_alias'])
                    ->where('q_stoComp_noOfShares', $flattenedData['no_of_shares'])
                    ->where('q_stoComp_currentBookValueShare', $flattenedData['current_book_value'])
                    ->where('q_stoComp_estimatedValue', $flattenedData['estimated_value'])
                    ->where('q_stoComp_purpose', $flattenedData['purpose'])
                    ->where('q_stoComp_exclusiveConjugal', $flattenedData['exclusive_conjugal'])
                    ->where('q_stoComp_shareSelf', $flattenedData['share_self'])
                    ->where('q_stoComp_shareSpouse', $flattenedData['share_spouse'])
                    ->where('q_stoComp_isListed', $flattenedData['listed_nonlisted'])
                    ->count();
            }else if($table == 'LifeAndHealthInsurance'){
                return LifeAndHealthInsurance::where('q_lifeHealth_id', $flattenedData['flahi_id'])->count();
            }else if($table == 'LifeAndHealthInsurance_V2'){
                return LifeAndHealthInsurance::where('q_lifeHealth_clientID', $flattenedData['client_id'])
                    ->where('q_lifeHealth_fromAetosAdviser', $request['fromAdviser'])
                    ->where('q_lifeHealth_insuranceCompany', $flattenedData['insurance_company'])
                    ->where('q_lifeHealth_policyNumber', $flattenedData['policy_number'])
                    ->where('q_lifeHealth_typeOfPolicy', $flattenedData['type_of_policy'])
                    ->where('q_lifeHealth_monthYearIssued', $flattenedData['month_year_issued'])
                    ->where('q_lifeHealth_policyOwner', $flattenedData['policy_owner'])
                    ->where('q_lifeHealth_insured', $flattenedData['insured'])
                    ->where('q_lifeHealth_purpose', $flattenedData['purpose'])
                    ->where('q_lifeHealth_withGuaranteedPayout', $flattenedData['with_guaranteed_payout'])
                    ->where('q_lifeHealth_faceAmountFamilyProtection', $flattenedData['faceamount_fpcf'])
                    ->where('q_lifeHealth_faceAmountEstateTax', $flattenedData['faceamount_etax'])
                    ->where('q_lifeHealth_faceAmountEstateDistribution', $flattenedData['faceamount_edistribution'])
                    ->where('q_lifeHealth_faceAmount', $flattenedData['faceamount_total'])
                    ->where('q_lifeHealth_currentFundValueEstimated', $flattenedData['current_account_value'])
                    ->count();
            }else if($table == 'CountBeneficiaries'){
                return Beneficiaries::where('q_benex_lifeHeath_id', $other)
                    ->where('q_benex_fullName', $flattenedData['full_name'])
                    ->where('q_benex_percentShare', $flattenedData['percent_share'])
                    ->where('q_benex_designation', $flattenedData['designation'])
                    ->where('q_benex_priority', $flattenedData['priority'])
                    ->count();
            }else if($table == 'FamilyHomeRealEstate'){
                return FamilyHomeEstate::where('q_homeEstate_id', $flattenedData['fh_id'])->count();
            }else if($table == 'FamilyHomeRealEstate_V2'){
                return FamilyHomeEstate::where('q_homeEstate_clientID', $flattenedData['client_id'])
                    ->where('q_homeEstate_tctNumber', $flattenedData['tct_cct_number'])
                    ->where('q_homeEstate_cityMunLocation', $flattenedData['location'])
                    ->where('q_homeEstate_areaSQM', $flattenedData['area_sqm'])
                    ->where('q_homeEstate_zoneValueEstimate', $flattenedData['bir_zonal_value'])
                    ->where('q_homeEstate_estimatedValue', $flattenedData['estimated_value'])
                    ->where('q_homeEstate_exclusiveConjugal', $flattenedData['exclusive_conjugal'])
                    ->where('q_homeEstate_purpose', $flattenedData['purpose'])
                    ->where('q_homeEstate_withGuaranteedPayout', $flattenedData['with_guaranteed_payout'])
                    ->where('q_homeEstate_shareSelf', $flattenedData['share_self'])
                    ->where('q_homeEstate_shareSpouse', $flattenedData['share_spouse'])
                    ->where('q_homeEstate_withPropertyInsurance', $flattenedData['with_property_insurance'])
                    ->where('q_homeEstate_renewalMonth', $flattenedData['renewalMonth'])
                    ->where('q_homeEstate_isHome', $request['isHome'])
                    ->count();
            }else if($table == 'Vehicles'){
                return Vehicles::where('q_vehicle_id', $flattenedData['vehicles_id'])->count();
            }else if($table == 'Vehicles_V2'){
                return Vehicles::where('q_vehicle_clientID', $flattenedData['client_id'])
                    ->where('q_vehicle_plateNo', $flattenedData['plate_no'])
                    ->where('q_vehicle_type', $flattenedData['vehicle_type'])
                    ->where('q_vehicle_estimatedValue', $flattenedData['estimated_value'])
                    ->where('q_vehicle_exclusiveConjugal', $flattenedData['exclusive_conjugal'])
                    ->where('q_vehicle_shareSelf', $flattenedData['share_self'])
                    ->where('q_vehicle_shareSpouse', $flattenedData['share_spouse'])
                    ->where('q_vehicle_withInsurance', $flattenedData['with_vehicle_insurance'])
                    ->where('q_vehicle_renewalMonth', $flattenedData['renewalMonth'])
                    ->count();
            }else if($table == 'PersonalAssets'){
                return PersonalAssets::where('q_perAs_id', $flattenedData['pa_id'])->count();
            }else if($table == 'PersonalAssets_V2'){
                return PersonalAssets::where('q_perAs_clientID', $flattenedData['client_id'])
                    ->where('q_perAs_item', $flattenedData['item_name'])
                    ->where('q_perAs_estimatedValue', $flattenedData['estimated_value'])
                    ->where('q_perAs_purpose', $flattenedData['purpose'])
                    ->where('q_perAs_withGuaranteedPayout', $flattenedData['with_guaranteed_payout'])
                    ->where('q_perAs_exclusiveConjugal', $flattenedData['exclusive_conjugal'])
                    ->where('q_perAs_shareSelf', $flattenedData['share_self'])
                    ->where('q_perAs_shareSpouse', $flattenedData['share_spouse'])
                    ->count();
            }else if($table == 'Liabilities'){
                return Liabilities::where('q_lia_id', $flattenedData['liabilities_id'])->count();
            }else if($table == 'Liabilities_V2'){
                return Liabilities::where('q_lia_clientID', $flattenedData['client_id'])
                    ->where('q_lia_creditorName', $flattenedData['name_of_creditor'])
                    ->where('q_lia_type', $flattenedData['type_of_liability'])
                    ->where('q_lia_totalUnpaidAmt', $flattenedData['total_unpaid_amount'])
                    ->where('q_lia_annualInterestRate', $flattenedData['annual_interest_rate'])
                    ->where('q_lia_amtOfMRI', $flattenedData['amount_of_mri'])
                    ->where('q_lia_renewalMonth', $flattenedData['renewal_month'])
                    ->where('q_lia_spousePartx', $flattenedData['spouse_participation'])
                    ->where('q_lia_propAssocx', $flattenedData['property_association'])
                    ->count();
            }else if($table == 'DreamsAndAspiration'){
                return DreamsAspirations::where('q_dreAsp_id', $flattenedData['dreasp_id'])->count();
            }else if($table == 'DreamsAndAspiration_V2'){
                return DreamsAspirations::where('q_dreAsp_client_id', $flattenedData['client_id'])
                    ->where('q_dreAsp_goals', $flattenedData['goals'])
                    ->where('q_dreAsp_otherGoals', $flattenedData['other_goals'])
                    ->where('q_dreAsp_typeTargetAmount', $flattenedData['target_amount'])
                    ->where('q_dreAsp_timeline', $flattenedData['timeline'])
                    ->count();
            }else if($table == 'PlanningSolutions'){
                return FinancialPlannSol::where('q_finPlSo_id', $flattenedData['plansol_id'])->count(); // old 'fps_id'
            }else if($table == 'PlanningSolutions_V2'){
                return FinancialPlannSol::where('q_finPlSo_clientID', $request['client_id'])
                    ->where('q_finPlSo_forTable', $request['fromTable'])
                    ->count();
            }else if($table == 'PlanSol_Todos'){
                return Todos::where('q_tdo_id', $flattenedData['todos_id'])->count();
            }else if($table == 'PlanSol_Todos_V2'){
                return Todos::where('q_tdo_clientID', $request['client_id'])
                    ->where('q_tdo_isForClientAgent', $flattenedData['todos_forClientAgent'])
                    ->where('q_tdo_descripx', $flattenedData['todo'])
                    ->count();
            }else if($table == 'saveNewFamProFNA'){
                return FPFNA::where('q_fpfna_id', $flattenedData['fpfna_id'])->count();
            }else if($table == 'saveNewFamProFNA_V2'){
                return FPFNA::where('q_fpfna_clientID', $request['client_id'])->count();
            }else if($table == 'DebtsAndFinalExpenses'){
                return DebtsAndFinalExpenses::where('q_debtFinExp_debFin_id', $flattenedData['debFin_id'])->count();
            }else if($table == 'DebtsAndFinalExpenses_V2'){
                return DebtsAndFinalExpenses::where('q_debtFinExp_client_id', $flattenedData['client_id'])
                ->where('q_debtFinExp_debFinList_id', $flattenedData['debFinList_id'])
                ->where('q_debtFinExp_amount_on_client', $flattenedData['amount_on_client'])
                ->where('q_debtFinExp_amount_on_spouse', $flattenedData['amount_on_spouse'])
                ->count();
            }else if($table == 'ExistLifeInsureCov'){
                return ExistingLifeInsuranceCoverage::where('q_exLifeInsCov_id', $flattenedData['exLifeInsCov_id'])->count();
            }else if($table == 'ExistLifeInsureCov_V2'){
                return ExistingLifeInsuranceCoverage::where('q_exLifeInsCov_clientID', $flattenedData['client_id'])
                ->where('q_exLifeInsCov_listID', $flattenedData['exLifeInsCovList_id'])
                ->where('q_exLifeInsCov_amtClient', $flattenedData['amount_on_client'])
                ->where('q_exLifeInsCov_amtSpouse', $flattenedData['amount_on_spouse'])
                ->count();
            }else if($table == 'RetirementPlanFNA'){
                return RetirePlannFNA::where('q_retPFNA_id', $flattenedData['retPFNA_id'])->count();
            }else if($table == 'RetirementPlanFNA_V2'){
                return RetirePlannFNA::where('q_retPFNA_clientID', $flattenedData['client_id'])
                ->where('q_retPFNAa_resRetPlann', $flattenedData['reason_retirement_plann'])
                ->where('q_retPFNA_howRetLooks', $flattenedData['how_retirement_looks'])
                ->where('q_retPFNA_currAgeCL', $flattenedData['current_age_cl'])
                ->where('q_retPFNA_currAgeSP', $flattenedData['current_age_sp'])
                ->where('q_retPFNA_ageRetCL', $flattenedData['age_retirement_cl'])
                ->where('q_retPFNA_ageRetSP', $flattenedData['age_retirement_sp'])
                ->where('q_retPFNA_yrsB4RetCL', $flattenedData['yrs_b4_retirement_cl'])
                ->where('q_retPFNA_yrsB4RetSP', $flattenedData['yrs_b4_retirement_sp'])
                ->where('q_retPFNA_lifeSpanCL', $flattenedData['life_span_cl'])
                ->where('q_retPFNA_lifeSpanSP', $flattenedData['life_span_sp'])
                ->where('q_retPFNA_yrsAfterRetCL', $flattenedData['yrs_after_retirement_cl'])
                ->where('q_retPFNA_yrsAfterRetSP', $flattenedData['yrs_after_retirement_sp'])
                ->where('q_retPFNA_avgInfaRate', $flattenedData['avg_inflation_rate'])
                ->where('q_retPFNA_intRetirement', $flattenedData['interest_retirement'])
                ->where('q_retPFNA_sssAnnualCL', $flattenedData['sss_anual_cl'])
                ->where('q_retPFNA_sssAnnualSP', $flattenedData['sss_anual_sp'])
                ->where('q_retPFNA_yrsBenefitCL1', $flattenedData['yrs_benefit_cl1'])
                ->where('q_retPFNA_yrsBenefitSP1', $flattenedData['yrs_benefit_sp1'])
                ->where('q_retPFNA_benefitRetCL', $flattenedData['benefit_retirement_cl'])
                ->where('q_retPFNA_benefitRetSP', $flattenedData['benefit_retirement_sp'])
                ->where('q_retPFNA_yrsBenefitCL2', $flattenedData['yrs_benefit_cl2'])
                ->where('q_retPFNA_yrsBenefitSP2', $flattenedData['yrs_benefit_sp2'])
                ->count();
            }else if($table == 'RetirementExpenses'){
                return RetirementExp::where('q_retExp_id', $flattenedData['dr_id'])->count();
            }else if($table == 'RetirementExpenses_V2'){
                return RetirementExp::where('q_retExp_clientID', $flattenedData['client_id'])
                ->where('q_retExp_retExpList_id', $flattenedData['retExpList_id'])
                ->where('q_retExp_presentValAmtCL', $flattenedData['presentVal_amt_cl'])
                ->where('q_retExp_presentValAmtSP', $flattenedData['presentVal_amt_sp'])
                ->count();
            }else if($table == 'EducationPlanFNA'){
                return EducPlannFNA::where('q_educPFNA_id', $flattenedData['educPlanFNA_id'])->count();
            }else if($table == 'EducationPlanFNA_V2'){
                return EducPlannFNA::where('q_educPFNA_clientID', $request['client_id'])
                ->where('q_educPFNA_resEducPlannImp', $flattenedData['reason_educPlan_important'])
                ->where('q_educPFNA_dreamsForChildren', $flattenedData['dreams_for_children'])
                ->count();
            }else if($table == 'EducPlanExpenses'){
                return EducPlannExp::where('q_educPExp_id', $flattenedData['educPlanExp_id'])->count();
            }else if($table == 'EducPlanExpenses_V2'){
                return EducPlannExp::where('q_educPExp_famComp_id', $flattenedData['familyComp_id'])
                ->where('q_educPExp_educPExpList_id', $flattenedData['educPlanExpList_id'])
                ->where('q_educPExp_presentValAmt', $flattenedData['presentVal_amt'])
                ->where('q_educPExp_avgInflationRate', $flattenedData['avg_inflation_rate'])
                ->where('q_educPExp_futureNeededValAmt', $flattenedData['fundNeeded_futureVal_amt'])
                ->count();
            }else if($table == 'ChildrenEPFNA'){
                return SelectedChildEducPlan::where('q_selChildEduP_famComp_id', $flattenedData['childrenEducFNA_id'])->count();
            }else if($table == 'ChildrenEPFNA_V2'){
                return SelectedChildEducPlan::where('q_selChildEduP_famComp_id', $flattenedData['famComp_id'])
                ->where('q_selChildEduP_desiredSchool', $flattenedData['desired_school'])
                ->where('q_selChildEduP_childAgeCollege', $flattenedData['age_for_college'])
                ->where('q_selChildEduP_totalEducFundNeeded', $flattenedData['total_educ_fund_needed'])
                ->where('q_selChildEduP_investmentAlloc', $flattenedData['investment_alloc'])
                ->count();
            }else if($table == 'ChildrenEPFNA_qEduc'){
                return SelectedChildEducPlan::where('q_selChildEduP_id', $flattenedData['childrenEducFNA_id'])->count();
            }else if($table == 'ChildrenEPFNA_V2_qEduc'){
                return SelectedChildEducPlan::where('q_selChildEduP_famComp_id', $flattenedData['famComp_id'])
                ->where('q_selChildEduP_desiredSchool', $flattenedData['desired_school'])
                ->where('q_selChildEduP_childAgeCollege', $flattenedData['age_for_college'])
                ->where('q_selChildEduP_totalEducFundNeeded', $flattenedData['total_educ_fund_needed'])
                ->where('q_selChildEduP_investmentAlloc', $flattenedData['investment_alloc'])
                ->count();
            }else if($table == 'cHealthFundPlannFNA'){
                return HealthFundPlannFNA::where('q_healthFP_id', $flattenedData['healthFP_id'])->count();
            }else if($table == 'cHealthFundPlannFNA_V2'){
                return HealthFundPlannFNA::where('q_healthFP_clientID', $request['client_id'])
                ->where('q_healthFP_resHealthFund', $flattenedData['reason_health_fund'])
                ->where('q_healthFP_finSitWithIllMember', $flattenedData['financialSit_with_illMember'])
                ->where('q_healthFP_finImpact', $flattenedData['financial_impact'])
                ->count();
            }else if($table == 'cHealthCovSummary'){
                return HealthCovSumm::where('q_healthCovSum_id', $flattenedData['healthCovSum_id'])->count();
            }else if($table == 'cHealthCovSummary_V2'){
                return HealthCovSumm::where('q_healthCovSum_type', $flattenedData['type'])
                ->where('q_healthCovSum_famCompID', $flattenedData['famComp_id'])
                ->where('q_healthCovSum_policyRefNo', $flattenedData['policyRef_no'])
                ->where('q_healthCovSum_origin', $flattenedData['origin'])
                ->count();
            }else if($table == 'cTargetLimits'){
                return TargetLimits::where('q_targLim_id', $flattenedData['TL_id'])->count();
            }else if($table == 'cTargetLimits_V2'){
                return TargetLimits::where('q_targLim_famCompID', $flattenedData['famComp_id'])
                ->where('q_targLim_type', $flattenedData['type'])
                ->count();
            }
        } catch (\Throwable $th) {
            return 0;
        }
    }
}
