<?php

/**
 * ADT API
 * Official & Core API for ADT  [ADT](http://adtcore.io)  Main API 
 *
 * OpenAPI spec version: 1.0.0
 * 
 *
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Dingo\Api\Routing\Helpers;

use App\Models\ListsModels\Allergies; 
use App\Models\ListsModels\Appointmet;
use App\Models\ListsModels\Category;
use App\Models\ListsModels\County;
use App\Models\ListsModels\Dependanat; 
use App\Models\ListsModels\Prophylaxis; 
use App\Models\ListsModels\Regimen; 
use App\Models\ListsModels\WhoStage; 
use App\Models\ListsModels\Illnesses; 
use App\Models\ListsModels\Sources; 
use App\Models\ListsModels\Pepreason; 
use App\Models\ListsModels\Familyplanning;
use App\Models\ListsModels\Services;
use App\Models\ListsModels\Sub_county;
use App\Models\ListsModels\ChangeReason;

class ListsApi extends Controller
{
    use Helpers;
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    // ///////////////////////
    // Allergies            //
    // //////////////////////

    /**
     * Operation listsAllergiesGet
     *
     * Fetch Regimen allergies (for select options).
     *
     *
     * @return Http response
     */
    public function listsAllergiesGet()
    {
        $response = Allergies::all(); 
         return response()->json($response, 200); 
    }

    /**
     * Operation listsAllergiesAllergyIdGet
     *
     * Fetch Allergy specified by allergyId.
     *
     * @param int $allergy_id ID of allergy (required)
     *
     * @return Http response
     */
    public function listsAllergiesByIdGet($allergy_id)
    {
        $response = Allergies::findOrFail($allergy_id);
        return response()->json($response, 200);
    }


    // ///////////////////////
    // Categories          //
    // //////////////////////

    /**
     * Operation listsCategoriesGet
     *
     * Fetch Regimen Categories (for select options).
     *
     *
     * @return Http response
     */
    public function listsCategoriesGet()
    {
        $response = Category::all();
        return response()->json($response, 200);
    }
    /**
     * Operation listsCategoriesCategoryIdGet
     *
     * Fetch Category specified by categoryId.
     *
     * @param int $category_id ID of Service that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsCategoriesByIdGet($category_id)
    {
        $category = Category::findOrFail($category_id);
        return response()->json($category, 200);
    }
    /**
     * Operation listsCategoriesPost
     *
     * create a Category.
     *
     *
     * @return Http response
     */
    public function listsCategoriesPost()
    {
        $input = Request::all();
        $new_catgory = Category::create($input);
        if($new_catgory){
            return response()->json(['msg' => 'Created Category'], 200);
        }else{
            return response('Oops, seems like something went wrong while trying to create a category');
        }
    }

    /**
     * Operation listsCategoriesCategoryIdPut
     *
     * Update an existing Category.
     *
     * @param int $category_id ID of Service that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsCategoriesPut($category_id)
    {
        $input = Request::all();
        $category = Category::findOrFail($category_id);
        $category->update(['name' => $input['name']]);
        if($category->save()){
            return response()->json(['msg' => 'Updated Category'], 200);
        }else{
            return response('Oops, seems like something went wrong while trying to update a category');
        }
    }
    /**
     * Operation listsCategoriesCategoryIdDelete
     *
     * Deletes a Category specified by serviceId.
     *
     * @param int $category_id ID of Service that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsCategoriesDelete($category_id)
    {
        Category::destroy($category_id);
        return response()->json(['msg' => 'deleted Category'], 200);
    }

    // ///////////////////////
    // Counties            //
    // //////////////////////
    /**
     * Operation listsCountiesGet
     *
     * Fetch counties (for select options).
     *
     *
     * @return Http response
     */
    public function listsCountiesGet()
    {
        $response = County::all();
        return response()->json($response, 200);
    }
    /**
     * Operation listsCountiesCountyIdGet
     *
     * Fetch County specified by countyId.
     *
     * @param int $county_id ID of county that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsCountiesByIdGet($county_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsCountiesCountyIdGet as a GET method ?');
    }
    /**
     * Operation listsCountiesCountyIdDelete
     *
     * Deletes a County specified by countyId.
     *
     * @param int $county_id ID of county that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsCountiesDelete($county_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsCountiesCountyIdDelete as a DELETE method ?');
    }

    // ///////////////////////
    //CountiesSubcounties  //
    // //////////////////////
    /**
     * Operation listsCountiesCountyIdSubcountiesGet
     *
     * Fetch counties (for select options).
     *
     * @param int $county_id ID of County that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsCountiesSubcountiesGet($county_id)
    {
        $response = Sub_county::all();
        return response()->json($response, 200);
    }
    /**
     * Operation listsCountiesCountyIdSubcountiesSubcountyIdGet
     *
     * Fetch County specified by countyId.
     *
     * @param int $county_id ID of county (required)
     * @param int $subcounty_id ID of subcounty (required)
     *
     * @return Http response
     */
    public function listsCountiesSubcountiesByIdGet($county_id, $subcounty_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsCountiesCountyIdSubcountiesSubcountyIdGet as a GET method ?');
    }

    /**
     * Operation listsCountiesCountyIdSubcountiesSubcountyIdDelete
     *
     * Deletes a SubCounty specified by subcountyId in a County specified by countyId.
     *
     * @param int $county_id ID of county (required)
     * @param int $subcounty_id ID of subcounty (required)
     *
     * @return Http response
     */
    public function listsCountiesSubcountiesDelete($county_id, $subcounty_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsCountiesCountyIdSubcountiesSubcountyIdDelete as a DELETE method ?');
    }

    // ///////////////////////
    //Family planning      //
    // //////////////////////

    /**
     * Operation listslGet
     *
     * Fetch list of Family Planning (for select options).
     *
     *
     * @return Http response
     */
    public function listsFamilyplanningGet()
    {
        $response = Familyplanning::all();
        return response()->json($response, 200);
    }
    /**
     * Operation listsFamilyplanningFamilyplanningIdGet
     *
     * Fetch a FamilyPlanning item specified by familyplanningId.
     *
     * @param int $familyplanning_id ID of FamilyPlanning item (required)
     *
     * @return Http response
     */
    public function listsFamilyplanningByIdGet($familyplanning_id)
    {
        $response = FamilyPlanning::findOrFail();
        return response()->json($response, 200);
    }
    /**
     * Operation listsFamilyplanningPost
     *
     * create a FamilyPlanning item.
     *
     *
     * @return Http response
     */
    public function listsFamilyplanningPost()
    {
        $input = Request::all();
        $new_familyPlan = FamilyPlanning::create($input);
        if($new_familyPlan){
            return response()->json(['msg' => 'Created a new Family plan']);
        }else{
            return response('Oops, seems like something went wrong while trying to create a new family plan');
        }
    }
    /**
     * Operation listsFamilyplanningFamilyplanningIdPut
     *
     * Update an existing FamilyPlanning item.
     *
     * @param int $familyplanning_id ID of FamilyPlanning item (required)
     *
     * @return Http response
     */
    public function listsFamilyplanningPut($familyplanning_id)
    {
        $input = Request::all();
        $family_plan = FamilyPlanning::findOrFail($familyplanning_id);
        $family_plan->update(['name' => $input['name']]);
        if($family_plan->save()){
            return response()->json(['msg' => 'Updated family plan'], 200);
        }else{
            return response('Oops, seems like something went wrong while trying to update a plan');
        }
    }
    /**
     * Operation listsFamilyplanningFamilyplanningIdDelete
     *
     * Deletes a FamilyPlanning item specified by familyplanningId.
     *
     * @param int $familyplanning_id ID of FamilyPlanning item (required)
     *
     * @return Http response
     */
    public function listsFamilyplanningDelete($familyplanning_id)
    {
        FamilyPlanning::destroy($familyplanning_id);
        return response()->json(['msg' => 'deleted family plan'], 200);
    }


    // ///////////////////////
    //  Illnesses           //
    // //////////////////////

    /**
     * Operation listsIllnessesGet
     *
     * Fetch list of Illnessess(for select options).
     *
     *
     * @return Http response
     */
    public function listsIllnessesGet()
    {
        $response = Illnesses::all();
        return response()->json($response, 200);
    }
    /**
     * Operation listsIllnessesIllnessIdGet
     *
     * Fetch a Illness specified by illnessId.
     *
     * @param int $illness_id ID of FamilyPlanning item (required)
     *
     * @return Http response
     */
    public function listsIllnessesByIdGet($illness_id)
    {
        $response = Illnesses::findOrFail($illness_id);
        return response()->json($response, 200);
    }
    /**
     * Operation listsIllnessesPost
     *
     * Add an illness.
     *
     *
     * @return Http response
     */
    public function listsIllnessesPost()
    {
        $input = Request::all();
        $new_illness = Illnesses::create($input);
        if($new_illness){
            return response()->json(['msg' => 'Created new illness'],200);
        }else{
            return response('Oops, it seems like something went wrong while trying to create a new illness');
        }
    }
    /**
     * Operation listsIllnessesIllnessIdPut
     *
     * Update an existing Illness specified by illnessId.
     *
     * @param int $illness_id ID of FamilyPlanning item (required)
     *
     * @return Http response
     */
    public function listsIllnessesPut($illness_id)
    {
        $input = Request::all();
        $illness = Illnesses::findOrFail($illness_id);
        $illness->update(['name' => $input['name']]);
        if($illness->save()){
            return response()->json(['msg' => 'Updated Illness'], 200);
        }else{
            return response('Oops, it seems like something went wrong while trying to update the illness');
        }
    }
    /**
     * Operation listsIllnessesIllnessIdDelete
     *
     * Deletes a FamilyPlanning item specified by familyplanningId.
     *
     * @param int $illness_id ID of FamilyPlanning item (required)
     *
     * @return Http response
     */
    public function listsIllnessesDelete($illness_id)
    {
        Illnesses::destroy($illness_id);
        return response()->json(['msg' => 'Deleted illness']);
    }


    // ///////////////////////
    // Services            //
    // //////////////////////

    /**
     * Operation listsServicesGet
     *
     * Fetch Drug Allergies  (for select options).
     *
     *
     * @return Http response
     */
    public function listsServicesGet()
    {
        $response = Services::with('regimen')->get();
        return response()->json($response,200);
    }
    /**
     * Operation listsServicesServiceIdGet
     *
     * Fetch Service specified by serviceId.
     *
     * @param int $service_id ID of Service that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsServicesByIdGet($service_id)
    {
        $response = Services::findOrFail($service_id);
        return response()->json($response,200);
    }
    /**
     * Operation listsServicesPost
     *
     * create a service.
     *
     *
     * @return Http response
     */
    public function listsServicesPost()
    {
        $input = Request::all();
        $new_serivce = Services::create($input);
        if($new_serivce){
            return response()->json(['msg'=> 'Created a new service'],200);
        }else{
            return response('Oops, seems like something went wrong while trying to create a new service');
        }
    }
    /**
     * Operation listsServicesServiceIdPut
     *
     * Update an existing Service.
     *
     * @param int $service_id ID of Service that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsServicesPut($service_id)
    {
        $input = Request::all();
        $service = Services::findOrFail($service_id);
        $service->update(['name' => $input['name']]);
        if($service->save()){
            return response()->json(['msg' => 'Updated service']);
        }else{
            return response('Oops, it seems like somthing went wrong while trying to update the servie');
        }
    }
    /**
     * Operation listsServicesServiceIdDelete
     *
     * Deletes a service specified by serviceId.
     *
     * @param int $service_id ID of Service that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsServicesDelete($service_id)
    {
        $serive = Services::destroy($service_id);
        if($serive){
            return response()->json(['msg' => 'Deleted the servie']);
        }else{
            return response('Oops, seems like something went wrong while deleting the service');
        }
    }

    // ///////////////////////////
    // Changereasons functions //
    // /////////////////////////

    /**
     * Operation listsChangereasonGet
     *
     * Fetch Change Reasons (for select options).
     *
     *
     * @return Http response
     */
    public function listsChangereasonget()
    {
        $response = ChangeReason::all();
        return response()->json($response, 200);
    }
    /**
     * Operation listsChangereasonChangereasonIdGet
     *
     * Fetch Change Reason specified by changereasonId.
     *
     * @param int $changereason_id ID of Change Reason that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsChangereasonByIdget($changereason_id)
    {
        $response = ChangeReason::findOrFail($changereason_id);
        if($response){
            return response()->json($response, 200);
        }else{
            return $this->response->errorNotFound();
        }
    }
    /**
     * Operation listsChangereasonPost
     *
     * create a Change Reason.
     *
     *
     * @return Http response
     */
    public function listsChangereasonpost()
    {
        $input = Request::all();
        $new_reason = ChangeReason::create($input);
        if($new_reason){
            return $this->response->created();
        }else{
            return $this->response->errorBadRequest(); 
        }
    }

    /**
     * Operation listsChangereasonChangereasonIdPut
     *
     * Update an existing Change Reason.
     *
     * @param int $changereason_id ID of Change Reason that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsChangereasonput($changereason_id)
    {
        $input = Request::all();
        $reason = ChangeReason::findOrFail($changereason_id);
        $reason->update([ 'name' => $input['name'] ]);
        if($reason->save()){
            return response()->json(['msg' => 'Updated change']);
        }else{
            return response('Oops, seems like something went wrong while trying to update reason');
        }
    }
    /**
     * Operation listsChangereasonChangereasonIdDelete
     *
     * Deletes a Change Reason specified by changereasonId.
     *
     * @param int $changereason_id ID of Change Reason that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsChangereasondelete($changereason_id)
    {
        $deleted_change = ChangeReason::destroy($changereason_id);
        if($deleted_change){
            return response()->json(['msg'=>'deleted reason']);
        }
    }

    // ///////////////////////////
    // Generic functions       //
    // /////////////////////////

    /**
     * Operation listsGenericGet
     *
     * Fetch list of Generic items(for select options).
     *
     *
     * @return Http response
     */
    public function listsGenericget()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $name = $input['name'];


        return response('How about implementing listsGenericGet as a GET method ?');
    }
    /**
     * Operation listsGenericGenericIdGet
     *
     * Fetch a Generic item specified by genericId.
     *
     * @param int $generic_id ID of Generic item (required)
     *
     * @return Http response
     */
    public function listsGenericgenericByIdget($generic_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsGenericGenericIdGet as a GET method ?');
    }
    /**
     * Operation listsGenericPost
     *
     * Add an generic item.
     *
     *
     * @return Http response
     */
    public function listsgenericpost()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $body = $input['body'];


        return response('How about implementing listsGenericPost as a POST method ?');
    }
    /**
     * Operation listsGenericGenericIdPut
     *
     * Update an existing Generic item specified by genericId.
     *
     * @param int $generic_id ID of Generic item (required)
     *
     * @return Http response
     */
    public function listsgenericGenericput($generic_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsGenericGenericIdPut as a PUT method ?');
    }
    /**
     * Operation listsGenericGenericIdDelete
     *
     * Deletes a Generic item specified by genericId.
     *
     * @param int $generic_id ID of Generic item (required)
     *
     * @return Http response
     */
    public function listsGenericdelete($generic_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsGenericGenericIdDelete as a DELETE method ?');
    }

    // /////////////////////////////
    // Drug instruction functions//
    // ///////////////////////////

    /**
     * Operation listsInstructionGet
     *
     * Fetch list ofInstructionsIllnessess(for select options).
     *
     *
     * @return Http response
     */
    public function listsInstructionget()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $name = $input['name'];


        return response('How about implementing listsInstructionGet as a GET method ?');
    }
    /**
     * Operation listsInstructionInstructionIdGet
     *
     * Fetch a Instruction specified by instructionId.
     *
     * @param int $instruction_id ID of Instruction item (required)
     *
     * @return Http response
     */
    public function listsInstructionByIdget($instruction_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsInstructionInstructionIdGet as a GET method ?');
    }
    /**
     * Operation listsInstructionPost
     *
     * Add an illness.
     *
     *
     * @return Http response
     */
    public function listsInstructionpost()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $body = $input['body'];


        return response('How about implementing listsInstructionPost as a POST method ?');
    }
    /**
     * Operation listsInstructionInstructionIdPut
     *
     * Update an existing Illness specified by instructionId.
     *
     * @param int $instruction_id ID of Instruction item (required)
     *
     * @return Http response
     */
    public function listsInstructionput($instruction_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsInstructionInstructionIdPut as a PUT method ?');
    }
    /**
     * Operation listsInstructionInstructionIdDelete
     *
     * Deletes a Instruction item specified by instructionId.
     *
     * @param int $instruction_id ID of Instruction item (required)
     *
     * @return Http response
     */
    public function listsInstructiondelete($instruction_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsInstructionInstructionIdDelete as a DELETE method ?');
    }

    // /////////////////////////////
    // Nonaadherence functions   //
    // ///////////////////////////

    /**
     * Operation listsNonaadherencereasonGet
     *
     * Fetch Non-Adherence Reasons  (for select options).
     *
     *
     * @return Http response
     */
    public function listsNonaadherencereasonget()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $name = $input['name'];


        return response('How about implementing listsNonaadherencereasonGet as a GET method ?');
    }

    /**
     * Operation listsNonadherenceNonadherenceIdGet
     *
     * Fetch Non-Adherence Reason specified by nonadherenceId.
     *
     * @param int $nonadherence_id ID of Non-Adherence Reason that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsNonadherencebyIdget($nonadherence_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsNonadherenceNonadherenceIdGet as a GET method ?');
    }
    /**
     * Operation listsNonaadherencereasonPost
     *
     * create a Non-Adherence Reason.
     *
     *
     * @return Http response
     */
    public function listsNonaadherencereasonpost()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $name = $input['name'];


        return response('How about implementing listsNonaadherencereasonPost as a POST method ?');
    }
    /**
     * Operation listsNonadherenceNonadherenceIdPut
     *
     * Update an existing Non-Adherence Reason.
     *
     * @param int $nonadherence_id ID of Non-Adherence Reason that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsNonadherenceput($nonadherence_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsNonadherenceNonadherenceIdPut as a PUT method ?');
    }
    /**
     * Operation listsNonadherenceNonadherenceIdDelete
     *
     * Deletes a Non-Adherence Reason specified by nonadherenceId.
     *
     * @param int $nonadherence_id ID of Non-Adherence Reason that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsNonadherencedelete($nonadherence_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsNonadherenceNonadherenceIdDelete as a DELETE method ?');
    }

    // /////////////////////////////
    // Sources functions         //
    // ///////////////////////////

    /**
     * Operation listsPatientsourcesGet
     *
     * Fetch Sources list  (for select options).
     *
     *
     * @return Http response
     */
    public function listsPatientsourcesget()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $name = $input['name'];


        return response('How about implementing listsPatientsourcesGet as a GET method ?');
    }
    /**
     * Operation listsPatientsourcesPatientsourcesIdGet
     *
     * Fetch Source specified by patientsourcesId.
     *
     * @param int $patientsources_id ID of Source that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsPatientsourcesByIdget($patientsources_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsPatientsourcesPatientsourcesIdGet as a GET method ?');
    }
    /**
     * Operation listsPatientsourcesPost
     *
     * create a Source.
     *
     *
     * @return Http response
     */
    public function listsPatientsourcespost()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $name = $input['name'];


        return response('How about implementing listsPatientsourcesPost as a POST method ?');
    }
    /**
     * Operation listsPatientsourcesPatientsourcesIdPut
     *
     * Update an existing Source.
     *
     * @param int $patientsources_id ID of Source that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsPatientsourcesput($patientsources_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsPatientsourcesPatientsourcesIdPut as a PUT method ?');
    }
    /**
     * Operation listsPatientsourcesPatientsourcesIdDelete
     *
     * Deletes a Source specified by patientsourcesId.
     *
     * @param int $patientsources_id ID of Source that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsPatientsourcesdelete($patientsources_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsPatientsourcesPatientsourcesIdDelete as a DELETE method ?');
    }

    // /////////////////////////////
    // Pepreason functions       //
    // ///////////////////////////

    /**
     * Operation listsPepreasonGet
     *
     * Fetch PEP Reasons  (for select options).
     *
     *
     * @return Http response
     */
    public function listsPepreasonget()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $name = $input['name'];


        return response('How about implementing listsPepreasonGet as a GET method ?');
    }
    /**
     * Operation listsPepreasonPepreasonIdGet
     *
     * Fetch PEP Reason specified by pepreasonId.
     *
     * @param int $pepreason_id ID of PEP Reason that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsPepreasonByIdget($pepreason_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsPepreasonPepreasonIdGet as a GET method ?');
    }

    /**
     * Operation listsPepreasonPost
     *
     * create a PEP Reason.
     *
     *
     * @return Http response
     */
    public function listsPepreasonpost()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $name = $input['name'];


        return response('How about implementing listsPepreasonPost as a POST method ?');
    }
    /**
     * Operation listsPepreasonPepreasonIdPut
     *
     * Update an existing PEP Reason.
     *
     * @param int $pepreason_id ID of PEP Reason that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsPepreasonput($pepreason_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsPepreasonPepreasonIdPut as a PUT method ?');
    }
    /**
     * Operation listsPepreasonPepreasonIdDelete
     *
     * Deletes a PEP Reason specified by pepreasonId.
     *
     * @param int $pepreason_id ID of PEP Reason that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsPepreasondelete($pepreason_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsPepreasonPepreasonIdDelete as a DELETE method ?');
    }

    // /////////////////////////////
    // Prophylaxis functions     //
    // ///////////////////////////

    /**
     * Operation listsProphylaxisGet
     *
     * Fetch Prophylaxis  (for select options).
     *
     *
     * @return Http response
     */
    public function listsProphylaxisget()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $name = $input['name'];


        return response('How about implementing listsProphylaxisGet as a GET method ?');
    }
    /**
     * Operation listsProphylaxisProphylaxisIdGet
     *
     * Fetch Prophylaxis specified by prophylaxisId.
     *
     * @param int $prophylaxis_id ID of Prophylaxis that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsProphylaxisByIdget($prophylaxis_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsProphylaxisProphylaxisIdGet as a GET method ?');
    }
    /**
     * Operation listsProphylaxisPost
     *
     * create a Prophylaxis.
     *
     *
     * @return Http response
     */
    public function listsProphylaxispost()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $name = $input['name'];


        return response('How about implementing listsProphylaxisPost as a POST method ?');
    }
    /**
     * Operation listsProphylaxisProphylaxisIdPut
     *
     * Update an existing Prophylaxis.
     *
     * @param int $prophylaxis_id ID of Prophylaxis that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsProphylaxisput($prophylaxis_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsProphylaxisProphylaxisIdPut as a PUT method ?');
    }
    /**
     * Operation listsProphylaxisProphylaxisIdDelete
     *
     * Deletes a Prophylaxis specified by prophylaxisId.
     *
     * @param int $prophylaxis_id ID of Prophylaxis that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsProphylaxisdelete($prophylaxis_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsProphylaxisProphylaxisIdDelete as a DELETE method ?');
    }

    // /////////////////////////////
    // Purpose functions         //
    // ///////////////////////////
    /**
     * Operation listsPurposeGet
     *
     * Fetch Purpose list  (for select options).
     *
     *
     * @return Http response
     */
    public function listsPurposeget()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $name = $input['name'];


        return response('How about implementing listsPurposeGet as a GET method ?');
    }
    /**
     * Operation listsPurposePurposeIdGet
     *
     * Fetch Purpose specified by purposeId.
     *
     * @param int $purpose_id ID of Purpose that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsPurposeByIdget($purpose_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsPurposePurposeIdGet as a GET method ?');
    }
    /**
     * Operation listsPurposePost
     *
     * create a Purpose.
     *
     *
     * @return Http response
     */
    public function listsPurposepost()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $name = $input['name'];


        return response('How about implementing listsPurposePost as a POST method ?');
    }
    /**
     * Operation listsPurposePurposeIdPut
     *
     * Update an existing Purpose.
     *
     * @param int $purpose_id ID of Purpose that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsPurposeput($purpose_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsPurposePurposeIdPut as a PUT method ?');
    }
    /**
     * Operation listsPurposePurposeIdDelete
     *
     * Deletes a Purpose specified by purposeId.
     *
     * @param int $purpose_id ID of Purpose that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsPurposedelete($purpose_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsPurposePurposeIdDelete as a DELETE method ?');
    }
    // /////////////////////////////
    // Whostage functions        //
    // ///////////////////////////
    /**
     * Operation listsWhostageGet
     *
     * Fetch Drug Allergies  (for select options).
     *
     *
     * @return Http response
     */
    public function listsWhostageget()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $name = $input['name'];


        return response('How about implementing listsWhostageGet as a GET method ?');
    }
    /**
     * Operation listsWhostageWhostageIdGet
     *
     * Fetch a list of WHO stages specified by whostageId.
     *
     * @param int $whostage_id ID of Service that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsWhostageByIdget($whostage_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsWhostageWhostageIdGet as a GET method ?');
    }
    /**
     * Operation listsWhostagePost
     *
     * create a service.
     *
     *
     * @return Http response
     */
    public function listsWhostagepost()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $name = $input['name'];


        return response('How about implementing listsWhostagePost as a POST method ?');
    }
    /**
     * Operation listsWhostageWhostageIdPut
     *
     * Update an existing Who Stage.
     *
     * @param int $whostage_id ID of Service that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsWhostageput($whostage_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsWhostageWhostageIdPut as a PUT method ?');
    }
    /**
     * Operation listsWhostageWhostageIdDelete
     *
     * Deletes a service specified by whostageId.
     *
     * @param int $whostage_id ID of Service that needs to be fetched (required)
     *
     * @return Http response
     */
    public function listsWhostagedelete($whostage_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing listsWhostageWhostageIdDelete as a DELETE method ?');
    }


    // ///////////////////////
    // Temp functions      //
    // //////////////////////
    public function prophylaxis()
    {
        $response = Prophylaxis::all();
        return response()->json($response, 200);
    }
    public function pep()
    {
       $response = Pepreason::all();
       return response()->json($response, 200);
    }
    public function whoStage()
    {
        $response = WhoStage::all();
        return response()->json($response, 200);
    }
    public function patientSources()
    {
        $response = Sources::all();
        return response()->json($response, 200);
    }

    public function sub_county(){
        $response = Sub_county::all();
        return response()->json($response, 200);
    }
}
