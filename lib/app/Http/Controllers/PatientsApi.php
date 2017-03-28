<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Requests;
use Illuminate\Pagination\Paginator;

use App\Models\PatientModels\Patient;
use App\Models\PatientModels\PatientFamilyPlanning;
use App\Models\PatientModels\PatientDependant;
use App\Models\PatientModels\PatientDrugAllergyOther;
use App\Models\PatientModels\PatientAllergies;
use App\Models\PatientModels\PatientDrugOther;
use App\Models\PatientModels\PatientIllness;
use App\Models\PatientModels\PatientIllnessOther;
use App\Models\PatientModels\PatientPartner;
use App\Models\PatientModels\PatientProphylaxis;
use App\Models\PatientModels\PatientRegimen;
use App\Models\PatientModels\PatientStatus;
use App\Models\PatientModels\PatientTb;
use App\Models\PatientModels\PatientViralload;

use App\Models\VisitModels\Appointment;
use App\Models\VisitModels\Visit;
// 
use App\Events\CreatePatientEvent;
use App\Events\UpdatePatientEvent;
use App\Events\DispensePatientEvent;

class PatientsApi extends Controller
    {
        /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Operation patientsGet
     *
     * get's a list of patients.
     *
     *
     * @return Http response
     */
    public function patientsGet()
    {
        $response = Patient::get();
        // $response->load('current_status');
        return response()->json($response, 200);
    }
    // check if ccc_number number is in use
    public function check_ccc_number($ccc_number){
        $check = Patient::where('ccc_number', $ccc_number)->count();
        if($check > 0){
            return response()->json(['msg' => 'true'],200);
        }else{
           return response()->json(['msg' => 'false'],200);
        }
    }
    /**
     * Operation getPatientById
     *
     * Find patient by patientId.
     *
     * @param int $patient_id ID of patient that needs to be fetched (required)
     *
     * @return Http response
     */
    public function getPatientById($patient_id)
    {
        $patient = Patient::findOrFail($patient_id);
        $patient->load('service','facility', 'supporter', 'source', 'who_stage', 'prophylaxis', 'tb', 'other_drug',
                        'current_status', 'drug_allergy', 'other_drug_allergy', 'illnesses', 
                        'other_illnesses', 'patient_dependant', 'family_planning', 'partner', 
                        'next_appointment', 'place_of_birth', 'first_visit');
        return response()->json($patient, 200);
    }

    /**
     * Operation addPatient
     *
     * Add a new patient to the facility.
     *
     *
     * @return Http response
     */
    public function addPatient(Request $request)
    {
        $input = $request::all();
        // return response()->json($input);
        $new_patient = event(new CreatePatientEvent($input)); 
        return response()->json(['msg' => 'created patient', 'new patient' => $input],200); 
        
    }

    /**
     * Operation updatePatient
     *
     * Update an existing patient.
     *
     * @param int $patient_id Patient id to delete (required)
     *
     * @return Http response
     */
    public function updatePatient($patient_id)
    {
        $input = Request::all();
        return $input;
        event(new UpdatePatientEvent($input, $patient_id));
    }

    /**
     * Operation deletePatient
     *
     * Deletes a patient.
     *
     * @param int $patient_id Patient id to delete (required)
     *
     * @return Http response
     */
    public function deletePatient($patient_id)
    {
        $patient = Patient::find($patient_id);
        $patient->delete();
        return response()->json(['msg' => 'Deleted Patient from facility']);
    }

    /**
     * Operation patientAllergies
     *
     * Fetch a patient's allergies.
     *
     
     * @param int $patient_id ID&#39;s of patient that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientAllergiesget($patient_id)
    {
        $response = PatientAllergies::where('patient_id',  $patient_id)->get();
        if(!$response){  
            return response()->json(['msg' => 'could not find patient allergies'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation patientAllergies
     *
     * Fetch a patient's allergies.
     *
     
     * @param int $patient_id ID&#39;s of patient that needs to be fetched (required)
     * @param int $allergie_id ID of Allergies that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientAllergiesByIdget($patient_id, $allergie_id)
    {
        $response = PatientAllergies::where('patient_id',  $patient_id)->where('drug_id',  $allergie_id)->get();
        if(!$response){  
            return response()->json(['msg' => 'could not find allergies for this patient'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation addPatientAllergies
     *
     * Add a new PatientDrugAllergy to a patient.
     *
     * @param int $patient_id ID&#39;s of patient (required)
     * @param int $allergie_id ID of Allergies (required)
     *
     * @return Http response
     */
    public function patientAllergiespost()
    {
        $input = Request::all();
        $new_patient_allergy = PatientAllergies::create($input);
        if($new_patient_allergy){
            return response()->json(['msg'=> 'Allergies added to patient', 'response'=> $new_patient_allergy], 201);
        }else{
            return response()->json(['msg'=> 'Could not map patient to allergy'], 400);
        }

    }

    /**
     * Operation updatePatientAllergies
     *
     * Update an existing patient Allergies.
     *
     * @param int $patient_id Patient id to update (required)
     * @param int $allergie_id Allergies id to update (required)
     *
     * @return Http response
     */
    public function patientAllergiesput($patient_id, $allergie_id)
    {
        $input = Request::all();
        $patientAllergy = PatientAllergies::where('patient_id', $patient_id)
                                            ->where('drug_id', $allergie_id)
                                            ->update(['drug_id' => $input['drug_id']]);
        if($patientAllergy){
            return response()->json(['msg' => 'Updated allergy']);
        }else{
            return response()->json(['msg' => 'Could not update record'], 405);
        }

    }

    /**
     * Operation deletePatientAllergies
     *
     * Remove a patient PatientAllergies.
     *
     * @param int $patient_id ID&#39;s of patient and allergies that needs to be fetched (required)
     * @param int $allergie_id ID of allergies that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientAllergiesdelete($patient_id, $allergie_id)
    {
        $patientAllergy = PatientAllergies::where('patient_id', $patient_id)
                                            ->where('drug_id', $allergie_id)
                                            ->delete();
        if($patientAllergy){
            return response()->json(['msg' => 'Saftly deleted the patient allergy record'],200);
        }else{
            return response()->json(['msg' => 'Could not delete record'], 400);
        }

    }

    // family plan
    /**
     * Operation PatientFamilyPlannings
     *
     * Fetch a patient's family plannings.
     *
     
     * @param int $patient_id ID&#39;s of patient that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientFamilyPlanningget($patient_id)
    {
        $response = PatientFamilyPlanning::where('patient_id',  $patient_id)->get();
        if(!$response){  
            return response()->json(['msg' => 'could not find patient family plan'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation PatientFamilyPlannings
     *
     * Fetch a patient's familyplanning.
     *
     
     * @param int $patient_id ID&#39;s of patient that needs to be fetched (required)
     * @param int $family_planning_id ID family plan that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientFamilyPlanningbyIdget($patient_id, $family_planning_id)
    {
        $response = PatientFamilyPlanning::where('patient_id',  $patient_id)->where('id',  $family_planning_id)->get();
        if(!$response){  
            return response()->json(['msg' => 'could not find family plan for this patient'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation addPatientFamilyPlannings
     *
     * Add a new patient allergy to a patient.
     *
     * @param int $patient_id ID&#39;s of patient (required)
     *
     * @return Http response
     */
    public function patientFamilyPlanningpost()
    {
        $input = Request::all();

        // return $input;
        $new_patient_familly_plan = PatientFamilyPlanning::create($input);
        if($new_patient_familly_plan){
            return response()->json(['msg'=> 'family plannings added to patient', 'response'=> $new_patient_familly_plan], 201);
        }else{
            return response()->json(['msg'=> 'Could not map patient to family planning'], 400);
        }

    }

    /**
     * Operation updatePatientFamilyPlannings
     *
     * Update an existing patient family plannings.
     *
     * @param int $patient_id Patient id to update (required)
     * @param int $family_planning_id family plannings id to update (required)
     *
     * @return Http response
     */
    public function patientFamilyPlanningput($patient_id, $family_planning_id)
    {
        $input = Request::all();
        $patientAllergy = PatientFamilyPlanning::where('patient_id', $patient_id)
                                            ->where('family_planning_id', $family_planning_id)
                                            ->update(['family_planning_id' => $input['family_planning_id']]);
        if($patientAllergy){
            return response()->json(['msg' => 'Updated allergy']);
        }else{
            return response()->json(['msg' => 'Could not update record'], 405);
        }

    }

    /**
     * Operation deletePatientFamilyPlannings
     *
     * Remove a patient PatientFamilyPlannings.
     *
     * @param int $patient_id ID&#39;s of patient and allergy that needs to be fetched (required)
     * @param int $family_planning_id ID of allergy that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientFamilyPlanningdelete($patient_id, $family_planning_id)
    {
        $patientAllergy = PatientFamilyPlanning::where('patient_id', $patient_id)
                                            ->where('id', $family_planning_id)
                                            ->delete();
        if($patientAllergy){
            return response()->json(['msg' => 'Saftly deleted the patient family planning record'],200);
        }else{
            return response()->json(['msg' => 'Could not delete record'], 400);
        }

    }


    // dependant 
    /**
     * Operation patientdependants
     *
     * Fetch a patient's dependants.
     *
     
     * @param int $patient_id ID&#39;s of patient that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientDependantsget($patient_id)
    {
        $response = PatientDependant::where('patient_id',  $patient_id)->with('dependant')->get();
        if(!$response){  
            return response()->json(['msg' => 'could not find patient dependants'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation patientdependants
     *
     * Fetch a patient's dependants.
     *
     
     * @param int $patient_id ID&#39;s of patient that needs to be fetched (required)
     * @param int $dependant_id ID of dependants that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientDependantsByIdget($patient_id, $dependant_id)
    {
        $response = PatientDependant::where('patient_id',  $patient_id)->where('dependant_id',  $dependant_id)->get();
        if(!$response){  
            return response()->json(['msg' => 'could not find dependants for this patient'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation addPatientdependants
     *
     * Add a new patient dependant to a patient.
     *
     * @param int $patient_id ID&#39;s of patient (required)
     * @param int $dependant_id ID of dependants (required)
     *
     * @return Http response
     */
    public function patientDependantspost()
    {
        $input = Request::all();
        $new_patient_allergy = PatientDependant::create($input);
        if($new_patient_allergy){
            return response()->json(['msg'=> 'dependants added to patient', 'response'=> $new_patient_allergy], 201);
        }else{
            return response()->json(['msg'=> 'Could not map patient to dependant'], 400);
        }

    }

    /**
     * Operation updatePatientdependants
     *
     * Update an existing patient dependants.
     *
     * @param int $patient_id Patient id to update (required)
     * @param int $dependant_id dependants id to update (required)
     *
     * @return Http response
     */
    public function patientDependantsput($patient_id, $dependant_id)
    {
        $input = Request::all();
        $patientAllergy = PatientDependant::where('patient_id', $patient_id)
                                            ->where('dependant_id', $dependant_id)
                                            ->update(['dependant_id' => $input['dependant_id']]);
        if($patientAllergy){
            return response()->json(['msg' => 'Updated dependant']);
        }else{
            return response()->json(['msg' => 'Could not update record'], 405);
        }

    }

    /**
     * Operation deletePatientdependants
     *
     * Remove a patient Patientdependants.
     *
     * @param int $patient_id ID&#39;s of patient and dependant that needs to be fetched (required)
     * @param int $dependant_id ID of dependant that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientDependantsdelete($patient_id, $dependant_id)
    {
        $patientAllergy = PatientDependant::where('patient_id', $patient_id)
                                            ->where('dependant_id', $dependant_id)
                                            ->delete();
        if($patientAllergy){
            return response()->json(['msg' => 'Saftly deleted the patient dependant record'],200);
        }else{
            return response()->json(['msg' => 'Could not delete record'], 400);
        }

    }

#   ======================== PATIENT ILLNESS

    /**
     * Operation patientIllness
     *
     * Fetch a patient's illnesses.
     *
     
     * @param int $patient_id ID&#39;s of patient that needs to be fetched (required)
     * @param int $illness_id ID of Allergies that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientIllnessget($patient_id)
    {

        $response = PatientIllness::where('patient_id',  $patient_id)->get();
        if(!$response){  
            return response('cant find patient nor Illnesses');
        }else{
            return response()->json($response, 200);
        }
    }

      public function patientIllnessByIdget($patient_id, $illness_id)
    {
        $response = PatientIllness::where('patient_id', $patient_id)->where('illness_id', $illness_id)->get();
        if(!$response){  
            return response()->json(['msg' => 'Could not find record'], 404);
        }else{
            return response()->json($response, 200);
        }
    }

    /**
     * Operation addPatientIllness
     *
     * Add a new PatientIllness to a patient.
     *
     * @param int $patient_id ID&#39;s of patient (required)
     * @param int $illness_id ID of Allergies (required)
     *
     * @return Http response
     */
    public function patientIllnesspost()
    {
        $input = Request::all();
        $new_patient_illness = PatientIllness::create($input);
        if($new_patient_illness){
            return response()->json(['msg'=> 'Illness added to patient', 'response'=> $new_patient_illness]);
        }else{
            return response()->json(['msg'=> 'There seems to have been a problem']);
        }

    }

    /**
     * Operation updatePatientIllness
     *
     * Update an existing patient Allergies.
     *
     * @param int $patient_id Patient id to update (required)
     * @param int $illness_id Allergies id to update (required)
     *
     * @return Http response
     */
    public function patientIllnessput($patient_id, $illness_id)
    {
        $input = Request::all();
        $updatedpatientIllness = PatientIllness::where('patient_id', $patient_id)
                                ->where('illness_id', $illness_id)
                                ->update([ 'patient_id'=>$input['patient_id'], 'illness_id'=>$input['illness_id']]);

        if($updatedpatientIllness){
            return response()->json(['msg' => 'Updated Illness','data'=> $updatedpatientIllness]);
        }else{
            return response("there seems to have been a problem while updating");
        }

    }

    /**
     * Operation deletePatientIllness
     *
     * Remove a patient PatientIllness.
     *
     * @param int $patient_id ID&#39;s of patient and appointment that needs to be fetched (required)
     * @param int $illness_id ID of appointment that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientIllnessdelete($patient_id, $illness_id)
    {
        $deleted_patientIllness = PatientIllness::where('patient_id', $patient_id)
                                ->where('illness_id', $illness_id)
                                ->delete();

        if($deleted_patientIllness){
            return response()->json(['msg' => 'deleted the patient Illness record']);
        }else{
            return response('there seems to have been a problem while delteting');
        }

    }
#   ========================/ PATIENT ILLNESS

#   ======================== PATIENT OTHER ILLNESS

       /**
     * Operation patientOtherIllness
     *
     * Fetch a patient's illnesses.
     *
     
     * @param int $patient_id ID&#39;s of patient that needs to be fetched (required)
     * @param int $illness_id ID of Allergies that needs to be fetched (required)
     *
     * @return Http response
     */
    public function getpatientOtherIllness($patient_id)
    {

        $response = PatientIllnessOther::where('patient_id',  $patient_id)->get();
        if(!$response){  
            return response()->json(['msg' => 'cant find patient nor Illnesses'],404);
        }else{
            return response()->json($response, 200);
        }
    }

      public function getpatientOtherIllnessbyId($patient_id, $illness_id)
    {
        
        // $response = PatientIllness::where('patient_id',  $patient_id)->where('illness_id',  $illness_id)->get();
        $response = PatientIllnessOther::findOrFail($illness_id);
        if(!$response){  
            return response('cant find patient nor Illnesses');
        }else{
            return response()->json($response, 200);
        }
    }

    /**
     * Operation addPatientIllness
     *
     * Add a new PatientIllness to a patient.
     *
     * @param int $patient_id ID&#39;s of patient (required)
     * @param int $illness_id ID of Allergies (required)
     *
     * @return Http response
     */
    public function addPatientOtherIllness()
    {
        $input = Request::all();
        $save = PatientIllnessOther::create($input);
        if($save){
            return response()->json(['msg'=> 'Illness added to patient', 'response'=> $input]);
        }else{
            return response()->json(['msg'=> 'There seems to have been a problem']);
        }

    }

    /**
     * Operation updatePatientIllness
     *
     * Update an existing patient Allergies.
     *
     * @param int $patient_id Patient id to update (required)
     * @param int $illness_id Allergies id to update (required)
     *
     * @return Http response
     */
    public function updatePatientOtherIllness($patient_id, $illness_id)
    {
        // patient_id  illness_id
        $input = Request::all();
        $updatedpatientIllness = PatientIllnessOther::findOrFail($illness_id);
        $updatedpatientIllness->update([ 'patient_id'=>$input['patient_id'], 'other_illness'=>$input['other_illness']]);
        if($updatedpatientIllness->save()){
            return response()->json(['msg' => 'Updated Illness','data'=> $updatedpatientIllness]);
        }else{
            return response("there seems to have been a problem while updating");
        }

    }

    /**
     * Operation deletePatientIllness
     *
     * Remove a patient PatientIllness.
     *
     * @param int $patient_id ID&#39;s of patient and appointment that needs to be fetched (required)
     * @param int $illness_id ID of appointment that needs to be fetched (required)
     *
     * @return Http response
     */
    public function deletePatientOtherIllness($patient_id, $illness_id)
    {
        $patientIllness = PatientIllnessOther::destroy($illness_id);

                if($patientIllness){
            return response()->json(['msg' => 'deleted the patient Other Illness record']);
        }else{
            return response('there seems to have been a problem while delteting');
        }

    }
    // drug allergy other
    /**
     * Operation PatientDrugAllergyOthers
     *
     * Fetch a patient's dependants.
     *
     
     * @param int $patient_id ID&#39;s of patient that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientOtherAllergiesget($patient_id)
    {
        $response = PatientDrugAllergyOther::where('patient_id',  $patient_id)->get();
        if(!$response){  
            return response()->json(['msg' => 'could not find patient allergies'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation PatientDrugAllergyOthers
     *
     * Fetch a patient's allergies.
     *
     
     * @param int $patient_id ID&#39;s of patient that needs to be fetched (required)
     * @param int $drug_allergy__other_id ID allergies that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientOtherAllergiesbyIdget($patient_id, $allergy_other_id)
    {
        $response = PatientDrugAllergyOther::where('patient_id',  $patient_id)->where('id',  $allergy_other_id)->get();
        if(!$response){  
            return response()->json(['msg' => 'could not find allergies for this patient'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation addPatientDrugAllergyOthers
     *
     * Add a new patient allergy to a patient.
     *
     * @param int $patient_id ID&#39;s of patient (required)
     *
     * @return Http response
     */
    public function patientOtherAllergiespost()
    {
        $input = Request::all();
        $new_patient_allergy_other = PatientDrugAllergyOther::create($input);
        if($new_patient_allergy_other){
            return response()->json(['msg'=> 'dependants added to patient', 'response'=> $new_patient_allergy_other], 201);
        }else{
            return response()->json(['msg'=> 'Could not map patient to dependant'], 400);
        }

    }

    /**
     * Operation updatePatientDrugAllergyOthers
     *
     * Update an existing patient dependants.
     *
     * @param int $patient_id Patient id to update (required)
     * @param int $drug_allergy__other_id dependants id to update (required)
     *
     * @return Http response
     */
    public function patientOtherAllergiesput($patient_id, $drug_allergy__other_id)
    {
        $input = Request::all();
        $patientAllergy = PatientDrugAllergyOther::where('patient_id', $patient_id)
                                            ->where('id', $drug_allergy__other_id)
                                            ->update(['allergy_name' => $input['allergy_name']]);
        if($patientAllergy){
            return response()->json(['msg' => 'Updated allergy']);
        }else{
            return response()->json(['msg' => 'Could not update record'], 405);
        }

    }

    /**
     * Operation deletePatientDrugAllergyOthers
     *
     * Remove a patient PatientDrugAllergyOthers.
     *
     * @param int $patient_id ID&#39;s of patient and allergy that needs to be fetched (required)
     * @param int $drug_allergy__other_id ID of allergy that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientOtherAllergiesdelete($patient_id, $drug_allergy__other_id)
    {
        $patientAllergy = PatientDrugAllergyOther::where('patient_id', $patient_id)
                                            ->where('id', $drug_allergy__other_id)
                                            ->delete();
        if($patientAllergy){
            return response()->json(['msg' => 'Saftly deleted the patient dependant record'],200);
        }else{
            return response()->json(['msg' => 'Could not delete record'], 400);
        }

    }


#   ========================/ PATIENT OTHER ILLNESS


#   ======================== PATIENT PARTNERS

    /**
     * Operation PatientPartners
     *
     * Fetch a patient's family plannings.
     *
     
     * @param int $patient_id ID&#39;s of patient that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientPartnerget($patient_id)
    {
        $response = PatientPartner::where('patient_id',  $patient_id)->get();
        if(!$response){  
            return response()->json(['msg' => 'could not find patient partner plan'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation PatientPartners
     *
     * Fetch a patient's familyplanning.
     *
     
     * @param int $patient_id ID&#39;s of patient that needs to be fetched (required)
     * @param int $partner_id ID family plan that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientPartnerbyIdget($patient_id, $partner_id)
    {
        $response = PatientPartner::where('patient_id',  $patient_id)->where('partner_id',  $partner_id)->get();
        if(!$response){  
            return response()->json(['msg' => 'could not find partner for this patient'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation addPatientPartners
     *
     * Add a new patient allergy to a patient.
     *
     * @param int $patient_id ID&#39;s of patient (required)
     *
     * @return Http response
     */
    public function patientPartnerpost()
    {
        $input = Request::all();
        $new_patient_partner = PatientPartner::create($input);
        if($new_patient_partner){
            return response()->json(['msg'=> 'partner given to patient', 'response'=> $new_patient_partner], 201);
        }else{
            return response()->json(['msg'=> 'Could not map patient to partner'], 400);
        }

    }

    /**
     * Operation updatePatientPartners
     *
     * Update an existing patient partner plannings.
     *
     * @param int $patient_id Patient id to update (required)
     * @param int $partner_id family plannings id to update (required)
     *
     * @return Http response
     */
    public function patientPartnerput($patient_id, $partner_id)
    {
        $input = Request::all();
        $patient_partner = PatientPartner::where('patient_id', $patient_id)
                                            ->where('partner_id', $partner_id)
                                            ->update(['partner_id' => $input['partner_id']]);
        if($patient_partner){
            return response()->json(['msg' => 'Updated partner']);
        }else{
            return response()->json(['msg' => 'Could not update record'], 405);
        }

    }

    /**
     * Operation deletePatientPartners
     *
     * Remove a patient PatientPartners.
     *
     * @param int $patient_id ID&#39;s of patient and allergy that needs to be fetched (required)
     * @param int $partner_id ID of allergy that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientPartnerdelete($patient_id, $partner_id)
    {
        $patient_partner = PatientPartner::where('patient_id', $patient_id)
                                            ->where('partner_id', $partner_id)
                                            ->delete();
        if($patient_partner){
            return response()->json(['msg' => 'Saftly deleted the patient partner planning record'],200);
        }else{
            return response()->json(['msg' => 'Could not delete record'], 400);
        }

    }

    /**
     * Operation updatePatientPartner
     *
     * Update an existing patient Allergies.
     *
     * @param int $patient_id Patient id to update (required)
     * @param int $partner_id Allergies id to update (required)
     *
     * @return Http response
     */
    public function updatePatientPartner($patient_id, $partner_id)
    {
        // patient_id  partner_id
        $input = Request::all();
        $updatedpatientPartner = PatientPartner::findOrFail($partner_id);
        $updatedpatientPartner->update([ 'patient_id'=>$input['patient_id'], 'partner_id'=>$input['partner_id']]);
        if($updatedpatientPartner->save()){
            return response()->json(['msg' => 'Updated Partner','data'=> $updatedpatientPartner]);
        }else{
            return response("there seems to have been a problem while updating");
        }

    }

    /**
     * Operation deletePatientPartner
     *
     * Remove a patient PatientPartner.
     *
     * @param int $patient_id ID&#39;s of patient and appointment that needs to be fetched (required)
     * @param int $partner_id ID of appointment that needs to be fetched (required)
     *
     * @return Http response
     */
    public function deletePatientPartner($patient_id, $partner_id)
    {
        $patientPartner = PatientPartner::destroy($partner_id);

                if($patientPartner){
            return response()->json(['msg' => 'deleted the patient Partner record']);
        }else{
            return response('there seems to have been a problem while delteting');
        }

    }
#   ========================/ PATIENT PARTNERS

    /**
     * Operation PatientProphylaxiss
     *
     * Fetch a patient's family plannings.
     *
     
     * @param int $patient_id ID&#39;s of patient that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientProphylaxisget($patient_id)
    {
        $response = PatientProphylaxis::where('patient_id',  $patient_id)->get();
        if(!$response){  
            return response()->json(['msg' => 'could not find patient Prophylaxis'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation PatientProphylaxiss
     *
     * Fetch a patient's familyplanning.
     *
     
     * @param int $patient_id ID&#39;s of patient that needs to be fetched (required)
     * @param int $Prophylaxis_id ID family plan that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientProphylaxisByIdget($patient_id, $prophylaxis_id)
    {
        $response = PatientProphylaxis::where('patient_id', $patient_id)
                                            ->where('prophylaxis_id', $prophylaxis_id)
                                            ->first();
        if(!$response){  
            return response()->json(['msg' => 'could not find Prophylaxis for this patient'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation addPatientProphylaxiss
     *
     * Add a new patient allergy to a patient.
     *
     * @param int $patient_id ID&#39;s of patient (required)
     *
     * @return Http response
     */
    public function patientProphylaxispost()
    {
        $input = Request::all();
        $new_patient_prophylaxis = PatientProphylaxis::create($input);
        if($new_patient_prophylaxis){
            return response()->json(['msg'=> 'added prophylaxis to patient', 'response'=> $new_patient_prophylaxis], 201);
        }else{
            return response()->json(['msg'=> 'Could not map patient to Prophylaxis'], 400);
        }
    }

    /**
     * Operation updatePatientProphylaxiss
     *
     * Update an existing patient Prophylaxis plannings.
     *
     * @param int $patient_id Patient id to update (required)
     * @param int $Prophylaxis_id family plannings id to update (required)
     *
     * @return Http response
     */
    public function patientProphylaxisput($patient_id, $prophylaxis_id)
    {
        $input = Request::all();
        $patient_Prophylaxis = PatientProphylaxis::where('patient_id', $patient_id)
                                            ->where('id', $prophylaxis_id)
                                            ->update(['prophylaxis_id' => $input['prophylaxis_id']]);
        if($patient_Prophylaxis){
            return response()->json(['msg' => 'Updated prophylaxis', 'prophylaxis' => $patient_Prophylaxis]);
        }else{
            return response()->json(['msg' => 'Could not update record'], 405);
        }
    }

    /**
     * Operation deletePatientProphylaxiss
     *
     * Remove a patient PatientProphylaxiss.
     *
     * @param int $patient_id ID&#39;s of patient and allergy that needs to be fetched (required)
     * @param int $Prophylaxis_id ID of allergy that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientProphylaxisdelete($patient_id, $prophylaxis_id)
    {
        $patient_Prophylaxis = PatientProphylaxis::where('patient_id', $patient_id)
                                            ->where('prophylaxis_id', $prophylaxis_id)
                                            ->delete();
        if($patient_Prophylaxis){
            return response()->json(['msg' => 'Saftly deleted the patient Prophylaxis record'],200);
        }else{
            return response()->json(['msg' => 'Could not delete record'], 400);
        }
    }

    // status 
    /**
     * Operation PatientStatuss
     *
     * Fetch a patient's family plannings.
     *
     
     * @param int $patient_id ID&#39;s of patient that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientStatusget($patient_id)
    {
        $response = PatientStatus::where('patient_id',  $patient_id)->get();
        if(!$response){  
            return response()->json(['msg' => 'could not find patient status'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation PatientStatuss
     *
     * Fetch a patient's familyplanning.
     *
     
     * @param int $patient_id ID&#39;s of patient that needs to be fetched (required)
     * @param int $status_id ID family plan that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientStatusByIdget($patient_id, $status_id)
    {
        $response = PatientStatus::where('patient_id', $patient_id)
                                            ->where('status_id', $status_id)
                                            ->first();
        if(!$response){  
            return response()->json(['msg' => 'could not find status for this patient'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation addPatientStatuss
     *
     * Add a new patient status to a patient.
     *
     * @param int $patient_id ID&#39;s of patient (required)
     *
     * @return Http response
     */
    public function patientStatuspost()
    {
        $input = Request::all();
        $new_patient_status = PatientStatus::create($input);
        if($new_patient_status){
            return response()->json(['msg'=> 'added status to patient', 'response'=> $new_patient_status], 201);
        }else{
            return response()->json(['msg'=> 'Could not map patient to status'], 400);
        }
    }

    /**
     * Operation updatePatientStatuss
     *
     * Update an existing patient statuss plannings.
     *
     * @param int $patient_id Patient id to update (required)
     * @param int $status_id family plannings id to update (required)
     *
     * @return Http response
     */
    public function patientStatusput($patient_id, $status_id)
    {
        $input = Request::all();
        $patient_status = PatientStatus::where('patient_id', $patient_id)
                                            ->where('status_id', $status_id)
                                            ->update(['status_id' => $input['status_id']]);
        if($patient_status){
            return response()->json(['msg' => 'Updated status']);
        }else{
            return response()->json(['msg' => 'Could not update record'], 405);
        }
    }

    /**
     * Operation deletePatientStatuss
     *
     * Remove a patient PatientStatuss.
     *
     * @param int $patient_id ID&#39;s of patient and status that needs to be fetched (required)
     * @param int $status_id ID of status that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientStatusdelete($patient_id, $status_id)
    {
        $patient_status = PatientStatus::where('patient_id', $patient_id)
                                            ->where('status_id', $status_id)
                                            ->delete();
        if($patient_status){
            return response()->json(['msg' => 'Saftly deleted the patient status record'],200);
        }else{
            return response()->json(['msg' => 'Could not delete record'], 400);
        }
    }

    // tb 
    /**
     * Operation PatientTbs
     *
     * Fetch a patient's tb.
     *
     
     * @param int $patient_id ID&#39;s of patient that needs to be fetched (required)
     *
     * @return Http response
     */
    public function PatientTbget($patient_id)
    {
        $response = PatientTb::where('patient_id',  $patient_id)->get();
        if(!$response){  
            return response()->json(['msg' => 'could not find tb'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation PatientTbs
     *
     * Fetch a patient's familyplanning.
     *
     
     * @param int $patient_id ID&#39;s of patient that needs to be fetched (required)
     * @param int $tb_id ID family plan that needs to be fetched (required)
     *
     * @return Http response
     */
    public function PatientTbByIdget($patient_id, $tb_id)
    {
        $response = PatientTb::where('patient_id', $patient_id)
                                            ->where('id', $tb_id)
                                            ->first();
        if(!$response){  
            return response()->json(['msg' => 'could not find tb records for this patient'], 204);
        }else{
            return response()->json($response, 200);
        }
    }
    /**
     * Operation addPatientTbs
     *
     * Add a new patient tb to a patient.
     *
     * @param int $patient_id ID&#39;s of patient (required)
     *
     * @return Http response
     */
    public function PatientTbpost()
    {
        $input = Request::all();
        $new_patient_tb = PatientTb::create($input);
        if($new_patient_tb){
            return response()->json(['msg'=> 'added tb to patient', 'response'=> $new_patient_tb], 201);
        }else{
            return response()->json(['msg'=> 'Could not map patient to tb'], 400);
        }
    }

    /**
     * Operation updatePatientTbs
     *
     * Update an existing patient tbs plannings.
     *
     * @param int $patient_id Patient id to update (required)
     * @param int $tb_id family plannings id to update (required)
     *
     * @return Http response
     */
    public function PatientTbput($patient_id, $tb_id)
    {
        $input = Request::all();
        $patient_tb = PatientTb::where('patient_id', $patient_id)
                                ->where('id', $tb_id)
                                ->update([
                                    "category" => $input['category'],
                                    "phase" => $input['phase'],
                                    "start_date" => $input['start_date'],
                                    "end_date" => $input['end_date']
                                 ]);
        if($patient_tb){
            return response()->json(['msg' => 'Updated tb']);
        }else{
            return response()->json(['msg' => 'Could not update record'], 405);
        }
    }

    /**
     * Operation deletePatientTbs
     *
     * Remove a patient PatientTbs.
     *
     * @param int $patient_id ID&#39;s of patient and tb that needs to be fetched (required)
     * @param int $tb_id ID of tb that needs to be fetched (required)
     *
     * @return Http response
     */
    public function PatientTbdelete($patient_id, $tb_id)
    {
        $patient_tb = PatientTb::where('patient_id', $patient_id)
                                            ->where('id', $tb_id)
                                            ->delete();
        if($patient_tb){
            return response()->json(['msg' => 'Saftly deleted the patient tb record'],200);
        }else{
            return response()->json(['msg' => 'Could not delete record'], 400);
        }
    }


    // viralload    
    /**
     * Operation patientviralload
     *
     * Fetch a patient's viralload.
     *
     * @param int $patient_id ID&#39;s of patient and viralload that needs to be fetched (required)
     * @param int $viralload_id ID&#39;s of viralload that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientViralload($patient_id)
    {   
        $patient_viralload = PatientViralload::where('patient_id', $patient_id)->get();
        return response()->json($patient_viralload,200); 
    }

    /**
     * Operation addPatientviralload
     *
     * Add a new viralload to a patient.
     *
     * @param int $patient_id ID&#39;s of patient (required)
     * @param int $viralload_id ID&#39;s of viralload (required)
     *
     * @return Http response
     */
    public function addPatientViralload($patient_id)
    {
        $input = Request::all();
        $new_virallod = PatientViralload::create($input);
        if($new_virallod){
            return response()->json('',201);
        }else{
            return response()->json('',400);
        }
    }

    /**
     * Operation updatePatientviralload
     *
     * Update an existing patient appointment.
     *
     * @param int $patient_id Patient id to update (required)
     * @param int $viralload_id viralload id to update (required)
     *
     * @return Http response
     */
    public function updatePatientViralload($patient_id, $viralload_id)
    {
        $input = Request::all();
        
        $viralload = PatientViralload::findOrFail($viralload_id)->where('patient_id', $patient_id);
        $viralload->update([
            'test_date' => $input[''],
            'result' => $input['result'],
            'justification' => $input['justification']
        ]);

        if($viralload->save()){
            return response()->json('',202);
        }else{
            return response()->json('', 400);
        }
    }

    /**
     * Operation deletePatientviralload
     *
     * Remove a patient viralload.
     *
     * @param int $patient_id ID&#39;s of patient and appointment that needs to be fetched (required)
     * @param int $viralload_id ID of appointment that needs to be fetched (required)
     *
     * @return Http response
     */
    public function deletePatientViralload($patient_id, $viralload_id)
    {
        $deleted_viralload = PatientViralload::destroy($viralload_id);
        return response()->json('',200); 
    }



    // functoins to return only the latest
    public function return_latest_appointment($patient_id){
        $appointment = Appointment::where('patient_id', $patient_id)->latest()->take(1)->get();
        return response()->json($appointment,200);
    }

    public function return_latest_visit($patient_id){
        $visit = Visit::with('purpose')->where('patient_id', $patient_id)->latest()->take(1)->get();
        return response()->json($visit,200);
    }

    // functions to return only return the first 
    public function return_first_visit($patient_id){
        $first_visit = Visit::where('patient_id', $patient_id)->first();
        return response()->json($first_visit, 200);
    }

}