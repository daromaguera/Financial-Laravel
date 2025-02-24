<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FamilyComposition;
use App\Models\Client;
use App\Models\Admin;
use App\Models\Agent;
use App\Models\EducPlannExp;
use App\Models\Heir;

use App\Http\Resources\ClientFNResource;
use App\Http\Resources\ClientSpouseFNResource;
// use Jenssegers\Agent\Facades\Agent;
use Carbon\Carbon;

class MainController extends ExtendedController{
    public $wantField = '';
    public $data = null, $validated = null, $tempMessage = null;
    public $dataRequest = array(), $dataFetch = array(), $collectedErrors = array(), $payLoad = array();


//   ******    *******   ***********
//  *          *              *
//  * *****    *****          *
//  *      *   *              *
//   ******    *******        *

    // Verifying User
    public function verifyWebUser(Request $request){
        $result = $this->verifyUser($request, 0);
        return response()->json(['data' => $result], 200);
    }

    // Agent Section
    public function getAllAgents(Request $request){
        $this->data = $this->getAllData('Agent', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Agent');
    }
    public function getAllAgentsOrderFirstNameASC(Request $request){
        $this->data = $this->getAllDataInOrder('Agent','q_agnt_f_name','ASC', $request);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Agent');
    }
    public function getAllAgentsOrderFirstNameDESC(Request $request){
        $this->data = $this->getAllDataInOrder('Agent','q_agnt_f_name','DESC', $request);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Agent');
    }
    public function getAllAgentsOrderLastNameASC(Request $request){
        $this->data = $this->getAllDataInOrder('Agent','q_agnt_l_name','ASC', $request);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Agent');
    }
    public function getAllAgentsOrderLastNameDESC(Request $request){
        $this->data = $this->getAllDataInOrder('Agent','q_agnt_l_name','DESC', $request);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Agent');
    }

    // Client Section
    public function getIndividualClient(Request $request){
        $this->validated = $this->validateFields('individualClient', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->getAllData('IndividualClient', $request, null);
        return $this->customJsonFormatterIndividualClient($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Client');
    }
    public function getAllClients(Request $request){
        $this->validated = $this->validateFields('Client_checkAgentID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $count = Agent::where('q_agnt_id', $request['agent_id'])->count();
        if($count > 0){
            $this->data = $this->getAllData('Client', $request, null);
        }else{
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Client');
    }
    public function adm_getAllClients(Request $request){
        $this->validated = $this->validateFields('Client_checkAgentID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $count = Agent::where('q_agnt_id', $request['agent_id'])->where('q_agnt_uType', 'S')->count();
        if($count > 0){
            $this->data = $this->getAllData('AllClient', $request, null);
        }else{
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Client');
    }
    public function getAllClientsOrderFirstNameASC(Request $request){
        $this->validated = $this->validateFields('Client_checkAgentID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->getAllDataInOrder('Client','q_clnt_f_name','ASC', $request);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Client');
    }
    public function getAllClientsOrderFirstNameDESC(Request $request){
        $this->validated = $this->validateFields('Client_checkAgentID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->getAllDataInOrder('Client','q_clnt_f_name','DESC', $request);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Client');
    }
    public function getAllClientsOrderLastNameASC(Request $request){
        $this->validated = $this->validateFields('Client_checkAgentID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $count = Agent::where('q_agnt_id', $request['agent_id'])->count();
        if($count > 0){
            $this->data = $this->getAllDataInOrder('Client','q_clnt_l_name','ASC', $request);
        }else{
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Client');
    }
    public function adm_getAllClientsOrderLastNameASC(Request $request){
        $this->validated = $this->validateFields('Client_checkAgentID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $count = Agent::where('q_agnt_id', $request['agent_id'])->where('q_agnt_uType', 'S')->count();
        if($count > 0){
            $this->data = $this->getAllDataInOrder('adm_Client_onSort','q_clnt_l_name','ASC', $request);
        }else{
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Client');
    }
    public function getAllClientsOrderLastNameDESC(Request $request){
        $this->validated = $this->validateFields('Client_checkAgentID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->getAllDataInOrder('Client','q_clnt_l_name','DESC', $request);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Client');
    }
    public function adm_getAllClientsOrderLastNameDESC(Request $request){
        $this->validated = $this->validateFields('Client_checkAgentID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->getAllDataInOrder('adm_Client_onSort','q_clnt_l_name','DESC', $request);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Client');
    }
    public function getFamilyMember(Request $request, $out, $famType){
        $this->validated = $this->validateFields('famCompReq_checkCID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->getAllData("getFamMember", $request, $famType);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, $out);
    }
    public function getClientChildren(Request $request){
        return $this->getFamilyMember($request, "Client_Children", 2);
    }
    public function getClientSpouse(Request $request){
        return $this->getFamilyMember($request, "Client_Spouse", 0);
    }
    public function getClientPartner(Request $request){
        return $this->getFamilyMember($request, "Client_Partner", 1);
    }
    public function getClientFather(Request $request){
        return $this->getFamilyMember($request, "Client_Father", 3);
    }
    public function getClientMother(Request $request){
        return $this->getFamilyMember($request, "Client_Mother", 4);
    }

    // Financial Priorities
    public function getAllFinancialPriority(Request $request){
        $this->data = $this->getAllData('financial_priority', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Financial_Priorities');
    }
    public function getAllSelectedFinancialPriorities(Request $request){
        $this->validated = $this->validateFields('SFP_checkCID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->getAllData('selected_financial_priorities', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Selected_Financial_Priorities');
    }

    // Cash Flow List
    public function getCashFlowList(Request $request){
        $this->validated = $this->validateFields('cash_flow_list', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->CashFlowList = $this->getAllData('CashFlowList', $request, null);
        $this->CashFlowAnalysis = $this->getAllData('CashFlowAnalysis', $request, null);
        if($this->CashFlowAnalysis['http'] == 200){
            $request['isInflowOutflow'] = $request['cashFlowType'];
            $this->Recommendations = $this->getAllData('Recommendations', $request, null);
        }

        $clientData = Client::where('q_clnt_id', $request['client_id'])
            ->select(['q_clnt_l_name', 'q_clnt_f_name', 'q_clnt_m_name'])
            ->first();

        $clientResource = $clientData
            ? new ClientFNResource($clientData)
            : null;

        $spouseData = FamilyComposition::where('q_famComp_clientID', $request['client_id'])
            ->where('q_famComp_compType', 0)
            ->select(['q_famComp_lastName', 'q_famComp_firstName', 'q_famComp_middleInitial'])
            ->first();

        $spouseResource = $spouseData
            ? new ClientSpouseFNResource($spouseData)
            : "No Spouse";
            
        $this->data = [
            'http' => $this->CashFlowList['http'],
            intval($request['cashFlowType']) == 0 ? 'CashFlowList_InFlow' : 'CashFlowList_OutFlow' => $this->CashFlowList['data'],
            'Cash_Flow_Analysis' => $this->CashFlowAnalysis['http'] == 200 ? $this->CashFlowAnalysis['data'] : [],
            'Recommendations' => $this->Recommendations['http'] == 200 ? $this->Recommendations['data'] : [],
            'Client_FN' => $clientResource,
            'Spouse_FN' => $spouseResource,
            'message' => $this->CashFlowList['message'],
            'meta' => '',
        ];
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data : '', $this->data, 'Family_Cash_Flow_Analysis');
    }
    public function cashFlowListOrderDescriptionASC(Request $request){
        $this->validated = $this->validateFields('cash_flow_list', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->CashFlowList = $this->getAllDataInOrder('cash_flow_list','q_cfl_descripx','ASC', $request);
        $this->CashFlowAnalysis = $this->getAllData('CashFlowAnalysis', $request, null);
        if($this->CashFlowAnalysis['http'] == 200){
            $request['isInflowOutflow'] = $request['cashFlowType'];
            $this->Recommendations = $this->getAllData('Recommendations', $request, null);
        }
        $data = [
            'http' => $this->CashFlowList['http'],
            intval($request['cashFlowType']) == 0 ? 'CashFlowList_InFlow' : 'CashFlowList_OutFlow' => $this->CashFlowList['data'],
            'Cash_Flow_Analysis' => $this->CashFlowAnalysis['http'] == 200 ? $this->CashFlowAnalysis['data'] : [],
            'Recommendations' => $this->Recommendations['http'] == 200 ? $this->Recommendations['data'] : [],
            'message' => $this->CashFlowList['message'],
            'meta' => '',
        ];
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data : '', $this->data, 'Family_Cash_Flow_Analysis');
    }

    // Family Composition
    public function getFamilyComposition(Request $request){
        $this->validated = $this->validateFields('FC_checkCID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->getAllData('family_composition', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Family_Composition');
    }

    // Cash and Deposits
    public function getCashAndDeposit(Request $request){
        $this->validated = $this->validateFields('CAD_checkCID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->getAllData('CashAndDeposits', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Cash_And_Deposits');
    }

    // Receivables
    public function getReceivables(Request $request){
        $this->validated = $this->validateFields('R_checkCID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->getAllData('Receivables', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Receivables');
    }

    // Mutual Funds / UITF
    public function getMutualFunds(Request $request){
        $this->validated = $this->validateFields('MF_checkCID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->getAllData('Mutual_Funds', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Mutual_Funds');
    }

    // Bonds
    public function getBonds(Request $request){
        $this->validated = $this->validateFields('Bonds_checkCID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->getAllData('Bonds', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Bonds');
    }

    // Stocks in Companies (Listed or Non-Listed)
    public function getStocksInCompanies(Request $request, $isListed){
        $this->validated = $this->validateFields('SIC_checkCID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->dataRequest = [
            'client_id' => $request['client_id'],
            'isListed' => $isListed
        ];
        $this->data = $this->getAllData('StockInCompanies', $this->dataRequest, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Stocks_In_Companies');
    }
    public function getStocksInCompaniesListed(Request $request){
        return $this->getStocksInCompanies($request, 1);
    }
    public function getStocksInCompaniesNonListed(Request $request){
        return $this->getStocksInCompanies($request, 0);
    }

    // Life and Health Insurance
    public function getLifeHealthInsurance(Request $request, $fromAdviser){
        $this->validated = $this->validateFields('LAHI_checkCID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422);
        }
        $this->dataRequest = [
            'client_id' => $request['client_id'],
            'fromAdviser' => $fromAdviser
        ];
        $this->data = $this->getAllData('LifeAndHealthInsurance', $this->dataRequest, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Life_And_Health_Insurance');
    }
    public function getLifeHealthInsuranceAdviser(Request $request){
        return $this->getLifeHealthInsurance($request, 1);
    }
    public function getLifeHealthInsuranceNotAdviser(Request $request){
        return $this->getLifeHealthInsurance($request, 2);
    }

    // Family Homes
    public function getFamilyHomesRealEstate(Request $request){
        $this->validated = $this->validateFields('FamilyHomesRealEstate', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->getAllData('FamilyHomeRealEstate', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, $request['isHome'] == 1 ? 'Family_Homes' : 'Real_Estate');
    }
    // Family Homes
    public function getFamilyHomes(Request $request){
        $this->validated = $this->validateFields('FamilyHomesRealEstate', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->getAllData('FamilyHome', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Family_Homes');
    }
    // Real Estate
    public function getRealEstate(Request $request){
        $this->validated = $this->validateFields('FamilyHomesRealEstate', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->getAllData('RealEstate', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Real_Estate');
    }

    // Vehicles
    public function getVehicles(Request $request){
        $this->validated = $this->validateFields('Vehicles_checkCID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->getAllData('Vehicles', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Vehicles');
    }

    // Personal Assets
    public function getPersonalAssets(Request $request){
        $this->validated = $this->validateFields('PA_checkID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->getAllData('PersonalAssets', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Personal_Assets');
    }

    // Liabilities
    public function getLiabilities(Request $request){
        $this->validated = $this->validateFields('Lia_checkCID', 'get', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->getAllData('Liabilities', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Liabilities');
    }

    // Family Protection FNA
    public function getFamProFNA(Request $request){
        $validUser = $this->validateUserAndRequest($request);
        if($validUser["success"] == 0){
            return response()->json(['response' => $validUser], 422);
        }
        $result = $this->getMultiData('famProtectionFNA', $request);
        return $this->customJsonFormatter($result['data'] !== null ? $result : '', $result, 'Family_Protection_FNA');
    }

    // Retirement Planning FNA
    public function getRetPlanFNA(Request $request){
        $validUser = $this->validateUserAndRequest($request);
        if($validUser["success"] == 0){
            return response()->json(['response' => $validUser], 422);
        }
        $result = $this->getMultiData('retPlanFNA', $request);
        return $this->customJsonFormatter($result['data'] !== null ? $result : '', $result, 'Retirement_Planning_FNA');
    }

    // Education Planning FNA
    public function getEducPlanFNA(Request $request){
        $validUser = $this->validateUserAndRequest($request);
        if($validUser["success"] == 0){
            return response()->json(['response' => $validUser], 422);
        }
        $result = $this->getMultiData('educPlanFNA', $request);
        return $this->customJsonFormatter($result['data'] !== null ? $result : '', $result, 'Education_Planning-FNA');
    }

    // Health Fund Planning FNA
    public function getHealthFundPlanFNA(Request $request){
        $validUser = $this->validateUserAndRequest($request);
        if($validUser["success"] == 0){
            return response()->json(['response' => $validUser], 422);
        }
        $result = $this->getMultiData('healthFundPlanFNA', $request);
        return $this->customJsonFormatter($result['data'] !== null ? $result : '', $result, 'Health_Fund_Planning_FNA');
    }

    // Education Fund List
    public function getEducFundListWithoutPayout(Request $request){
        $validUser = $this->validateUserAndRequest($request);
        if($validUser["success"] == 0){
            return response()->json(['response' => $validUser], 422);
        }
        $result = $this->getMultiData('educFundListWithoutPayout', $request);
        return $this->customJsonFormatter($result['data'] !== null ? $result : '', $result, 'Education_Fund_List_Without_Guaranteed_Payout');
    }
    // Education Fund List
    public function getEducFundListWithPayout(Request $request){
        $validUser = $this->validateUserAndRequest($request);
        if($validUser["success"] == 0){
            return response()->json(['response' => $validUser], 422);
        }
        $result = $this->getMultiData('educFundListWithPayout', $request);
        return $this->customJsonFormatter($result['data'] !== null ? $result : '', $result, 'Education_Fund_List_With_Guaranteed_Payout');
    }

    // Retirement Fund Value without Guaranteed Payout
    public function getRetirementFundValueWithoutPayout(Request $request){
        $validUser = $this->validateUserAndRequest($request);
        if($validUser["success"] == 0){
            return response()->json(['response' => $validUser], 422);
        }
        $result = $this->getMultiData('retirementFundValueWithoutPayout', $request);
        return $this->customJsonFormatter($result['data'] !== null ? $result : '', $result, 'Retirement_Fund_Without_Guaranteed_Payout');
    }

    // Retirement Fund Value with Guaranteed Payout
    public function getRetirementFundValueWithPayout(Request $request){
        $validUser = $this->validateUserAndRequest($request);
        if($validUser["success"] == 0){
            return response()->json(['response' => $validUser], 422);
        }
        $result = $this->getMultiData('retirementFundValueWithPayout', $request);
        return $this->customJsonFormatter($result['data'] !== null ? $result : '', $result, 'Retirement_Fund_Without_Guaranteed_Payout');
    }

    // Admin Settings
    public function getAdminSettings(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $this->data = $this->getAllData('AdminSettings', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Admin_Settings');
    }

    // FNA Completion
    public function getFNACompletion(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $this->data = $this->getAllData('FNACompletion', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'FNA_Completion');
    }

    // My To Dos for Clients
    public function getAgentToDosForClients(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $count = Agent::where('q_agnt_id', $request['user_id'])->count();
        if($count > 0){
            $this->data = $this->getAllData('MyToDosForClients', $request, null);
        }else{
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        // else{
        //     $count = Admin::where('q_ADm_id', $request['user_id'])->count();
        //     if($count > 0){
        //         $this->data = $this->getAllData('AdminRQT_AgentToDosForClients', $request, null);
        //     }else{
        //         return $this->throwErrorCustom('Unauthorized user.', 401);
        //     }
        // }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'My_ToDos_for_Clients');
    }
    // My To Dos for Clients Order by Clients
    public function getAgentToDosForClientsOrderByClients(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $count = Agent::where('q_agnt_id', $request['user_id'])->count();
        if($count > 0){
            $this->data = $this->getAllData('MyToDosForClientsOrderByClients', $request, null);
        }else{
            $count = Admin::where('q_ADm_id', $request['user_id'])->count();
            if($count > 0){
                $this->data = $this->getAllData('MyToDosForClientsOrderByClients', $request, null);
                //$this->data = $this->getAllData('AdminRQT_MyToDosForClientsOrderByClients', $request, null);
            }else{
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'My_ToDos_for_Clients');
    }

    // Search from the Agent To Dos for Clients
    public function searchAgentToDos(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $count = Agent::where('q_agnt_id', $request['user_id'])->count();
        if($count > 0){
            $this->data = $this->searchData('AgentToDos',$request,'All','');
        }else{
            $count = Admin::where('q_ADm_id', $request['user_id'])->count();
            if($count > 0){
                $this->data = $this->searchData('AdminRQT_AgentToDos',$request,'All','');
            }else{
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'My_ToDos_for_Clients');
    }

    // Clients for Annual Review - Data sourced from the Table Financial Planning Solution
    public function getClientAnnualReview(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $count = Agent::where('q_agnt_id', $request['user_id'])->count();
        if($count > 0){
            $this->data = $this->getAllData('clientsForAnnualReview', $request, null);
        }else{
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        // else{
        //     $count = Admin::where('q_ADm_id', $request['user_id'])->count();
        //     if($count > 0){
        //         $this->data = $this->getAllData('AdminRQT_clientsForAnnualReview', $request, null);
        //     }else{
        //         return $this->throwErrorCustom('Unauthorized user.', 401);
        //     }
        // }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Clients_For_Annual_Review');
    }

    // Clients for Annual Review - Data sourced from the Table Financial Planning Solution - Order by Client Name
    public function getClientAnnualReviewByClientName(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $count = Agent::where('q_agnt_id', $request['user_id'])->count();
        if($count > 0){
            $this->data = $this->getAllData('clientsForAnnualReviewOrderName', $request, null);
        }else{
            $count = Admin::where('q_ADm_id', $request['user_id'])->count();
            if($count > 0){
                $this->data = $this->getAllData('AdminRQT_clientsForAnnualReviewOrderName', $request, null);
            }else{
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Clients_For_Annual_Review');
    }

    // Clients for Annual Review - Data sourced from the Table Financial Planning Solution - Order by Date
    public function getClientAnnualReviewByDate(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $count = Agent::where('q_agnt_id', $request['user_id'])->count();
        if($count > 0){
            $this->data = $this->getAllData('clientsForAnnualReviewOrderDate', $request, null);
        }else{
            $count = Admin::where('q_ADm_id', $request['user_id'])->count();
            if($count > 0){
                $this->data = $this->getAllData('AdminRQT_clientsForAnnualReviewOrderDate', $request, null);
            }else{
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Clients_For_Annual_Review');
    }

    // Clients for Annual Review - Data sourced from the Table Financial Planning Solution
    public function searchClientAnnualReview(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $count = Agent::where('q_agnt_id', $request['user_id'])->count();
        if($count > 0){
            $this->data = $this->searchData('searchClientsAnnualReview',$request,'All','');
        }else{
            $count = Admin::where('q_ADm_id', $request['user_id'])->count();
            if($count > 0){
                $this->data = $this->searchData('AdminRQT_searchClientsAnnualReview',$request,'All','');
            }else{
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Clients_For_Annual_Review');
    }

    // Overdue - Agent To Dos for Clients
    public function getOverdueToDos(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $this->data = $this->getAllData('overdueToDos', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Overdue_ToDos');
    }

    // My To Dos for Clients Order by Date
    public function getAgentToDosForClientsOrderDate(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $count = Agent::where('q_agnt_id', $request['user_id'])->count();
        if($count > 0){
            $this->data = $this->getAllData('MyToDosForClientsOrderByDate', $request, null);
        }else{
            $count = Admin::where('q_ADm_id', $request['user_id'])->count();
            if($count > 0){
                $this->data = $this->getAllData('AdminRQT_MyToDosForClientsOrderByDate', $request, null);
            }else{
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'My_ToDos_for_Clients');
    }

    // Advisor Activities
    public function getAdvisorActivities(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $count = Agent::where('q_agnt_id', $request['user_id'])->count();
        if($count > 0){
            $this->data = $this->getAllData('advisorActivities', $request, null);
        }else{
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Advisor_Activities');
    }

    // Education Planning FNA
    public function getAllChildDataForEducPlanning(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $this->data = [
            'data' => [
                'baseDataEducPlan' => $this->getAllData('baseDataEducPlan', $request, null),
                'Family_Composition' => $this->getAllData('child_in_family_composition', $request, null),
                'Admin_Settings' => $this->getAllData('AdminSettings', $request, null),
                'Client_Info' => $this->getAllData('IndividualClient', $request, null),
                'Selected_Child' => $this->getAllData('SelectedChild', $request, null),
                'EducPlanExp' => $this->getAllData('getEducPlanningExpRTrl', $request, null),
                'EducPlanExpList' => $this->getAllData('educPlanExpListIto', $request, null),
                'finPlanSol' => $this->getAllData('getFinPlanSol', $request, null),
            ],
            'message' => "Successful Server Operation",
            'http' => 200,
            'meta' => ''
        ];
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Educ_Planning_FNA');
    }
    public function getEducInvestmentGuaranteedP(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $this->data = [
            'data' => [
                'WOGP_Cash_And_Deposit' => $this->getAllData('CashAndDepoWithoutGuaranteedPayout', $request, null),
                'WOGP_Mutual_Funds' => $this->getAllData('MutualFundsWithoutGuaranteedPayout', $request, null),
                'WOGP_Bonds' => $this->getAllData('BondsWithoutGuaranteedPayout', $request, null),
                'WOGP_Life_And_Health_Insurance' => $this->getAllData('LifeAndHealthInsuranceWithoutGuaranteedPayout', $request, null),
                'WOGP_Home_Estate' => $this->getAllData('HomeEstateWithoutGuaranteedPayout', $request, null),
                'WOGP_Personal_Assets' => $this->getAllData('PersonalAssetsWithoutGuaranteedPayout', $request, null),
                
                'WGP_Cash_And_Deposit' => $this->getAllData('CashAndDepoWithGuaranteedPayout', $request, null),
                'WGP_Mutual_Funds' => $this->getAllData('MutualFundsWithGuaranteedPayout', $request, null),
                'WGP_Bonds' => $this->getAllData('BondsWithGuaranteedPayout', $request, null),
                'WGP_StocksInCompanies' => $this->getAllData('StocksInCompaniesWithGuaranteedPayout', $request, null),
                'WGP_Life_And_Health_Insurance' => $this->getAllData('LifeAndHealthInsuranceWithGuaranteedPayout', $request, null),
                'WGP_Home_Estate' => $this->getAllData('HomeEstateWithGuaranteedPayout', $request, null),
                'WGP_Personal_Assets' => $this->getAllData('PersonalAssetsWithGuaranteedPayout', $request, null),
            ],
            'message' => "Successful Server Operation",
            'http' => 200,
            'meta' => ''
        ];
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Educ_Planning_Fund_Allocation');
    }
    public function getRetirementInvestmentGuaranteedP(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $this->data = [
            'data' => [
                'WOGP_Cash_And_Deposit' => $this->getAllData('CashAndDepoWithoutGuaranteedPayout_ret', $request, null),
                'WOGP_Mutual_Funds' => $this->getAllData('MutualFundsWithoutGuaranteedPayout_ret', $request, null),
                'WOGP_Bonds' => $this->getAllData('BondsWithoutGuaranteedPayout_ret', $request, null),
                'WOGP_Life_And_Health_Insurance' => $this->getAllData('LifeAndHealthInsuranceWithoutGuaranteedPayout_ret', $request, null),
                'WOGP_Home_Estate' => $this->getAllData('HomeEstateWithoutGuaranteedPayout_ret', $request, null),
                'WOGP_Personal_Assets' => $this->getAllData('PersonalAssetsWithoutGuaranteedPayout_ret', $request, null),
                
                'WGP_Cash_And_Deposit' => $this->getAllData('CashAndDepoWithGuaranteedPayout_ret', $request, null),
                'WGP_Mutual_Funds' => $this->getAllData('MutualFundsWithGuaranteedPayout_ret', $request, null),
                'WGP_Bonds' => $this->getAllData('BondsWithGuaranteedPayout_ret', $request, null),
                'WGP_StocksInCompanies' => $this->getAllData('StocksInCompaniesWithGuaranteedPayout_ret', $request, null),
                'WGP_Life_And_Health_Insurance' => $this->getAllData('LifeAndHealthInsuranceWithGuaranteedPayout_ret', $request, null),
                'WGP_Home_Estate' => $this->getAllData('HomeEstateWithGuaranteedPayout_ret', $request, null),
                'WGP_Personal_Assets' => $this->getAllData('PersonalAssetsWithGuaranteedPayout_ret', $request, null),
            ],
            'message' => "Successful Server Operation",
            'http' => 200,
            'meta' => ''
        ];
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Retirement_Planning_Fund_Allocation');
    }
    public function getEducPlanningExp(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $this->data = $this->getAllData('getEducPlanningExp', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Educ_Plan_Exp_Data');
    }

    // Financial Planning Solution
    public function getFinancialPlanSol(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $this->data = $this->getAllData('getFinPlanSol', $request, null);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Financial_Planning_Solution');
    }

    // Getting all Data to initialize the front-end application
    public function getAllDataToInitialize(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $this->data = [
            'data' => [
                'adm_clients' => $this->getAllData('AllClient', $request, null),
                'client' => $this->getAllData('Client', $request, null),
                'My_ToDos_for_Clients' => $this->getAllData('MyToDosForClientsIsFor2', $request, null),
                'Client_ToDos' => $this->getAllData('MyToDosForClientsIsFor1', $request, null),
                'Overdue_ToDos' => $this->getAllData('overdueToDos', $request, null),
                'Overdue_ToDosForClient' => $this->getAllData('overdueToDosForClient', $request, null),
                'Clients_For_Annual_Review' => $this->getAllData('clientsForAnnualReview', $request, null),
                'Clients_For_Annual_Review2' => $this->getAllData('clientsForAnnualReview2', $request, null),
            ],
            'message' => "Successful Server Operation",
            'http' => 200,
            'meta' => ''
        ];
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'All_Data');
    }

    // Sync Clients from LINDA
    public function syncClientsFromLinda(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $resultSync = $this->syncData('Client', $request);
        if($resultSync){
            $count = Agent::where('q_agnt_id', $request['agent_id'])->count();
            if($count > 0){
                $this->data = $this->getAllData('Client', $request, null);
            }else{
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Client');
        }else{
            return [
                'status' => false,
                'errorMessage' => 'Something went wrong during syncing... Try again later or consult the system administrator.'
            ];
        }
    }

    // getting list of clients and Financial Plan Presentation
    public function getClientsForFinPlanPres(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $this->data = [
            'data' => [
                'client_info' => $this->getAllData('IndividualClient', $request, null),
                'all_clients' => $this->getAllData('Client', $request, null),
            ],
            'message' => "Successful Server Operation",
            'http' => 200,
            'meta' => ''
        ];
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Data_Fin_Plan_Pres');
    }

    // getting list of clients and Selected Financial Piorities
    public function getSelFinPrioAndClients(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $this->data = [
            'data' => [
                'selected_Financial_Priorities' => $this->getAllData('selected_financial_priorities', $request, null),
                'all_clients' => $this->getAllData('Client', $request, null),
            ],
            'message' => "Successful Server Operation",
            'http' => 200,
            'meta' => ''
        ];
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Data_Fin_Plan_Pres');
    }

    // All Data for Networth Inventory
    public function getAllDataNetworthInventory(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $dataRequest1 = [
            'client_id' => $request['client_id'],
            'isListed' => 1
        ];
        $dataRequest2 = [
            'client_id' => $request['client_id'],
            'isListed' => 0
        ];
        $lifeHealthInsu1 = [
            'client_id' => $request['client_id'],
            'fromAdviser' => 1
        ];
        $lifeHealthInsu2= [
            'client_id' => $request['client_id'],
            'fromAdviser' => 2
        ];
        $this->data = [
            'data' => [
                'CashAndDeposit' => $this->getAllData('CashAndDeposits', $request, null),
                'Receivables' => $this->getAllData('Receivables', $request, null),
                'MutualFunds' => $this->getAllData('Mutual_Funds', $request, null),
                'Bonds' => $this->getAllData('Bonds', $request, null),
                'StockListed' => $this->getAllData('StockInCompanies', $dataRequest1, null),
                'StockNotListed' => $this->getAllData('StockInCompanies', $dataRequest2, null),
                'LifeAndHealthInsuFromAetosAdvisor' => $this->getAllData('LifeAndHealthInsurance', $lifeHealthInsu1, null),
                'LifeAndHealthInsuFromOtherAetosAdvisor' => $this->getAllData('LifeAndHealthInsurance', $lifeHealthInsu2, null),
                'FamilyHome' => $this->getAllData('FamilyHome', $request, null),
                'RealEstate' => $this->getAllData('RealEstate', $request, null),
                'Vehicles' => $this->getAllData('Vehicles', $request, null),
                'PersonalAssets' => $this->getAllData('PersonalAssets', $request, null),
                'Liabilities' => $this->getAllData('Liabilities', $request, null),
                'all_clients' => $this->getAllData('Client', $request, null),
                'client_info' => $this->getAllData('IndividualClient', $request, null),
                'familyComposition' => $this->getAllData('family_composition', $request, null),
            ],
            'message' => "Successful Server Operation",
            'http' => 200,
            'meta' => ''
        ];
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'All_Networth_Inventory');
    }



//  *****      *****       ********    ***********
//  *     *   *     *     *                 *
//  *     *   *     *     ********          *
//  *****     *     *              *        *
//  *          *****       ********         *

    // Admin Settings
    public function saveAdminSettings(Request $request){
        try {
            $verified = $this->verifyUser($request, 0);
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            $this->data = $this->saveUpdateNewData($request, 'admin_settings');
            if($this->data){
                $this->data = [
                    'data' => 1,
                    'message' => "Successful Server Operation.",
                    'http' => 200,
                    'meta' => []
                ];
            }else{
                $this->data = [
                    'data' => 0,
                    'message' => "Unable to save/update data. Please consult the system administrator.",
                    'http' => 200,
                    'meta' => []
                ];
            }
            return $this->customJsonFormatter($this->data['data'], $this->data, 'admin_setting');
        } catch (\Throwable $th) {
            $this->data = [
                'data' => 0,
                'message' => "Unexpected error occurred. Please consult the system administrator.",
                'http' => 200,
                'meta' => []
            ];
            return $this->customJsonFormatter($this->data['data'], $this->data, 'admin_setting');
        }
    }

    // Logout
    public function logoutUser(Request $request){
        try {
            Agent::where('q_agnt_id', $request['agent_id'])
            ->orWhere('q_agnt_token', $request['token'])
            ->update([
                'q_agnt_token' => "123",
                'q_agnt_isActive' => 0,
            ]);
            Admin::where('q_ADm_id', $request['agent_id'])
            ->orWhere('q_ADm_token', $request['token'])
            ->update([
                'q_ADm_token' => "123",
                'q_ADm_isActive' => 0,
            ]);
        } catch (\Throwable $th) {
            return 1;
        }
        return 1;
    }
    // Agent Section...
    public function searchAllFromAgents(Request $request){
        $this->validated = $this->validateFields('AgentSearch', 'post', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->searchData('Agent',$request,'All','');
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Agent');
    }
    public function searchFromAgentsOnField(Request $request){
        $this->validated = $this->validateFields('AgentOnField', 'post', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->wantField = $this->wantField($request, 'agents');
        if($this->wantField == ''){
            $msg = [
                'http' => 500,
                'message' => '\'wantField\' field is required. Also, this field requires a value indicator on which field to search, such as \'first_name\', \'last_name\', \'address\', or \'last_successfulsync\'.',
            ];
            return $this->customJsonFormatter('', $msg, 'Agent');
        }
        $this->data = $this->searchData('Agent',$request, 'Specific', $this->wantField);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Agent');
    }

    // Client Section
    public function searchAllFromClients(Request $request){
        $this->validated = $this->validateFields('ClientSearch', 'post', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $count = Agent::where('q_agnt_id', $request['agent_id'])->count();
        if($count > 0){
            $this->data = $this->searchData('Client',$request,'All','');
        }else{
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Client');
    }
    public function adm_searchAllFromClients(Request $request){
        $this->validated = $this->validateFields('ClientSearch', 'post', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $count = Agent::where('q_agnt_id', $request['agent_id'])->where('q_agnt_uType', 'S')->count();
        if($count > 0){
            $this->data = $this->searchData('AllClients',$request,'All','');
        }else{
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Client');
    }
    public function searchFromClientsOnField(Request $request){
        $this->validated = $this->validateFields('ClientOnField', 'post', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422);
        }
        $this->wantField = $this->wantField($request, 'clients');
        if($this->wantField == ''){
            $msg = [
                'http' => 500,
                'message' => '\'wantField\' field is required. Also, this field requires a value indicator on which field to search, such as \'first_name\', \'last_name\', \'gender\', \'contact_number\', \'email_address\', \'civil_status\', \'details_of_health_condition\', \'date_of_successful_sync\', or \'wedding_date\' as value for \'wantField\' key.',
            ];
            return $this->customJsonFormatter('', $msg, 'Client');
        }
        $this->data = $this->searchData('Client',$request,'Specific',$this->wantField);
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Client');
    }

    // Financial Priorities
    public function addUpdateSelFinPriorities(Request $request){
        try {
            $verified = $this->verifyUser($request, 2);
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            $this->dataFetch = [
                'data' => $request[0]['data'],
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
                'client_id' => $request[1]['client_id']
            ]; // fetching JSON in 'data'
            $request = [ 'client_id' => $request[1]['client_id'] ];
            $this->collectedErrors = $this->evaluateAndValidate($this->dataFetch, 'selected_financial_priorities', 'selected_financial_priorities', 'post', null);
            if($this->collectedErrors){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'selected_financial_priorities');
        if($this->data['hasSuccess'] > 0){
            $this->tempMessage = [ 'message' => $this->data['message'] ];
        }
        if($this->data['http'] == 200){
            $this->data = $this->getAllData('selected_financial_priorities', $request, null);
            if($this->tempMessage){
                $this->data['message'] = $this->tempMessage['message'];
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Selected_Financial_Priorities');
    }

    // Cash Flow Section...
    public function saveNewSetOfCashFlow(Request $request){
        $dataFetch = null; $recommendations = null; $targetCashInflow = null; $targetCashOutflow = null; $CFA_type = null;
        $clientData = array(); $cfaErrors = array(); $recommendationErrors = array();
        try {
            $userCompleteName = ""; $resultVerify = 0;
            if($request[4]['CashFlowType'] == 0 || $request[4]['CashFlowType'] == 2){ // 'web_user' is in similar position at '6'
                $verified = $this->verifyUser($request, 6); // Verifying Web User before proceeding to any system processes...
            }else if($request[4]['CashFlowType'] == 1){
                $verified = $this->verifyUser($request, 8); // Verifying Web User before proceeding to any system processes...
            }
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            // fetching JSON in 'data'  // Perform validation here for Data and CashFlowType
            $dataFetch = [
                'data' => $request[0]['data'],
                'flowType' => $request[4]['CashFlowType'],
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
                'client_id' => $request[5]['client_id'],
            ];
            if($request[4]['CashFlowType'] == 0){ // for InFlow
                $recommendations = [
                    'isInflowOutflow' => $request[4]['CashFlowType'],
                    'client_id' => $request[5]['client_id'],
                    'recommendations' => $request[1]['recommendations'],
                    'userFN' => $verified['userCompleteName'],
                    'userID' => $verified['userID'],
                ];
                $targetCashInflow = [
                    'client_id' => $request[5]['client_id'],
                    'client' => $request[2]['target_cashinflow_client'],
                    'spouse' => $request[3]['target_cashinflow_spouse'],
                    'flowType' => $request[4]['CashFlowType'],
                    'userFN' => $verified['userCompleteName'],
                    'userID' => $verified['userID'],
                ];
            }else if($request[4]['CashFlowType'] == 1){ // for Outflow
                $targetCashOutflow = [
                    'client_id' => $request[5]['client_id'],
                    'clientExpenses' => $request[2]['target_cashoutflow_client'],
                    'spouseExpenses' => $request[3]['target_cashoutflow_spouse'],
                    'clientshare_rfn' => $request[6]['clientshare_rfn'],
                    'spouseshare_rfn' => $request[7]['spouseshare_rfn'],
                    'reduce_cf_attempt' => $request[1]['reduce_cf_attempt'],
                    'flowType' => $request[4]['CashFlowType'],
                    'userFN' => $verified['userCompleteName'],
                    'userID' => $verified['userID'],
                ];
            }else if($request[4]['CashFlowType'] == 2){ // For Outflow Budget
                $recommendations = [
                    'isInflowOutflow' => $request[4]['CashFlowType'],
                    'client_id' => $request[5]['client_id'],
                    'recommendations' => $request[1]['recommendations'],
                    'userFN' => $verified['userCompleteName'],
                    'userID' => $verified['userID'],
                ];             
                $targetCashOutflow = [
                    'client_id' => $request[5]['client_id'],
                    'expected_savings' => $request[2]['expected_savings'],
                    'goes_well' => $request[3]['goes_well'],
                    'flowType' => $request[4]['CashFlowType'],
                    'userFN' => $verified['userCompleteName'],
                    'userID' => $verified['userID'],
                ];
            }
            $CFA_type = $request[4]['CashFlowType'];
            $clientData = [
                'client_id' => $request[5]['client_id'],
                'cashFlowType' => $request[4]['CashFlowType']
            ];

            $i = 0; $counter = 0;
            foreach ($dataFetch['data'] as $index => $data) {
                $flattenedData = $this->flattenData($data);  // This code flattens the nested arrays in each set of data before running the validation. Adjust the validation rules and custom error messages based on your actual field names and requirements.
                $validated = null;
                if(intval($CFA_type) == 0){
                    $validated = $this->validateFields('CashFlowAnalysisInflow', 'post', $flattenedData, null);
                }else if(intval($CFA_type) == 1){
                    $validated = $this->validateFields('CashFlowAnalysisOutflow', 'post', $flattenedData, null);
                }else if(intval($CFA_type) == 2){
                    $validated = $this->validateFields('CashFlowAnalysisOutflowWithBudget', 'post', $flattenedData, null);
                }
                if(!$validated['validate']){
                    $cfaErrors[$i] = ['Error in set No. ' . $counter+1 . ' from the submitted Cash Flow Analysis' => $validated['errors']->errors()];
                    $i++;
                }
                $counter++;
            }
            $allCollectedErrors = array();
            $allCollectedErrors = ['CashFlowAnalysis_Errors' => $cfaErrors];
            if(intval($CFA_type) == 0){
                $i = 0; $counter = 0;
                foreach ($request[1]['recommendations'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $validated = null;
                    if($CFA_type == 0 || $CFA_type == '0'){
                        $validated = $this->validateFields('CashFlowRecommendations', 'post', $flattenedData, null);
                    }
                    if(!$validated['validate']){
                        $recommendationErrors[$i] = ['Error in set No. ' . $counter+1 . ' from the submitted Recommendation for Cash Flow Analysis' => $validated['errors']->errors()];
                        $i++;
                    }
                    $counter++;
                }
                $allCollectedErrors += ['CashFlowAnalysis_Recommendations_Errors' => $recommendationErrors];
            }
            if($cfaErrors || $recommendationErrors){
                return response()->json(['ERRORs' => $allCollectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $data = null; $data2 = null; $data3 = null; $data4 = null;
        $tempMessage = ['message' => null ];
        $successOperation = 0;

        $data = $this->saveUpdateNewData($dataFetch, 'CashFlowAnalysisInOutflow');
        $tempMessage['message'] = [ intval($CFA_type) == 0 ? 'CashFlowAnalysisInflow_Message' : 'CashFlowAnalysisOutflow_Message' => $data['message'] ];
        if($data['http'] == 200){
            $successOperation = 1;
        }

        $data2 = $this->saveUpdateNewData(intval($CFA_type) == 0 ? $targetCashInflow : $targetCashOutflow, 'CashFlowAnalysis');
        $tempMessage['message'] += [ 'CashFlowAnalysis_Message' => $data2['message'] ];
        if($data2['http'] == 200){
            $successOperation = 1;
        }
        
        if(intval($CFA_type) == 0 || intval($CFA_type) == 2){
            $data3 = $this->saveUpdateNewData($recommendations, 'CashFlowRecommendations');
            if($data3['http'] == 200){
                $successOperation = 1;
            }
            $tempMessage['message'] += [ 'CashFlowRecommendations_Message' => $data3['message'] ];
        }
        $data = [
            'data' => null,
            'message' => null,
            'http' => null,
            'meta' => ''
        ];
        if($successOperation == 1){  
            $clientData += ['isInflowOutflow' => intval($CFA_type)];
            if(intval($CFA_type) == 0 || intval($CFA_type) == 2){
                if(intval($CFA_type) == 2){
                    $clientData['cashFlowType'] = 1;
                }
                $data['data'] = [
                    'Cash_Flow_List' => $this->getAllData('CashFlowList', $clientData, null),
                    'Cash_Flow_Analysis' => $this->getAllData('CashFlowAnalysis', $clientData, null),
                    'Recommendations' => $this->getAllData('Recommendations', $clientData, null),
                ];
            }else{
                $data['data'] = [
                    'Cash_Flow_List' => $this->getAllData('CashFlowList', $clientData, null),
                    'Cash_Flow_Analysis' => $this->getAllData('CashFlowAnalysis', $clientData, null),
                ];     
            }
            $data['message'] = $tempMessage['message'];
            $data['http'] = 200;
        }else{
            $data = [
                'http' => 500,
                'message' => $tempMessage['message'],
                'meta' => ''
            ];
        }
        return $this->customJsonFormatter($successOperation ? $data['data'] : [], $data, 'Cash_Flow_Data');
    }

    // Cash and Deposits
    public function saveNewCashAndDeposit(Request $request){
        try {
            $verified = $this->verifyUser($request, 3); // Verifying Web User before proceeding to any system processes...
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            $this->dataFetch = [
                'data' => $request[0]['data'],
                'heirs' => $request[1]['heirs'],
                'client_id' => $request[2]['client_id'],
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
            ]; // fetching JSON in 'data'
            $CDErrors = array(); $heirsErrors = array();
            $CDErrors = $this->evaluateAndValidate($this->dataFetch, 'cash_and_deposits', 'cash_and_deposits', 'post', null);
            $this->collectedErrors = ['CashAndDeposit_Errors' => $CDErrors];
            $getHeirData = ['data' => $this->dataFetch['heirs']];
            $heirsErrors = $this->evaluateAndValidate($getHeirData, 'heirs', 'heirsInCashAndDeposits', 'post', null);
            $this->collectedErrors += ['Heirs_Errors' => $heirsErrors];
            if($CDErrors || $heirsErrors){
                return response()->json(['errors' => $this->collectedErrors], 422);         
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'CashAndDeposits');
        if($this->data['hasSuccess'] > 0){
            $this->tempMessage = [ 'message' => $this->data['message'] ];
        }
        if($this->data['http'] == 200){
            $this->data = $this->getAllData('CashAndDeposits', $this->dataFetch, null);
            if($this->tempMessage){
                $this->data['message'] = $this->tempMessage['message'];
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'CashAndDeposits');
    }

    // Family Composition
    public function saveNewFamilyComposition(Request $request){
        try {
            $verified = $this->verifyUser($request, 2); // Verifying Web User before proceeding to any system processes...
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            $this->dataFetch = [
                'data' => $request[0]['data'],
                'client_id' => $request[1]['client_id'],
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
            ]; // fetching JSON in 'data'
            $this->collectedErrors = $this->evaluateAndValidate($this->dataFetch, 'updateClient', 'famComp', 'post', null);
            if($this->collectedErrors){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) { 
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'family_composition');
        if($this->data['hasSuccess'] > 0){  
            $this->tempMessage = [ 'message' => $this->data['message'] ];
        }
        if($this->data['http'] == 200){
            $this->data = $this->getAllData('family_composition', $this->dataFetch, null);
            if($this->tempMessage){
                $this->data['message'] = $this->tempMessage['message'];
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Family_Composition');
    }

    // Receivables
    public function saveNewReceivables(Request $request){
        try {
            $verified = $this->verifyUser($request, 3); // Verifying Web User before proceeding to any system processes...
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            $this->dataFetch = [
                'data' => $request[0]['data'],
                'heirs' => $request[1]['heirs'],
                'client_id' => $request[2]['client_id'],
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
            ]; // fetching JSON in 'data'
            $RecErrors = array(); $heirsErrors = array();
            $RecErrors = $this->evaluateAndValidate($this->dataFetch, 'receivables', 'receivables', 'post', null);
            $this->collectedErrors = ['Receivables_Errors' => $RecErrors];
            $heirsData = ['data' => $this->dataFetch['heirs']];
            $heirsErrors = $this->evaluateAndValidate($heirsData, 'heirs', 'heirsInReceivables', 'post', null);
            $this->collectedErrors += ['Heirs_Errors' => $heirsErrors];
            if($RecErrors || $heirsErrors){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'Receivables');
        if($this->data['hasSuccess'] > 0){
            $this->tempMessage = [ 'message' => $this->data['message'] ];
        }
        if($this->data['http'] == 200){
            $this->data = $this->getAllData('Receivables', $this->dataFetch, null);
            if($this->tempMessage){
                $this->data['message'] = $this->tempMessage['message'];
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Receivables');
    }

    // Mutual Funds / UITF
    public function saveNewMutualFunds(Request $request){
        try {
            $verified = $this->verifyUser($request, 3); // Verifying Web User before proceeding to any system processes...
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            $this->dataFetch = [
                'data' => $request[0]['data'],
                'heirs' => $request[1]['heirs'],
                'client_id' => $request[2]['client_id'],
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
            ]; // fetching JSON in 'data'
            $RecErrors = array(); $heirsErrors = array();
            $RecErrors = $this->evaluateAndValidate($this->dataFetch, 'Mutual_Funds', 'Mutual_Funds', 'post', null);
            $this->collectedErrors = ['MutualFund_Errors' => $RecErrors];
            $heirsData = ['data' => $this->dataFetch['heirs']];
            $heirsErrors = $this->evaluateAndValidate($heirsData, 'heirs', 'heirsInMutualFund', 'post', null);
            $this->collectedErrors += ['Heirs_Errors' => $heirsErrors];
            if($RecErrors || $heirsErrors){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'Mutual_Funds');
        if($this->data['hasSuccess'] > 0){
            $this->tempMessage = [ 'message' => $this->data['message'] ];
        }
        if($this->data['http'] == 200){
            $this->data = $this->getAllData('Mutual_Funds', $this->dataFetch, null);
            if($this->tempMessage){
                $this->data['message'] = $this->tempMessage['message'];
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Mutual_Funds');
    }

    // Bonds
    public function saveNewBonds(Request $request){
        try {
            $verified = $this->verifyUser($request, 3); // Verifying Web User before proceeding to any system processes...
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            $this->dataFetch = [
                'data' => $request[0]['data'],
                'heirs' => $request[1]['heirs'],
                'client_id' => $request[2]['client_id'],
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
            ]; // fetching JSON in 'data'
            $RecErrors = array(); $heirsErrors = array();
            $RecErrors = $this->evaluateAndValidate($this->dataFetch, 'Bonds', 'Bonds', 'post', null);
            $this->collectedErrors = ['Bonds_Errors' => $RecErrors];
            $heirsData = ['data' => $this->dataFetch['heirs']];
            $heirsErrors = $this->evaluateAndValidate($heirsData, 'heirs', 'heirsInBonds', 'post', null);
            $this->collectedErrors += ['Heirs_Errors' => $heirsErrors];
            if($RecErrors || $heirsErrors){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'Bonds');
        if($this->data['hasSuccess'] > 0){
            $this->tempMessage = [ 'message' => $this->data['message'] ];
        }
        if($this->data['http'] == 200){
            $this->data = $this->getAllData('Bonds', $this->dataFetch, null);
            if($this->tempMessage){
                $this->data['message'] = $this->tempMessage['message'];
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Bonds');
    }

    // Stocks in Companies (Listed or Non-Listed)
    public function stockInCompanies(Request $request, $isListed){
        try {
            $verified = $this->verifyUser($request, 3); // Verifying Web User before proceeding to any system processes...
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            $this->dataFetch = [
                'data' => $request[0]['data'],
                'heirs' => $request[1]['heirs'],
                'client_id' => $request[2]['client_id'],
                'isListed' => $isListed,  // 1 - Listed, 2 - Non Listed
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
            ]; // fetching JSON in 'data'
            $RecErrors = array(); $heirsErrors = array();
            $RecErrors = $this->evaluateAndValidate($this->dataFetch, 'StockInCompanies', 'StockInCompanies', 'post', null);
            $this->collectedErrors = ['Bonds_Errors' => $RecErrors];
            $heirsData = ['data' => $this->dataFetch['heirs']];
            $heirsErrors = $this->evaluateAndValidate($heirsData, 'heirs', 'heirsInStocksInCompanies', 'post', null);
            $this->collectedErrors += ['Heirs_Errors' => $heirsErrors];
            if($RecErrors || $heirsErrors){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'StockInCompanies');
        if($this->data['hasSuccess'] > 0){
            $this->tempMessage = [ 'message' => $this->data['message'] ];
        }
        if($this->data['http'] == 200){
            $this->data = $this->getAllData('StockInCompanies', $this->dataFetch, null);
            if($this->tempMessage){
                $this->data['message'] = $this->tempMessage['message'];
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Stocks_In_Companies');
    }
    public function saveNewStockInCompaniesListed(Request $request){
        return $this->stockInCompanies($request, 1);  // 1 - Listed, 2 - Non Listed
    }
    public function saveNewStockInCompaniesNonListed(Request $request){
        return $this->stockInCompanies($request, 0);  // 1 - Listed, 2 - Non Listed
    }

    public function saveNewLifeAndHealthInsurance(Request $request, $fromAdviser){
        try {
            $verified = $this->verifyUser($request, 3); // Verifying Web User before proceeding to any system processes...
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            $this->dataFetch = [
                'data' => $request[0]['data'],
                'beneficiaries' => $request[1]['beneficiaries'],
                'client_id' => $request[2]['client_id'],
                'fromAdviser' => $fromAdviser,  // 1 - From Aetos Adviser, 0 - Not From Aetos Adviser
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
            ]; // fetching JSON in 'data'
            $RecErrors = array(); $heirsErrors = array();
            $RecErrors = $this->evaluateAndValidate($this->dataFetch, 'lifeAndHealthInsurance', 'lifeAndHealthInsurance', 'post', null);
            $this->collectedErrors = ['Bonds_Errors' => $RecErrors];
            $beneData = ['data' => $this->dataFetch['beneficiaries']];
            $heirsErrors = $this->evaluateAndValidate($beneData, 'beneficiaries', 'beneInLifeHealthInsurance', 'post', null);
            $this->collectedErrors += ['Beneficiaries_Errors' => $heirsErrors];
            if($RecErrors || $heirsErrors){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'LifeAndHealthInsurance');
        if($this->data['hasSuccess'] > 0){
            $this->tempMessage = [ 'message' => $this->data['message'] ];
        }
        if($this->data['http'] == 200){
            $this->data = $this->getAllData('LifeAndHealthInsurance', $this->dataFetch, null);
            if($this->tempMessage){
                $this->data['message'] = $this->tempMessage['message'];
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Life_And_Health_Insurance');
    }
    public function saveNewLifeHealthInsuranceAdviser(Request $request){
        return $this->saveNewLifeAndHealthInsurance($request, 1);  // 1 - From Aetos Adviser, 2 - Not From Aetos Adviser
    }
    public function saveNewLifeHealthInsuranceNotAdviser(Request $request){
        return $this->saveNewLifeAndHealthInsurance($request, 2);  // 1 - From Aetos Adviser, 2 - Not From Aetos Adviser
    }

    // Family Home and Real Estate
    public function saveNewFEH(Request $request, $isHome){
        try {
            $verified = $this->verifyUser($request, 3); // Verifying Web User before proceeding to any system processes...
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            $this->dataFetch = [
                'data' => $request[0]['data'],
                'heirs' => $request[1]['heirs'],
                'client_id' => $request[2]['client_id'],
                'isHome' => $isHome,  // 1 - Family Home, 2 - Real Estate
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
            ]; // fetching JSON in 'data'
            $RecErrors = array(); $heirsErrors = array();
            $RecErrors = $this->evaluateAndValidate($this->dataFetch, 'FamilyHomeRealEstate', 'FamilyHomeRealEstate', 'post', $this->dataFetch);
            $this->collectedErrors = ['FamilyHomeRealEstate_Errors' => $RecErrors];
            $heirsData = ['data' => $this->dataFetch['heirs']];
            $heirsErrors = $this->evaluateAndValidate($heirsData, 'heirs', 'heirsFamilyHomeRealEstate', 'post', null);
            $this->collectedErrors += ['Heirs_Errors' => $heirsErrors];
            if($RecErrors || $heirsErrors){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'FamilyHomeRealEstate');
        if($this->data['hasSuccess'] > 0){
            $this->tempMessage = [ 'message' => $this->data['message'] ];
        }
        if($this->data['http'] == 200){
            $this->data = $this->getAllData('FamilyHomeRealEstate', $this->dataFetch, null);
            if($this->tempMessage){
                $this->data['message'] = $this->tempMessage['message'];
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Family_Home_Real_Estate');
    }
    public function saveNewFamilyHome(Request $request){
        return $this->saveNewFEH($request, 1);  // 1 - Family Home, 2 - Real Estate
    }
    public function saveNewRealEstate(Request $request){
        return $this->saveNewFEH($request, 2);  // 1 - Family Home, 2 - Real Estate
    }

    // Vehicles
    public function saveNewVehicles(Request $request){
        try {
            $verified = $this->verifyUser($request, 3); // Verifying Web User before proceeding to any system processes...
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            $this->dataFetch = [
                'data' => $request[0]['data'],
                'heirs' => $request[1]['heirs'],
                'client_id' => $request[2]['client_id'],
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
            ]; // fetching JSON in 'data'
            $RecErrors = array(); $heirsErrors = array();
            $RecErrors = $this->evaluateAndValidate($this->dataFetch, 'Vehicles', 'Vehicles', 'post', null);
            $this->collectedErrors = ['Vehicles_Errors' => $RecErrors];
            $heirsData = ['data' => $this->dataFetch['heirs']];
            $heirsErrors = $this->evaluateAndValidate($heirsData, 'heirs', 'heirsInVehicles', 'post', null);
            $this->collectedErrors += ['Heirs_Errors' => $heirsErrors];
            if($RecErrors || $heirsErrors){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'Vehicles');
        if($this->data['hasSuccess'] > 0){
            $this->tempMessage = [ 'message' => $this->data['message'] ];
        }
        if($this->data['http'] == 200){
            $this->data = $this->getAllData('Vehicles', $this->dataFetch, null);
            if($this->tempMessage){
                $this->data['message'] = $this->tempMessage['message'];
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Vehicles');
    }

    // Personal Assets
    public function saveNewPersonalAssets(Request $request){
        try {
            $verified = $this->verifyUser($request, 3); // Verifying Web User before proceeding to any system processes...
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            $this->dataFetch = [
                'data' => $request[0]['data'],
                'heirs' => $request[1]['heirs'],
                'client_id' => $request[2]['client_id'],
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
            ]; // fetching JSON in 'data'
            $RecErrors = array(); $heirsErrors = array();
            $RecErrors = $this->evaluateAndValidate($this->dataFetch, 'PersonalAssets', 'PersonalAssets', 'post', null);
            $this->collectedErrors = ['PersonalAssets_Errors' => $RecErrors];
            $heirsData = ['data' => $this->dataFetch['heirs']];
            $heirsErrors = $this->evaluateAndValidate($heirsData, 'heirs', 'heirsInPersonalAssets', 'post', null);
            $this->collectedErrors += ['Heirs_Errors' => $heirsErrors];
            if($RecErrors || $heirsErrors){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'PersonalAssets');
        if($this->data['hasSuccess'] > 0){
            $this->tempMessage = [ 'message' => $this->data['message'] ];
        }
        if($this->data['http'] == 200){
            $this->data = $this->getAllData('PersonalAssets', $this->dataFetch, null);      
            if($this->tempMessage){
                $this->data['message'] = $this->tempMessage['message'];
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Personal_Assets');
    }

    // Liabilities
    public function saveNewLiabilities(Request $request){
        try {
            $verified = $this->verifyUser($request, 2); // Verifying Web User before proceeding to any system processes...
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            $this->dataFetch = [
                'data' => $request[0]['data'],
                'client_id' => $request[1]['client_id'],
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
            ]; // fetching JSON in 'data'
            $this->collectedErrors = $this->evaluateAndValidate($this->dataFetch, 'Liabilities', 'Liabilities', 'post', null);
            if($this->collectedErrors){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'Liabilities');
        if($this->data['hasSuccess'] > 0){
            $this->tempMessage = [ 'message' => $this->data['message'] ];
        }
        if($this->data['http'] == 200){
            $this->data = $this->getAllData('Liabilities', $this->dataFetch, null);
            if($this->tempMessage){
                $this->data['message'] = $this->tempMessage['message'];
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Liabilities');
    }

    // Dreams and Aspiration
    public function saveNewDreamsAspiration(Request $request){
        try {
            $verified = $this->verifyUser($request, 2); // Verifying Web User before proceeding to any system processes...
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            $this->dataFetch = [
                'data' => $request[0]['data'],
                'client_id' => $request[1]['client_id'],
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
            ]; // fetching JSON in 'data'
            $this->collectedErrors = $this->evaluateAndValidate($this->dataFetch, 'DreamsAndAspiration', 'DreamsAndAspiration', 'post', null);
            if($this->collectedErrors){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'DreamsAndAspiration');
        if($this->data['hasSuccess'] > 0){
            $this->tempMessage = [ 'message' => $this->data['message'] ];
        }
        if($this->data['http'] == 200){
            $this->data = $this->getAllData('DreamsAndAspiration', $this->dataFetch, null);
            if($this->tempMessage){
                $this->data['message'] = $this->tempMessage['message'];
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Dreams_And_Aspiration');
    }

    // Financial Planning Solutions
    public function saveNewFinnPlannSolx(Request $request){
        try {
            $verified = $this->verifyUser($request, 4); // Verifying Web User before proceeding to any system processes...
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            $this->dataFetch = [
                'data' => $request[0]['data'],
                'todos' => $request[1]['todos'],
                'client_id' => $request[2]['client_id'],
                'fromTable' => $request[3]['fromTable'],
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
            ]; // fetching JSON in 'data'
            $planSolDataError = array(); $todosError = array();
            $planSolDataError = $this->evaluateAndValidate($this->dataFetch, 'PlanningSolutions', 'PlanningSolutions', 'post', null);
            $this->collectedErrors = ['PlanSolDataError' => $planSolDataError];
            $todosData = ['data' => $this->dataFetch['todos']];
            $todosError = $this->evaluateAndValidate($todosData, 'PlanSol_todos', 'PlanSol_todos', 'post', null);
            $this->collectedErrors += ['TodoError' => $todosError];
            if($planSolDataError || $todosError){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $dataRes['data'] = [];
        $this->tempMessage['message'] = [];
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'PlanningSolutions');
        $successOperation = 0;
        if($this->data['http'] == 200){
            $dataRes['data'] += ['Planning_Solutions' => $this->getAllData('PlanningSolutions', $this->dataFetch, null)];
            $successOperation = 1;
        }else{
            $dataRes['data'] += ['Planning_Solutions' => []];
        }
        $this->tempMessage['message'] += [ 'Planning_Solution_Msg' => $this->data['message'] ];
        
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'PlanSol_Todos');
        if($this->data['http'] == 200){
            $dataRes['data'] += ['Todos' => $this->getAllData('PlanSol_Todos', $this->dataFetch, null)];
            $successOperation = 1;
        }else{
            $dataRes['data'] += ['Todos' => []];
        }
        $this->tempMessage['message'] += [ 'Planning_Solution_Todos_Msg' => $this->data['message'] ];
        if($successOperation == 1){
            if($this->tempMessage){
                $this->data['message'] = $this->tempMessage['message'];
                $this->data['data'] = $dataRes;
                $this->data['http'] = 200;
                $this->data['meta'] = '';
            }
        }
        return $this->customJsonFormatter($successOperation == 1 ? $dataRes : '', $this->data, 'Planning_Solutions');
    }

    // Family Protection - FNA
    public function saveNewFamProFNA(Request $request){
        try {
            // Verifying Web User before proceeding to any system processes...
            $verified = $this->verifyUser($request, 4);
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            // Verified
            $this->dataFetch = [
                'data' => $request[0]['data'],
                'DFE' => $request[1]['debts_and_final_expenses'],
                'ELIC' => $request[2]['existing_life_insurance_coverage'],
                'client_id' => $request[3]['client_id'],
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
            ]; // fetching JSON in 'data'
            $famProFNAError = array(); $debtFinExpError = array(); $ExistLifeInsureCovError = array();
            $famProFNAError = $this->evaluateAndValidate($this->dataFetch, 'saveNewFamProFNA', 'saveNewFamProFNA', 'post', null);
            $this->collectedErrors = ['famProFNA_Error' => $famProFNAError];
            $debtFinalExpenseData = ['data' => $this->dataFetch['DFE']];
            $debtFinExpError = $this->evaluateAndValidate($debtFinalExpenseData, 'DebtFinalExpenses', 'DebtFinalExpenses', 'post', null);
            $this->collectedErrors += ['DebtFinalExpenses_Error' => $debtFinExpError];
            $ExistLifeInsureCovData = ['data' => $this->dataFetch['ELIC']];
            $ExistLifeInsureCovError = $this->evaluateAndValidate($ExistLifeInsureCovData, 'ExistLifeInsureCov', 'ExistLifeInsureCov', 'post', null);
            $this->collectedErrors += ['ExistLifeInsureCov_Error' => $ExistLifeInsureCovError];
            if($famProFNAError || $debtFinExpError || $ExistLifeInsureCovError){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $dataRes['data'] = [];
        $this->tempMessage['message'] = [];
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'saveNewFamProFNA');
        $successOperation = 0;
        if($this->data['http'] == 200){
            $dataRes['data'] += ['Family_Protection_FNA' => $this->getAllData('FamProFNA', $this->dataFetch, null)];
            $successOperation = 1;
        }else{
            $dataRes['data'] += ['Family_Protection_FNA' => []];
        }
        $this->tempMessage['message'] += [ 'Family_Protection_FNA_Msg' => $this->data['message'] ];

        $this->data = $this->saveUpdateNewData($this->dataFetch, 'DebtsAndFinalExpenses');
        if($this->data['http'] == 200){
            $dataRes['data'] += ['Debts_And_Final_Expenses_in_List' => $this->getAllData('DebtsAndFinalList', $this->dataFetch, null)];
            $successOperation = 1;
        }else{
            $dataRes['data'] += ['Debts_And_Final_Expenses_in_List' => []];
        }
        $this->tempMessage['message'] += [ 'Debts_And_Final_Expenses_Msg' => $this->data['message'] ];

        $this->data = $this->saveUpdateNewData($this->dataFetch, 'ExistLifeInsureCov');
        if($this->data['http'] == 200){
            $dataRes['data'] += ['Existing_Life_Insurance_Coverage_in_List' => $this->getAllData('LifeInsuranceCovList', $this->dataFetch, null)];
            $successOperation = 1;
        }else{
            $dataRes['data'] += ['Existing_Life_Insurance_Coverage_in_List' => []];
        }
        $this->tempMessage['message'] += [ 'Existing_Life_Insurance_Coverage_Msg' => $this->data['message'] ];

        if($this->tempMessage){
            $this->data['message'] = $this->tempMessage['message'];
            $this->data['data'] = $dataRes;
            $this->data['http'] = 200;
            $this->data['meta'] = '';
        }
        return $this->customJsonFormatter($successOperation == 1 ? $this->data['data'] : '', $this->data, 'Family_Protection_-_FNA');
    }

    // Retirement Planning - FNA
    public function saveNewRetirementPlanningFNA(Request $request){
        try {
            // Verifying Web User before proceeding to any system processes...
            $verified = $this->verifyUser($request, 3);
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            // Verified
            $this->dataFetch = [
                'data' => $request[0]['data'],
                'RE' => $request[1]['retirement_expenses'],
                'client_id' => $request[2]['client_id'],
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
            ]; // fetching JSON in 'data'
            $retirementPlanFNAError = array(); $retirementExpErrors = array();
            $retirementPlanFNAError = $this->evaluateAndValidate($this->dataFetch, 'saveNewRetirementPlanFNA', 'saveNewRetirementPlanFNA', 'post', null);
            $this->collectedErrors = ['Retirement_Planning_FNA_Error' => $retirementPlanFNAError];
            $retirementExpData = ['data' => $this->dataFetch['RE']];
            $retirementExpErrors = $this->evaluateAndValidate($retirementExpData, 'retirementExpenses', 'retirementExpenses', 'post', null);
            $this->collectedErrors += ['Retirement_Expenses_Error' => $retirementExpErrors];
            if($retirementPlanFNAError || $retirementExpErrors){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $dataRes['data'] = [];
        $this->tempMessage['message'] = [];
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'RetirementPlanFNA');
        $successOperation = 0;
        if($this->data['http'] == 200){
            $dataRes['data'] += ['Retirement_Planning_FNA' => $this->getAllData('RetirementPlanFNA', $this->dataFetch, null)];
            $successOperation = 1;
        }else{
            $dataRes['data'] += ['Retirement_Planning_FNA' => []];
        }
        $this->tempMessage['message'] += [ 'Retirement_Planning_FNA_Msg' => $this->data['message'] ];

        $this->data = $this->saveUpdateNewData($this->dataFetch, 'RetirementExpenses');
        if($this->data['http'] == 200){
            $dataRes['data'] += ['Retirement_Expenses_in_List' => $this->getAllData('RetirementExpensesList', $this->dataFetch, null)];
            $successOperation = 1;
        }else{
            $dataRes['data'] += ['Retirement_Expenses_in_List' => []];
        }
        $this->tempMessage['message'] += [ 'Retirement_Expenses_Msg' => $this->data['message'] ];

        if($this->tempMessage){
            $this->data['message'] = $this->tempMessage['message'];
            $this->data['data'] = $dataRes;
            $this->data['http'] = 200;
            $this->data['meta'] = '';
        }
        return $this->customJsonFormatter($successOperation == 1 ? $this->data['data'] : '', $this->data, 'Family_Protection_-_FNA');
    }

    // Education Planning - FNA
    public function saveNewEducationPlanningFNA(Request $request){
        try {
            // Verifying Web User before proceeding to any system processes...
            $verified = $this->verifyUser($request, 4);
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            // Verified
            $this->dataFetch = [
                'data' => $request[0]['data'],
                'children' => $request[1]['Children'],
                'EPE' => $request[2]['Education_Plan_Expenses'],
                'client_id' => $request[3]['client_id'],
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
            ]; // fetching JSON in 'data'
            $educPlanFNAError = array(); $childrenError = array(); $educExpensesError = array();
            $educPlanFNAError = $this->evaluateAndValidate($this->dataFetch, 'educPlannFNA', 'educPlannFNA', 'post', null);
            $this->collectedErrors = ['Education_Planning_FNA_Error' => $educPlanFNAError];
            $childrenData = ['data' => $this->dataFetch['children']];
            $childrenError = $this->evaluateAndValidate($childrenData, 'childrenSelected', 'childrenSelected', 'post', null);
            $this->collectedErrors += ['Children_Error' => $childrenError];
            $educExpensesData = ['data' => $this->dataFetch['EPE']];
            $educExpensesError = $this->evaluateAndValidate($educExpensesData, 'EducationPlannExp', 'EducationPlannExp', 'post', null);
            $this->collectedErrors += ['Education_Plann_Exp_Error' => $educExpensesError];
            if($educPlanFNAError || $childrenError || $educExpensesError){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $dataRes['data'] = [];
        $this->tempMessage['message'] = [];
        $successOperation = 0;
        // Educ FNA
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'EducationPlanFNA');
        if($this->data['http'] == 200){
            $dataRes['data'] += ['Education_Planning_FNA' => $this->getAllData('EducationPlanFNA', $this->dataFetch, null)];
            $successOperation = 1;
        }else{
            $dataRes['data'] += ['Education_Planning_FNA' => []];
        }
        $this->tempMessage['message'] += [ 'Education_Planning_FNA_Msg' => $this->data['message'] ];
        // Children
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'ChildrenEPFNA');
        if($this->data['http'] == 200){
            $dataRes['data'] += ['Selected_Children_for_Educ_Planning_FNA' => $this->getAllData('ChildrenEPFNA', $this->dataFetch, $this->data['extData'])];
            $successOperation = 1;
        }else{
            $dataRes['data'] += ['Selected_Children_for_Educ_Planning_FNA' => []];
        }
        $this->tempMessage['message'] += [ 'Selected_Children_for_Educ_Planning_FNA_Msg' => $this->data['message'] ];
        // Expenses
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'EducPlanExpenses');
        if($this->data['http'] == 200){
            $dataRes['data'] += ['Education_Planning_Expenses_in_List' => $this->getAllData('EducationExpensesList', $this->dataFetch, $this->data['extData'])];
            $successOperation = 1;
        }else{
            $dataRes['data'] += ['Education_Planning_Expenses_in_List' => []];
        }
        $this->tempMessage['message'] += [ 'Education_Planning_Expenses_Msg' => $this->data['message'] ];

        if($this->tempMessage){
            $this->data['message'] = $this->tempMessage['message'];
            $this->data['data'] = $dataRes;
            $this->data['http'] = 200;
            $this->data['meta'] = '';
        }
        return $this->customJsonFormatter($successOperation == 1 ? $this->data['data'] : '', $this->data, 'Educ_Planning_FNA');
    }
    public function saveNewHealthFundPlanningFNA(Request $request){
        try {
            // Verifying Web User before proceeding to any system processes...
            $verified = $this->verifyUser($request, 3);
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            // Verified
            $this->dataFetch = [
                'data' => $request[0]['data'],
                // 'HCSF' => $request[1]['Health_Coverage_Summary_For'],
                'TL' => $request[1]['Target_Limits'],
                'client_id' => $request[2]['client_id'],
                'userFN' => $verified['userCompleteName'],
                'userID' => $verified['userID'],
            ]; // fetching JSON in 'data'
            $healthFundPlannFNAError = array(); $healthCovSummError = array(); $targetLimitsError = array();
            $healthFundPlannFNAError = $this->evaluateAndValidate($this->dataFetch, 'healthFundPlannFNA', 'healthFundPlannFNA', 'post', null);
            $this->collectedErrors = ['Health_Fund_Planning_FNA_Error' => $healthFundPlannFNAError];
            // $healthCovSummData = ['data' => $this->dataFetch['HCSF']];
            // $healthCovSummError = $this->evaluateAndValidate($healthCovSummData, 'healthCovSumm', 'healthCovSumm', 'post', null);
            // $this->collectedErrors += ['Health_Coverage_Summary_Error' => $healthCovSummError];
            $targetLimitsData = ['data' => $this->dataFetch['TL']];
            $targetLimitsError = $this->evaluateAndValidate($targetLimitsData, 'targetLimits', 'targetLimits', 'post', null);
            $this->collectedErrors += ['Target_Limits_Error' => $targetLimitsError];
            if($healthFundPlannFNAError || $targetLimitsError){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $dataRes['data'] = [];
        $this->tempMessage['message'] = [];
        $successOperation = 0;
        // Health Fund Planning - FNA
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'HealthFundPlannFNA');
        if($this->data['http'] == 200){
            $dataRes['data'] += ['Health_Fund_Planning_FNA' => $this->getAllData('HealthFundPlannFNA', $this->dataFetch, null)];
            $successOperation = 1;
        }else{
            $dataRes['data'] += ['Health_Fund_Planning_FNA' => []];
        }
        $this->tempMessage['message'] += [ 'Health_Fund_Planning_FNA_Msg' => $this->data['message'] ];

        // Health Coverage Summary for...
        // $this->data = $this->saveUpdateNewData($this->dataFetch, 'healthCovSummary');
        // if($this->data['http'] == 200){
        //     $dataRes['data'] += ['Health_Coverage_Summary' => $this->getAllData('healthCoverageSummaryWithFamComp', $this->dataFetch, null)];
        //     $successOperation = 1;
        // }else{
        //     $dataRes['data'] += ['Health_Coverage_Summary' => []];
        // }
        // $this->tempMessage['message'] += [ 'Health_Coverage_Summary_Msg' => $this->data['message'] ];

        // Expenses
        $this->data = $this->saveUpdateNewData($this->dataFetch, 'TargetLimits');
        if($this->data['http'] == 200){
            $dataRes['data'] += ['Target_Limits' => $this->getAllData('targetLimits', $this->dataFetch, null)];
            $successOperation = 1;
        }else{
            $dataRes['data'] += ['Target_Limits' => []];
        }
        $this->tempMessage['message'] += [ 'Target_Limits_Msg' => $this->data['message'] ];

        if($this->tempMessage){
            $this->data['message'] = $this->tempMessage['message'];
            $this->data['data'] = $dataRes;
            $this->data['http'] = 200;
            $this->data['meta'] = '';
        }
        return $this->customJsonFormatter($successOperation == 1 ? $this->data['data'] : '', $this->data, 'Education_Fund_Planning_-_FNA');
    }

    // Family Composition - Adding New Child
    public function saveNewChildFamComp(Request $request){
        try {
            $verified = $this->verifyUser($request, 0);
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            // $this->validated = $this->validateFields('newChildForFamComp', 'post', $request, null);
            // if(!$this->validated['validate']){
            //     return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
            // }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $this->data = $this->saveUpdateNewData($request, 'newChildForFamComp');
        if($this->data){
            $this->data = [
                'data' => [
                    'Family_Composition' => $this->getAllData('family_composition', $request, null),
                    'Admin_Settings' => $this->getAllData('AdminSettings', $request, null),
                ],
                'message' => "Successful Server Operation",
                'http' => 200,
                'meta' => ''
            ];
            return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Educ_Planning_FNA');
        }
    }

    // Health Coverage Summary For - Health FNA
    public function saveNewHealthCovSumFor(Request $request){
        try {
            // Verifying Web User before proceeding to any system processes...
            $verified = $this->verifyUser($request, 0);
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            $this->collectedErrors = $this->validateFields('healthCovSum', null, $request, null);
            if(!$this->collectedErrors){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        $this->data = $this->saveUpdateNewData($request, 'healthCovSum');
        if($this->data){
            $this->data = [
                'message' => "Successful Server Operation",
                'http' => 200,
                'data' => null,
                'meta' => '',
            ];
            if($request['type'] == 0){
                $this->data['data'] = $this->getAllData('healthCoverageSummaryClient', $request, null);
            }else if($request['type'] == 1 || $request['type'] == 2){
                $this->data['data'] = $this->getAllData('healthCoverageSummarySpouse', $request, $request['famComp_id']);
            }else if($request['type'] == 3){
                $this->data['data'] = $this->getAllData('healthCoverageSummaryChild', $request, $request['famComp_id']);
            }else if($request['type'] == 4 || $request['type'] == 5){
                $this->data['data'] = $this->getAllData('healthCoverageSummaryParent', $request, $request['famComp_id']);
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Health_Coverage_Summary_For');
    }


//  ******      *       *   *********
//  *      *    *       *       *
//  *      *    *       *       *
//  ******      *       *       *
//  *           ********        *

    // Clients
    public function updateClient(Request $request){
        $this->validated = $this->validateFields('updateClient', 'put', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->saveUpdateNewData($request, 'updateClient');
        if($this->data['hasSuccess'] > 0){
            $this->tempMessage = [ 'message' => $this->data['message'] ];
        }
        if($this->data['http'] == 200){
            $this->data = $this->getAllData('updatedClient', $request, null);
            if($this->tempMessage){
                $this->data['message'] = $this->tempMessage['message'];
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Client');
    }
    public function clientMakeActive(Request $request){  
        return $this->isActiveClient($request, 1);
    } 
    public function clientMakeNonActive(Request $request){
        return $this->isActiveClient($request, 0);
    }
    public function isActiveClient(Request $request, $status){
        $this->validated = $this->validateFields('clientIsActive', 'put', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422);
        }
        $this->payLoad = [
            'client_id' => $request['client_id'],
            'changeStatusTo' => $status
        ];
        $this->data = $this->saveUpdateNewData($this->payLoad, 'updateClientIsActive');
        if($this->data['hasSuccess'] > 0){
            $this->tempMessage = [ 'message' => $this->data['message'] ];
        }
        if($this->data['http'] == 200){
            $this->data = $this->getAllData('updatedClient', $request, null);
            if($this->tempMessage){
                $this->data['message'] = $this->tempMessage['message'];
            }
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Client');
    }
    // Advisor Activities
    public function updateAgentLinkLastVisited(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $count = Agent::where('q_agnt_id', $request['user_id'])->count();
        if($count > 0){
            $this->data = $this->updateData('updateAgentVisitedLink', $request, null);
            if(!$this->data){
                return 0;
            }else{
                return 1;
            }
        }else{ return $this->throwErrorCustom('Unauthorized user.', 401); }
    }
    // Advisor Activities
    public function updateToDoResolved(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $count = Agent::where('q_agnt_id', $request['user_id'])->count();
        if($count > 0){
            $this->data = $this->updateData('updateToDoResolved', $request, null);
            if($this->data){
                $this->data = $this->getAllData('MyToDosForClients', $request, null);
                return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'My_ToDos_for_Clients');
            }else{
                return 0;
            }
        }else{
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
    }

    // Financial Planning Solution
    public function updatePlanSolResolved(Request $request){
        $verified = $this->verifyUser($request, 0);
        if(!$verified){
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
        $count = Agent::where('q_agnt_id', $request['user_id'])->count();
        if($count > 0){
            $this->data = $this->updateData('updatePlanSolToResolved', $request, null);
            if($this->data){
                $this->data = $this->getAllData('clientsForAnnualReview', $request, null);
                return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Clients_For_Annual_Review');
            }else{
                return 0;
            }
        }else{
            return $this->throwErrorCustom('Unauthorized user.', 401);
        }
    }

    // Allocated Fund for Education Planning
    public function updateEPFAF(Request $request, $op){
        try {
            $verified = $this->verifyUser($request, 2);
            if(!$verified){
                return $this->throwErrorCustom('Unauthorized user.', 401);
            }
            $this->dataFetch = [
                'data' => $request[0]['data'],
                'client_id' => $request[1]['client_id'],
                'userID' => $verified['userID'],
                'userCompleteName' => $verified['userCompleteName'],
            ]; // fetching JSON in 'data'
            if($op == 1){
                $this->collectedErrors = $this->evaluateAndValidate($this->dataFetch, 'educFundAllocated', 'educFundAllocated', 'post', null);
            }
            if($op == 2){
                $this->collectedErrors = $this->evaluateAndValidate($this->dataFetch, 'educFundGuaranteedPaySched', 'educFundGuaranteedPaySched', 'post', null);
            }
            if($this->collectedErrors){
                return response()->json(['errors' => $this->collectedErrors], 422);
            }
        } catch (\Throwable $th) {
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
        if($op == 1){
            $this->data = $this->saveUpdateNewData($this->dataFetch, 'educFundAllocated');
        }
        if($op == 2){
            $this->data = $this->saveUpdateNewData($this->dataFetch, 'educFundGuaranteedPaySched');
        }
        if($this->data['hasSuccess'] > 0){
            $this->tempMessage = [ 'message' => $this->data['message'] ];
        }
        if($this->data['http'] == 200){
            $request = ['client_id' => $request[1]['client_id']];
            $this->data = [
                'data' => [
                    'WOGP_Cash_And_Deposit' => $this->getAllData('CashAndDepoWithoutGuaranteedPayout', $request, null),
                    'WOGP_Mutual_Funds' => $this->getAllData('MutualFundsWithoutGuaranteedPayout', $request, null),
                    'WOGP_Bonds' => $this->getAllData('BondsWithoutGuaranteedPayout', $request, null),
                    'WOGP_Life_And_Health_Insurance' => $this->getAllData('LifeAndHealthInsuranceWithoutGuaranteedPayout', $request, null),
                    'WOGP_Home_Estate' => $this->getAllData('HomeEstateWithoutGuaranteedPayout', $request, null),
                    'WOGP_Personal_Assets' => $this->getAllData('PersonalAssetsWithoutGuaranteedPayout', $request, null),
                    
                    'WGP_Cash_And_Deposit' => $this->getAllData('CashAndDepoWithGuaranteedPayout', $request, null),
                    'WGP_Mutual_Funds' => $this->getAllData('MutualFundsWithGuaranteedPayout', $request, null),
                    'WGP_Bonds' => $this->getAllData('BondsWithGuaranteedPayout', $request, null),
                    'WGP_StocksInCompanies' => $this->getAllData('StocksInCompaniesWithGuaranteedPayout', $request, null),
                    'WGP_Life_And_Health_Insurance' => $this->getAllData('LifeAndHealthInsuranceWithGuaranteedPayout', $request, null),
                    'WGP_Home_Estate' => $this->getAllData('HomeEstateWithGuaranteedPayout', $request, null),
                    'WGP_Personal_Assets' => $this->getAllData('PersonalAssetsWithGuaranteedPayout', $request, null),
                ],
                'message' => "Successful Server Operation",
                'http' => 200,
                'meta' => ''
            ];
            return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Educ_Planning_Fund_Allocation');
        }else{
            return $this->throwErrorCustom('Invalid data format. Please consult the system admin, or read the documentation.', 500);
        }
    }

    public function updateEducPlanningForAllocatedFund(Request $request){
        return $this->updateEPFAF($request, 1);
    }
    public function updateByGuaranteedPayoutSchedule(Request $request){
        return $this->updateEPFAF($request, 2);
    }

//  ****        ******    *         ******   *********   ******
//  *     *     *         *         *            *       *
//  *      *    ****      *         ****         *       ****
//  *     *     *         *         *            *       *
//  ***         ******    ******    ******       *       ******
    // Agent Section
    public function deleteAgent(Request $request){
        $this->validated = $this->validateFields('agent', 'delete', $request, null);
        
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->deleteData('Agent', $request['id']);
        if($this->data['http'] == 200){
            $this->data = $this->getAllData('Agent', $request, null);
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Agent');
    }
    // Client Section
    public function deleteClient(Request $request){
        $this->validated = $this->validateFields('client', 'delete', $request, null);
        if(!$this->validated['validate']){
            return response()->json(['errors' => $this->validated['errors']->errors()], 422); 
        }
        $this->data = $this->deleteData('Client', $request['id']);
        if($this->data['http'] == 200){
            $this->data = $this->getAllData('Client', $request, null);
        }
        return $this->customJsonFormatter($this->data['http'] == 200 ? $this->data['data'] : '', $this->data, 'Client');
    }
    // Education Plan FNA - Delete
    public function deleteEducPlanExp(Request $request){
        $result = EducPlannExp::where('q_educPExp_id', $request['educPlanExp_id'])->delete();
        return $result;
    }
    // Heir Section
    public function deleteHeir(Request $request){
        $result = Heir::where('q_heir_id', $request[0]['heir_id'])->delete();
        return $result;
    }
}
