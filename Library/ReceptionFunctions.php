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

namespace Ignite\Reception\Library;

use Ignite\Evaluation\Entities\Visit;
use Ignite\Reception\Entities\PatientInsurance;
use Ignite\Reception\Repositories\ReceptionRepository;
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

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var int
     */
    protected $id = null;

    /**
     * ReceptionFunctions constructor.
     * @param Request $request
     */
    public function __construct(Request $request) {
        $this->request = $request;
        if ($this->request->has('id')) {
            $this->id = $this->request->id;
        }
    }

    /**
     * Performs a fast forward checkin. Just dive in to checkin without prior appointments
     * @return bool
     */
    public function checkin_patient() {
        //this patient must already be here
        $today = Visit::where('created_at', '>=', new Date('today'))
                        ->where('patient', $this->request->patient)->get()->first();
        if ($today) {
            $visit = Visit::find($today->id);
        } else {
            $visit = new Visit;
        }
        $visit->patient = $this->request->patient;
        $visit->clinic = session('clinic', 1);
        if ($this->request->has('purpose')) {
            $visit->purpose = $this->request->purpose;
        }
        $visit->payment_mode = $this->request->payment_mode;
        if ($this->request->has('to_nurse')) {
            $visit->nurse = true;
        }
        $this->coallesce($visit, $this->request);
        $visit->user = $this->request->user()->id;
        if ($this->request->has('scheme')) {
            $visit->scheme = $this->request->scheme;
        }
        if ($visit->save()) {
            flash("Patient has been checked in", 'success');
            return true;
        }
        flash("An error occurred", 'danger');
        return false;
    }

    private function coallesce(&$visit) {
        if (intval($this->request->destination) > 0) {
            $visit->destination = $this->request->destination;
            $visit->evaluation = true;
        } else {
            switch ($this->request->destination) {
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
     * @param Request $this ->request
     * @param null $this ->id
     * @return bool
     */
    public function add_patient() {
        DB::transaction(function () {
            //patient first
            $patient = Patients::findOrNew($this->id);
            $patient->first_name = ucfirst($this->request->first_name);
            $patient->middle_name = ucfirst($this->request->middle_name);
            $patient->last_name = ucfirst($this->request->last_name);
            $patient->id_no = $this->request->id_number;
            $patient->dob = $this->request->dob;
            $patient->sex = $this->request->sex;
            $patient->telephone = $this->request->telephone;
            $patient->mobile = $this->request->mobile;
            $patient->alt_number = $this->request->alt_number;
            $patient->email = strtolower($this->request->email);
            $patient->address = $this->request->address;
            $patient->post_code = $this->request->post_code;
            $patient->town = ucfirst($this->request->town);
            if ($this->request->has('imagesrc')) {
                $patient->image = $this->request->imagesrc;
            }
            $patient->save();
            //next of kins
            if ($this->request->has('first_name_nok')) {
                $nok = NextOfKin::findOrNew($this->id);
                $nok->patient = $patient->id;
                $nok->first_name = ucfirst($this->request->first_name_nok);
                $nok->middle_name = ucfirst($this->request->middle_name_nok);
                $nok->last_name = ucfirst($this->request->last_name_nok);
                $nok->mobile = $this->request->mobile_nok;
                $nok->relationship = $this->request->nok_relationship;
                $nok->save();
            }
            if ($patient->insured == 1) {
                foreach ($this->request->scheme as $key => $scheme) {
                    $schemes = new PatientInsurance;
                    $schemes->patient_id = $patient->patient_id;
                    $schemes->scheme_id = strtoupper($this->request->scheme[$key]);
                    $schemes->policy_number = $this->request->policy_number[$key];
                    $schemes->principal = ucwords($this->request->principal[$key]);
                    $schemes->dob = $this->request->principal_dob[$key];
                    $schemes->relationship = $this->request->principal_relationship[$key];
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
     * @param Request $this ->request
     * @param $this ->id
     * @return bool
     */
    public function reschedule_appointment() {
        $appointment = Appointments::find($this->id);
        $appointment->time = new \Date($this->request->date . ' ' . $this->request->time);
//$appointment->procedure = $this->request->procedure;
        $appointment->doctor = $this->request->doctor;
        $appointment->instructions = $this->request->instructions;
        $appointment->clinic = $this->request->clinic;
        $appointment->category = $this->request->category;
        if ($appointment->save()) {
//  self::sendRescheduleNotification($this->id);
            $this->request->session()->flash('success', "Appointment has been rescheduled");
            return true;
        }
        $this->request->session()->flash('error', "An error occurred");
        return false;
    }

    /**
     * Add an appointment for patient
     * @param Request $this ->request
     * @return bool
     */
    public function add_appointment() {
        $appointment = new Appointments;
        if (is_numeric($this->request->patient)) {
            $appointment->patient = $this->request->patient;
        } else {
            $appointment->guest = $this->request->patient;
        }
        $appointment->time = new \Date($this->request->date . ' ' . $this->request->time);
//$appointment->procedure = $this->request->procedure;
        $appointment->doctor = $this->request->doctor;
        $appointment->instructions = $this->request->instructions;
        $appointment->clinic = $this->request->clinic;
        $appointment->category = $this->request->category;
        if ($appointment->save()) {
//dispatch(new \Dervis\Jobs\SendNotificationSMS($appointment->schedule_id), 'reminders');
//  sendAppointmentNotification($appointment);
            flash("Appointment has been saved", 'success');
            return true;
        }
        flash("An error occurred", 'danger');
        return false;
    }

    /**
     * Upload patient document
     * @param int $patient
     * @return bool
     */
    public function upload_document($patient) {
        $file = $this->request->file('doc');
        if (empty($file) || !$file->isValid()) {
            flash()->warning("Invalid file. Upload aborted");
            return false;
        }
        $document = new PatientDocuments;
        $document->patient = $patient;
        $document->document = base64_encode(file_get_contents($file->getRealPath()));
        $document->filename = $file->getClientOriginalName();
        $document->mime = $file->getClientMimeType();
        $document->document_type = $this->request->document_type;
        $document->description = $file->getSize();
        $document->user = $this->request->user()->id;
        if ($document->save()) {
            flash()->success("Patient Document saved");
            return true;
        }
        flash()->error("An error occurred");
        return false;
    }

}
