<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FamilyComposition;

class ExtendedController extends ImplementingController
{
    public function getMultiData($op, $request){
        $result = [
            "success" => 0,
            "message" => "",
            "error" => "Unexpected error occurred.",
            "data" => []
        ];
        try {
            $dataRes = [
                "success" => 0,
                "http" => 500,
                "message" => "",
                "data" => [],
                "meta" => ""
            ];
            $success = 0;
            if($op == 'famProtectionFNA'){
                $dataRes['data'] += ['Family_Protection_FNA' => $this->getAllData('FamProFNA', $request, null)];
                if($dataRes['data']['Family_Protection_FNA']['data']){
                    $dataRes['data'] += ['Debts_And_Final_Expenses_in_List' => $this->getAllData('DebtsAndFinalList', $request, null)];
                    $dataRes['data'] += ['Existing_Life_Insurance_Coverage_in_List' => $this->getAllData('LifeInsuranceCovList', $request, null)];
                }
                $success = 1;
            }else if($op == 'retPlanFNA'){
                $dataRes['data'] += ['Retirement_Planning_FNA' => $this->getAllData('RetirementPlanFNA', $request, null)];
                if($dataRes['data']['Retirement_Planning_FNA']['data']){
                    $dataRes['data'] += ['Retirement_Expenses_in_List' => $this->getAllData('RetirementExpensesList', $request, null)];
                }
                $success = 1;
            }else if($op == 'educPlanFNA'){
                $dataRes['data'] += ['Education_Planning_FNA' => $this->getAllData('EducationPlanFNA', $request, null)];
                if($dataRes['data']['Education_Planning_FNA']['data']){
                    $dataRes['data'] += ['Selected_Children_for_Educ_Planning_FNA' => $this->getAllData('ChildrenEPFNA', $request, null)];
                    if($dataRes['data']['Selected_Children_for_Educ_Planning_FNA']['data']){
                        $data = $dataRes['data']['Selected_Children_for_Educ_Planning_FNA']['data'];
                        $x = json_encode($data, JSON_PRETTY_PRINT); // transforming Array into Json file
                        $p = json_decode($x); // decoding json file for json related use in foreach
                        $q = [];
                        $i = 0;
                        foreach ($p as $item) {
                            $q[$i] = $item->famComp_id;
                            $i++;
                        }
                        $dataRes['data'] += ['Education_Planning_Expenses_in_List' => $this->getAllData('EducationExpensesList', $request, $q)];
                    }
                }
                $success = 1;
            }else if($op == 'healthFundPlanFNA'){
                $dataRes['data'] += ['FinPlanSol' => $this->getAllData('getFinPlanSol', $request, null)];
                $dataRes['data'] += ['Health_Fund_Planning_FNA' => $this->getAllData('HealthFundPlannFNA', $request, null)];
                $dataRes['data'] += ['Client_Info' => $this->getAllData('IndividualClient', $request, null)];
                $dataRes['data'] += ['Life_and_Health_Insu_Self_Aetos' => $this->getAllData('lifeAndHealthInsuAetos', $request, null)];
                $dataRes['data'] += ['Life_and_Health_Insu_Self_NotAetos' => $this->getAllData('lifeAndHealthInsuNotAetos', $request, null)];
                $dataRes['data'] += ['Health_Coverage_Summary' => $this->getAllData('healthCoverageSummaryClient', $request, null)];
                $dataRes['data'] += ['Target_Limits_Client' => $this->getAllData('targetLimits_client', $request, null)];
                // Spouse
                $spouseData = FamilyComposition::where('q_famComp_clientID', $request['client_id'])
                ->where(function ($query) {
                    $query->where('q_famComp_compType', 0)
                        ->orWhere('q_famComp_compType', 1);
                })
                ->select(['q_famComp_id', 'q_famComp_firstName', 'q_famComp_lastName', 'q_famComp_compType'])
                ->first();
                if($spouseData){
                    $dataRes['data'] += ['SpouseCName' => $spouseData->q_famComp_firstName . " " . $spouseData->q_famComp_lastName];
                    $dataRes['data'] += ['type' => $spouseData->q_famComp_compType == 0 ? 2 : 1];
                    $dataRes['data'] += ['Life_and_Health_Insu_Spouse_Aetos' => $this->getAllData('Life_and_Health_Insu_FamComp_Aetos', $request, $spouseData->q_famComp_id)];
                    $dataRes['data'] += ['Life_and_Health_Insu_Spouse_NotAetos' => $this->getAllData('Life_and_Health_Insu_FamComp_NotAetos', $request, $spouseData->q_famComp_id)];
                    $dataRes['data'] += ['Health_Coverage_Summary_Spouse' => $this->getAllData('healthCoverageSummarySpouse', $request, $spouseData->q_famComp_id)];
                    $dataRes['data'] += ['Target_Limits_Spouse' => $this->getAllData('targetLimits_other', $request, $spouseData->q_famComp_id)];
                }
                // Child
                $childrenData = FamilyComposition::where('q_famComp_clientID', $request['client_id'])
                ->where(function ($query) {
                    $query->where('q_famComp_compType', 2);
                })
                ->select(['q_famComp_id', 'q_famComp_firstName', 'q_famComp_lastName', 'q_famComp_compType'])
                ->get();
                if($childrenData){
                    $childDataXt = [];
                    $x = 0;
                    foreach ($childrenData as $item) {
                        $childDataXt[$x] = [
                            'id' => $item->q_famComp_id,
                            'completeName' => $item->q_famComp_firstName . " " . $item->q_famComp_lastName,
                            'dataFromAetos' => $this->getAllData('Life_and_Health_Insu_FamComp_Aetos', $request, $item->q_famComp_id),
                            'dataFromNotAetos' => $this->getAllData('Life_and_Health_Insu_FamComp_NotAetos', $request, $item->q_famComp_id),
                            'Health_Coverage_Summary' => $this->getAllData('healthCoverageSummaryChild', $request, $item->q_famComp_id),
                            'Target_Limits_Child' => $this->getAllData('targetLimits_other', $request, $item->q_famComp_id),
                            'type' => 3
                        ];
                        $x++;
                    }
                    $dataRes['data'] += ['childrenLifeAndHealthInsu' => $childDataXt];
                }
                // Parent
                $parentData = FamilyComposition::where('q_famComp_clientID', $request['client_id'])
                ->where(function ($query) {
                    $query->where('q_famComp_compType', 3)
                        ->orWhere('q_famComp_compType', 4);
                })
                ->select(['q_famComp_id', 'q_famComp_firstName', 'q_famComp_lastName', 'q_famComp_compType'])
                ->get();
                if($parentData){
                    $childDataXt = [];
                    $x = 0;
                    foreach ($parentData as $item) {
                        $childDataXt[$x] = [
                            'id' => $item->q_famComp_id,
                            'completeName' => $item->q_famComp_firstName . " " . $item->q_famComp_lastName,
                            'dataFromAetos' => $this->getAllData('Life_and_Health_Insu_FamComp_Aetos', $request, $item->q_famComp_id),
                            'dataFromNotAetos' => $this->getAllData('Life_and_Health_Insu_FamComp_NotAetos', $request, $item->q_famComp_id),
                            'Health_Coverage_Summary' => $this->getAllData('healthCoverageSummaryParent', $request, $item->q_famComp_id),
                            'Target_Limits_Parent' => $this->getAllData('targetLimits_other', $request, $item->q_famComp_id),
                            'type' => $item->q_famComp_compType == 3 ? 4 : 5
                        ];
                        $x++;
                    }
                    $dataRes['data'] += ['parentLifeAndHealthInsu' => $childDataXt];
                }
                $success = 1;
            }else if($op == 'educFundListWithoutPayout'){
                $dataRes['data'] += ['Cash_and_Deposits' => $this->specSQL_getData('cashAndDeposit', $request, 2, 0)];
                $dataRes['data'] += ['Mutual_Funds_UITF' => $this->specSQL_getData('mutualFundsMUIFT', $request, 2, 0)];
                $dataRes['data'] += ['Bonds' => $this->specSQL_getData('bonds', $request, 2, 0)];
                $dataRes['data'] += ['family_Life_Health_Insurance_from_Aetos_Advisor' => $this->specSQL_getData('familyLifeHealthInsuranceFromAetos', $request, 2, 0)];
                $dataRes['data'] += ['family_Life_Health_Insurance' => $this->specSQL_getData('familyLifeHealthInsurance', $request, 2, 0)];
                $dataRes['data'] += ['Real_Estate' => $this->specSQL_getData('realEstate', $request, 2, 0)];
                $dataRes['data'] += ['Personal_Assets' => $this->specSQL_getData('personalAssets', $request, 2, 0)];
                $success = 1;
            }else if($op == 'educFundListWithPayout'){
                $dataRes['data'] += ['Cash_and_Deposits' => $this->specSQL_getData('cashAndDeposit', $request, 2, 1)];
                $dataRes['data'] += ['Mutual_Funds_UITF' => $this->specSQL_getData('mutualFundsMUIFT', $request, 2, 1)];
                $dataRes['data'] += ['Bonds' => $this->specSQL_getData('bonds', $request, 2, 1)];
                $dataRes['data'] += ['family_Life_Health_Insurance_from_Aetos_Advisor' => $this->specSQL_getData('familyLifeHealthInsuranceFromAetos', $request, 2, 1)];
                $dataRes['data'] += ['family_Life_Health_Insurance' => $this->specSQL_getData('familyLifeHealthInsurance', $request, 2, 1)];
                $dataRes['data'] += ['Real_Estate' => $this->specSQL_getData('realEstate', $request, 2, 1)];
                $dataRes['data'] += ['Personal_Assets' => $this->specSQL_getData('personalAssets', $request, 2, 1)];
                $success = 1;
            }else if($op == 'retirementFundValueWithoutPayout'){
                $dataRes['data'] += ['Cash_and_Deposits' => $this->specSQL_getData('cashAndDeposit', $request, 1, 0)];
                $dataRes['data'] += ['Mutual_Funds_UITF' => $this->specSQL_getData('mutualFundsMUIFT', $request, 1, 0)];
                $dataRes['data'] += ['Bonds' => $this->specSQL_getData('bonds', $request, 1, 0)];
                $dataRes['data'] += ['family_Life_Health_Insurance_from_Aetos_Advisor' => $this->specSQL_getData('familyLifeHealthInsuranceFromAetos', $request, 1, 0)];
                $dataRes['data'] += ['family_Life_Health_Insurance' => $this->specSQL_getData('familyLifeHealthInsurance', $request, 1, 0)];
                $dataRes['data'] += ['Real_Estate' => $this->specSQL_getData('realEstate', $request, 1, 0)];
                $dataRes['data'] += ['Personal_Assets' => $this->specSQL_getData('personalAssets', $request, 1, 0)];
                $success = 1;
            }else if($op == 'retirementFundValueWithPayout'){
                $dataRes['data'] += ['Cash_and_Deposits' => $this->specSQL_getData('cashAndDeposit', $request, 1, 1)];
                $dataRes['data'] += ['Mutual_Funds_UITF' => $this->specSQL_getData('mutualFundsMUIFT', $request, 1, 1)];
                $dataRes['data'] += ['Bonds' => $this->specSQL_getData('bonds', $request, 1, 1)];
                $dataRes['data'] += ['family_Life_Health_Insurance_from_Aetos_Advisor' => $this->specSQL_getData('familyLifeHealthInsuranceFromAetos', $request, 1, 1)];
                $dataRes['data'] += ['family_Life_Health_Insurance' => $this->specSQL_getData('familyLifeHealthInsurance', $request, 1, 1)];
                $dataRes['data'] += ['Real_Estate' => $this->specSQL_getData('realEstate', $request, 1, 1)];
                $dataRes['data'] += ['Personal_Assets' => $this->specSQL_getData('personalAssets', $request, 1, 1)];
                $success = 1;
            }
            // Adding important data...
            if($success == 1){
                $dataRes["success"] = 1;
                $dataRes["http"] = 200;
                $dataRes["message"] = "Successful server operation";
                $dataRes["meta"] = "";
            }
            return $dataRes;
        } catch (\Throwable $th) {
            $result["message"] = $th;
            return $result;
        }
    }
}
