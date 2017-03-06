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
use Illuminate\Pagination\Paginator;
use App\Models\DrugModels\Drug;
use App\Models\DrugModels\Regimen;
use App\Models\DrugModels\RegimenDrug;

class DrugsApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Operation drugsGet
     *
     * fetches a list of services at a facility.
     *
     *
     * @return Http response
     */
    public function drugsget()
    {
        $response = Drug::paginate(10);
        return response()->json($response, 200);
    }
    /**
     * Operation drugsDrugIdGet
     *
     * Find drug by drugId.
     *
     * @param int $drug_id Particular Service at facility specified by the ID (required)
     *
     * @return Http response
     */
    public function drugsByIdget($drug_id)
    {
        $response = Drug::findOrFail($drug_id);
        return response()->json($response, 200);
    }
    /**
     * Operation drugsPost
     *
     * Add a new service to the facility.
     *
     *
     * @return Http response
     */
    public function drugspost()
    {
        $input = Request::all();
        $new_drug = Drug::create($input);
        if($new_drug){
            return response()->json(['msg' => 'Added new drug', 'drug' => $new_drug],201);
        }
        return response()->json(['msg' => 'could not save drug'],400);
    }
    /**
     * Operation drugsDrugIdPut
     *
     * Update an existing drug specified by the drugId.
     *
     * @param int $drug_id Particular Service at facility specified by the ID (required)
     *
     * @return Http response
     */
    public function drugsput($drug_id)
    {
        $drug = Drug::findOrFail($drug_id);
        $input = Request::all();
        $drug->name = $input['name'];
        $drug->pack_size = $input['pack_size'];
        $drug->duration = $input['duration'];
        $drug->quantity = $input['quantity'];
        $drug->is_arv = $input['is_arv'];
        $drug->is_tb = $input['is_tb'];
        $drug->unit_id = $input['unit_id'];
        $drug->dose_id = $input['dose_id'];
        $drug->generic_id = $input['generic_id'];
        $drug->supporter_id = $input['supporter_id'];
        if($drug->save()){
            return response()->json(['msg' => 'updated drug', 'updated drug' => $drug],202);
        }else{
            return response()->json(['msg' => 'Could not update'], 400);
        }
    }
    /**
     * Operation drugsDrugIdDelete
     *
     * Deletes the drug specified by drugId.
     *
     * @param int $drug_id Particular Service at facility specified by the ID (required)
     *
     * @return Http response
     */
    public function drugsdelete($drug_id)
    {
        $deleted_drug = Drug::destroy($drug_id);
        if($deleted_drug){
            return response()->json(['msg' => 'Deleted drug'],301);
        }
    }

    // regimen
    /**
     * Operation regimenGET
     *
     * Fetch a regimen.
     *
     *
     * @return Http response
     */
    public function regimenget()
    {
        $response = Regimen::get();
        if(!$response){  
            return response()->json(['msg' => 'could not find regimen'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation regimens
     *
     * Fetch a regimenType.
     *
     
     * @param int $regimen_id ID&#39;s of visit that needs to be fetched (required)
     * @param int $ ID regimen that needs to be fetched (required)
     *
     * @return Http response
     */
    public function regimenByIdget($regimen_id)
    {
        $response = Regimen::findOrFail($regimen_id);
        if(!$response){  
            return response()->json(['msg' => 'could not find regimen'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation addregimens
     *
     * Add a new regimen to a visit.
     *
     * @param int $regimen_id ID&#39;s of visit (required)
     *
     * @return Http response
     */
    public function regimenpost()
    {
        $input = Request::all();
        $new_regimen = Regimen::create($input);
        if($new_regimen){
            return response()->json(['msg'=> 'added regimen', 'response'=> $new_regimen], 201);
        }else{
            return response()->json(['msg'=> 'Could not add regimen'], 400);
        }
    }

    /**
     * Operation updateregimens
     *
     * Update an existing regimens .
     *
     * @param int $regimen_id visit id to update (required)
     * @param int $ regimen id to update (required)
     *
     * @return Http response
     */
    public function regimenput($regimen_id)
    {
        $input = Request::all();
        $regimen = Regimen::findOrFail($regimen_id)->update([
                                        "code" => $input['code'],
                                        "name" => $input['name'],
                                        "service_id" => $input['service_id'],
                                        "category_id" => $input['category_id']
                                    ]);
        if($regimen){
            return response()->json(['msg' => 'Updated regimen'], 201);
        }else{
            return response()->json(['msg' => 'Could not update record'], 405);
        }
    }

    /**
     * Operation deleteregimen
     *
     * Remove a regimen.
     *
     * @param int $regimen_id ID&#39;s of visit and tb that needs to be fetched (required)
     * @param int $ ID of tb that needs to be fetched (required)
     *
     * @return Http response
     */
    public function regimendelete($regimen_id)
    {
        $regimen = Regimen::destroy($regimen_id);
        if($regimen){
            return response()->json(['msg' => 'Saftly deleted the regimen'],200);
        }else{
            return response()->json(['msg' => 'Could not delete record'], 400);
        }
    }


    // regimen drugs 

    /**
     * Operation regimenDrugGet
     *
     * fetches a list of services at a facility.
     *
     *
     * @return Http response
     */
    public function regimenDrugget($regimen_id)
    {
        $response = RegimenDrug::where('regimen_id', $regimen_id)->get();
        $response->load('drug');
        return response()->json($response, 200);
    }
    /**
     * Operation regimenDrugDrugIdGet
     *
     * Find drug by drugId.
     *
     * @param int $drug_id Particular Service at facility specified by the ID (required)
     *
     * @return Http response
     */
    public function regimenDrugByIdget($regimen_id, $drug_id)
    {
        $response = RegimenDrug::where('regimen_id', $regimen_id)->where('drug_id', $drug_id)->get();
        return response()->json($response, 200);
    }
    /**
     * Operation regimenDrugPost
     *
     * Add a new service to the facility.
     *
     *
     * @return Http response
     */
    public function regimenDrugpost($regimen_id)
    {
        $input = Request::all();
        $new_regimen_drug = RegimenDrug::create($input);
        if($new_regimen_drug){
            return response()->json(['msg' => 'Added new drug to regimen', 'drug' => $new_regimen_drug],201);
        }
        return response()->json(['msg' => 'could not save drug to regimen'],400);
    }
    /**
     * Operation regimenDrugDrugIdPut
     *
     * Update an existing drug specified by the drugId.
     *
     * @param int $drug_id Particular Service at facility specified by the ID (required)
     *
     * @return Http response
     */
    public function regimenDrugput($regimen_id, $drug_id)
    {
        $input = Request::all();
        $regimen_drug = RegimenDrug::where('regimen_id', $regimen_id)->where('drug_id', $drug_id)
                            ->update([
                                "drug_id" => $input['drug_id'],
                                "source" => $input['source'],
                                "ccc_store_sp" => $input['ccc_store_sp']
                            ]);
        if($regimen_drug){
            return response()->json(['msg' => 'updated drug', 'updated drug' => $regimen_drug],202);
        }else{
            return response()->json(['msg' => 'Could not update'], 400);
        }
    }
    /**
     * Operation regimenDrugDrugIdDelete
     *
     * Deletes the drug specified by drugId.
     *
     * @param int $drug_id Particular Service at facility specified by the ID (required)
     *
     * @return Http response
     */
    public function regimenDrugdelete($regimen_id, $drug_id)
    {
        $deleted_regimen_drug = RegimenDrug::where('regimen_id', $regimen_id)->where('drug_id', $drug_id)->delete();
        if($deleted_regimen_drug){
            return response()->json(['msg' => 'Deleted drug'],301);
        }
    }

    

}
