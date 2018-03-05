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

use Carbon\Carbon;
use Ignite\Evaluation\Entities\ExternalOrders;
use Ignite\Evaluation\Entities\Visit;
use Ignite\Evaluation\Entities\VisitDestinations;
use Ignite\Finance\Entities\Copay;
use Ignite\Reception\Entities\PatientInsurance;
use Ignite\Reception\Events\AppointmentCreated;
use Ignite\Reception\Events\AppointmentRescheduled;
use Ignite\Reception\Repositories\ReceptionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ignite\Reception\Entities\Patients;
use Ignite\Reception\Entities\NextOfKin;
use Ignite\Reception\Entities\Appointments;
use Ignite\Reception\Entities\PatientDocuments;
use Ignite\Evaluation\Entities\Investigations;
use Ignite\Evaluation\Entities\Procedures;

/**
 * Description of ReceptionFunctions
 *
 * @author Samuel Dervis <samueldervis@gmail.com>
 */
class ReceptionFunctions implements ReceptionRepository
{

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
    public function __construct()
    {
        $this->request = request();
        if ($this->request->has('id')) {
            $this->id = $this->request->id;
        }
    }

    /**
     * Performs a fast forward checkin. Just dive in to checkin without prior appointments
     * @return int
     */
    public function checkin_patient()
    {
//        if(Visit::wherePatient($this->request->patient)->get())
//        {
//            flash('Sorry! This patient is already checked in!', 'error');
//
//            return redirect()->back();
//        }

        \DB::beginTransaction();
        $visit = new Visit;
        $visit->patient = $this->request->patient;
        $visit->clinic = session('clinic', 1);
        if ($this->request->has('external_order')) {
            $visit->external_order = $this->request->external_order;
        }
        if ($this->request->destination == 13) {
            $visit->inpatient = 'on';
        }

        if ($this->request->has('purpose')) {
            $visit->purpose = $this->request->purpose;
        }

        $visit->payment_mode = $this->request->payment_mode;
        $visit->user = $this->request->user()->id;
        $attempt_copay = false;
        if ($this->request->has('scheme')) {
            $visit->scheme = $this->request->scheme;
            $attempt_copay = true;
        }
        //External Doctor Requests (Applies to Externally Ordered Labs)
        if ($this->request->has('external_doctor')) {
            $visit->external_doctor = $this->request->external_doc;
        }
        $visit->save();
        if ($attempt_copay) {
            $this->save_copay($visit);
        }
        if ($this->request->has('external_order')) {
            $visit->external_order = $this->request->external_order;
            $this->order_procedures($this->request->ordered_procedure, $visit);
            $this->updateExternalOrder($this->request->external_order);
        }

        if ($this->request->has('as_ordered')) {
            //From external order
            foreach ($this->request->destination as $destination) {
                $this->checkin_at($visit->id, $destination);
            }
        } else {
            $this->checkin_at($visit->id, $this->request->destination);
        }

        if ($this->request->has('to_nurse')) { //quick way to forge an entry to nurse section
            $this->checkin_at($visit->id, 'nursing');
        }

        //precharge
        if ($this->request->has('precharge')) {
            $this->order_procedures($this->request->precharge, $visit);
        }
        flash('Patient has been checked in', 'success');
        \DB::commit();
        reload_payments();
        return $visit->id;
    }

    public function order_procedures($procedures, Visit $visit)
    {
        try {
            foreach ($procedures as $key => $value) {
                $inv = new Investigations;
                $procedure = Procedures::find($value);
                $inv->visit = $visit->id;

                if (preg_match('/Lab/', $procedure->categories->name)) {
                    $inv->type = 'laboratory';
                } else {
                    $inv->type = strtolower($procedure->categories->name);
                }
                $inv->procedure = $value;
                $inv->quantity = 1;
                $inv->discount = 0;
                $inv->price = $inv->amount = $visit->payment_mode === 'cash' ? $procedure->cash_charge : $procedure->insurance_charge;
                if (filter_var($this->request->destination, FILTER_VALIDATE_INT)) {
                    $inv->user = $this->request->destination;
                }
                $inv->ordered = 1;
                $inv->save();
            }
        } catch (\Exception $e) {

        }
    }

    public function updateExternalOrder($id)
    {
        $order = ExternalOrders::find($id);
        $order->status = 'processed';
        return $order->update();
    }

    /**
     * New way to checkin patient
     * @param $visit
     * @param $place
     * @return bool
     */
    private function checkin_at($visit, $place)
    {
        $department = $place;
        $destination = NULL;
        $room = null;
        if ((int)$place > 0) {
            $destination = (int)$department;
            $department = 'doctor';
        }
        if (starts_with($place, 'R-')) {
            $room = substr($place, 2);
            $department = 'doctor';
        }
        /** @var VisitDestinations $destinations */
        $destinations = VisitDestinations::firstOrNew(['visit' => $visit, 'department' => ucwords($department)]);
        $destinations->destination = $destination;
        $destinations->user = $this->request->user()->id;
        if ($room) {
            $destinations->room_id = $room;
        }
        return $destinations->save();
    }

    /**
     * Saves a new patient model to database. Updates patient if ID parameter supplied
     * @param Request $this ->request
     * @return bool
     */
    public function add_patient()
    {
        \DB::beginTransaction();
        /** @var Patients $patient */
        $patient = Patients::findOrNew($this->id);
        $patient->first_name = ucfirst($this->request->first_name);
        $patient->middle_name = ucfirst($this->request->middle_name);
        $patient->last_name = ucfirst($this->request->last_name);
        $patient->id_no = $this->request->id_number;
        $patient->dob = new \Date($this->request->dob);
        $patient->sex = $this->request->sex;
        $patient->telephone = $this->request->telephone;
        $patient->mobile = $this->request->mobile;
        $patient->alt_number = $this->request->alt_number;
        if(!$this->id)
        {
            $patient->patient_no = (int) (Patients::all()->pluck('patient_no')->max() ?? m_setting('reception.patient_start_at')) + 1;
//                (Patients::max('patient_no') ?? m_setting('reception.patient_start_at')) + 1;
        }
        $patient->email = strtolower($this->request->email);
        $patient->address = $this->request->address;
        $patient->post_code = $this->request->post_code;
        $patient->town = ucfirst($this->request->town);
        if ($this->request->has('imagesrc')) {
            $patient->image = $this->request->imagesrc;
        }
        if ($this->request->has('external_institution')) {
            $patient->external_institution = $this->request->external_institution;
        }
        if ($this->request->has('age')) {
            $patient->age = $this->request->age;
            $patient->age_in = $this->request->age_in;
            if ($this->request->dob == '') {
                $age = $this->request->age;
                $age_in = $this->request->age_in;
//                    $patient->dob = $this->reverse_birthday($age, $age_in);
                $patient->dob = Carbon::parse("- $age $age_in")->toDateString();
            }
        }
        $patient->save();

        //next of kins
        if ($this->request->has('first_name_nok')) {
            foreach ($this->request->first_name_nok as $key => $value) {
                try {
                    $nok = NextOfKin::findOrNew($this->id);
                    $nok->patient = $patient->id;
                    $nok->first_name = ucfirst($this->request->first_name_nok[$key]);
                    $nok->middle_name = ucfirst($this->request->middle_name_nok[$key]);
                    $nok->last_name = ucfirst($this->request->last_name_nok[$key]);
                    $nok->mobile = $this->request->mobile_nok[$key];
                    $nok->relationship = $this->request->nok_relationship[$key];
                    $nok->save();
                } catch (\Exception $e) {
                    //Something weird may have happened
                }
            }
        }
        //if ($patient->insured == 1) {
        if (isset($this->request->insured)) {
            //dd($this->request->insured);
            if ($this->request->insured == 1) {
                try {
                    //foreach ((array) $this->request->scheme1 as $key => $scheme) {
                    $schemes = new PatientInsurance;
                    $schemes->patient = $patient->id;
                    $schemes->scheme = $this->request->scheme1;
                    $schemes->policy_number = $this->request->policy_number1;
                    $schemes->principal = ucwords($this->request->principal1);
                    $schemes->dob = $this->request->principal_dob1;//Carbon::createFromDate($this->request->principal_dob1);
                    $schemes->relationship = $this->request->principal_relationship1;
                    $schemes->save();
                } catch (\Exception $exception) {

                }
            }
        }
        $addon = "Click <a href='" . route('reception.checkin', $patient->patient_id) . "'>here</a> to checkin";
        flash()->success($patient->full_name . " details saved. $addon");

        if ($this->request->has('save_and_checkin')) {
            session(['patient_just_created' => $patient->id]);
            // return redirect()->route('reception.checkin', $patient->id);
        }
        \DB::commit();
        return true;
    }


    /**
     * @return bool
     */
    public function reschedule_appointment()
    {
        $appointment = Appointments::find($this->id);
        $appointment->time = new \Date($this->request->date . ' ' . $this->request->time);
//$appointment->procedure = $this->request->procedure;
        $appointment->doctor = $this->request->doctor;
        $appointment->instructions = $this->request->instructions;
        $appointment->clinic = $this->request->clinic;
        $appointment->category = $this->request->category;
        if ($appointment->save()) {
            event(new AppointmentRescheduled($appointment));
            flash('Appointment has been rescheduled', 'success');
            return true;
        }
        flash('An error occurred', 'danger');
        return false;
    }

    /**
     * Add a new appointment
     * @return bool
     */
    public function add_appointment()
    {
        $appointment = new Appointments;
        if (is_numeric($this->request->patient)) {
            $appointment->patient = $this->request->patient;
        } else {
            $appointment->guest = ucwords($this->request->patient);
        }
        $appointment->time = new \Date($this->request->date . ' ' . $this->request->time);
//$appointment->procedure = $this->request->procedure;
        $appointment->doctor = $this->request->doctor;
        $appointment->instructions = $this->request->instructions;
        $appointment->clinic = $this->request->clinic;
        $appointment->category = $this->request->category;
        if ($appointment->save()) {
            event(new AppointmentCreated($appointment));
            flash('Appointment has been saved', 'success');
            return true;
        }
        flash('An error occurred', 'danger');
        return false;
    }

    /**
     * Upload patient document
     * @param int $patient
     * @return bool
     */
    public function upload_document($patient)
    {
        $file = $this->request->file('doc');
        if (empty($file) || !$file->isValid()) {
            flash()->warning("Invalid file. Upload aborted");
            return false;
        }

        $document = new PatientDocuments;
        $document->patient = $patient;

        if ($this->request->has('imagesrc')) {
            if (!($this->request->imagesrc == 'not_image')) {
                $document->document = $this->request->imagesrc;
            } else {
                $document->document = base64_encode(file_get_contents($file->getRealPath()));
            }
        }
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

    public function scan_and_upload(Request $request)
    {
        $path = $request->path;
        $files = \File::allFiles($path);
        $patients = Patients::all();
        foreach ($patients as $p) {
            foreach ($files as $file) {
                $f = pathinfo($file);
                $filename = $f['filename'];
                if ($filename == $p->patient_no) {
                    $this->bulk_uploader($p->id, $file);
                }
            }
        }
        flash()->success('Scan complete, all patient related files have been uploaded');
    }

    /**
     * @param Visit $visit
     * @return bool
     */
    public function save_copay(Visit $visit)
    {
        if ($visit->patient_scheme->schemes->type == 3) {
            $copay = new Copay();
            $copay->visit_id = $visit->id;
            $copay->patient_id = $visit->patient;
            $copay->scheme_id = @$visit->patient_scheme->schemes->id;
            $copay->company_id = @$visit->patient_scheme->schemes->company;
            $copay->amount = @$visit->patient_scheme->schemes->amount;
            return $copay->save();
        }
        return false;
    }

    public function bulk_uploader($patient, $file)
    {
        $f = pathinfo($file);
        $document = new PatientDocuments;
        $document->patient = $patient;
        $document->document = base64_encode(file_get_contents($file->getRealPath()));
        $document->filename = $f['basename'];
        $document->mime = $this->mime($file);
        $document->document_type = $f['extension'];
        $document->description = $file->getSize();
        $document->user = $this->request->user()->id;
        $document->save();
    }

    public function mime($file)
    {
        $mime = finfo_open(FILEINFO_MIME_TYPE);
        return finfo_file($mime, $file->getRealPath());
    }

}
