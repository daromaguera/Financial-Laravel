<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Admin;
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
use App\Models\FamilyHomeEstate;
use App\Models\Vehicles;
use App\Models\PersonalAssets;
use App\Models\Liabilities;
use App\Models\DreamsAspirations;
use App\Models\FinancialPlannSol;
use App\Models\Todos;
use App\Models\FPFNA;
use App\Models\DebtsAndFinalList;
use App\Models\LifeInsuranceCoverageList;
use App\Models\RetirePlannFNA;
use App\Models\RetirementExpList;
use App\Models\EducPlannFNA;
use App\Models\EducPlannExpList;
use App\Models\SelectedChildEducPlan;
use App\Models\HealthFundPlannFNA;
use App\Models\HealthCovSumm;
use App\Models\TargetLimits;
use App\Models\AdminSettings;
use App\Models\FNACompletion;
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
use App\Http\Resources\FamilyCompositionResource;
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
use App\Http\Resources\RetirementPlannFNAResource;
use App\Http\Resources\RetirementExpListResource;
use App\Http\Resources\EducPlannFNAResource;
use App\Http\Resources\EducPExpListResource;
use App\Http\Resources\EducPExpListResource2;
use App\Http\Resources\ChildEducPlanResource;
use App\Http\Resources\HealthFundPlannFNAResource;
use App\Http\Resources\HealthCovSummResource;
use App\Http\Resources\TargetLimitsResource;
use App\Http\Resources\AdminSettingResource;
use App\Http\Resources\FnaCompletionResource;
use App\Http\Resources\ClientsAnnualReviewResource;
use App\Http\Resources\AdvActResource;
use App\Http\Resources\FinancialPlannSolResource2;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImplementingController extends Controller
{
    // Typical request - GET ***************************************************************************************************************************
    // ************************************************************************************************************************************
    // ************************************************************************************************************************************
    // **************************************************************************************************************************
    public function specSQL_getData($table, $request, $purpose, $other){
        $page = $request['page'] ?? 1;
        $perPage = $request['per_page'] ?? self::PER_PAGE;
        try {
            $data = $table == 'cashAndDeposit' ? CashAndDepositResource::collection(CashAndDeposits::where('q_cad_clientID', $request['client_id'])->where('q_cad_withGuaranteedPayout', $other)->where('q_cad_purpose', $purpose)->paginate($perPage,['*'],'1',$page)) : null;
            $data = $table == 'mutualFundsMUIFT' ? MutualFundsUITFResource::collection(MutualFundsUITF::where('q_uitf_clientID', $request['client_id'])->where('q_uitf_withGuaranteedPayout', $other)->where('q_uitf_purpose', $purpose)->paginate($perPage,['*'],'1',$page)) : $data;
            $data = $table == 'bonds' ? BondsResource::collection(Bonds::where('q_bond_clientID', $request['client_id'])->where('q_bond_withGuaranteedPayout', $other)->where('q_bond_purpose', $purpose)->paginate($perPage,['*'],'1',$page)) : $data;
            $data = $table == 'familyLifeHealthInsuranceFromAetos' ? LifeAndHealthInsuranceResource::collection(LifeAndHealthInsurance::where('q_lifeHealth_clientID', $request['client_id'])->where('q_lifeHealth_withGuaranteedPayout', $other)->where('q_lifeHealth_purpose', $purpose)->where('q_lifeHealth_fromAetosAdviser', 1)->paginate($perPage,['*'],'1',$page)) : $data;
            $data = $table == 'familyLifeHealthInsurance' ? LifeAndHealthInsuranceResource::collection(LifeAndHealthInsurance::where('q_lifeHealth_clientID', $request['client_id'])->where('q_lifeHealth_withGuaranteedPayout', $other)->where('q_lifeHealth_purpose', $purpose)->where('q_lifeHealth_fromAetosAdviser', 2)->paginate($perPage,['*'],'1',$page)) : $data;
            $data = $table == 'realEstate' ? HomeEstateResource::collection(FamilyHomeEstate::where('q_homeEstate_clientID', $request['client_id'])->where('q_homeEstate_withGuaranteedPayout', $other)->where('q_homeEstate_purpose', $purpose)->paginate($perPage,['*'],'1',$page)) : $data;
            $data = $table == 'personalAssets' ? PersonalAssetsResource::collection(PersonalAssets::where('q_perAs_clientID', $request['client_id'])->where('q_perAs_withGuaranteedPayout', $other)->where('q_perAs_purpose', $purpose)->paginate($perPage,['*'],'1',$page)) : $data;
        } catch (\Throwable $th) {
            return $this->thrownErrorInterrupted($th, 500);
        }
        if($data != null && $data != ''){
            $normalRowsCount = 0;
            $normalRowsCount = $table == 'Agent' ? Agent::orderBy('q_agnt_id', 'DESC') : 0;
            $normalRowsCount = $table == 'Client' ? Client::where('q_clnt_agnt_id', $request['agent_id'])->count() : $normalRowsCount;
            return [
                'message' => $this->successOperation,
                'http' => 200,
                'data' => $data->items(),
                'meta' => [
                    'total' => $normalRowsCount ? $normalRowsCount : $data->count(),
                    'page' => $page,
                    'per_page' => $perPage
                ],
            ];
        }else {
            return $this->unexpectedError($this->unexpectedError, 500);
        }
    }
    public function getAllData($table, $request, $other){
        $page = $request['page'] ?? 1;
        $perPage = $request['per_page'] ?? self::PER_PAGE;
        try {
            $data = $table == 'Agent' ? AgentResource::collection(Agent::orderBy('q_agnt_id', 'DESC')->paginate($perPage,['*'],'1',$page)) : null;
            $data = !$data && $table == 'Client' ? ClientResource::collection(Client::where('q_clnt_agnt_id', $request['agent_id'])->orderBy($request['sortedAlpha'] ? 'q_clnt_l_name' : 'id', $request['sortedAlpha'] == 1 ? 'ASC' : ($request['sortedAlpha'] == 2 ? 'DESC' : 'DESC'))->paginate($perPage,['*'],'1',$page)) : $data;
            if(!$data && $table == 'AllClient'){
                $data = ClientResource::collection(
                    Client::leftJoin('agents', function ($join) use ($request) {
                            $join->on('clients.q_clnt_agnt_id', 'agents.q_agnt_id');
                        })
                        ->where('q_clnt_agnt_id', '!=', $request['agent_id'])
                        ->orderBy($request['sortedAlpha'] ? 'q_clnt_l_name' : 'id', $request['sortedAlpha'] == 1 ? 'ASC' : ($request['sortedAlpha'] == 2 ? 'DESC' : 'DESC'))
                        ->paginate($perPage,['*'],'1',$page)
                );
            }
            if(!$data && $table == 'IndividualClient'){
                $cntAgnt = Agent::where('q_agnt_id', $request['agent_id'])->where('q_agnt_uType', 'S')->count();
                if($cntAgnt > 0){
                    $data = ClientResource::collection(Client::where('q_clnt_id', $request['client_id'])->paginate($perPage,['*'],'1',$page));
                }else{
                    $data = ClientResource::collection(Client::where('q_clnt_id', $request['client_id'])->where('q_clnt_agnt_id', $request['agent_id'])->paginate($perPage,['*'],'1',$page));
                }
            }
            $data = !$data && $table == 'selected_financial_priorities' ? SelectedFinancialPriority::collection(SelectedFinancialPriorities::where('q_sfp_clnt_id', $request['client_id'])->orderBy('q_sfp_rank', 'ASC')->paginate($perPage,['*'],'1',$page)) : $data;
            $data = !$data && $table == 'CashFlowList' ?
                CashFlowListResource::collection(
                    $query = CashFlowList::
                    leftJoin('cash_flow_data', function ($join) use ($request) {
                        $join->on('cash_flow_lists.q_cfl_id', '=', 'cash_flow_data.q_cfd_cfl_id')
                            ->where('cash_flow_data.q_cfd_clnt_id', '=', $request['client_id']);
                    })
                    ->where(function($query) use ($request) {
                        $query->where('cash_flow_lists.q_cfl_isOther', 0)
                              ->where('cash_flow_lists.q_cfl_type', $request['cashFlowType']);
                    })
                    ->orWhere(function($query) use ($request){
                        $query->where('cash_flow_lists.q_cfl_isOther', 1)
                              ->where('cash_flow_lists.q_cfl_type', $request['cashFlowType'])
                              ->where('cash_flow_data.q_cfd_clnt_id', '=', $request['client_id']);
                    })
                    ->orderBy('cash_flow_lists.q_cfl_order', 'ASC')
                    ->paginate(100,['*'],'1',1)
                ) : $data;
            $data = !$data && $table == 'CashFlowAnalysis' ? CashFlowAnalysisResource::collection(CashFlowAnalysis::where('q_cfa_clnt_id', $request['client_id'])->paginate(100,['*'],'1',1)) : $data;
            if(!$data && $table == 'Recommendations'){
                $getCfaId = CashFlowAnalysis::where('q_cfa_clnt_id', $request['client_id'])->select(['q_cfa_id'])->first();
                if($getCfaId){
                    $inf = intval($request['isInflowOutflow']) == 0 ? 1 : 2;
                    $data = RecommendResource::collection(Recommendations::where('q_recommx_cfa_id', $getCfaId['q_cfa_id'])->where('q_recommx_isInflowOutflow', $inf)->paginate(100,['*'],'1',1));
                }
            }
            if(!$data && $table == 'financial_priority'){
                $data = FinancialPrioritiesResource::collection(FinancialPriorities::orderBy('q_fp_id', 'ASC')->paginate(100,['*'],'1',1));
            }
            $data = !$data && $table == 'family_composition' ? FamilyCompositionResource::collection(FamilyComposition::where('q_famComp_clientID', $request['client_id'])->orderBy('q_famComp_compType', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'child_in_family_composition' ? FamilyCompositionResource::collection(FamilyComposition::where('q_famComp_clientID', $request['client_id'])->where('q_famComp_compType', 2)->orderBy('q_famComp_compType', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'CashAndDeposits' ? CashAndDepositResource::collection(CashAndDeposits::where('q_cad_clientID', $request['client_id'])->orderBy('q_cad_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'Receivables' ? ReceivableResource::collection(Receivables::where('q_rec_clientID', $request['client_id'])->orderBy('q_rec_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'Mutual_Funds' ? MutualFundsUITFResource::collection(MutualFundsUITF::where('q_uitf_clientID', $request['client_id'])->orderBy('q_uitf_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'Bonds' ? BondsResource::collection(Bonds::where('q_bond_clientID', $request['client_id'])->orderBy('q_bond_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'StockInCompanies' ? StockInCompaniesResource::collection(StocksInCompanies::where('q_stoComp_clientID', $request['client_id'])->where('q_stoComp_isListed', $request['isListed'])->orderBy('q_stoComp_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'LifeAndHealthInsurance' ? LifeAndHealthInsuranceResource::collection(LifeAndHealthInsurance::where('q_lifeHealth_clientID', $request['client_id'])->where('q_lifeHealth_fromAetosAdviser', $request['fromAdviser'])->orderBy('q_lifeHealth_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'updatedClient' ? ClientResource::collection(Client::where('q_clnt_id', $request['client_id'])->paginate($perPage,['*'],'1',$page)) : $data;
            $data = !$data && $table == 'FamilyHomeRealEstate' ? HomeEstateResource::collection(FamilyHomeEstate::where('q_homeEstate_clientID', $request['client_id'])->where('q_homeEstate_isHome', $request['isHome'])->orderBy('q_homeEstate_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'FamilyHome' ? HomeEstateResource::collection(FamilyHomeEstate::where('q_homeEstate_clientID', $request['client_id'])->where('q_homeEstate_isHome', 1)->orderBy('q_homeEstate_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'RealEstate' ? HomeEstateResource::collection(FamilyHomeEstate::where('q_homeEstate_clientID', $request['client_id'])->where('q_homeEstate_isHome', 2)->orderBy('q_homeEstate_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'Vehicles' ? VehiclesResource::collection(Vehicles::where('q_vehicle_clientID', $request['client_id'])->orderBy('q_vehicle_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'PersonalAssets' ? PersonalAssetsResource::collection(PersonalAssets::where('q_perAs_clientID', $request['client_id'])->orderBy('q_perAs_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'Liabilities' ? LiabilitiesResource::collection(Liabilities::where('q_lia_clientID', $request['client_id'])->orderBy('q_lia_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'DreamsAndAspiration' ? DreamsAspirationsResource::collection(DreamsAspirations::where('q_dreAsp_client_id', $request['client_id'])->orderBy('q_dreAsp_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'PlanningSolutions' ? FinancialPlannSolResource::collection(FinancialPlannSol::where('q_finPlSo_clientID', $request['client_id'])->where('q_finPlSo_forTable', $request['fromTable'])->orderBy('q_finPlSo_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'PlanSol_Todos' ? TodosResource::collection(Todos::where('q_tdo_clientID', $request['client_id'])->orderBy('q_tdo_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'FamProFNA' ?
                FPFNAResource::collection(
                    FPFNA::
                    leftJoin('selected_financial_priorities', function ($join) use ($request) {
                        $join->on('f_p_f_n_a_s.q_fpfna_clientID', '=', 'selected_financial_priorities.q_sfp_clnt_id')
                            ->where('selected_financial_priorities.q_sfp_rank', '=', 1);
                    })
                    ->where('q_fpfna_clientID', $request['client_id'])
                    ->paginate(1,['*'],'1',1)) : $data;
            $data = !$data && $table == 'DebtsAndFinalList' ?
                DebtsAndFinalListResource::collection(
                    $query = DebtsAndFinalList::
                    leftJoin('debts_and_final_expenses', function ($join) use ($request) {
                        $join->on('debts_and_final_lists.q_debtFin_debFinList_id', '=', 'debts_and_final_expenses.q_debtFinExp_debFinList_id')
                            ->where('debts_and_final_expenses.q_debtFinExp_client_id', '=', $request['client_id']);
                    })
                    ->where(function($query) use ($request) {
                        $query->where('debts_and_final_lists.q_debtFin_isOtherCreated', 0);
                    })
                    ->orWhere(function($query) use ($request){
                        $query->where('debts_and_final_lists.q_debtFin_isOtherCreated', 1)
                              ->where('debts_and_final_expenses.q_debtFinExp_client_id', '=', $request['client_id']);
                    })
                    ->orderBy('debts_and_final_lists.q_debtFin_order', 'ASC')
                    ->paginate(100,['*'],'1',1)
                ) : $data;
            $data = !$data && $table == 'LifeInsuranceCovList' ?
                LifeInsuranceCoverageListResource::collection(
                    $query = LifeInsuranceCoverageList::
                    leftJoin('existing_life_insurance_coverages', function ($join) use ($request) {
                        $join->on('life_insurance_coverage_lists.q_lifeInsCovList_id', '=', 'existing_life_insurance_coverages.q_exLifeInsCov_listID')
                            ->where('existing_life_insurance_coverages.q_exLifeInsCov_clientID', '=', $request['client_id']);
                    })
                    ->where(function($query) use ($request) {
                        $query->where('life_insurance_coverage_lists.q_lifeInsCovList_isOtherCreated', 0);
                    })
                    ->orWhere(function($query) use ($request){
                        $query->where('life_insurance_coverage_lists.q_lifeInsCovList_isOtherCreated', 1)
                              ->where('existing_life_insurance_coverages.q_exLifeInsCov_clientID', '=', $request['client_id']);
                    })
                    ->orderBy('life_insurance_coverage_lists.q_lifeInsCovList_order', 'ASC')
                    ->paginate(100,['*'],'1',1)
                ) : $data;
            $data = !$data && $table == 'RetirementPlanFNA' ? RetirementPlannFNAResource::collection(RetirePlannFNA::where('q_retPFNA_clientID', $request['client_id'])->orderBy('q_retPFNA_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'RetirementExpensesList' ?
            RetirementExpListResource::collection(
                $query = RetirementExpList::
                    leftJoin('retirement_exps', function ($join) use ($request) {
                        $join->on('retirement_exp_lists.q_retExpList_id', '=', 'retirement_exps.q_retExp_retExpList_id')
                            ->where('retirement_exps.q_retExp_clientID', '=', $request['client_id']);
                    })
                    ->where(function($query) use ($request) {
                        $query->where('retirement_exp_lists.q_retExpList_isOther', 0);
                    })
                    ->orWhere(function($query) use ($request){
                        $query->where('retirement_exp_lists.q_retExpList_isOther', 1)
                              ->where('retirement_exps.q_retExp_clientID', '=', $request['client_id']);
                    })
                    ->orderBy('retirement_exp_lists.q_retExpList_id', 'ASC')
                    ->paginate(100,['*'],'1',1)
            ) : $data;
            $data = !$data && $table == 'EducationPlanFNA' ? EducPlannFNAResource::collection(EducPlannFNA::where('q_educPFNA_clientID', $request['client_id'])->orderBy('q_educPFNA_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'EducationExpensesList' ?
                EducPExpListResource::collection(
                    $query = EducPlannExpList::
                    leftJoin('educ_plann_exps', function ($join) use ($other) {
                        $join->on('educ_plann_exp_lists.q_educPExpList_id', '=', 'educ_plann_exps.q_educPExp_educPExpList_id');
                        // $slicedArray = array_slice($other, 1);
                        // foreach ($slicedArray as $oth) {
                        //     $join->orWhere('educ_plann_exps.q_educPExp_famComp_id', '=', $oth);
                        // }
                    })
                    ->where('educ_plann_exps.q_educPExp_famComp_id', '=', $other)
                    //->where('educ_plann_exp_lists.q_educPExpList_isOther', 0)
                    // ->orWhere(function($query) use ($other){
                    //     $query->where('educ_plann_exp_lists.q_educPExpList_isOther', 1)
                    //         ->where('educ_plann_exps.q_educPExp_famComp_id', '=', $other);
                    //     $slicedArray = array_slice($other, 1);
                    //     foreach ($slicedArray as $oth) {
                    //         $query->orWhere('educ_plann_exps.q_educPExp_famComp_id', '=', $oth);
                    //     }
                    // })
                    ->orderBy('educ_plann_exp_lists.q_educPExpList_id', 'ASC')
                    ->paginate(100,['*'],'1',1)
                ) : $data;
            $data = !$data && $table == 'getEducPlanningExp' ?
                EducPExpListResource::collection(
                    $query = EducPlannExpList::
                    leftJoin('educ_plann_exps', function ($join) use ($request) {
                        $join->on('educ_plann_exp_lists.q_educPExpList_id', '=', 'educ_plann_exps.q_educPExp_educPExpList_id');
                    })
                    ->where('educ_plann_exps.q_educPExp_famComp_id', '=', $request['famCompID'])
                    ->orderBy('educ_plann_exp_lists.q_educPExpList_id', 'ASC')
                    ->paginate(100,['*'],'1',1)
                ) : $data;
            $data = !$data && $table == 'ChildrenEPFNA' ?
                ChildEducPlanResource::collection(
                    $query = SelectedChildEducPlan::
                    leftJoin('family_compositions', function ($join) use ($request) {
                        $join->on('selected_child_educ_plans.q_selChildEduP_famComp_id', '=', 'family_compositions.q_famComp_id')
                            ->where('family_compositions.q_famComp_clientID', '=', $request['client_id']);
                    })
                    ->where('q_selChildEduP_famComp_id', $other)
                    ->orderBy('q_selChildEduP_id', 'ASC')
                    ->paginate(100,['*'],'1',1)
                ) : $data;
            $data = !$data && $table == 'HealthFundPlannFNA' ? HealthFundPlannFNAResource::collection(HealthFundPlannFNA::where('q_healthFP_clientID', $request['client_id'])->orderBy('q_healthFP_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            if(!$data && $table == 'healthCoverageSummaryClient'){
                $data = HealthCovSummResource::collection(
                    $query = HealthCovSumm::where('q_healthCovSum_clientID', $request['client_id'])
                    ->where('q_healthCovSum_type', 0)
                    ->orderBy('q_healthCovSum_id', 'ASC')
                    ->paginate(100,['*'],'1',1)
                );
            }
            if(!$data && $table == 'healthCoverageSummarySpouse'){
                $data = HealthCovSummResource::collection(
                    $query = HealthCovSumm::where('q_healthCovSum_clientID', $request['client_id'])
                    ->where(function($query) use ($request) {
                        $query->where('q_healthCovSum_type', 1)
                        ->orWhere('q_healthCovSum_type', 2);
                    })
                    ->where('q_healthCovSum_famCompID', $other)
                    ->orderBy('q_healthCovSum_id', 'ASC')
                    ->paginate(100,['*'],'1',1)
                );
            }
            if(!$data && $table == 'healthCoverageSummaryChild'){
                $data = HealthCovSummResource::collection(
                    $query = HealthCovSumm::where('q_healthCovSum_clientID', $request['client_id'])
                    ->where(function($query) use ($request) {
                        $query->where('q_healthCovSum_type', 3);
                    })
                    ->where('q_healthCovSum_famCompID', $other)
                    ->orderBy('q_healthCovSum_id', 'ASC')
                    ->paginate(100,['*'],'1',1)
                );
            }
            if(!$data && $table == 'healthCoverageSummaryParent'){
                $data = HealthCovSummResource::collection(
                    $query = HealthCovSumm::where('q_healthCovSum_clientID', $request['client_id'])
                    ->where(function($query) use ($request) {
                        $query->where('q_healthCovSum_type', 4)
                            ->orWhere('q_healthCovSum_type', 5);
                    })
                    ->where('q_healthCovSum_famCompID', $other)
                    ->orderBy('q_healthCovSum_id', 'ASC')
                    ->paginate(100,['*'],'1',1)
                );
            }
            $data = !$data && $table == 'targetLimits' ?
                TargetLimitsResource::collection(
                    $query = TargetLimits::
                    leftJoin('family_compositions', function ($join) use ($request) {
                        $join->on('target_limits.q_targLim_famCompID', '=', 'family_compositions.q_famComp_id')
                            ->where('family_compositions.q_famComp_clientID', '=', $request['client_id']);
                    })
                    ->orderBy('q_targLim_id', 'ASC')
                    ->paginate(100,['*'],'1',1)
                ) : $data;
            $data = !$data && $table == 'targetLimits_client' ?
                TargetLimitsResource::collection(
                    $query = TargetLimits::
                    leftJoin('family_compositions', function ($join) use ($request) {
                        $join->on('target_limits.q_targLim_famCompID', '=', 'family_compositions.q_famComp_id')
                            ->where('family_compositions.q_famComp_clientID', $request['client_id']);
                    })
                    ->where('q_targLim_clientID', $request['client_id'])
                    ->where('q_targLim_famCompID', 0)
                    ->orderBy('q_targLim_id', 'ASC')
                    ->paginate(100,['*'],'1',1)
                ) : $data;
            $data = !$data && $table == 'targetLimits_other' ?
                TargetLimitsResource::collection(
                    $query = TargetLimits::
                    leftJoin('family_compositions', function ($join) use ($request) {
                        $join->on('target_limits.q_targLim_famCompID', '=', 'family_compositions.q_famComp_id')
                            ->where('family_compositions.q_famComp_clientID', $request['client_id']);
                    })
                    ->where('q_targLim_clientID', $request['client_id'])
                    ->where('q_targLim_famCompID', $other)
                    ->orderBy('q_targLim_id', 'ASC')
                    ->paginate(100,['*'],'1',1)
                ) : $data;
            $data = !$data && $table == 'getFamMember' ? FamilyCompositionResource::collection(FamilyComposition::where('q_famComp_clientID', $request['client_id'])->where('q_famComp_compType', $other)->orderBy('q_famComp_id', 'ASC')->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'AdminSettings' ? AdminSettingResource::collection(AdminSettings::paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'FNACompletion' ? FnaCompletionResource::collection(FNACompletion::where('q_fnaComp_clientID', $request['client_id'])->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'MyToDosForClients' ?
            TodosResource::collection(
                $query = Todos::
                    leftJoin('clients', function ($join) use ($request) {
                        $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id')
                            ->where('clients.q_clnt_agnt_id', '=', $request['user_id']);
                    })
                    ->leftJoin('agents', function ($join) use ($request) {
                        $join->on('todos.q_tdo_agentID', '=', 'agents.q_agnt_id');
                    })
                    ->where('todos.q_tdo_isForClientAgent', $request['isFor'])
                    ->where('todos.q_tdo_agentID', $request['user_id'])
                    ->orderBy($request['sortedAlpha'] ? 'clients.q_clnt_l_name' : 'todos.q_tdo_id', 'ASC'
                    )
                    ->paginate($perPage,['*'],'1',$page)
            ) : $data;
            $data = !$data && $table == 'MyToDosForClientsIsFor2' ?
            TodosResource::collection(
                $query = Todos::
                    leftJoin('clients', function ($join) use ($request) {
                        $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id')
                            ->where('clients.q_clnt_agnt_id', '=', $request['user_id']);
                    })
                    ->leftJoin('agents', function ($join) use ($request) {
                        $join->on('todos.q_tdo_agentID', '=', 'agents.q_agnt_id');
                    })
                    ->where('todos.q_tdo_isForClientAgent', 2)
                    ->where('todos.q_tdo_agentID', $request['user_id'])
                    ->orderBy('todos.q_tdo_id', 'ASC')
                    ->paginate($perPage,['*'],'1',$page)
            ) : $data;
            $data = !$data && $table == 'MyToDosForClientsIsFor1' ?
            TodosResource::collection(
                $query = Todos::
                    leftJoin('clients', function ($join) use ($request) {
                        $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id')
                            ->where('clients.q_clnt_agnt_id', '=', $request['user_id']);
                    })
                    ->leftJoin('agents', function ($join) use ($request) {
                        $join->on('todos.q_tdo_agentID', '=', 'agents.q_agnt_id');
                    })
                    ->where('todos.q_tdo_isForClientAgent', 1)
                    ->where('todos.q_tdo_agentID', $request['user_id'])
                    ->orderBy('todos.q_tdo_id', 'ASC')
                    ->paginate($perPage,['*'],'1',$page)
            ) : $data;
            $data = !$data && $table == 'overdueToDos' ?
            TodosResource::collection(
                $query = Todos::
                    leftJoin('clients', function ($join) use ($request) {
                        $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id')
                            ->where('clients.q_clnt_agnt_id', '=', $request['user_id']);
                    })
                    ->leftJoin('agents', function ($join) use ($request) {
                        $join->on('todos.q_tdo_agentID', '=', 'agents.q_agnt_id');
                    })
                    ->where('q_tdo_isForClientAgent', 2)
                    ->where('q_tdo_agentID', $request['user_id'])
                    //->where('q_tdo_dateTodo', '<', Carbon::now()->format('Y-n-j'))
                    ->orderBy('q_tdo_id', 'ASC')
                    ->paginate(1000,['*'],'1',$page)
            ) : $data;
            $data = !$data && $table == 'overdueToDosForClient' ?
            TodosResource::collection(
                $query = Todos::
                    leftJoin('clients', function ($join) use ($request) {
                        $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id')
                            ->where('clients.q_clnt_agnt_id', '=', $request['user_id']);
                    })
                    ->leftJoin('agents', function ($join) use ($request) {
                        $join->on('todos.q_tdo_agentID', '=', 'agents.q_agnt_id');
                    })
                    ->where('q_tdo_isForClientAgent', 1)
                    ->where('q_tdo_agentID', $request['user_id'])
                    //->where('q_tdo_dateTodo', '<', Carbon::now()->format('Y-n-j'))
                    ->orderBy('q_tdo_id', 'ASC')
                    ->paginate(1000,['*'],'1',$page)
            ) : $data;
            $data = !$data && $table == 'AdminRQT_AgentToDosForClients' ?
            TodosResource::collection(
                $query = Todos::
                    leftJoin('clients', function ($join) use ($request) {
                        $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id');
                    })
                    ->leftJoin('agents', function ($join) use ($request) {
                        $join->on('todos.q_tdo_agentID', '=', 'agents.q_agnt_id');
                    })
                    ->where(function($query) use ($request) {
                        $query->where('q_tdo_isForClientAgent', $request['isFor']);
                    })
                    ->orderBy('q_tdo_id', 'ASC')
                    ->paginate($perPage,['*'],'1',$page)
            ) : $data;
            $data = !$data && $table == 'MyToDosForClientsOrderByClients' ?
            TodosResource::collection(
                $query = Todos::
                    leftJoin('clients', function ($join) use ($request) {
                        $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id')
                            ->where('clients.q_clnt_agnt_id', '=', $request['user_id']);
                    })
                    ->where(function($query) use ($request) {
                        $query->where('q_tdo_isForClientAgent', $request['isFor']);
                    })
                    ->where(function($query) use ($request) {
                        $query->where('q_tdo_agentID', $request['user_id']);
                    })
                    ->orderBy('clients.q_clnt_l_name', 'ASC')
                    ->paginate($perPage,['*'],'1',$page)
            ) : $data;
            $data = !$data && $table == 'MyToDosForClientsOrderByDate' ?
            TodosResource::collection(
                $query = Todos::
                    leftJoin('clients', function ($join) use ($request) {
                        $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id')
                            ->where('clients.q_clnt_agnt_id', '=', $request['user_id']);
                    })
                    ->where(function($query) use ($request) {
                        $query->where('q_tdo_isForClientAgent', $request['isFor']);
                    })
                    ->where(function($query) use ($request) {
                        $query->where('q_tdo_agentID', $request['user_id']);
                    })
                    ->orderBy('q_tdo_dateTodo', 'ASC')
                    ->paginate($perPage,['*'],'1',$page)
            ) : $data;
            $data = !$data && $table == 'AdminRQT_MyToDosForClientsOrderByClients' ?
            TodosResource::collection(
                $query = Todos::
                    leftJoin('clients', function ($join) use ($request) {
                        $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id');
                    })
                    ->leftJoin('agents', function ($join) use ($request) {
                        $join->on('todos.q_tdo_agentID', '=', 'agents.q_agnt_id');
                    })
                    ->where('q_tdo_isForClientAgent', $request['isFor'])
                    ->orderBy('clients.q_clnt_l_name', 'ASC')
                    ->paginate($perPage,['*'],'1',$page)
            ) : $data;
            $data = !$data && $table == 'AdminRQT_MyToDosForClientsOrderByDate' ?
            TodosResource::collection(
                $query = Todos::
                    leftJoin('clients', function ($join) use ($request) {
                        $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id');
                    })
                    ->leftJoin('agents', function ($join) use ($request) {
                        $join->on('todos.q_tdo_agentID', '=', 'agents.q_agnt_id');
                    })
                    ->where('q_tdo_isForClientAgent', $request['isFor'])
                    ->orderBy('q_tdo_dateTodo', 'ASC')
                    ->paginate($perPage,['*'],'1',$page)
            ) : $data;
            $data = !$data && $table == 'clientsForAnnualReview' ?
            ClientsAnnualReviewResource::collection(
                $query = FinancialPlannSol::
                    leftJoin('clients', function ($join) use ($request) {
                        $join->on('financial_plann_sols.q_finPlSo_clientID', 'clients.q_clnt_id');
                    })
                    ->where('clients.q_clnt_agnt_id', $request['user_id'])
                    ->orderBy('financial_plann_sols.q_finPlSo_id', 'ASC')
                    ->paginate($perPage,['*'],'1',$page)
            ) : $data;
            $data = !$data && $table == 'clientsForAnnualReview2' ?
            ClientsAnnualReviewResource::collection(
                $query = FinancialPlannSol::
                    leftJoin('clients', function ($join) use ($request) {
                        $join->on('financial_plann_sols.q_finPlSo_clientID', 'clients.q_clnt_id');
                    })
                    ->where('clients.q_clnt_agnt_id', $request['user_id'])
                    ->orderBy('financial_plann_sols.q_finPlSo_id', 'ASC')
                    ->paginate(1000,['*'],'1',$page)
            ) : $data;
            $data = !$data && $table == 'AdminRQT_clientsForAnnualReview' ?
            ClientsAnnualReviewResource::collection(
                $query = FinancialPlannSol::
                    leftJoin('clients', function ($join) use ($request) {
                        $join->on('financial_plann_sols.q_finPlSo_clientID', 'clients.q_clnt_id');
                    })
                    ->leftJoin('agents', function ($join) use ($request) {
                        $join->on('clients.q_clnt_agnt_id', 'agents.q_agnt_id');
                    })
                    ->orderBy('financial_plann_sols.q_finPlSo_id', 'ASC')
                    ->paginate($perPage,['*'],'1',$page)
            ) : $data;
            $data = !$data && $table == 'clientsForAnnualReviewOrderName' ?
            ClientsAnnualReviewResource::collection(
                $query = FinancialPlannSol::
                    leftJoin('clients', function ($join) use ($request) {
                        $join->on('financial_plann_sols.q_finPlSo_clientID', 'clients.q_clnt_id');
                    })
                    ->where('clients.q_clnt_agnt_id', $request['user_id'])
                    ->orderBy('clients.q_clnt_l_name', 'ASC')
                    ->paginate($perPage,['*'],'1',$page)
            ) : $data;
            $data = !$data && $table == 'clientsForAnnualReviewOrderDate' ?
            ClientsAnnualReviewResource::collection(
                $query = FinancialPlannSol::
                    leftJoin('clients', function ($join) use ($request) {
                        $join->on('financial_plann_sols.q_finPlSo_clientID', 'clients.q_clnt_id');
                    })
                    ->where('clients.q_clnt_agnt_id', $request['user_id'])
                    ->orderBy('financial_plann_sols.q_finPlSo_meetAdvisorOn', 'DESC')
                    ->paginate($perPage,['*'],'1',$page)
            ) : $data;
            $data = !$data && $table == 'advisorActivities' ?
            AdvActResource::collection(
                $query = AdvActivities::
                    leftJoin('clients', function ($join) use ($request) {
                        $join->on('adv_activities.q_advAct_clientID', 'clients.q_clnt_id');
                    })
                    ->where('adv_activities.q_advAct_agentID', $request['user_id'])
                    ->orderBy('adv_activities.q_advAct_id', 'DESC')
                    ->paginate($perPage,['*'],'1',$page)
            ) : $data;
            $data = !$data && $table == 'CashAndDepoWithoutGuaranteedPayout' ? CashAndDepositResource::collection(CashAndDeposits::where('q_cad_clientID', $request['client_id'])->where('q_cad_withGuaranteedPayout',0)->where('q_cad_purpose', 2)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'CashAndDepoWithoutGuaranteedPayout_ret' ? CashAndDepositResource::collection(CashAndDeposits::where('q_cad_clientID', $request['client_id'])->where('q_cad_withGuaranteedPayout',0)->where('q_cad_purpose', 1)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'CashAndDepoWithGuaranteedPayout' ? CashAndDepositResource::collection(CashAndDeposits::where('q_cad_clientID', $request['client_id'])->where('q_cad_withGuaranteedPayout',1)->where('q_cad_purpose', 2)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'CashAndDepoWithGuaranteedPayout_ret' ? CashAndDepositResource::collection(CashAndDeposits::where('q_cad_clientID', $request['client_id'])->where('q_cad_withGuaranteedPayout',1)->where('q_cad_purpose', 1)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'MutualFundsWithoutGuaranteedPayout' ? MutualFundsUITFResource::collection(MutualFundsUITF::where('q_uitf_clientID', $request['client_id'])->where('q_uitf_withGuaranteedPayout',0)->where('q_uitf_purpose', 2)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'MutualFundsWithoutGuaranteedPayout_ret' ? MutualFundsUITFResource::collection(MutualFundsUITF::where('q_uitf_clientID', $request['client_id'])->where('q_uitf_withGuaranteedPayout',0)->where('q_uitf_purpose', 1)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'MutualFundsWithGuaranteedPayout' ? MutualFundsUITFResource::collection(MutualFundsUITF::where('q_uitf_clientID', $request['client_id'])->where('q_uitf_withGuaranteedPayout',1)->where('q_uitf_purpose', 2)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'MutualFundsWithGuaranteedPayout_ret' ? MutualFundsUITFResource::collection(MutualFundsUITF::where('q_uitf_clientID', $request['client_id'])->where('q_uitf_withGuaranteedPayout',1)->where('q_uitf_purpose', 1)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'BondsWithoutGuaranteedPayout' ? BondsResource::collection(Bonds::where('q_bond_clientID', $request['client_id'])->where('q_bond_withGuaranteedPayout',0)->where('q_bond_purpose', 2)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'BondsWithoutGuaranteedPayout_ret' ? BondsResource::collection(Bonds::where('q_bond_clientID', $request['client_id'])->where('q_bond_withGuaranteedPayout',0)->where('q_bond_purpose', 1)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'BondsWithGuaranteedPayout' ? BondsResource::collection(Bonds::where('q_bond_clientID', $request['client_id'])->where('q_bond_withGuaranteedPayout',1)->where('q_bond_purpose', 2)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'BondsWithGuaranteedPayout_ret' ? BondsResource::collection(Bonds::where('q_bond_clientID', $request['client_id'])->where('q_bond_withGuaranteedPayout',1)->where('q_bond_purpose', 1)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'StocksInCompaniesWithGuaranteedPayout' ? StockInCompaniesResource::collection(StocksInCompanies::where('q_stoComp_clientID', $request['client_id'])->where('q_stoComp_purpose', 2)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'StocksInCompaniesWithGuaranteedPayout_ret' ? StockInCompaniesResource::collection(StocksInCompanies::where('q_stoComp_clientID', $request['client_id'])->where('q_stoComp_purpose', 1)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'LifeAndHealthInsuranceWithoutGuaranteedPayout' ? LifeAndHealthInsuranceResource::collection(LifeAndHealthInsurance::where('q_lifeHealth_clientID', $request['client_id'])->where('q_lifeHealth_withGuaranteedPayout',0)->where('q_lifeHealth_purpose', 2)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'LifeAndHealthInsuranceWithoutGuaranteedPayout_ret' ? LifeAndHealthInsuranceResource::collection(LifeAndHealthInsurance::where('q_lifeHealth_clientID', $request['client_id'])->where('q_lifeHealth_withGuaranteedPayout',0)->where('q_lifeHealth_purpose', 1)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'LifeAndHealthInsuranceWithGuaranteedPayout' ? LifeAndHealthInsuranceResource::collection(LifeAndHealthInsurance::where('q_lifeHealth_clientID', $request['client_id'])->where('q_lifeHealth_withGuaranteedPayout',1)->where('q_lifeHealth_purpose', 2)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'LifeAndHealthInsuranceWithGuaranteedPayout_ret' ? LifeAndHealthInsuranceResource::collection(LifeAndHealthInsurance::where('q_lifeHealth_clientID', $request['client_id'])->where('q_lifeHealth_withGuaranteedPayout',1)->where('q_lifeHealth_purpose', 1)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'HomeEstateWithoutGuaranteedPayout' ? HomeEstateResource::collection(FamilyHomeEstate::where('q_homeEstate_clientID', $request['client_id'])->where('q_homeEstate_withGuaranteedPayout',0)->where('q_homeEstate_purpose', 2)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'HomeEstateWithoutGuaranteedPayout_ret' ? HomeEstateResource::collection(FamilyHomeEstate::where('q_homeEstate_clientID', $request['client_id'])->where('q_homeEstate_withGuaranteedPayout',0)->where('q_homeEstate_purpose', 1)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'HomeEstateWithGuaranteedPayout' ? HomeEstateResource::collection(FamilyHomeEstate::where('q_homeEstate_clientID', $request['client_id'])->where('q_homeEstate_withGuaranteedPayout',1)->where('q_homeEstate_purpose', 2)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'HomeEstateWithGuaranteedPayout_ret' ? HomeEstateResource::collection(FamilyHomeEstate::where('q_homeEstate_clientID', $request['client_id'])->where('q_homeEstate_withGuaranteedPayout',1)->where('q_homeEstate_purpose', 1)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'PersonalAssetsWithoutGuaranteedPayout' ? PersonalAssetsResource::collection(PersonalAssets::where('q_perAs_clientID', $request['client_id'])->where('q_perAs_withGuaranteedPayout',0)->where('q_perAs_purpose', 2)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'PersonalAssetsWithoutGuaranteedPayout_ret' ? PersonalAssetsResource::collection(PersonalAssets::where('q_perAs_clientID', $request['client_id'])->where('q_perAs_withGuaranteedPayout',0)->where('q_perAs_purpose', 1)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'PersonalAssetsWithGuaranteedPayout' ? PersonalAssetsResource::collection(PersonalAssets::where('q_perAs_clientID', $request['client_id'])->where('q_perAs_withGuaranteedPayout',1)->where('q_perAs_purpose', 2)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'PersonalAssetsWithGuaranteedPayout_ret' ? PersonalAssetsResource::collection(PersonalAssets::where('q_perAs_clientID', $request['client_id'])->where('q_perAs_withGuaranteedPayout',1)->where('q_perAs_purpose', 1)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'baseDataEducPlan' ? EducPlannFNAResource::collection(EducPlannFNA::where('q_educPFNA_clientID', $request['client_id'])->paginate(100,['*'],'1',1)) : $data;
            $data = !$data && $table == 'SelectedChild' ?
            ChildEducPlanResource::collection(
                $query = SelectedChildEducPlan::
                    leftJoin('family_compositions', function ($join) use ($request) {
                        $join->on('selected_child_educ_plans.q_selChildEduP_famComp_id', 'family_compositions.q_famComp_id');
                    })
                    ->where('family_compositions.q_famComp_clientID', $request['client_id'])
                    ->paginate($perPage,['*'],'1',$page)
            ) : $data;
            if(!$data && $table == 'getEducPlanningExpRTrl'){
                $queryEducExp = SelectedChildEducPlan::
                    leftJoin('family_compositions', function ($join) use ($request) {
                        $join->on('selected_child_educ_plans.q_selChildEduP_famComp_id', 'family_compositions.q_famComp_id');
                    })
                    ->where('family_compositions.q_famComp_clientID', $request['client_id'])
                    ->get();
                $data = array();
                $i = 0;
                foreach ($queryEducExp as $item) {
                    $data[$i] = ['result' => EducPlannExpList::
                        leftJoin('educ_plann_exps', function ($join) use ($item) {
                            $join->on('educ_plann_exp_lists.q_educPExpList_id', '=', 'educ_plann_exps.q_educPExp_educPExpList_id');
                        })
                        ->where('educ_plann_exps.q_educPExp_famComp_id', '=', $item->q_famComp_id)
                        ->orderBy('educ_plann_exp_lists.q_educPExpList_id', 'ASC')->get()
                    ];
                    $i++;
                }
                return $data;
            }
            $data = !$data && $table == 'educPlanExpListIto' ? EducPExpListResource2::collection(EducPlannExpList::where('q_educPExpList_isOther', 0)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'getFinPlanSol' ? FinancialPlannSolResource2::collection(FinancialPlannSol::where('q_finPlSo_clientID', $request['client_id'])->where('q_finPlSo_forTable', $request['forTable'])->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'lifeAndHealthInsuAetos' ? LifeAndHealthInsuranceResource::collection(LifeAndHealthInsurance::where('q_lifeHealth_clientID', $request['client_id'])->where('q_lifeHealth_fromAetosAdviser', 1)->where('q_lifeHealth_insured', 0)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'lifeAndHealthInsuNotAetos' ? LifeAndHealthInsuranceResource::collection(LifeAndHealthInsurance::where('q_lifeHealth_clientID', $request['client_id'])->where('q_lifeHealth_fromAetosAdviser', 2)->where('q_lifeHealth_insured', 0)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'Life_and_Health_Insu_FamComp_Aetos' ? LifeAndHealthInsuranceResource::collection(LifeAndHealthInsurance::where('q_lifeHealth_clientID', $request['client_id'])->where('q_lifeHealth_fromAetosAdviser', 1)->where('q_lifeHealth_insured', $other)->paginate(1000,['*'],'1',1)) : $data;
            $data = !$data && $table == 'Life_and_Health_Insu_FamComp_NotAetos' ? LifeAndHealthInsuranceResource::collection(LifeAndHealthInsurance::where('q_lifeHealth_clientID', $request['client_id'])->where('q_lifeHealth_fromAetosAdviser', 2)->where('q_lifeHealth_insured', $other)->paginate(1000,['*'],'1',1)) : $data;
        } catch (\Throwable $th) {
            return $this->thrownErrorInterrupted($th, 500);
        }
        if($data != null && $data != ''){
            $normalRowsCount = 0;
            $normalRowsCount = $table == 'Client' ? Client::where('q_clnt_agnt_id', $request['agent_id'])->count() : $normalRowsCount;
            $normalRowsCount = $table == 'AllClient' ? Client::where('q_clnt_agnt_id', '!=', $request['agent_id'])->count() : $normalRowsCount;
            $normalRowsCount = $table == 'MyToDosForClients' || $table == 'MyToDosForClientsOrderByClients' ? Todos::where('q_tdo_agentID', $request['user_id'])->where('q_tdo_isForClientAgent', $request['isFor'])->count() : $normalRowsCount;
            $normalRowsCount = $table == 'overdueAgentToDos' ? Todos::where('q_tdo_agentID', $request['user_id'])->where('q_tdo_isForClientAgent', $request['isFor'])->where('q_tdo_dateTodo', '<=', Carbon::now()->format('Y-n-j'))->count() : $normalRowsCount;
            $normalRowsCount = $table == 'AdminRQT_AgentToDosForClients' || $table == 'AdminRQT_MyToDosForClientsOrderByClients' ? Todos::where('q_tdo_isForClientAgent', $request['isFor'])->count() : $normalRowsCount;
            $normalRowsCount = $table == 'clientsForAnnualReview' || $table == 'clientsForAnnualReviewOrderDate' || $table == 'clientsForAnnualReviewOrderName' ? FinancialPlannSol::leftJoin('clients', function ($join) use ($request) {$join->on('financial_plann_sols.q_finPlSo_clientID', 'clients.q_clnt_id');})->where('clients.q_clnt_agnt_id', $request['user_id'])->count() : $normalRowsCount;
            $normalRowsCount = $table == 'AdminRQT_clientsForAnnualReview' ? FinancialPlannSol::count() : $normalRowsCount;
            $normalRowsCount = $table == 'MyToDosForClientsOrderByDate' ? Todos::where('q_tdo_agentID', $request['user_id'])->where('q_tdo_isForClientAgent', $request['isFor'])->count() : $normalRowsCount;
            $normalRowsCount = $table == 'advisorActivities' ? AdvActivities::where('adv_activities.q_advAct_agentID', $request['user_id'])->count() : $normalRowsCount;
            return [
                'message' => $this->successOperation,
                'http' => 200,
                'data' => $data->items(),
                'meta' => [
                    'total' => $normalRowsCount ? $normalRowsCount : $data->count(),
                    'page' => $page,
                    'per_page' => $perPage
                ],
            ];
        }else {
            return $this->unexpectedError($this->unexpectedError, 500);
        }
    }



    //    ********      ******     *****      ***********
    //  **             *      *    *    **         *
    //   ********      *      *    *****           *
    //           **    *      *    *    *          *
    //    *******       ******     *      *        *

    public function getAllDataInOrder($table, $orderBy, $order, $request){
        $data = null;
        $page = $request['page'] ?? 1;
        $perPage = $request['per_page'] ?? self::PER_PAGE;
        try {
            $data = $table == 'Agent' ? AgentResource::collection(Agent::orderBy($orderBy, $order)->paginate($perPage,['*'],'1',$page)) : null;
            $data = !$data && $table == 'Client' ? ClientResource::collection(Client::where('q_clnt_agnt_id', $request['agent_id'])->orderBy($orderBy, $order)->paginate($perPage,['*'],'1',$page)) : $data;
            $data = !$data && $table == 'adm_Client_onSort' ? ClientResource::collection(Client::where('q_clnt_agnt_id', '!=', $request['agent_id'])->orderBy($orderBy, $order)->paginate($perPage,['*'],'1',$page)) : $data;
            $data = !$data && $table == 'cash_flow_list' ?
                CashFlowListResource::collection(
                    $query = CashFlowList::
                    leftJoin('cash_flow_data', function ($join) use ($request) {
                        $join->on('cash_flow_lists.q_cfl_id', '=', 'cash_flow_data.q_cfd_cfl_id')
                            ->where('cash_flow_data.q_cfd_clnt_id', '=', $request['client_id']);
                    })
                    ->where(function($query) use ($request) {
                        $query->where('cash_flow_lists.q_cfl_isOther', 0)
                              ->where('cash_flow_lists.q_cfl_type', $request['cashFlowType']);
                    })
                    ->orWhere(function($query) use ($request){
                        $query->where('cash_flow_lists.q_cfl_isOther', 1)
                              ->where('cash_flow_lists.q_cfl_type', $request['cashFlowType']);
                    })
                    ->orderBy($orderBy, $order)
                    ->paginate(100,['*'],'1',1)
                ) : $data;
        } catch (\Throwable $th) {
            return $this->thrownErrorInterrupted($this->interruptedError, 500);
        }
        if($data != null && $data != ''){
            $normalRowsCount = 0;
            $normalRowsCount = $table == 'Agent' ? Agent::orderBy('q_agnt_id', 'DESC') : 0;
            $normalRowsCount = !$normalRowsCount && $table == 'Client' ? Client::where('q_clnt_agnt_id', $request['agent_id'])->count() : 0;
            $normalRowsCount = !$normalRowsCount && $table == 'adm_Client_onSort' ? Client::where('q_clnt_agnt_id', '!=', $request['agent_id'])->count() : $normalRowsCount;
            return [
                'http' => 200,
                'data' => $data->items(),
                'message' => $this->successOperation,
                'meta' => [
                    'total' => $normalRowsCount ? $normalRowsCount : $data->count(),
                    'page' => $page,
                    'per_page' => $perPage
                ],
            ];
        }else {
            return $this->unexpectedError($this->unexpectedError, 500);
        }
    }


    //    ********    *******      *       *****       ******    *     *
    //  **            *           * *      *    *     *          *     *
    //   ********     *****      *****     ****       *          *******
    //           **   *         *     *    *    *     *          *     *
    //    *******     *******  *       *   *      *    ******    *     *

    public function searchData($table, $request, $option, $wantField){
        $data = null;
        $counterErr = 0;
        $specifyDateErr = '';
        $page = $request['page'] ?? 1;
        $perPage = $request['per_page'] ?? self::PER_PAGE;
        $totalRows = 0;
        if($option == 'All'){                                                                                 // on both Client & Agent table
            try {
                if($table == 'Agent'){
                    $queryRes = Agent::where(DB::raw('UPPER(q_agnt_f_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                        ->orWhere(DB::raw('UPPER(q_agnt_m_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                        ->orWhere(DB::raw('UPPER(q_agnt_l_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                        ->orWhere(DB::raw('UPPER(q_agnt_addrx)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%');
                    $data = AgentResource::collection($queryRes->paginate($perPage,['*'],'1',$page));
                    $totalRows = $queryRes->count();
                }
                if(!$data && $table == 'Client'){
                    $dataToSearch = $request['dataToSearch'];
                    $searchTerms = array_map('trim', explode(',', $dataToSearch)); // Split and trim search terms
                    $data = ClientResource::collection(Client::where('q_clnt_agnt_id', $request['agent_id'])
                        ->where(function ($query) use ($searchTerms) {
                            foreach ($searchTerms as $term) {
                                $query->orWhere(function ($subQuery) use ($term) {
                                    $term = strtoupper($term);
                                    $subQuery->where(DB::raw('UPPER(q_clnt_f_name)'), 'LIKE', '%' . $term . '%')
                                        ->orWhere(DB::raw('UPPER(q_clnt_m_name)'), 'LIKE', '%' . $term . '%')
                                        ->orWhere(DB::raw('UPPER(q_clnt_l_name)'), 'LIKE', '%' . $term . '%')
                                        ->orWhere(DB::raw('UPPER(q_clnt_gendr)'), 'LIKE', '%' . $term . '%')
                                        ->orWhere(DB::raw('UPPER(q_clnt_contNo)'), 'LIKE', '%' . $term . '%')
                                        ->orWhere(DB::raw('UPPER(q_clnt_emailAddrx)'), 'LIKE', '%' . $term . '%')
                                        ->orWhere(DB::raw('UPPER(q_clnt_healthCondiDetail)'), 'LIKE', '%' . $term . '%');
                                });
                            }
                        })
                        ->orderBy($request['sortedAlpha'] ? 'q_clnt_l_name' : 'id', $request['sortedAlpha'] == 1 ? 'ASC' : ($request['sortedAlpha'] == 2 ? 'DESC' : 'DESC'))
                        ->paginate($perPage, ['*'], '1', $page)
                    );
                    $totalRows = Client::where('q_clnt_agnt_id', $request['agent_id'])
                    ->where(function ($query) use ($searchTerms) {
                        foreach ($searchTerms as $term) {
                            $query->orWhere(function ($subQuery) use ($term) {
                                $term = strtoupper($term);
                                $subQuery->where(DB::raw('UPPER(q_clnt_f_name)'), 'LIKE', '%' . $term . '%')
                                    ->orWhere(DB::raw('UPPER(q_clnt_m_name)'), 'LIKE', '%' . $term . '%')
                                    ->orWhere(DB::raw('UPPER(q_clnt_l_name)'), 'LIKE', '%' . $term . '%')
                                    ->orWhere(DB::raw('UPPER(q_clnt_gendr)'), 'LIKE', '%' . $term . '%')
                                    ->orWhere(DB::raw('UPPER(q_clnt_contNo)'), 'LIKE', '%' . $term . '%')
                                    ->orWhere(DB::raw('UPPER(q_clnt_emailAddrx)'), 'LIKE', '%' . $term . '%')
                                    ->orWhere(DB::raw('UPPER(q_clnt_healthCondiDetail)'), 'LIKE', '%' . $term . '%');
                            });
                        }
                    })->count();
                }
                if(!$data && $table == 'AllClients'){
                    $dataToSearch = $request['dataToSearch'];
                    $searchTerms = array_map('trim', explode(',', $dataToSearch)); // Split and trim search terms
                    $data = ClientResource::collection(Client::where('q_clnt_agnt_id', '!=', $request['agent_id'])
                        ->where(function ($query) use ($searchTerms) {
                            foreach ($searchTerms as $term) {
                                $query->orWhere(function ($subQuery) use ($term) {
                                    $term = strtoupper($term);
                                    $subQuery->where(DB::raw('UPPER(q_clnt_f_name)'), 'LIKE', '%' . $term . '%')
                                        ->orWhere(DB::raw('UPPER(q_clnt_m_name)'), 'LIKE', '%' . $term . '%')
                                        ->orWhere(DB::raw('UPPER(q_clnt_l_name)'), 'LIKE', '%' . $term . '%')
                                        ->orWhere(DB::raw('UPPER(q_clnt_gendr)'), 'LIKE', '%' . $term . '%')
                                        ->orWhere(DB::raw('UPPER(q_clnt_contNo)'), 'LIKE', '%' . $term . '%')
                                        ->orWhere(DB::raw('UPPER(q_clnt_emailAddrx)'), 'LIKE', '%' . $term . '%')
                                        ->orWhere(DB::raw('UPPER(q_clnt_healthCondiDetail)'), 'LIKE', '%' . $term . '%');
                                });
                            }
                        })
                        ->orderBy($request['sortedAlpha'] ? 'q_clnt_l_name' : 'id', $request['sortedAlpha'] == 1 ? 'ASC' : ($request['sortedAlpha'] == 2 ? 'DESC' : 'DESC'))
                        ->paginate($perPage, ['*'], '1', $page)
                    );
                    $totalRows = Client::where('q_clnt_agnt_id', '!=', $request['agent_id'])
                        ->where(function ($query) use ($searchTerms) {
                            foreach ($searchTerms as $term) {
                                $query->orWhere(function ($subQuery) use ($term) {
                                    $term = strtoupper($term);
                                    $subQuery->where(DB::raw('UPPER(q_clnt_f_name)'), 'LIKE', '%' . $term . '%')
                                        ->orWhere(DB::raw('UPPER(q_clnt_m_name)'), 'LIKE', '%' . $term . '%')
                                        ->orWhere(DB::raw('UPPER(q_clnt_l_name)'), 'LIKE', '%' . $term . '%')
                                        ->orWhere(DB::raw('UPPER(q_clnt_gendr)'), 'LIKE', '%' . $term . '%')
                                        ->orWhere(DB::raw('UPPER(q_clnt_contNo)'), 'LIKE', '%' . $term . '%')
                                        ->orWhere(DB::raw('UPPER(q_clnt_emailAddrx)'), 'LIKE', '%' . $term . '%')
                                        ->orWhere(DB::raw('UPPER(q_clnt_healthCondiDetail)'), 'LIKE', '%' . $term . '%');
                                });
                            }
                        })->count();
                }
                if(!$data && $table == 'AgentToDos'){
                    $queryRes = null;
                    if($request['selectedFNA'] == "" && $request['dataToSearch'] != ""){
                        $data = TodosResource::collection(Todos::leftJoin('clients', function ($join) use ($request) {
                            $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id')
                                ->where('clients.q_clnt_agnt_id', '=', $request['user_id']);
                            })
                            ->where('q_tdo_isForClientAgent', $request['isFor'])
                            ->where('q_tdo_agentID', $request['user_id'])
                            ->where(function($query) use ($request) {
                                $query->where(DB::raw('UPPER(q_tdo_descripx)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_f_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_m_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_l_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%');
                            })->orderBy('q_tdo_id', 'ASC')->paginate($perPage,['*'],'1',$page));
                        $totalRows = Todos::leftJoin('clients', function ($join) use ($request) {
                            $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id')
                                ->where('clients.q_clnt_agnt_id', '=', $request['user_id']);
                            })
                            ->where('q_tdo_isForClientAgent', $request['isFor'])
                            ->where('q_tdo_agentID', $request['user_id'])
                            ->where(function($query) use ($request) {
                                $query->where(DB::raw('UPPER(q_tdo_descripx)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_f_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_m_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_l_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%');
                            })->count();
                    }else if($request['selectedFNA'] != "" && $request['dataToSearch'] != ""){
                        $data = TodosResource::collection(Todos::leftJoin('clients', function ($join) use ($request) {
                            $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id')
                                ->where('clients.q_clnt_agnt_id', '=', $request['user_id']);
                            })
                            ->where('q_tdo_isForClientAgent', $request['isFor'])
                            ->where('q_tdo_agentID', $request['user_id'])
                            ->where(DB::raw('UPPER(q_tdo_fromTable)'), strtoupper($request['selectedFNA']))
                            ->where(function($query) use ($request) {
                                $query->where(DB::raw('UPPER(q_tdo_descripx)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_f_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_m_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_l_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%');
                            })->orderBy('q_tdo_id', 'ASC')->paginate($perPage,['*'],'1',$page));
                        $totalRows = Todos::leftJoin('clients', function ($join) use ($request) {
                            $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id')
                                ->where('clients.q_clnt_agnt_id', '=', $request['user_id']);
                            })
                            ->where('q_tdo_isForClientAgent', $request['isFor'])
                            ->where('q_tdo_agentID', $request['user_id'])
                            ->where(DB::raw('UPPER(q_tdo_fromTable)'), strtoupper($request['selectedFNA']))
                            ->where(function($query) use ($request) {
                                $query->where(DB::raw('UPPER(q_tdo_descripx)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_f_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_m_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_l_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%');
                            })->count();
                    }else if($request['selectedFNA'] != "" && $request['dataToSearch'] == ""){
                        $data = TodosResource::collection(Todos::leftJoin('clients', function ($join) use ($request) {
                            $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id')
                                ->where('clients.q_clnt_agnt_id', '=', $request['user_id']);
                            })
                            ->where('q_tdo_isForClientAgent', $request['isFor'])
                            ->where('q_tdo_agentID', $request['user_id'])
                            ->where(function($query) use ($request) {
                                $query->where(DB::raw('UPPER(q_tdo_fromTable)'), 'LIKE', '%'.strtoupper($request['selectedFNA']).'%');
                            })->orderBy('q_tdo_id', 'ASC')->paginate($perPage,['*'],'1',$page));
                        $totalRows = Todos::leftJoin('clients', function ($join) use ($request) {
                            $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id')
                                ->where('clients.q_clnt_agnt_id', '=', $request['user_id']);
                            })
                            ->where('q_tdo_isForClientAgent', $request['isFor'])
                            ->where('q_tdo_agentID', $request['user_id'])
                            ->where(function($query) use ($request) {
                                $query->where(DB::raw('UPPER(q_tdo_fromTable)'), 'LIKE', '%'.strtoupper($request['selectedFNA']).'%');
                            })->count();
                    }
                }
                if(!$data && $table == 'AdminRQT_AgentToDos'){
                    $queryRes = null;
                    if($request['selectedFNA'] == "" && $request['dataToSearch'] != ""){
                        $queryRes = Todos::leftJoin('clients', function ($join) use ($request) {
                            $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id');
                            })
                            ->leftJoin('agents', function ($join) use ($request) {
                                $join->on('todos.q_tdo_agentID', '=', 'agents.q_agnt_id');
                            })
                            ->where('q_tdo_isForClientAgent', $request['isFor'])
                            ->where(function($query) use ($request) {
                                $query->where(DB::raw('UPPER(q_tdo_descripx)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_f_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_m_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_l_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%');
                        });
                    }else if($request['selectedFNA'] != "" && $request['dataToSearch'] != ""){
                        $queryRes = Todos::leftJoin('clients', function ($join) use ($request) {
                            $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id');
                            })
                            ->leftJoin('agents', function ($join) use ($request) {
                                $join->on('todos.q_tdo_agentID', '=', 'agents.q_agnt_id');
                            })
                            ->where('q_tdo_isForClientAgent', $request['isFor'])
                            ->where(DB::raw('UPPER(q_tdo_fromTable)'), strtoupper($request['selectedFNA']))
                            ->where(function($query) use ($request) {
                                $query->where(DB::raw('UPPER(q_tdo_descripx)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_f_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_m_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_l_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%');
                        });
                    }else if($request['selectedFNA'] != "" && $request['dataToSearch'] == ""){
                        $queryRes = Todos::leftJoin('clients', function ($join) use ($request) {
                            $join->on('todos.q_tdo_clientID', '=', 'clients.q_clnt_id');
                            })
                            ->leftJoin('agents', function ($join) use ($request) {
                                $join->on('todos.q_tdo_agentID', '=', 'agents.q_agnt_id');
                            })
                            ->where('q_tdo_isForClientAgent', $request['isFor'])
                            ->where(function($query) use ($request) {
                                $query->where(DB::raw('UPPER(q_tdo_fromTable)'), 'LIKE', '%'.strtoupper($request['selectedFNA']).'%');
                        });
                    }
                    $data = TodosResource::collection($queryRes->orderBy('q_tdo_id', 'ASC')->paginate($perPage,['*'],'1',$page));
                    $totalRows = $queryRes->count();
                }
                if(!$data && $table == 'searchClientsAnnualReview'){
                    $queryRes = null;
                    if($request['selectedFNA'] == "" && $request['dataToSearch'] != ""){
                        $data = ClientsAnnualReviewResource::collection(FinancialPlannSol::
                            leftJoin('clients', function ($join) use ($request) {
                                $join->on('financial_plann_sols.q_finPlSo_clientID', 'clients.q_clnt_id');
                            })
                            ->where('clients.q_clnt_agnt_id', $request['user_id'])
                            ->where(function($query) use ($request) {
                                $query->where(DB::raw('UPPER(clients.q_clnt_f_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_m_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_l_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%');
                        })->orderBy('q_finPlSo_id', 'ASC')->paginate($perPage,['*'],'1',$page));
                        $totalRows = FinancialPlannSol::
                            leftJoin('clients', function ($join) use ($request) {
                                $join->on('financial_plann_sols.q_finPlSo_clientID', 'clients.q_clnt_id');
                            })
                            ->where('clients.q_clnt_agnt_id', $request['user_id'])
                            ->where(function($query) use ($request) {
                                $query->where(DB::raw('UPPER(clients.q_clnt_f_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_m_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_l_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%');
                        })->count();
                    }else if($request['selectedFNA'] != "" && $request['dataToSearch'] != ""){
                        $data = ClientsAnnualReviewResource::collection(FinancialPlannSol::
                            leftJoin('clients', function ($join) use ($request) {
                                $join->on('financial_plann_sols.q_finPlSo_clientID', 'clients.q_clnt_id');
                            })
                            ->where('clients.q_clnt_agnt_id', $request['user_id'])
                            ->where(DB::raw('UPPER(q_finPlSo_forTable)'), strtoupper($request['selectedFNA']))
                            ->where(function($query) use ($request) {
                                $query->where(DB::raw('UPPER(clients.q_clnt_f_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_m_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_l_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%');
                        })->orderBy('q_finPlSo_id', 'ASC')->paginate($perPage,['*'],'1',$page));
                        $totalRows = FinancialPlannSol::
                            leftJoin('clients', function ($join) use ($request) {
                                $join->on('financial_plann_sols.q_finPlSo_clientID', 'clients.q_clnt_id');
                            })
                            ->where('clients.q_clnt_agnt_id', $request['user_id'])
                            ->where(DB::raw('UPPER(q_finPlSo_forTable)'), strtoupper($request['selectedFNA']))
                            ->where(function($query) use ($request) {
                                $query->where(DB::raw('UPPER(clients.q_clnt_f_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_m_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_l_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%');
                        })->count();
                    }else if($request['selectedFNA'] != "" && $request['dataToSearch'] == ""){
                        $data = ClientsAnnualReviewResource::collection(FinancialPlannSol::
                            leftJoin('clients', function ($join) use ($request) {
                                $join->on('financial_plann_sols.q_finPlSo_clientID', 'clients.q_clnt_id');
                            })
                            ->where('clients.q_clnt_agnt_id', $request['user_id'])
                            ->where(DB::raw('UPPER(financial_plann_sols.q_finPlSo_forTable)'), strtoupper($request['selectedFNA']))
                            ->orderBy('financial_plann_sols.q_finPlSo_id', 'ASC')->paginate($perPage,['*'],'1',$page));
                        $totalRows = FinancialPlannSol::
                            leftJoin('clients', function ($join) use ($request) {
                                $join->on('financial_plann_sols.q_finPlSo_clientID', 'clients.q_clnt_id');
                            })
                            ->where('clients.q_clnt_agnt_id', $request['user_id'])
                            ->where(DB::raw('UPPER(q_finPlSo_forTable)'), strtoupper($request['selectedFNA']))
                            ->count();
                    }
                }
                if(!$data && $table == 'AdminRQT_searchClientsAnnualReview'){
                    $queryRes = null;
                    if($request['selectedFNA'] == "" && $request['dataToSearch'] != ""){
                        $queryRes = FinancialPlannSol::
                            leftJoin('clients', function ($join) use ($request) {
                                $join->on('financial_plann_sols.q_finPlSo_clientID', 'clients.q_clnt_id');
                            })
                            ->where(function($query) use ($request) {
                                $query->where(DB::raw('UPPER(clients.q_clnt_f_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_m_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_l_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%');
                            });
                    }else if($request['selectedFNA'] != "" && $request['dataToSearch'] != ""){
                        $queryRes = FinancialPlannSol::
                            leftJoin('clients', function ($join) use ($request) {
                                $join->on('financial_plann_sols.q_finPlSo_clientID', 'clients.q_clnt_id');
                            })
                            ->where(DB::raw('UPPER(q_finPlSo_forTable)'), strtoupper($request['selectedFNA']))
                            ->where(function($query) use ($request) {
                                $query->where(DB::raw('UPPER(clients.q_clnt_f_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_m_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                                    ->orWhere(DB::raw('UPPER(clients.q_clnt_l_name)'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%');
                            });
                    }else if($request['selectedFNA'] != "" && $request['dataToSearch'] == ""){
                        $queryRes = FinancialPlannSol::
                            leftJoin('clients', function ($join) use ($request) {
                                $join->on('financial_plann_sols.q_finPlSo_clientID', 'clients.q_clnt_id');
                            })
                            ->where(DB::raw('UPPER(q_finPlSo_forTable)'), strtoupper($request['selectedFNA']));
                    }
                    $data = ClientsAnnualReviewResource::collection($queryRes->orderBy('q_finPlSo_id', 'ASC')->paginate($perPage,['*'],'1',$page));
                    $totalRows = $queryRes->count();
                }
            } catch (\Throwable $th) {
                return $this->thrownErrorInterrupted($this->interruptedError, 500);
            }
        }else{
            try {
                if($wantField == 'q_agnt_successfulDateSync'){                                                             // on Agent table
                    if(Carbon::hasFormat($request['dataToSearch'], 'Y-m-d')){
                        if($table == 'Agent'){
                            $data = AgentResource::collection(
                                Agent::whereDate($wantField, $request['dataToSearch'])->paginate($perPage,['*'],'1',$page)
                            );
                            $totalRows = Agent::whereDate($wantField, $request['dataToSearch'])->count();
                        }
                    }else{
                        $specifyDateErr = 'The Date you want to search must be a valid date, please use this format \'Y-m-d\' (Ex. 2023-05-23) instead.';
                        $counterErr = 1;
                    }
                }else if($wantField == 'q_clnt_successfulDateSync' || $wantField == 'q_clnt_weddDate'){                 // on Client table
                    if(Carbon::hasFormat($request['dataToSearch'], 'Y-m-d')){
                        if($table == 'Client'){
                            $data = ClientResource::collection(
                                Client::where('q_clnt_agnt_id', $request['agent_id'])
                                    ->whereDate($wantField, $request['dataToSearch'])->paginate($perPage,['*'],'1',$page)
                            );
                            $totalRows = Client::where('q_clnt_agnt_id', $request['agent_id'])
                                ->whereDate($wantField, $request['dataToSearch'])
                                ->count();
                        }
                    }else{
                        $specifyDateErr = 'The Date you want to search must be a valid date, please use this format \'Y-m-d\' (Ex. 2023-05-23) instead.';
                        $counterErr = 1;
                    }
                }else{
                    if($table == 'Agent'){
                        $data = AgentResource::collection(
                            Agent::where(DB::raw('UPPER('.$wantField.')'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')->paginate($perPage,['*'],'1',$page)
                        );
                        $totalRows = Agent::where(DB::raw('UPPER('.$wantField.')'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')->count();
                    }
                    if(!$data && $table == 'Client'){
                        $data = ClientResource::collection(
                            Client::where('q_clnt_agnt_id', $request['agent_id'])
                                ->where(DB::raw('UPPER('.$wantField.')'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')->paginate($perPage,['*'],'1',$page)
                            );
                        $totalRows = Client::where('q_clnt_agnt_id', $request['agent_id'])
                            ->where(DB::raw('UPPER('.$wantField.')'), 'LIKE', '%'.strtoupper($request['dataToSearch']).'%')
                            ->count();
                    }
                }
            } catch (\Throwable $th) {
                return $this->thrownErrorInterrupted($this->interruptedError, 500);
            }
        }
        if($data != null && $data != ''){
            return [
                'http' => 200,
                'message' => $counterErr ? $specifyDateErr : $this->successOperation,
                'data' => $counterErr ? [] : ($data ? $data->items() : ''),
                'meta' => [
                    'total' => $counterErr ? 0 : ( $totalRows ? $totalRows : ($data != '' ? $data->count() : 0) ),
                    'page' => $page,
                    'per_page' => $perPage
                ],
            ];
        }else {
            return $this->unexpectedError($this->unexpectedError, 500);
        }
    }


    //          *         ****        ****
    //         * *        *     *     *     *
    //        *****       *      *    *      *
    //       *     *      *     *     *     *
    //      *       *     ***         ***

    public function saveUpdateNewData($request, $op){
        if($op == 'selected_financial_priorities'){
            try {
                $collectedErrorsAndSuccess = array();
                $i = 0; $counter = 0; $success = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = collect($data)->flatMap(function ($values) {  // This code flattens the nested arrays in each set of data before running the validation. Adjust the validation rules and custom error messages based on your actual field names and requirements.
                        return $values;
                    })->toArray();
                    if($flattenedData['sfp_id'] == null || $flattenedData['sfp_id'] == '' || intval($flattenedData['sfp_id']) == 0){
                        // for Adding new Selected Financial Priorities
                        $countExist = 0;
                        $countExist = $this->checkAndCount('SelectedFinancialPriorities', $flattenedData, null, null);
                        if($countExist == 0){
                            $countExist = $this->checkAndCount('SelectedFinancialPriorities_V2', $flattenedData, null, null);
                            if($countExist == 0){
                                // Data insertion...
                                $res = 0;
                                $res = $this->insertIntoDB('SelectedFinancialPriorities', $flattenedData, $request, null);
                                if($res){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                    $i++;
                                    $success++;
                                    // Inserting logs...
                                    $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new data (ID no. ".$res.") on Financial Priorities for Client (ID no. ".$request['client_id'].").");
                                    $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new data (ID no. ".$res.") on Financial Priorities for Client ");
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => "Rank for the selected Financial Priority already found."];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => "Financial Priority list for the selected Financial Priority already found."];
                            $i++;
                        }
                    }else{
                        // for Updating Selected Financial Priorities
                        $countExist = $this->checkAndCount('SelectedFinancialPriorities_V3', $flattenedData, null, null);
                        if($countExist == 0){
                            // Updating data into DB
                            $res = 0;
                            $res = $this->updateIntoDB('SelectedFinancialPriorities', $flattenedData, $request, null);
                            if($res){
                                $res = $flattenedData['sfp_id'];
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                                // Inserting logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED data (ID no. ".$res.") on Financial Priorities for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED data (ID no. ".$res.") on Financial Priorities for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => "The system cannot perform unnecessary update. Try again with different data."];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'CashFlowAnalysisInOutflow'){
            try {
                $collectedErrorsAndSuccess = array();
                $i = 0; $counter = 0; $success = 0; $error333 = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = collect($data)->flatMap(function ($values) {  // This code flattens the nested arrays in each set of data before running the validation. Adjust the validation rules and custom error messages based on your actual field names and requirements.
                        return $values;
                    })->toArray();
                    $cfl_id = 0;
                    if(intval($flattenedData['cfl_id']) == 0 && $flattenedData['cfl_description'] != ""){
                        $cfl_id = $this->insertIntoDB('New_CashFlowList', $flattenedData, $request, null);
                    }else if(intval($flattenedData['cfl_id']) == 0 && $flattenedData['cfl_description'] == ""){
                        $error333 = 1;
                        break;
                    }
                    $countExist = 0;
                    $countExist = $this->checkAndCount('CashFlowData', $flattenedData, null, null);  // Check if there's any...
                    if($countExist > 0){ // For updating of Cash Flow Data Record
                        // Check for duplications...
                        $countExist = 0;
                        if($request['flowType'] == 0){  // Inflow
                            $countExist = $this->checkAndCount('CashFlowData_V2', $flattenedData, null, null);
                            if($countExist < 1){
                                // Update data...
                                $res = 0;
                                $res = $this->updateIntoDB('CashFlowData', $flattenedData, $request, null);
                                if($res){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                    $i++;
                                    $success++;
                                    // Inserting Logs
                                    $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED data (Inflow with ID no. ".$res.") of Family Cash Flow Analysis for Client (ID no. ".$request['client_id'].").");
                                    $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED data (ID no. ".$res.") of Family Cash Flow Analysis for Client ");
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => "Unnecessary updates can't be made. Consult the system administrator."];
                                $i++;
                            }
                        }else if($request['flowType'] == 1){   // Outflow
                            $countExist = $this->checkAndCount('CashFlowData_V3', $flattenedData, null, null);
                            if($countExist < 1){
                                // Update data...
                                $res = 0;
                                $res = $this->updateIntoDB('CashFlowData_v2', $flattenedData, $request, null);
                                if($res){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                    $i++;
                                    $success++;
                                    // Inserting Logs
                                    $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED data (Outflow with ID no. ".$res.") of Family Cash Flow Analysis for Client (ID no. ".$request['client_id'].").");
                                    $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED data (ID no. ".$res.") of Family Cash Flow Analysis for Client ");
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => "Unnecessary updates can't be made. Consult the system administrator."];
                                $i++;
                            }
                        }else if($request['flowType'] == 2){  // Outflow with Budget
                            $countExist = $this->checkAndCount('CashFlowData_V4', $flattenedData, null, null);
                            if($countExist < 1){
                                // Update data...
                                $res = 0;
                                $res = $this->updateIntoDB('CashFlowData_v3', $flattenedData, $request, null);
                                if($res){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                    $i++;
                                    $success++;
                                    // Inserting Logs
                                    $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED data (Outflow with Budget ID no. ".$res.") of Family Cash Flow Analysis for Client (ID no. ".$request['client_id'].").");
                                    $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED data (ID no. ".$res.") of Family Cash Flow Analysis for Client ");
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => "Unnecessary updates can't be made. Consult the system administrator."];
                                $i++;
                            }
                        }
                    }else{  // For Creating of Cash Flow Data Record
                        // Check for duplications...
                        $countExist = 0;
                        $countExist = $this->checkAndCount('CashFlowData_V5', $flattenedData, null, null);
                        if($countExist < 1){
                            // Data insertion...
                            $res = 0;
                            $res = $this->insertIntoDB('CashFlowData', $flattenedData, $request, $cfl_id);
                            if($res){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new data (ID no. ".$res.") of Family Cash Flow Analysis for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED data (ID no. ".$res.") of Family Cash Flow Analysis for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                if($error333 == 1){
                    return $this->thrownErrorInterruptedHasSuccess($this->g, 500, 0);
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'CashFlowRecommendations'){ // Has update...
            try {
                $collectedErrorsAndSuccess = array();
                $i = 0; $counter = 0; $success = 0; $updated = 0; $added = 0;
                $getCfaId = CashFlowAnalysis::where('q_cfa_clnt_id', $request['client_id'])->select(['q_cfa_id'])->first();
                foreach ($request['recommendations'] as $index => $data) {
                    $flattenedData = collect($data)->flatMap(function ($values) {  // This code flattens the nested arrays in each set of data before running the validation. Adjust the validation rules and custom error messages based on your actual field names and requirements.
                        return $values;
                    })->toArray();
                    $countExist = 0;
                    $countExist = $this->checkAndCount('Recommendations', $flattenedData, $request, null); // Check if there's any...
                    if($countExist > 0){
                        // Check for duplications...
                        $countExist = 0;
                        $countExist = $this->checkAndCount('Recommendations_V2', $flattenedData, $request, null);
                        if($countExist == 0){
                            // Update data...
                            $res = 0;
                            $res = $this->updateIntoDB('Recommendations', $flattenedData, $request, null);
                            if($res == 1){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                                $updated++;
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => "Unnecessary updates can't be made. Consult the system administrator."];
                            $i++;
                        }
                    }else{
                        // Check for duplications...
                        $countExist = 0;
                        $countExist = $this->checkAndCount('Recommendations_V4', $flattenedData, $request, $getCfaId);
                        if($countExist < 1){
                            // Insert data
                            $res = 0;
                            $res = $this->insertIntoDB('Recommendations', $flattenedData, $request, $getCfaId);
                            if($res == 1){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                $i++;
                                $success++;
                                $added++;
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                // Inserting Logs
                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully altered Recommendations of Family Cash Flow Analysis for Client (ID no. ".$request['client_id']."). ".$updated." updated and ".$added." added");
                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully altered Recommendations of Family Cash Flow Analysis for Client ");
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'CashFlowAnalysis'){
            try {
                $message = ''; $success = 0;
                $countCFA = $this->checkAndCount('CashFlowAnalysis', null, $request, null); // Check if there's any...
                if($countCFA > 0){
                    // update
                    if($request['flowType'] == 0){
                        $countExistCFA = CashFlowAnalysis::where('q_cfa_clnt_id', $request['client_id'])
                            ->where('q_cfa_targetCashInF_client', $request['client'])
                            ->where('q_cfa_targetCashInF_spouse', $request['spouse'])
                            ->count();
                    }else if($request['flowType'] == 1){
                        $countExistCFA = CashFlowAnalysis::where('q_cfa_clnt_id', $request['client_id'])
                            ->where('q_cfa_targetCashOutF_client', $request['clientExpenses'])
                            ->where('q_cfa_targetCashOutF_spouse', $request['spouseExpenses'])
                            ->where('q_cfa_clientShareRFN', $request['clientshare_rfn'])
                            ->where('q_cfa_spouseShareRFN', $request['spouseshare_rfn'])
                            ->where('q_cfa_reduceCFAttempt', $request['reduce_cf_attempt'])
                            ->count();
                    }else if($request['flowType'] == 2){
                        $countExistCFA = CashFlowAnalysis::where('q_cfa_clnt_id', $request['client_id'])
                            ->where('q_cfa_expectedSavings', $request['expected_savings'])
                            ->where('q_cfa_goesWell', $request['goes_well'])
                            ->count();
                    }
                    if($countExistCFA > 0){
                        $message = 'Cannot perform update with unnecessary data. Consult the system administrator.';
                    }else{
                        if($request['flowType'] == 0){
                            $res = CashFlowAnalysis::where('q_cfa_clnt_id', $request['client_id'])
                            ->update([
                                'q_cfa_targetCashInF_client' => $request['client'],
                                'q_cfa_targetCashInF_spouse' => $request['spouse'],
                                'q_cfa_dateUpdated' => date('Y-m-d'),
                            ]);
                            // Inserting Logs
                            $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED data (Inflow with ID no. ".$res->id.") of Family Cash Flow Analysis for Client (ID no. ".$request['client_id'].").");
                            $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED data (Inflow with ID no. ".$res->id.") of Family Cash Flow Analysis for Client ");
                        }else if($request['flowType'] == 1){
                            $res = CashFlowAnalysis::where('q_cfa_clnt_id', $request['client_id'])
                            ->update([
                                'q_cfa_targetCashOutF_client' => $request['clientExpenses'],
                                'q_cfa_targetCashOutF_spouse' => $request['spouseExpenses'],
                                'q_cfa_clientShareRFN' => $request['clientshare_rfn'],
                                'q_cfa_spouseShareRFN' => $request['spouseshare_rfn'],
                                'q_cfa_reduceCFAttempt' => $request['reduce_cf_attempt'],
                                'q_cfa_dateUpdated' => date('Y-m-d'),
                            ]);
                            // Inserting Logs
                            $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED data (Outflow with ID no. ".$res->id.") of Family Cash Flow Analysis for Client (ID no. ".$request['client_id'].").");
                            $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED data (Outflow with ID no. ".$res->id.") of Family Cash Flow Analysis for Client ");
                        }else if($request['flowType'] == 2){
                            $res = CashFlowAnalysis::where('q_cfa_clnt_id', $request['client_id'])
                            ->update([
                                'q_cfa_expectedSavings' => $request['expected_savings'],
                                'q_cfa_goesWell' => $request['goes_well'],
                                'q_cfa_dateUpdated' => date('Y-m-d'),
                            ]);
                            // Inserting Logs
                            $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED data (Outflow with Budget with ID no. ".$res->id.") of Family Cash Flow Analysis for Client (ID no. ".$request['client_id'].").");
                            $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED data (Outflow with Budget with ID no. ".$res->id.") of Family Cash Flow Analysis for Client ");
                        }
                        $message = 'Existing Cash Flow Analysis information of Client with ID '.$request['client_id'].' has been successfully updated.';
                        $success = 1;
                    }
                }else{
                    // add
                    $countExistCFA = $this->checkAndCount('CashFlowAnalysis_V2', null, $request, null);
                    if($countExistCFA > 0){
                        $message = 'Cannot perform update with unnecessary data. Consult the system administrator.';
                    }else{
                        // Insert data
                        $res = 0;
                        $res = $this->insertIntoDB('CashFlowAnalysis', null, $request, null);
                        if($res){
                            $message = 'New Cash Flow Analysis information of Client with ID '.$request['client_id'].' has been successfully added.';
                            $success = 1;
                            // Inserting Logs
                            $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new data (ID no. ".$res.") of Family Cash Flow Analysis for Client (ID no. ".$request['client_id'].").");
                            $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new data (ID no. ".$res.") of Family Cash Flow Analysis for Client ");
                        }else{
                            $message = $this->interruptedError;
                            $success = 0;
                        }
                    }
                }
                $cfa_IDcpy = null;
                return $this->getSuccessOperation($success ? 200 : 500, $message, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'heirs'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = collect($data)->flatMap(function ($values) {  // This code flattens the nested arrays in each set of data before running the validation. Adjust the validation rules and custom error messages based on your actual field names and requirements.
                        return $values;
                    })->toArray();
                    $countHeir = 0;
                    $countHeir = $this->checkAndCount('Heir', $flattenedData, $request, null); // Check if there's any...
                    if($countHeir == 0){
                        $countHeir = 0;
                        $countHeir = $this->checkAndCount('Heir_V2', $flattenedData, $request, null);
                        if($countHeir == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('Heir', $flattenedData, $request, null);
                            if($res == 1){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                $i++;
                                $success++;
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countHeir = 0;
                        $countHeir = $this->checkAndCount('Heir_V3', $flattenedData, $request, null);
                        if($countHeir == 0){
                            $res = 0;
                            $res = $this->insertIntoDB('Heir', $flattenedData, $request, $getCfaId);
                            if($res == 1){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => ". Unnecessary updates can't be made. Consult the system administrator."];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'CashAndDeposits'){
            //try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('CashAndDeposits', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('CashAndDeposits_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('CashAndDeposits', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Cash and Deposit as Assets of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Cash and Deposit as Assets of Networth Inventory (ID no. ".$res.") for Client ");
                                $result = 0;
                                $result = $this->insertIntoDB('Heirs', $res, $request, $flattenedData['part']);
                                if($result){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                    $i++;
                                    $success++;
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        // Update data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('CashAndDeposits_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('CashAndDeposits', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $res = $flattenedData['cad_id'];
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Cash and Deposit as Assets of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Cash and Deposit as Assets of Networth Inventory (ID no. ".$res.") for Client ");
                                $result = 0;
                                $result = $this->updateIntoDB('Multi_Heirs', $res, $request, $flattenedData['part']);
                                if($result){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                    $i++;
                                    $success++;
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError. "wew" ];
                                $i++;
                            }
                            // $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->updateOfRecordDiscge];
                            // $i++;
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            // } catch (\Throwable $th) {
            //     return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            // }
        }else if($op == 'family_composition'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('Family_Composition', $flattenedData, $request, null);
                    if($countCD == 0){
                        $countCD = 0;
                        $countCD = $this->checkAndCount('Family_Composition_v2', $flattenedData, $request, null);
                        if($countCD == 0){
                            $res = 0;
                            $res = $this->insertIntoDB('family_composition', $flattenedData, $request, null);
                            if($res){
                                $res = $flattenedData['fc_id'];
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Family Composition (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Family Composition (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        // Update data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('Family_Composition_v2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = 0;
                            $res = $this->updateIntoDB('family_composition', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $res = $flattenedData['fc_id'];
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Family Composition (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Family Composition (ID no. ".$res.") for Client ");
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError.$res ];
                                $i++;
                            }
                            // $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->updateOfRecordDiscge];
                            // $i++;
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'Receivables'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('Receivables', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('Receivables_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('Receivables', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Receivables as Asset of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Receivables as Asset of Networth Inventory (ID no. ".$res.") for Client ");
                                $result = 0;
                                $result = $this->insertIntoDB('Heirs', $res, $request, $flattenedData['part']);

                                if($result){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                    $i++;
                                    $success++;
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        // Update data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('Receivables_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('Receivables', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $res = $flattenedData['receivables_id'];
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Receivables as Asset of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Receivables as Asset of Networth Inventory (ID no. ".$res.") for Client ");
                                $result = 0;
                                $result = $this->updateIntoDB('Multi_Heirs', $res, $request, $flattenedData['part']);
                                if($result){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                    $i++;
                                    $success++;
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                            // $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->updateOfRecordDiscge];
                            // $i++;
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'Mutual_Funds'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('MutualFunds', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('MutualFunds_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('MutualFunds', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Mutual Funds as Financial Asset of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Mutual Funds as Financial Asset of Networth Inventory (ID no. ".$res.") for Client ");
                                $result = 0;
                                $result = $this->insertIntoDB('Heirs', $res, $request, $flattenedData['part']);

                                if($result){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                    $i++;
                                    $success++;
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('MutualFunds_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('MutualFunds', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $res = $flattenedData['mfuitf_id'];
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Mutual Funds as Financial Asset of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Mutual Funds as Financial Asset of Networth Inventory (ID no. ".$res.") for Client ");
                                $result = 0;
                                $result = $this->updateIntoDB('Multi_Heirs', $res, $request, $flattenedData['part']);
                                if($result){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                    $i++;
                                    $success++;
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                            // $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->updateOfRecordDiscge];
                            // $i++;
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'Bonds'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('Bonds', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('Bonds_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('Bonds', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Bonds as Financial Asset of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Bonds as Financial Asset of Networth Inventory (ID no. ".$res.") for Client ");
                                $result = 0;
                                $result = $this->insertIntoDB('Heirs', $res, $request, $flattenedData['part']);

                                if($result){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                    $i++;
                                    $success++;
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('Bonds_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('Bonds', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $res = $flattenedData['bond_id'];
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Bonds as Financial Asset of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Bonds as Financial Asset of Networth Inventory (ID no. ".$res.") for Client ");
                                $result = 0;
                                $result = $this->updateIntoDB('Multi_Heirs', $res, $request, $flattenedData['part']);
                                if($result){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                    $i++;
                                    $success++;
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                            // $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->updateOfRecordDiscge];
                            // $i++;
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'StockInCompanies'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('StockInCompanies', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('StockInCompanies_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('StockInCompanies', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Stocks in Listed Companies as Financial Asset of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Stocks in Listed Companies as Financial Asset of Networth Inventory (ID no. ".$res.") for Client ");
                                $result = 0;
                                $result = $this->insertIntoDB('Heirs', $res, $request, $flattenedData['part']);

                                if($result){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                    $i++;
                                    $success++;
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('StockInCompanies_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('StockInCompanies', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $res = $flattenedData['sic_id'];
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Stocks in Listed Companies as Financial Asset of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Stocks in Listed Companies as Financial Asset of Networth Inventory (ID no. ".$res.") for Client ");
                                $result = 0;
                                $result = $this->updateIntoDB('Multi_Heirs', $res, $request, $flattenedData['part']);
                                if($result){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                    $i++;
                                    $success++;
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                            // $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->updateOfRecordDiscge];
                            // $i++;
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'LifeAndHealthInsurance'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('LifeAndHealthInsurance', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('LifeAndHealthInsurance_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('LifeAndHealthInsurance', $flattenedData, $request, null);
                            // $collectedErrorsAndSuccess[$i] = ['Error: ' => $res ];
                            // $i++;
                            if($res){
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Family Life and Health Insurance as Financial Asset of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Family Life and Health Insurance as Financial Asset of Networth Inventory (ID no. ".$res.") for Client ");
                                $collectedErrorsAndSuccess[$i] = ['Family_Life_and_Health_Insurance' => "Successfully added."];
                                $collectedErrorsAndSuccess[$i] += ['Beneficiaries' => $this->insertIntoDB('Beneficiaries', $res, $request, null)];
                                $i++;
                                $success++;
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error: ' => $this->interruptedError . "wew" ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error: ' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('LifeAndHealthInsurance_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('LifeAndHealthInsurance', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $res = $flattenedData['flahi_id'];
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Family Life and Health Insurance as Financial Asset of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Family Life and Health Insurance as Financial Asset of Networth Inventory (ID no. ".$res.") for Client ");
                                $collectedErrorsAndSuccess[$i] = ['Result' => $this->updateIntoDB('Beneficiaries', $res, $request, null)];
                                //$collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                            // $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->updateOfRecordDiscge];
                            // $i++;
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    break;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'updateClientIsActive'){
            $success = $this->updateIntoDB('updateClientIsActive', null, $request, null);
            return $this->getSuccessOperation($success > 0 ? 200 : 500, "Client's active status has been successfully updated.", $success, null);
        }else if($op == 'FamilyHomeRealEstate'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('FamilyHomeRealEstate', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('FamilyHomeRealEstate_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('FamilyHomeRealEstate', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Family Home or Real Estate as Asset of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Family Home or Real Estate as Asset of Networth Inventory (ID no. ".$res.") for Client ");
                                $result = 0;
                                $result = $this->insertIntoDB('Heirs', $res, $request, $flattenedData['part']);

                                if($result){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                    $i++;
                                    $success++;
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError . $res ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('FamilyHomeRealEstate_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('FamilyHomeRealEstate', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $res = $flattenedData['fh_id'];
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Family Home or Real Estate as Asset of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Family Home or Real Estate as Asset of Networth Inventory (ID no. ".$res.") for Client ");
                                $result = 0;
                                $result = $this->updateIntoDB('Multi_Heirs', $res, $request, $flattenedData['part']);
                                if($result){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                    $i++;
                                    $success++;
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                            // $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->updateOfRecordDiscge];
                            // $i++;
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'Vehicles'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('Vehicles', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('Vehicles_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('Vehicles', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Vehicle as Asset of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Vehicle as Asset of Networth Inventory (ID no. ".$res.") for Client ");
                                $result = 0;
                                $result = $this->insertIntoDB('Heirs', $res, $request, $flattenedData['part']);

                                if($result){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                    $i++;
                                    $success++;
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('Vehicles_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('Vehicles', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $res = $flattenedData['vehicles_id'];
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Vehicle as Asset of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Vehicle as Asset of Networth Inventory (ID no. ".$res.") for Client ");
                                $result = 0;
                                $result = $this->updateIntoDB('Multi_Heirs', $res, $request, $flattenedData['part']);
                                if($result){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                    $i++;
                                    $success++;
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                            // $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->updateOfRecordDiscge];
                            // $i++;
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'updateClient'){
            $success = $this->updateIntoDB('updateClient', null, $request, null);
            return $this->getSuccessOperation($success > 0 ? 200 : 500, "Client's active status has been successfully updated.", $success, null);
        }else if($op == 'PersonalAssets'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('PersonalAssets', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('PersonalAssets_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('PersonalAssets', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Personal Asset of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Personal Asset of Networth Inventory (ID no. ".$res.") for Client ");
                                $result = 0;
                                $result = $this->insertIntoDB('Heirs', $res, $request, $flattenedData['part']);

                                if($result){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                    $i++;
                                    $success++;
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError . $res ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('PersonalAssets_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('PersonalAssets', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $res = $flattenedData['pa_id'];
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Personal Asset of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Personal Asset of Networth Inventory (ID no. ".$res.") for Client ");
                                $result = 0;
                                $result = $this->updateIntoDB('Multi_Heirs', $res, $request, $flattenedData['part']);
                                if($result){
                                    $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                    $i++;
                                    $success++;
                                }else{
                                    $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                    $i++;
                                }
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                            // $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->updateOfRecordDiscge];
                            // $i++;
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'Liabilities'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('Liabilities', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('Liabilities_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('Liabilities', $flattenedData, $request, null);
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $res ];
                            $i++;
                            // if($res){
                            //     $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                            //     $i++;
                            //     $success++;
                            //     // Inserting Logs
                            //     $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Liability of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                            //     $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Liability of Networth Inventory (ID no. ".$res.") for Client ");
                            // }else{
                            //     $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError . $res ];
                            //     $i++;
                            // }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('Liabilities_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('Liabilities', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $res = $flattenedData['liabilities_id'];
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Liability of Networth Inventory (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Liability of Networth Inventory (ID no. ".$res.") for Client ");
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                            // $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->updateOfRecordDiscge];
                            // $i++;
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'DreamsAndAspiration'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('DreamsAndAspiration', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('DreamsAndAspiration_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('DreamsAndAspiration', $flattenedData, $request, null);
                            if($res){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Dream and Aspiration of Financial Priorities (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Dream and Aspiration of Financial Priorities (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError . $res ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('DreamsAndAspiration_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('DreamsAndAspiration', $flattenedData, $request, null);
                            if($res){
                                // Inserting Logs
                                $res = $flattenedData['dreasp_id'];
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Dream and Aspiration of Financial Priorities (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Dream and Aspiration of Financial Priorities (ID no. ".$res.") for Client ");
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                            // $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->updateOfRecordDiscge];
                            // $i++;
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'PlanningSolutions'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('PlanningSolutions', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('PlanningSolutions_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('PlanningSolutions', $flattenedData, $request, null);
                            if($res > 0){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED Financial Planning Solutions of <b><i>".$request['fromTable']."</i></b> (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED Financial Planning Solutions of <b><i>".$request['fromTable']."</i></b> (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('PlanningSolutions_V2', $flattenedData, $request, null);
                        if($countCD == 1){
                            // Update data...
                            $res = $this->updateIntoDB('PlanningSolutions', $flattenedData, $request, null);
                            if($res){
                                $res = $flattenedData['plansol_id'];
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Financial Planning Solutions of <b><i>".$request['fromTable']."</i></b> (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Financial Planning Solutions of <b><i>".$request['fromTable']."</i></b> (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    // $counter++;
                    break;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'PlanSol_Todos'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['todos'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('PlanSol_Todos', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('PlanSol_Todos_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('PlanSol_Todos', $flattenedData, $request, null);
                            if($res){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                $i++;
                                $success++;
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('PlanSol_Todos_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('PlanSol_Todos', $flattenedData, $request, null);
                            if($res){
                                $res = $flattenedData['todos_id'];
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Todos of Financial Planning Solutions of <b><i>".$request['fromTable']."</i></b> (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Todos of Financial Planning Solutions of <b><i>".$request['fromTable']."</i></b> (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError."wew" ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'saveNewFamProFNA'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('saveNewFamProFNA', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('saveNewFamProFNA_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('saveNewFamProFNA', $flattenedData, $request, null);
                            if($res){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED Family Protection FNA information (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED Family Protection FNA information (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('saveNewFamProFNA_V2', $flattenedData, $request, null);
                        if($countCD == 1){
                            // Update data...
                            $res = $this->updateIntoDB('saveNewFamProFNA', $flattenedData, $request, null);
                            if($res){
                                $res = $flattenedData['fpfna_id'];
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Family Protection FNA information (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Family Protection FNA information (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg ];
                            $i++;
                        }
                    }
                    $counter++;
                    break;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'DebtsAndFinalExpenses'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['DFE'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('DebtsAndFinalExpenses', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('DebtsAndFinalExpenses_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            if(intval($flattenedData['debFinList_id']) == 0 && $flattenedData['debFinList_description'] != ""){
                                $res = $this->insertIntoDB('DebtsAndFinalList', $flattenedData, $request, null); // insert List
                                if($res){
                                    // Inserting Logs
                                    $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new in the list of Debts and Final Expenses (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                    $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new in the list of Debts and Final Expenses (ID no. ".$res.") for Client ");
                                }
                                $result = $this->insertIntoDB('DebtsAndFinalExpenses', $flattenedData, $request, $res); // insert Data
                                if($result){
                                    // Inserting Logs
                                    $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Debts and Final Expense (ID no. ".$result.") for Client (ID no. ".$request['client_id'].").");
                                    $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Debts and Final Expense (ID no. ".$result.") for Client ");
                                }
                            }else if(intval($flattenedData['debFinList_id']) == 0 && $flattenedData['debFinList_description'] == ""){
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => ". Unnecessary data can't be made. 'debFinList_description' field is required or consult the system administrator."];
                                $i++;
                            }else{
                                $result = $this->insertIntoDB('DebtsAndFinalExpenses', $flattenedData, $request, null);
                                if($result){
                                    // Inserting Logs
                                    $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Debts and Final Expense (ID no. ".$result.") for Client (ID no. ".$request['client_id'].").");
                                    $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Debts and Final Expense (ID no. ".$result.") for Client ");
                                }
                            }
                            if($result){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                $i++;
                                $success++;
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('saveNewFamProFNA_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('saveNewFamProFNA', $flattenedData, $request, null);
                            if($res){
                                $res = $flattenedData['fpfna_id'];
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Family Protection FNA information (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Family Protection FNA information (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'ExistLifeInsureCov'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['ELIC'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('ExistLifeInsureCov', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('ExistLifeInsureCov_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0; $result = 0;
                            if(intval($flattenedData['exLifeInsCovList_id']) == 0 && $flattenedData['exLifeInsCovList_description'] != ""){
                                $res = $this->insertIntoDB('LifeInsuranceCovList', $flattenedData, $request, null); // insert List
                                if($res){
                                    // Inserting Logs
                                    $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new in the list of Existing Life Insurance Coverage (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                    $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new in the list of Existing Life Insurance Coverage (ID no. ".$res.") for Client ");
                                }
                                $result = $this->insertIntoDB('ExistLifeInsureCov', $flattenedData, $request, $res); // insert Data
                                if($result){
                                    // Inserting Logs
                                    $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Existing Life Insurance Coverage (ID no. ".$result.") for Client (ID no. ".$request['client_id'].").");
                                    $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Existing Life Insurance Coverage (ID no. ".$result.") for Client ");
                                }
                            }else if(intval($flattenedData['exLifeInsCovList_id']) == 0 && $flattenedData['exLifeInsCovList_description'] == ""){
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => ". Unnecessary data can't be made. 'exLifeInsCovList_description' field is required or consult the system administrator."];
                                $i++;
                            }else{
                                $result = $this->insertIntoDB('ExistLifeInsureCov', $flattenedData, $request, null);
                                if($result){
                                    // Inserting Logs
                                    $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Existing Life Insurance Coverage (ID no. ".$result.") for Client (ID no. ".$request['client_id'].").");
                                    $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Existing Life Insurance Coverage (ID no. ".$result.") for Client ");
                                }
                            }
                            if($result){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                $i++;
                                $success++;
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('ExistLifeInsureCov_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('ExistLifeInsureCov', $flattenedData, $request, null);
                            if($res){
                                $res = $flattenedData['exLifeInsCov_id'];
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Existing Life Insurance Coverage (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Existing Life Insurance Coverage (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'RetirementPlanFNA'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('RetirementPlanFNA', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('RetirementPlanFNA_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('RetirementPlanFNA', $flattenedData, $request, null);
                            if($res){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED Retirement Planning FNA information (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED Retirement Planning FNA information (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('RetirementPlanFNA_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('RetirementPlanFNA', $flattenedData, $request, null);
                            if($res){
                                $res = $flattenedData['retPFNA_id'];
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Retirement Planning FNA information (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Retirement Planning FNA information (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                    break;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'RetirementExpenses'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['RE'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('RetirementExpenses', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('RetirementExpenses_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            ####################### To Review
                            if(intval($flattenedData['retExpList_id']) == 0 && $flattenedData['retExpList_description'] != ""){
                                $res = $this->insertIntoDB('RetirementExpensesList', $flattenedData, $request, null); // insert List
                                if($res){
                                    // Inserting Logs
                                    $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new in the list of Retirement Expenses (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                    $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new in the list of Retirement Expenses (ID no. ".$res.") for Client ");
                                }
                                $result = $this->insertIntoDB('RetirementExpenses', $flattenedData, $request, $res); // insert Data
                                if($result){
                                    // Inserting Logs
                                    $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Retirement Expense (ID no. ".$result.") for Client (ID no. ".$request['client_id'].").");
                                    $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Retirement Expense (ID no. ".$result.") for Client ");
                                }
                            }else if(intval($flattenedData['retExpList_id']) == 0 && $flattenedData['retExpList_description'] == ""){
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => ". Unnecessary data can't be made. 'retExpList_description' field is required or consult the system administrator."];
                                $i++;
                            }else{
                                $result = $this->insertIntoDB('RetirementExpenses', $flattenedData, $request, null);
                                if($result){
                                    // Inserting Logs
                                    $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Retirement Expense (ID no. ".$result.") for Client (ID no. ".$request['client_id'].").");
                                    $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Retirement Expense (ID no. ".$result.") for Client ");
                                }
                            }
                            ####################### End - To Review
                            if($result){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                $i++;
                                $success++;
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('RetirementExpenses_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('RetirementExpenses', $flattenedData, $request, null);
                            if($res){
                                $res = $flattenedData['dr_id'];
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") ". " has successfully UPDATED Retirement Expense (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Retirement Expense (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'EducationPlanFNA'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('EducationPlanFNA', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('EducationPlanFNA_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('EducationPlanFNA', $flattenedData, $request, null);
                            if($res){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED Education Planning FNA (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED Education Planning FNA (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('EducationPlanFNA_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('EducationPlanFNA', $flattenedData, $request, null);
                            if($res){
                                $res = $flattenedData['educPlanFNA_id'];
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Education Planning FNA (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Education Planning FNA (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'EducPlanExpenses'){
            try {
                $collectedErrorsAndSuccess = array(); $famCompIDs = 0;
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['EPE'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    // getting all Family Composition ID desired to be saved...
                    $famCompIDs = $flattenedData['familyComp_id'];
                    $countCD = 0;
                    $countCD = $this->checkAndCount('EducPlanExpenses', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('EducPlanExpenses_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            ####################### To Review
                            if(intval($flattenedData['educPlanExpList_id']) == 0 && $flattenedData['educPlanExpList_description'] != ""){
                                $res = $this->insertIntoDB('EducPlanExpensesList', $flattenedData, $request, null); // insert List
                                if($res){
                                    // Inserting Logs
                                    $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new in the list of Education Planning Expenses (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                    $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new in the list of Education Planning Expenses (ID no. ".$res.") for Client ");
                                }
                                $result = $this->insertIntoDB('EducPlanExpenses', $flattenedData, $request, $res); // insert Data
                                if($result){
                                    // Inserting Logs
                                    $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Education Planning Expense (ID no. ".$result.") for Client (ID no. ".$request['client_id'].").");
                                    $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Education Planning Expense (ID no. ".$result.") for Client ");
                                }
                            }else if(intval($flattenedData['educPlanExpList_id']) == 0 && $flattenedData['educPlanExpList_description'] == ""){
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => ". Unnecessary data can't be made. 'educPlanExpList_description' field is required or consult the system administrator."];
                                $i++;
                            }else{
                                $result = $this->insertIntoDB('EducPlanExpenses', $flattenedData, $request, null);
                                if($result){
                                    // Inserting Logs
                                    $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED new Education Planning Expense (ID no. ".$result.") for Client (ID no. ".$request['client_id'].").");
                                    $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED new Education Planning Expense (ID no. ".$result.") for Client ");
                                }
                            }
                            ####################### End - To Review
                            if($result){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                $i++;
                                $success++;
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('EducPlanExpenses_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('EducPlanExpenses', $flattenedData, $request, null);
                            if($res){
                                $res = $flattenedData['educPlanExp_id'];
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "rtyfg updated."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Education Planning Expense (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Education Planning Expense (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => "Cannot perform unnecessary updates when fields aren't updated well."];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, $famCompIDs);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'ChildrenEPFNA'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0; $famCompIDs = 0;
                foreach ($request['children'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $famCompIDs = $flattenedData['famComp_id'];
                    $countCD = 0;
                    $countCD = $this->checkAndCount('ChildrenEPFNA_qEduc', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('ChildrenEPFNA_V2', $flattenedData, $request, null);
                        if($countCD == 0){ 
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('ChildrenEPFNA', $flattenedData, $request, null);
                            if($res){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED Selected Children as part of Education Planning FNA (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED Selected Children as part of Education Planning FNA (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('ChildrenEPFNA_V2_qEduc', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('ChildrenEPFNA', $flattenedData, $request, null);
                            if($res){
                                $res = $flattenedData['childrenEducFNA_id'];
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Selected Children as part of Education Planning FNA (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Selected Children as part of Education Planning FNA (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, $famCompIDs);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'HealthFundPlannFNA'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('cHealthFundPlannFNA', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('cHealthFundPlannFNA_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('cHealthFundPlannFNA', $flattenedData, $request, null);
                            if($res){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED Health Fund Planning FNA (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED Health Fund Planning FNA (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('cHealthFundPlannFNA_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('cHealthFundPlannFNA', $flattenedData, $request, null);
                            if($res){
                                $res = $flattenedData['healthFP_id'];
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Health Fund Planning FNA (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Health Fund Planning FNA (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'healthCovSummary'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['HCSF'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('cHealthCovSummary', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('cHealthCovSummary_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('insHealthCovSummary', $flattenedData, $request, null);
                            if($res){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED Health Coverage Summary for Health Fund Planning FNA (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED Health Coverage Summary for Health Fund Planning FNA (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('cHealthCovSummary_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('upsHealthCovSummary', $flattenedData, $request, null);
                            if($res){
                                $res = $flattenedData['healthCovSum_id'];
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Health Coverage Summary for Health Fund Planning FNA (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Health Coverage Summary for Health Fund Planning FNA (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'TargetLimits'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['TL'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);
                    $countCD = 0;
                    $countCD = $this->checkAndCount('cTargetLimits', $flattenedData, $request, null);
                    if($countCD == 0){
                        // Insert data...
                        $countCD = 0;
                        $countCD = $this->checkAndCount('cTargetLimits_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Insert data...
                            $res = 0;
                            $res = $this->insertIntoDB('cTargetLimits', $flattenedData, $request, null);
                            if($res){
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully added."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully ADDED Target Limits for Health Fund Planning FNA (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully ADDED Target Limits for Health Fund Planning FNA (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->interruptedError ];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }else{
                        $countCD = 0;
                        $countCD = $this->checkAndCount('cTargetLimits_V2', $flattenedData, $request, null);
                        if($countCD == 0){
                            // Update data...
                            $res = $this->updateIntoDB('updTargetLimits', $flattenedData, $request, null);
                            if($res){
                                $res = $flattenedData['TL_id'];
                                $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                                $i++;
                                $success++;
                                // Inserting Logs
                                $this->recordSystemLogs($request['userFN']." (ID no. ".$request['userID'].") " . " has successfully UPDATED Health Coverage Summary for Health Fund Planning FNA (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                                $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED Health Coverage Summary for Health Fund Planning FNA (ID no. ".$res.") for Client ");
                            }else{
                                $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                                $i++;
                            }
                        }else{
                            $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'admin_settings'){
            $result = null;
            $count = AdminSettings::count();
            if($count){
                $result = AdminSettings::query()
                ->update([
                    'q_admSett_lastUpdatedByID' => $request['user_id'],
                    'q_admSett_famProInflaRate' => $request['val1'],
                    'q_admSett_retInflationRate' => $request['val2'],
                    'q_admSett_retEstInterestRate' => $request['val3'],
                    'q_admSett_childEducInflaRate' => $request['val4'],
                    'q_admSett_estateConvCurrTaxRate' => $request['val5'],
                    'q_admSett_estateConvOtherExpenses' => $request['val6'],
                    'q_admSett_ageChildGoCollege' => $request['val7'],
                    'q_admSett_dateUpdated' => date('Y-m-d'),
                ]);
            }else{
                $result = AdminSettings::create([
                    'q_admSett_lastUpdatedByID' => $request['user_id'],
                    'q_admSett_famProInflaRate' => $request['val1'],
                    'q_admSett_retInflationRate' => $request['val2'],
                    'q_admSett_retEstInterestRate' => $request['val3'],
                    'q_admSett_childEducInflaRate' => $request['val4'],
                    'q_admSett_estateConvCurrTaxRate' => $request['val5'],
                    'q_admSett_estateConvOtherExpenses' => $request['val6'],
                    'q_admSett_ageChildGoCollege' => $request['val7'],
                    'q_admSett_dateUpdated' => date('Y-m-d'),
                    'q_admSett_dateCreated' => date('Y-m-d'),
                ]);
            }
            if($result){ return 1;}
            else{ return 0; }
        }else if($op == 'newChildForFamComp'){
            $result = FamilyComposition::create([
                'q_famComp_clientID' => $request['client_id'],
                'q_famComp_firstName' => $request['fn'],
                'q_famComp_lastName' => $request['ln'],
                'q_famComp_middleInitial' => $request['mn'],
                'q_famComp_compType' => 2,
                'q_famComp_withWithoutChildren' => $request['fs'],
                'q_famComp_dateMarried' => null,
                'q_famComp_birthDay' => $request['bd'],
                'q_famComp_healthCondition' => $request['hc'],
                'q_famComp_status' => $request['status'],
                'q_famComp_revocableLiving' => null,
                'q_famComp_revocableLast' => null,
                'q_famComp_dateUpdated' => date('Y-m-d'),
                'q_famComp_dateCreated' => date('Y-m-d'),
            ]);
            return 1;
        }else if($op == 'educFundAllocated'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);

                    $res = $this->updateIntoDB('fundAllocatedEducPlanningFNA', $flattenedData, $request, null);
                    if($res){
                        $res = $flattenedData['table_id'];
                        $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                        $i++;
                        $success++;
                        // Inserting Logs
                        $this->recordSystemLogs($request['userCompleteName']." (ID no. ".$request['userID'].") " . " has successfully UPDATED ".$flattenedData['from_table']." for Education Planning FNA (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                        $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED ".$flattenedData['from_table']." for Education Planning FNA (ID no. ".$res.") for Client ");
                    }else{
                        $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg ];
                        $i++;
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'educFundGuaranteedPaySched'){
            try {
                $collectedErrorsAndSuccess = array();
                $message = ''; $success = 0; $i = 0; $counter = 0;
                foreach ($request['data'] as $index => $data) {
                    $flattenedData = $this->flattenData($data);

                    $res = $this->updateIntoDB('educFundGuaranteedPaySched', $flattenedData, $request, null);
                    if($res){
                        $res = $flattenedData['table_id'];
                        $collectedErrorsAndSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => "Successfully updated."];
                        $i++;
                        $success++;
                        // Inserting Logs
                        $this->recordSystemLogs($request['userCompleteName']." (ID no. ".$request['userID'].") " . " has successfully UPDATED ".$flattenedData['from_table']." for Guaranteed Payout Schedule of Education Planning FNA (ID no. ".$res.") for Client (ID no. ".$request['client_id'].").");
                        $this->advActivityLog($request['userID'], $request['client_id'], "You have successfully UPDATED ".$flattenedData['from_table']." for Guaranteed Payout Schedule of Education Planning FNA (ID no. ".$res.") for Client ");
                    }else{
                        $collectedErrorsAndSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => $this->consultAdminMsg ];
                        $i++;
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsAndSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($op == 'healthCovSum'){
            $result = HealthCovSumm::create([
                'q_healthCovSum_clientID' => $request['client_id'],
                'q_healthCovSum_famCompID' => $request['famComp_id'],
                'q_healthCovSum_type' => $request['type'],
                'q_healthCovSum_policyRefNo' => $request['policyRef_no'],
                'q_healthCovSum_origin' => $request['origin'],
                'q_healthCovSum_amtInPatient' => $request['amt_in_patient'],
                'q_healthCovSum_opInPatient' => $request['op_in_patient'],
                'q_healthCovSum_amtOutPatient' => $request['amt_out_patient'],
                'q_healthCovSum_opOutPatient' => $request['op_out_patient'],
                'q_healthCovSum_amtCritIllLim' => $request['amt_critical_illness_limit'],
                'q_healthCovSum_opCritIllLim' => $request['op_critical_illness_limit'],
                'q_healthCovSum_amtLabLim' => $request['amt_lab_limit'],
                'q_healthCovSum_amtHospIncome' => $request['amt_hosp_income'],
                'q_healthCovSum_maxNoDays' => $request['maxNoDays'],
                'q_healthCovSum_notes' => $request['notes'],
                'q_healthCovSum_dateUpdated' => date('Y-m-d'),
                'q_healthCovSum_dateCreated' => date('Y-m-d'),
            ]);
            return $result->id;
        }
        else{
            return $this->unexpectedErrorHasSuccess($this->unexpectedError, 500, 0);
        }
    }


    //      *****    ****        **      *********
    //      **       *     *     **          *
    //      *****    *      *    **          *
    //      **       *     *     **          *
    //      *****    ***         **          *

    public function updateData($table, $request){
        if($table == 'selected_financial_priorities'){
            try {
                $collectedErrorsSuccess = array();
                $i = 0; $counter = 0; $success = 0;
                foreach ($request as $index => $data) {
                    $flattenedData = collect($data)->flatMap(function ($values) {  // This code flattens the nested arrays in each set of data before running the validation. Adjust the validation rules and custom error messages based on your actual field names and requirements.
                        return $values;
                    })->toArray();
                    $countRecord = 0; $countRecordP2 = 0; $countRecordP3 = 0;
                    $countRecord = $this->checkAndCount('SelectedFinancialPriorities', $flattenedData, $request, null);
                    $countRecordP2 = $this->checkAndCount('SelectedFinancialPriorities_V2', $flattenedData, $request, null);
                    $countRecordP3 = $this->checkAndCount('SelectedFinancialPriorities_V3', $flattenedData, $request, null);
                    if($countRecord > 0 && $countRecordP2 > 0 && $countRecordP3 > 0){
                        $collectedErrorsSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => 'No changes can be made. Similar user with similar value on the field has been detected. Please try again.'];
                        $i++;
                    }else{
                        $err = 0;$scc = 0;
                        if($countRecord == 0){
                            $toSwap = SelectedFinancialPriorities::where('q_sfp_rank', $flattenedData['rank_number'])->where('q_sfp_clnt_id', $flattenedData['client_id'])->select(['q_sfp_id'])->first();
                            $oldRank = SelectedFinancialPriorities::where('q_sfp_id', $flattenedData['sfp_id'])->select(['q_sfp_rank'])->first();
                            if($toSwap && $oldRank){
                                SelectedFinancialPriorities::where('q_sfp_id', $flattenedData['sfp_id'])->update(['q_sfp_rank' => $flattenedData['rank_number']]);
                                SelectedFinancialPriorities::where('q_sfp_id', $toSwap['q_sfp_id'])->update(['q_sfp_rank' => $oldRank['q_sfp_rank']]);
                                $scc = 1;
                            }else{ $err = 1; }
                        }
                        if($countRecordP2 == 0){
                            SelectedFinancialPriorities::where('q_sfp_id', $flattenedData['sfp_id'])->update(['q_sfp_fp_id' => $flattenedData['priority_list_id']]);
                            $scc = 1;
                        }
                        if($countRecordP3 == 0){
                            SelectedFinancialPriorities::where('q_sfp_id', $flattenedData['sfp_id'])->update(['q_sfp_reason' => $flattenedData['reason']]);
                            $scc = 1;
                        }
                        if($err == 0 && $scc == 1){
                            $collectedErrorsSuccess[$i] = ['Success in set # ' . $counter+1 . ':' => 'Successfully updated.'];
                            $i++;
                            $success++;
                        }else{
                            $collectedErrorsSuccess[$i] = ['Error in set # ' . $counter+1 . ':' => 'No existing record based on the field submitted.'];
                            $i++;
                        }
                    }
                    $counter++;
                }
                return $this->getSuccessOperation($success > 0 ? 200 : 500, $collectedErrorsSuccess, $success, null);
            } catch (\Throwable $th) {
                return $this->thrownErrorInterruptedHasSuccess($this->interruptedError, 500, 0);
            }
        }else if($table == 'updateAgentVisitedLink'){
            return $this->updateIntoDB('visitedLinkOfAgent', null, $request, null);
        }else if($table == 'updateToDoResolved'){
            if($this->updateIntoDB('updateToDoToResolved', null, $request, null)){
                $this->recordSystemLogs("User (ID no. ".$request['user_id'].") " . " has successfully UPDATED data to Resolve To Do (ID no. ".$request['toDoID'].").");
                $this->advActivityLog($request['user_id'], null, "You have successfully UPDATED data to Resolve To Do (ID no. ".$request['toDoID'].").");
                return 1;
            }else{ return 0; }
        }else if($table == 'updatePlanSolToResolved'){
            if($this->updateIntoDB('updatePlanSolToResolved', null, $request, null)){
                $this->recordSystemLogs("User (ID no. ".$request['user_id'].") " . " has successfully UPDATED Financial Planning Solution to Resolve Annual Review (ID no. ".$request['finPlSo_id'].").");
                $this->advActivityLog($request['user_id'], null, "You have successfully UPDATED Financial Planning Solution to Resolve Annual Review (ID no. ".$request['finPlSo_id'].").");
                return 1;
            }else{ return 0; }
        }else{ return $this->unexpectedErrorHasSuccess($this->unexpectedError, 500, 0); }
    }


    //      ****        ******    *         ******   *********   ******
    //      *     *     *         *         *            *       *
    //      *      *    ****      *         ****         *       ****
    //      *     *     *         *         *            *       *
    //      ***         ******    ******    ******       *       ******

    public function deleteData($table, $id){
        $data = null;
        try {
            $data = $table == 'Agent' ? Agent::where('q_agnt_id', $id)->delete() : null;
            $data = !$data && $table == 'Client' ? Client::where('q_clnt_id', $id)->delete() : $data;
            return [ // successful operation...
                'http' => 200,
                'message' => $this->successOperation
            ];
        } catch (\Throwable $th) {
            return $this->thrownErrorInterrupted($this->interruptedError, 500);
        }
    }
}
