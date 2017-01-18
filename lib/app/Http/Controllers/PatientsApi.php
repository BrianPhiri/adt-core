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
use App\Http\Requests;

use App\Models\PatientModels\Patient;
use App\Models\PatientModels\PatientFamilyPlanning;
use App\Models\PatientModels\PatientDependants;
use App\Models\PatientModels\PatientDrugAllergyOther;
use App\Models\PatientModels\PatientAllergies;
use App\Models\PatientModels\PatientDrugOther;
use App\Models\PatientModels\PatientIllness;
use App\Models\PatientModels\PatientPartner;
use App\Models\PatientModels\PatientProphylaxis;
use App\Models\PatientModels\PatientRegimens;
use App\Models\PatientModels\PatientStatus;
use App\Models\PatientModels\PatientTb;

// 
use App\Events\CreatePatientEvent;

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
        $response = Patient::all();
        return response()->json($response, 200);
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
        $response = Patient::findOrFail($patient_id);
        $response->load('service','facility', 'supporter', 'source', 'who_stage', 'patient_prophylaxis', 'patient_tb', 'patient_drug_other',
                        'patient_status', 'patient_drug_allergy', 'drug_allergy_other', 'patient_dependant', 'patient_family_planning', 'patient_partner');
        return response()->json($response, 200);

        $patients = Patient::with('service','facility.county_sub.county', 'supporter', 'who_stage', 'source','patient_partner','patient_dependant','patient_prophylaxis', 'patient_tb', 'patient_drug_other',
         'drug_allergy.drug', 'drug_allergy_other', 'patient_drug_allergy.drug','patient_family_planning')->get();
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
        event(new CreatePatientEvent($input));
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

        //path params validation


        //not path params validation

        return response('How about implementing updatePatient as a PUT method ?');
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
        $patient = Products::find($patient_id);
        $patient->delete();
        return response()->json(['msg' => 'Deleted Patient from facility']);
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
    public function patientAllergies($patient_id, $allergie_id)
    {
        $response = PatientAllergies::where('patient_id',  $patient_id)->where('drug_id',  $allergie_id)->get();
        if(!$response){  
            return response('cant find patient nor allergies');
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
    public function addPatientAllergies()
    {
        $input = Request::all();
        $save = PatientAllergies::create($input);
        if($save){
            return response()->json(['msg'=> 'Allergies added to patient', 'response'=> $input]);
        }else{
            return response()->json(['msg'=> 'There seems to have been a problem']);
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
    public function updatePatientAllergies($patient_id, $allergie_id)
    {
        $input = Request::all();

        $patientAllergy = PatientAllergies::where('patient_id', $patient_id)
                                            ->where('drug_id', $allergie_id)
                                            ->update(['drug_id' => $input['drug_id']]);
        if($patientAllergy){
            return response()->json(['msg' => 'Updated allergy']);
        }else{
            return response("there seems to have been a problem while updating");
        }

    }

    /**
     * Operation deletePatientAllergies
     *
     * Remove a patient PatientAllergies.
     *
     * @param int $patient_id ID&#39;s of patient and appointment that needs to be fetched (required)
     * @param int $allergie_id ID of appointment that needs to be fetched (required)
     *
     * @return Http response
     */
    public function deletePatientAllergies($patient_id, $allergie_id)
    {
        $patientAllergy = PatientAllergies::where('patient_id', $patient_id)
                                            ->where('drug_id', $allergie_id)
                                            ->delete();
        if($patientAllergy){
            return response()->json(['msg' => 'Saftly deleted the patient allergy record']);
        }else{
            return response('there seems to have been a problem while delteting');
        }

    }

    /**
     * Operation patientAppointments
     *
     * Fetch the patient's appointments.
     *
     * @param int $patient_id ID&#39;s of patient and appointment that needs to be fetched (required)
     * @param int $appointment_id ID of appointment that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientAppointments($patient_id, $appointment_id)
    {

    }

    /**
     * Operation addPatientAppointments
     *
     * Add a new Appointments to a patient.
     *
     * @param int $patient_id ID of patient (required)
     * @param int $appointment_id ID of appointment (required)
     *
     * @return Http response
     */
    public function addPatientAppointments($patient_id, $appointment_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing addPatientAppointments as a POST method ?');
    }

    /**
     * Operation updatePatientAppointments
     *
     * Update an existing patient appointment.
     *
     * @param int $patient_id ID of patient for update (required)
     * @param int $appointment_id ID of appointment for update (required)
     *
     * @return Http response
     */
    public function updatePatientAppointments($patient_id, $appointment_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing updatePatientAppointments as a PUT method ?');
    }

    /**
     * Operation deletePatientAppointment
     *
     * Remove a patient appointment.
     *
     * @param int $patient_id ID of patient and appointment that needs to be fetched (required)
     * @param int $appointment_id ID of appointment that needs to be deleted (required)
     *
     * @return Http response
     */
    public function deletePatientAppointment($patient_id, $appointment_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deletePatientAppointment as a DELETE method ?');
    }

    /**
     * Operation patientregimens
     *
     * Fetch the regimens patient is administered.
     *
     * @param int $patient_id ID of patient that needs to be fetched (required)
     * @param int $regimen_id ID of regimen that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientregimens($patient_id, $regimen_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing patientregimens as a GET method ?');
    }

        /**
     * Operation addPatientRegimen
     *
     * Add a new regimen to a patient.
     *
     * @param int $patient_id Patient id to delete (required)
     * @param int $regimen_id Patient id to delete (required)
     *
     * @return Http response
     */
    public function addPatientRegimen($patient_id, $regimen_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing addPatientRegimen as a POST method ?');
    }
    /**
     * Operation deletePatientRegimens
     *
     * Remove a patient of a regimen.
     *
     * @param int $patient_id Patient id and Regimen id to delete (required)
     * @param int $regimen_id Patient id to delete (required)
     *
     * @return Http response
     */
    public function deletePatientRegimens($patient_id, $regimen_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deletePatientRegimens as a DELETE method ?');
    }

    /**
     * Operation updatePatientRegimens
     *
     * Update an existing patient regimen.
     *
     * @param int $patient_id Patient id to update (required)
     * @param int $regimen_id Patient id to update (required)
     *
     * @return Http response
     */
    public function updatePatientRegimens($patient_id, $regimen_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing updatePatientRegimens as a PUT method ?');
    }



    /**
     * Operation patientProphylaxis
     *
     * Fetch the prophylaxis patient is administered.
     *
     * @param int $patient_id ID of patient that needs to be fetched (required)
     * @param int $prophylaxis_id ID of prophylaxis that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientProphylaxis($patient_id, $prophylaxis_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing patientProphylaxis as a GET method ?');
    }
    /**
     * Operation updatePatientProphylaxis
     *
     * Update an existing patient prophylaxis.
     *
     * @param int $patient_id Patient id to delete (required)
     * @param int $prophylaxis_id Patient id to delete (required)
     *
     * @return Http response
     */
    public function updatePatientProphylaxis($patient_id, $prophylaxis_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing updatePatientProphylaxis as a PUT method ?');
    }

    /**
     * Operation deletePatientProphylaxis
     *
     * Remove a patient of a Prophylaxis.
     *
     * @param int $patient_id Patient id to delete (required)
     * @param int $prophylaxis_id Patient id to delete (required)
     *
     * @return Http response
     */
    public function deletePatientProphylaxis($patient_id, $prophylaxis_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deletePatientProphylaxis as a DELETE method ?');
    }

        /**
     * Operation patientVisits
     *
     * Fetch a patient's visit.
     *
     * @param int $patient_id ID&#39;s of patient and Visits that needs to be fetched (required)
     * @param int $visit_id ID&#39;s of Visits that needs to be fetched (required)
     *
     * @return Http response
     */
    public function patientVisits($patient_id, $visit_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing patientVisits as a GET method ?');
    }
    /**
     * Operation updatePatientVisit
     *
     * Update an existing patient appointment.
     *
     * @param int $patient_id Patient id to update (required)
     * @param int $visit_id visit id to update (required)
     *
     * @return Http response
     */
    public function updatePatientVisit($patient_id, $visit_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing updatePatientVisit as a PUT method ?');
    }

    /**
     * Operation addPatientVisits
     *
     * Add a new Visits to a patient.
     *
     * @param int $patient_id ID&#39;s of patient (required)
     * @param int $visit_id ID&#39;s of Visits (required)
     *
     * @return Http response
     */
    public function addPatientVisits($patient_id, $visit_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing addPatientVisits as a POST method ?');
    }
    /**
     * Operation deletePatientVisit
     *
     * Remove a patient Visit.
     *
     * @param int $patient_id ID&#39;s of patient and appointment that needs to be fetched (required)
     * @param int $visit_id ID of appointment that needs to be fetched (required)
     *
     * @return Http response
     */
    public function deletePatientVisit($patient_id, $visit_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deletePatientVisit as a DELETE method ?');
    }
    /**
     * Operation addService
     *
     * Add a new service to the facility.
     *
     *
     * @return Http response
     */
    public function addService()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $body = $input['body'];


        return response('How about implementing addService as a POST method ?');
    }
}