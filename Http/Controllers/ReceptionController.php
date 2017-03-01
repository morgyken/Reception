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
use Ignite\Reception\Entities\Spine; //temp
use Ignite\Reception\Entities\PatientInsurance;
use Illuminate\Support\Facades\Auth;
use Ignite\Reception\Entities\NextOfKin;
use Ignite\Evaluation\Entities\Procedures;

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

    public function purge_patient(Request $request) {
        try {
            $patient = Patients::find($request->id);
            ///$patient->delete();
            flash("Patient Information Deleted", 'success');
            return back();
        } catch (\Exception $ex) {
            flash("Patient Information Could not be deleted", 'error');
            return back();
        }
    }

    /**
     * Save patient information
     * @param CreatePatientRequest $request
     * @param int|null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save_patient(CreatePatientRequest $request, $id = null) {
        if ($this->receptionRepository->add_patient($request, $request->id)) {
            //$this->savePatientScheme($request, $request->id);
            flash("Patient Information saved", 'success');
        }
        return redirect()->route('reception.add_patient');
    }

    public function savePatientScheme(Request $request, $patient) {
        $ins = new PatientInsurance;
        $ins->patient = $patient;
        $ins->scheme = $request->scheme1;
        $ins->policy_number = $request->policy_number1;
        $ins->principal = $request->principal1;
        $ins->dob = $request->principal_dob1;
        $ins->relationship = $request->principal_relationship1;
        $ins->save();
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

    public function appointments_res(CreateAppointmentRequest $request) {
        $this->receptionRepository->reschedule_appointment($request, $request->id);
        return redirect()->route('reception.appointments');
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
            $this->data['precharge'] = Procedures::where("precharge", "=", 1)->get();
            return view('reception::checkin_patient', ['data' => $this->data]);
        }
        $this->data['patients'] = Patients::all();
        return view('reception::checkin', ['data' => $this->data]);
    }

    public function patients_queue() {
        $this->data['visits'] = Visit::with('destinations')->get();
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

    /*
      public function Skipper() {

      /*
      $ins = Spine::all();
      $n = 0;
      foreach ($ins as $i) {
      $n+=1;
      $firm = new \Ignite\Settings\Entities\Insurance;
      $firm->id = $i->Insurance_ID;
      $firm->name = $i->Insurance_Name;
      $firm->post_code = $i->Post_Code;
      $firm->street = $i->Street;
      $firm->town = $i->City;
      $firm->email = $i->Email;
      $firm->telephone = $i->Telephone;
      $firm->save();
      echo $n . '<br/>';
      } */
    /*
      //\DB::transaction(function () {
      $spine = Spine::all();
      //dd($spine);
      foreach ($spine as $p) {
      //patient first
      $patient = new Patients; //::findOrNew($p->Patient_ID);
      $patient->id = $p->Patient_ID;
      $patient->first_name = ucfirst($p->First_Name);
      $patient->middle_name = ucfirst($p->Middle_Name);
      $patient->last_name = ucfirst($p->Last_Name);

      $patient->id_no = $p->ID_Passport_Number;
      $patient->dob = new \Date($p->Date_Of_Birth);

      $patient->sex = $p->Sex ? $p->Sex : 'male';
      //$patient->telephone = $p->telephone;
      $patient->mobile = $p->Mobile_Number;
      //$patient->alt_number = $this->request->alt_number;
      $patient->email = strtolower($p->Email_Address);
      $patient->address = $p->Postal_Address;
      $patient->post_code = $p->Post_Code;
      //$patient->town = ucfirst($this->request->town);
      //if ($this->request->has('imagesrc')) {
      //  $patient->image = $this->request->imagesrc;
      //}
      $patient->save();

      //next of kins
      if (isset($p->NOK_First_Name)) {
      $nok = NextOfKin::findOrNew($patient->id);
      $nok->patient = $patient->id;
      $nok->first_name = ucfirst($p->NOK_First_Name);
      $nok->middle_name = ucfirst($p->NOK_Middle_Name);
      $nok->last_name = ucfirst($p->NOK_Last_Name);
      $nok->mobile = $p->NOK_Mobile;
      $nok->relationship = $p->NOK_Relationship_ID;
      $nok->save();
      }
      //if ($patient->insured == 1) {
      if ($p->PI_Insurance_Provider_ID > 0) {
      //foreach ((array) $this->request->scheme1 as $key => $scheme) {
      // $s = new \Ignite\Settings\Entities\Schemes;
      // $s->company = $p->PI_Insurance_Provider_ID;
      // $s->name = $p->PI_Plan_Name;
      // $s->save();

      $schemes = new PatientInsurance;
      $schemes->patient = $patient->id;
      $schemes->scheme = $p->PI_Insurance_Provider_ID;
      $schemes->policy_number = $p->PI_Policy_Number;
      $schemes->principal = ucwords($p->PI_Subscriber);
      $schemes->dob = new \Date($p->PI_Date_Of_Birth);
      $schemes->relationship = $p->PI_Relationship_ID;
      $schemes->save();
      // }
      }
      //flash()->success($patient->full_name . " details saved. $addon");
      }
      echo 'Done';
      // });
      }
     */
}
