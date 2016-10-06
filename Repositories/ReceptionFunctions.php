<?php

/*
 * =============================================================================
 *
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 *
 * =============================================================================
 */

namespace Ignite\Reception\Repositories;

use Ignite\Evaluation\Entities\Visit;
use Ignite\Reception\Entities\PatientInsurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ignite\Reception\Entities\Patients;
use Ignite\Reception\Entities\NextOfKin;
use Ignite\Reception\Entities\Appointments;
use Ignite\Reception\Entities\PatientDocuments;
use Jenssegers\Date\Date;

/**
 * Description of ReceptionFunctions
 *
 * @author Samuel Dervis <samueldervis@gmail.com>
 */
class ReceptionFunctions implements ReceptionRepository {

    public function __construct() {

    }

    /**
     * Performs a fast forward checkin. Just dive in to checkin without prior appointments
     * @param Request $request
     * @return bool
     */
    public function checkin_patient(Request $request) {
        //this patient must already be here
        $today = Visit::where('created_at', '>=', new Date('today'))
                        ->where('patient', $request->patient)->get()->first();
        if ($today) {
            $visit = Visit::find($today->visit_id);
        } else {
            $visit = new Visit;
        }
        $visit->patient = $request->patient;
        $visit->clinic = \Session::get('clinic');
        $visit->purpose = $request->purpose;
        $visit->payment_mode = $request->payment_mode;

        if ($request->has('to_nurse')) {
            $visit->nurse = true;
        }
        $this->coallesce($visit, $request);

        $visit->user = $request->user()->id;
        if ($request->has('scheme')) {
            $visit->scheme = $request->scheme;
        }
        if ($visit->save()) {
            flash("Patient has been checked in", 'success');
            return true;
        }
        flash("An error occurred", 'danger');
        return false;
    }

    private function coallesce(&$visit, $request) {
        if (intval($request->destination) > 0) {
            $visit->destination = $request->destination;
            $visit->evaluation = true;
        } else {
            switch ($request->destination) {
                case 'laboratory':
                    $visit->laboratory = true;
                    break;
                case 'theatre':
                    $visit->theatre = true;
                    break;
                case 'diagnostics':
                    $visit->diagnostics = true;
                    break;
                case 'pharmacy' :
                    $visit->pharmacy = true;
                    break;
                case 'optical':
                    $visit->optical = true;
                    break;
                case 'radiology':
                    $visit->radiology = true;
                    break;
                default :
                    flash("Unknown checkin destination", 'danger');
                    return false;
            }
        }
    }

    /**
     * Saves a new patient model to database. Updates patient if ID parameter supplied
     * @param Request $request
     * @param null $id
     * @return bool
     */
    public function add_patient(Request $request, $id = null) {
        DB::transaction(function () use ($request, $id) {
//patient first
            $patient = Patients::findOrNew($id);
            $patient->first_name = ucfirst($request->first_name);
            $patient->middle_name = ucfirst($request->middle_name);
            $patient->last_name = ucfirst($request->last_name);
            $patient->id_no = $request->id_number;
            $patient->dob = $request->dob;
            $patient->sex = $request->sex;
            $patient->telephone = $request->telephone;
            $patient->mobile = $request->mobile;
            $patient->alt_number = $request->alt_number;
            $patient->email = strtolower($request->email);
            $patient->address = $request->address;
            $patient->post_code = $request->post_code;
            $patient->town = ucfirst($request->town);
            if ($request->has('imagesrc')) {
                $patient->image = $request->imagesrc;
            }
            $patient->save();
//next of kins
            $nok = NextOfKin::findOrNew($id);
            $nok->patient = $patient->id;
            $nok->first_name = ucfirst($request->first_name_nok);
            $nok->middle_name = ucfirst($request->middle_name_nok);
            $nok->last_name = ucfirst($request->last_name_nok);
            $nok->mobile = $request->mobile_nok;
            $nok->relationship = $request->nok_relationship;
            $nok->save();
            if ($patient->insured == 1) {
                foreach ($request->scheme as $key => $scheme) {
                    $schemes = new PatientInsurance;
                    $schemes->patient_id = $patient->patient_id;
                    $schemes->scheme_id = strtoupper($request->scheme[$key]);
                    $schemes->policy_number = $request->policy_number[$key];
                    $schemes->principal = ucwords($request->principal[$key]);
                    $schemes->dob = $request->principal_dob[$key];
                    $schemes->relationship = $request->principal_relationship[$key];
                    $schemes->save();
                }
            }
            $addon = "Click <a href='" . route('reception.checkin', $patient->patient_id) . "'>here</a> to checkin";
            flash()->success($patient->full_name . " details saved. $addon");
        });
        return true;
    }

    /**
     * Reschedules previously added appointments
     * @param Request $request
     * @param $id
     * @return bool
     */
    public function reschedule_appointment(Request $request, $id) {
        $appointment = Appointments::find($id);
        $appointment->time = new \Date($request->date . ' ' . $request->time);
        //$appointment->procedure = $request->procedure;
        $appointment->doctor = $request->doctor;
        $appointment->instructions = $request->instructions;
        $appointment->clinic = $request->clinic;
        $appointment->category = $request->category;
        if ($appointment->save()) {
            //  self::sendRescheduleNotification($id);
            $request->session()->flash('success', "Appointment has been rescheduled");
            return true;
        }
        $request->session()->flash('error', "An error occurred");
        return false;
    }

    /**
     * Guess the patient id
     * @todo Make this better might be slow for large data
     * @author Samuel Okoth <sodhiambo@collabmed.com>
     * @param string $name
     */
    public function guess_patient_id(string $name) {
        $they = Patients::all();
        foreach ($they as $patient) {
            if ($patient->full_name == $name) {
                return $patient->patient_id;
            }
        }
        return ucwords($name); //$name;
    }

    /**
     * Add an appointment for patient
     * @param Request $request
     * @return bool
     */
    public function add_appointment(Request $request) {
        $patient = self::guess_patient_id($request->patient);
        $appointment = new Appointments;
        if (is_int($patient))
            $appointment->patient = $patient;
        else
            $appointment->guest = $patient;
        $appointment->time = new \Date($request->date . ' ' . $request->time);
        //$appointment->procedure = $request->procedure;
        $appointment->doctor = $request->doctor;
        $appointment->instructions = $request->instructions;
        $appointment->clinic = $request->clinic;
        $appointment->category = $request->category;

        if ($appointment->save()) {
//dispatch(new \Dervis\Jobs\SendNotificationSMS($appointment->schedule_id), 'reminders');
            //  sendAppointmentNotification($appointment);
            $request->session()->flash('success', "Appointment has been saved");
            return true;
        }
        $request->session()->flash('error', "An error occurred");
        return false;
    }

    /**
     * Upload patient document
     * @param Request $request
     * @param int $patient
     * @return bool
     */
    public function upload_document(Request $request, $patient) {
        $file = $request->file('doc');
        if (empty($file) || !$file->isValid()) {
            flash()->warning("Invalid file. Upload aborted");
            return false;
        }
        $document = new PatientDocuments;
        $document->patient = $patient;
        $document->document = base64_encode(file_get_contents($file->getRealPath()));
        $document->filename = $file->getClientOriginalName();
        $document->mime = $file->getClientMimeType();
        $document->document_type = $request->document_type;
        $document->description = $file->getSize();
        $document->user = $request->user()->id;
        if ($document->save()) {
            flash()->success("Patient Document saved");
            return true;
        }
        flash()->error("An error occurred");
        return false;
    }

}
