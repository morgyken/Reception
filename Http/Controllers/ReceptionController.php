<?php

namespace Ignite\Reception\Http\Controllers;

use Ignite\Core\Http\Controllers\AdminBaseController;
use Ignite\Evaluation\Entities\Visit;
use Ignite\Reception\Entities\AppointmentCategory;
use Ignite\Reception\Entities\Appointments;
use Ignite\Reception\Http\Requests\CheckinPatientRequest;
use Ignite\Reception\Http\Requests\CreateAppointmentRequest;
use Ignite\Reception\Http\Requests\CreatePatientRequest;
use Ignite\Reception\Library\ReceptionPipeline;
use Ignite\Reception\Repositories\ReceptionRepository;
use Ignite\Settings\Entities\Clinics;
use Illuminate\Http\Request;
use Ignite\Reception\Entities\Patients;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;
use Ignite\Reception\Entities\PatientDocuments;
use Ignite\Reception\Entities\Spine;
use Illuminate\Support\Facades\Auth;
use Ignite\Reception\Entities\Spine_Visit;

class ReceptionController extends AdminBaseController {

    /**
     * @var ReceptionRepository
     */
    protected $receptionRepository;

    /**
     * ReceptionController constructor.
     * @param ReceptionRepository $receptionRepository
     */
    public function __construct(ReceptionRepository $receptionRepository) {
        parent::__construct();
        $this->receptionRepository = $receptionRepository;
    }

    /**
     * Show form for creating patient
     * @param null|int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add_patient($id = null) {
        $this->data['patient'] = Patients::findOrNew($id);
        if (!empty($id)) {
            return view('reception::edit_patient', ['data' => $this->data]);
        }
        return view('reception::add_patient', ['data' => $this->data]);
    }

    /**
     * Save patient information
     * @param CreatePatientRequest $request
     * @param int|null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save_patient(CreatePatientRequest $request, $id = null) {
        if ($this->receptionRepository->add_patient($request, $request->id)) {
            flash("Patient Information saved", 'success');
        }
        return redirect()->route('reception.add_patient');
    }

    /**
     * List all patients
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_patients() {
        $this->data['patients'] = Patients::all();
        return view('reception::patients', ['data' => $this->data]);
    }

    /**
     * View Patient
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view_patient($id) {
        $this->data['patient'] = Patients::with('nok')->find($id);
        $this->data['docs'] = PatientDocuments::wherePatient($id)->get();
        if (empty($this->data['patient'])) {
            flash()->warning('The selected patient is not available');
            return redirect()->route('reception.show_patients');
        }
        return view('reception::view_patient', ['data' => $this->data]);
    }

    /**
     * Get all appointments
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function appointments() {
        $this->data['clinics'] = Clinics::all();
        $this->data['categories'] = AppointmentCategory::all();
        return view('reception::appointments', ['data' => $this->data]);
    }

    /**
     * Save to database
     * @param CreateAppointmentRequest $request
     * @param int|null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function appointments_save(CreateAppointmentRequest $request, $id = null) {
        if (!empty($id)) {
            $this->receptionRepository->reschedule_appointment($request, $id);
            return redirect()->route('reception.appointments');
        }
        if ($this->receptionRepository->add_appointment($request)) {
            return redirect()->route('reception.appointments');
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function calendar() {
        ReceptionPipeline::calendar_assets($this->assetPipeline, $this->assetManager);
        $events = Appointments::all();
        $this->data['calendar'] = Calendar::addEvents($events)
                        ->setOptions(calendar_options())
                        ->setCallbacks([
                            'dayClick' => 'function(date, jsEvent, view) { changeView(view,date);}'
                        ])->setId('sam');
        return view('reception::calendar', ['data' => $this->data]);
    }

    /*
      public function appointments($id = null) {
      return view('reception::patient_schedule', ['data', $this->data]);
      } */

    public function documents() {
        $this->data['patients'] = Patients::all();
        return view('reception::patient_documents', ['data' => $this->data]);
    }

    public function upload_doc(Request $request, $id) {
        if ($request->isMethod('post')) {
            if ($this->receptionRepository->upload_document($id)) {
                return back();
            }
        }
        $this->data['patient'] = Patients::find($id);
        $this->data['docs'] = PatientDocuments::wherePatient($id)->get();
        return view('reception::upload_doc', ['data' => $this->data]);
    }

    public function do_check(CheckinPatientRequest $request, $patient_id = null, $visit_id = null) {
        if ($this->receptionRepository->checkin_patient($request, $visit_id)) {
            return redirect()->route('reception.patients_queue');
        }
    }

    public function checkin($patient_id = null, $visit_id = null) {
        if (!empty($patient_id)) {
            $this->data['visits'] = Visit::wherePatient($patient_id)->get();
            $this->data['patient'] = Patients::find($patient_id);
            return view('reception::checkin_patient', ['data' => $this->data]);
        }
        $this->data['patients'] = Patients::all();
        return view('reception::checkin', ['data' => $this->data]);
    }

    public function patients_queue() {
        $this->data['visits'] = Visit::all();
        return view('reception::patients_queue', ['data' => $this->data]);
    }

    public function document_viewer($id) {
        $file_meta = PatientDocuments::find($id);
        header("Content-type: $file_meta->mime");
        header("Content-Disposition: inline; filename='$file_meta->filename'");
        header("Content-Transfer-Encoding:binary");
        header("Accept-Ranges:bytes");
        echo base64_decode($file_meta->document);
    }

    public function Spine() {
        //$patient = Spine::query()->where('id', '>=', 1)->get();
        //$this->migrate_patient($patient);
        $visits = Spine_Visit::query()->where('id', '>=', 1)->get();
        $this->migrate_visits($visits);
    }

    public function migrate_patient($data) {
        foreach ($data as $d) {
            $p = new Patients;
            $p->id = $d->id;
            $p->first_name = $d->First_Name;
            $p->middle_name = $d->Midle_Name;
            $p->last_name = $d->Last_Name;
            $p->dob = new \Date($d->Date_Of_Birth);
            $p->sex = $d->Sex;
            $p->id_no = $d->ID_Passport_Number;
            $p->address = $d->Postal_Address;
            $p->mobile = $d->Mobile_Number;
            $p->email = $d->Email_Address;
            $p->save();
        }
        echo 'Patient Data Migration Successfull... Thank you<br/>';
    }

    public function migrate_visits($data) {
        foreach ($data as $d) {
            $visit = new Visit;
            $visit->clinic = 1;
            $visit->patient = $d->patient;
            if ($visit->scheme > 0) {
                $visit->scheme = $d->scheme;
            }
            $visit->purpose = NULL; //$d->purpose;
            $visit->payment_mode = $d->payment_mode;
            $visit->user = Auth::user()->id;
            $visit->created_at = $d->check_in;
            $visit->save();
        }
        echo 'Visit Data Migration Successfull... Thank you<br/>';
    }

}
