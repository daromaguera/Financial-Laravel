<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//   ******    *******   ***********
//  *          *              *
//  * *****    *****          *
//  *      *   *              *
//   ******    *******        *
// GET section
    // Verify User
    Route::get('verifyUser', [MainController::class, 'verifyWebUser']);

    // Agents
    Route::get('agents', [MainController::class, 'getAllAgents']);
    Route::get('agentsOrderFirstNameASC', [MainController::class, 'getAllAgentsOrderFirstNameASC']);
    Route::get('agentsOrderFirstNameDESC', [MainController::class, 'getAllAgentsOrderFirstNameDESC']);
    Route::get('agentsOrderLastNameASC', [MainController::class, 'getAllAgentsOrderLastNameASC']);
    Route::get('agentsOrderLastNameDESC', [MainController::class, 'getAllAgentsOrderLastNameDESC']);
    Route::get('searchFromAgents', [MainController::class, 'searchAllFromAgents']); // Both applicable on POST and GET
    Route::get('searchFromAgentsOnField', [MainController::class, 'searchFromAgentsOnField']); // Both applicable on POST and GET

    // Clients
    Route::get('individualClient', [MainController::class, 'getIndividualClient']);
    Route::get('clientsForFinPlanPres', [MainController::class, 'getClientsForFinPlanPres']);
    Route::get('selFinPrioAndClients', [MainController::class, 'getSelFinPrioAndClients']);
    Route::get('clients', [MainController::class, 'getAllClients']);
    Route::get('adm_clients', [MainController::class, 'adm_getAllClients']);
    Route::get('clientsOrderFirstNameASC', [MainController::class, 'getAllClientsOrderFirstNameASC']);
    Route::get('clientsOrderFirstNameDESC', [MainController::class, 'getAllClientsOrderFirstNameDESC']);
    Route::get('clientsOrderLastNameASC', [MainController::class, 'getAllClientsOrderLastNameASC']);
    Route::get('adm_clientsOrderLastNameASC', [MainController::class, 'adm_getAllClientsOrderLastNameASC']);
    Route::get('adm_clientsOrderLastNameDESC', [MainController::class, 'adm_getAllClientsOrderLastNameDESC']);
    Route::get('clientsOrderLastNameDESC', [MainController::class, 'getAllClientsOrderLastNameDESC']);
    Route::get('searchFromClients', [MainController::class, 'searchAllFromClients']);
    Route::get('adm_searchFromClients', [MainController::class, 'adm_searchAllFromClients']); // Both applicable on POST and GET
    Route::get('searchFromClientsOnField', [MainController::class, 'searchFromClientsOnField']);
    Route::get('clientChildren', [MainController::class, 'getClientChildren']);
    Route::get('clientSpouse', [MainController::class, 'getClientSpouse']);
    Route::get('clientPartner', [MainController::class, 'getClientPartner']);
    Route::get('clientFather', [MainController::class, 'getClientFather']);
    Route::get('clientMother', [MainController::class, 'getClientMother']);

    // Financial Priorities
    Route::get('financialPriority', [MainController::class, 'getAllFinancialPriority']);
    Route::get('selectedFinancialPriorities', [MainController::class, 'getAllSelectedFinancialPriorities']);
    
    // Cash Flow List
    Route::get('cashFlowList', [MainController::class, 'getCashFlowList']);
    Route::get('cashFlowListOrderDescriptionASC', [MainController::class, 'cashFlowListOrderDescriptionASC']);

    // Family Composition
    Route::get('familyComposition', [MainController::class, 'getFamilyComposition']);

    // Cash and Deposits
    Route::get('cashAndDeposit', [MainController::class, 'getCashAndDeposit']);
    
    // Receivables
    Route::get('receivables', [MainController::class, 'getReceivables']);

    // Mutual Funds / UITF
    Route::get('mutualFunds', [MainController::class, 'getMutualFunds']);

    // Bonds
    Route::get('bonds', [MainController::class, 'getBonds']);

    // Stocks in Companies (Listed or Non-Listed)
    Route::get('stocksInCompaniesListed', [MainController::class, 'getStocksInCompaniesListed']);
    Route::get('stocksInCompaniesNonListed', [MainController::class, 'getStocksInCompaniesNonListed']);

    // Family Life and Health Insurance (From Aetos Adviser or Not)
    Route::get('lifeHealthInsuranceAdviser', [MainController::class, 'getLifeHealthInsuranceAdviser']);
    Route::get('lifeHealthInsuranceNotAdviser', [MainController::class, 'getLifeHealthInsuranceNotAdviser']);

    // Family Home and Real Estate
    Route::get('familyHomesRealEstate', [MainController::class, 'getFamilyHomesRealEstate']);

    // Vehicles
    Route::get('vehicles', [MainController::class, 'getVehicles']);

    // Personal Assets
    Route::get('personalAssets', [MainController::class, 'getPersonalAssets']);

    // Liabilities
    Route::get('liabilities', [MainController::class, 'getLiabilities']);

    // Family Protection FNA
    Route::get('famProFNA', [MainController::class, 'getFamProFNA']);

    // Retirement Planning FNA
    Route::get('retPlanFNA', [MainController::class, 'getRetPlanFNA']);

    // Education Planning FNA
    Route::get('educPlanFNA', [MainController::class, 'getEducPlanFNA']);

    // Health Fund Planning FNA
    Route::get('healthFundPlanFNA', [MainController::class, 'getHealthFundPlanFNA']);

    // Education Fund List without Guaranteed Payout
    Route::get('educFundListWithoutPayout', [MainController::class, 'getEducFundListWithoutPayout']);

    // Education Fund List with Guaranteed Payout
    Route::get('educFundListWithPayout', [MainController::class, 'getEducFundListWithPayout']);

    // Retirement Fund Value without Guaranteed Payout
    Route::get('retFundValWithoutPayout', [MainController::class, 'getRetirementFundValueWithoutPayout']);

    // Retirement Fund Value with Guaranteed Payout
    Route::get('retFundValWithPayout', [MainController::class, 'getRetirementFundValueWithPayout']);

    // Admin Settings
    Route::get('getAdminSettings', [MainController::class, 'getAdminSettings']);

    // FNA Completion
    Route::get('FNACompletion', [MainController::class, 'getFNACompletion']);

    // Agent To Dos for Clients
    Route::get('agentToDosForClients', [MainController::class, 'getAgentToDosForClients']);

    // Agent To Dos for Clients Order By Client Last Name
    Route::get('agentToDosForClientsOrderByClients', [MainController::class, 'getAgentToDosForClientsOrderByClients']);

    // Agent To Dos for Clients Order By Client Last Name
    Route::get('agentToDosForClientsOrderDate', [MainController::class, 'getAgentToDosForClientsOrderDate']);

    // Search from the Agent To Dos for Clients
    Route::get('searchAgentToDos', [MainController::class, 'searchAgentToDos']);

    // Client for Annual View
    Route::get('clientAnnualReview', [MainController::class, 'getClientAnnualReview']);
    // Client for Annual View Order by Client Name
    Route::get('clientAnnualReviewByClientName', [MainController::class, 'getClientAnnualReviewByClientName']);
    // Client for Annual View Order by Client Name
    Route::get('clientAnnualReviewByDate', [MainController::class, 'getClientAnnualReviewByDate']);

    // Search Client for Annual View
    Route::get('searchClientAnnualReview', [MainController::class, 'searchClientAnnualReview']);

    // Overdue - For Notification - Agent and Client To Dos
    Route::get('overdueToDos', [MainController::class, 'getOverdueToDos']);

    // Advisor Activities
    Route::get('advisorActivities', [MainController::class, 'getAdvisorActivities']);
    
    // Education Planning FNA
    Route::get('allDataForEducPlanning', [MainController::class, 'getAllChildDataForEducPlanning']);
    Route::get('educInvestmentGuaranteedP', [MainController::class, 'getEducInvestmentGuaranteedP']);
    Route::get('getEducPlanningExp', [MainController::class, 'getEducPlanningExp']);

    // Retirement Planning FNA
    Route::get('retInvestmentGuaranteedP', [MainController::class, 'getRetirementInvestmentGuaranteedP']);

    // Financial Planning Solution
    Route::get('financialPlanSol', [MainController::class, 'getFinancialPlanSol']);

    // Getting all Data to initialize the front-end application
    Route::get('getAllDataToInitialize', [MainController::class, 'getAllDataToInitialize']);

    // Sync Clients from LINDA
    Route::get('syncClientsFromLinda', [MainController::class, 'syncClientsFromLinda']);

    // All Data for Networth Inventory
    Route::get('allDataNetworthInventory', [MainController::class, 'getAllDataNetworthInventory']);

//  *****      *****       ********    ***********
//  *     *   *     *     *                 *
//  *     *   *     *     ********          *
//  *****     *     *              *        *
//  *          *****       ********         *
// POST section
    // Health Coverage Summary For - Health FNA
    Route::post('saveHealthCovSumFor', [MainController::class, 'saveNewHealthCovSumFor']);

    // Health Fund Planning - FNA
    Route::post('healthFundPlanningFNA', [MainController::class, 'saveNewHealthFundPlanningFNA']);

    // Education Planning - FNA
    Route::post('educationPlanningFNA', [MainController::class, 'saveNewEducationPlanningFNA']);

    // Retirement - FNA
    Route::post('retirementPlanningFNA', [MainController::class, 'saveNewRetirementPlanningFNA']);

    // Family Protection - FNA
    Route::post('familyProtectionFNA', [MainController::class, 'saveNewFamProFNA']);

    // Financial Planning Solutions for all FNAs
    Route::post('financialPlanningSolutions', [MainController::class, 'saveNewFinnPlannSolx']);
 
    // Dreams and Aspiration
    Route::post('dreamsAspiration', [MainController::class, 'saveNewDreamsAspiration']);

    // Liabilities
    Route::post('newLiabilities', [MainController::class, 'saveNewLiabilities']);

    // Personal Assets
    Route::post('newPersonalAssets', [MainController::class, 'saveNewPersonalAssets']);

    // Vehicles
    Route::post('newVehicles', [MainController::class, 'saveNewVehicles']);
    // Real Estate
    Route::post('newRealEstate', [MainController::class, 'saveNewRealEstate']);

    // Family Home
    Route::post('newFamilyHome', [MainController::class, 'saveNewFamilyHome']);

    // Family Life and Health Insurance (From Aetos Adviser or Not)
    Route::post('newLifeHealthInsuranceAdviser', [MainController::class, 'saveNewLifeHealthInsuranceAdviser']);
    Route::post('newLifeHealthInsuranceNotAdviser', [MainController::class, 'saveNewLifeHealthInsuranceNotAdviser']);

    // Agents
    Route::post('searchFromAgents', [MainController::class, 'searchAllFromAgents']);
    Route::post('searchFromAgentsOnField', [MainController::class, 'searchFromAgentsOnField']);

    // Clients
    Route::post('searchFromClients', [MainController::class, 'searchAllFromClients']);
    Route::post('searchFromClientsOnField', [MainController::class, 'searchFromClientsOnField']);
    
    // Financial Priorities
    Route::post('addUpdateSelectedFinancialPriorities', [MainController::class, 'addUpdateSelFinPriorities']);

    // Cash Flow List
    Route::post('newCashFlowData', [MainController::class, 'saveNewSetOfCashFlow']);

    // Cash and Deposits
    Route::post('newCashAndDeposit', [MainController::class, 'saveNewCashAndDeposit']);
    
    // Family Composition
    Route::post('newFamilyComposition', [MainController::class, 'saveNewFamilyComposition']);
    Route::post('newChildFamComp', [MainController::class, 'saveNewChildFamComp']);

    // Receivables
    Route::post('newReceivables', [MainController::class, 'saveNewReceivables']);

    // Mutual Funds / UITF
    Route::post('newMutualFunds', [MainController::class, 'saveNewMutualFunds']);

    // Bonds
    Route::post('newBonds', [MainController::class, 'saveNewBonds']);

    // Stocks in Companies (Listed or Non-Listed)
    Route::post('newStockInCompaniesListed', [MainController::class, 'saveNewStockInCompaniesListed']);
    Route::post('newStockInCompaniesNonListed', [MainController::class, 'saveNewStockInCompaniesNonListed']);

    // Admin Settings
    Route::post('adminSettings', [MainController::class, 'saveAdminSettings']);

    // Logout
    Route::post('logout', [MainController::class, 'logoutUser']);


// PUT section
    // Agents
        // Route::put('Agent', [MainController::class, 'updateAgent']);
        Route::put('updateAgentLinkLastVisited', [MainController::class, 'updateAgentLinkLastVisited']);

    // Clients
        Route::put('updateClient', [MainController::class, 'updateClient']);
        Route::put('clientMakeActive', [MainController::class, 'clientMakeActive']);
        Route::put('clientMakeNonActive', [MainController::class, 'clientMakeNonActive']);

    // Financial Priorities
        // Route::put('updateFinancialPriorities', [MainController::class, 'updateFinancialPriorities']);

    // Financial Priorities
        // Route::put('updateSelectedFinancialPriorityRank', [MainController::class, 'updateSelFinPriRank']);

    // To Dos
        Route::put('updateToDoResolved', [MainController::class, 'updateToDoResolved']);

    // Financial Planning Solution
        Route::put('updatePlanSolResolved', [MainController::class, 'updatePlanSolResolved']);

    // Education Planning
        Route::put('updateEducPlanningForAllocatedFund', [MainController::class, 'updateEducPlanningForAllocatedFund']);
        Route::put('updateByGuaranteedPayoutSchedule', [MainController::class, 'updateByGuaranteedPayoutSchedule']);

// DELETE section
    Route::get('deleteEducPlanExp', [MainController::class, 'deleteEducPlanExp']);
    Route::post('deleteHeir', [MainController::class, 'deleteHeir']);
    // Agents.. Temporary disabled due to the fact that it's not necessary to delete agents
        // Route::delete('deleteAgent', [MainController::class, 'deleteAgent']);
    // Clients
        // Route::delete('deleteClient', [MainController::class, 'deleteClient']);



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Fallback route to handle unexpected requests
Route::fallback(function () {
    // You can customize the response if necessary
    return response()->json(['error' => 'Unexpected request'], 404);
});