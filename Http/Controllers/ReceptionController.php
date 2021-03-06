<?php

namespace Ignite\Reception\Http\Controllers;

use Ignite\Core\Http\Controllers\AdminBaseController;
use Ignite\Evaluation\Entities\Visit;
use Ignite\Inventory\Entities\InventoryBatchProductSales;
use Ignite\Reception\Entities\AppointmentCategory;
use Ignite\Reception\Entities\Appointments;
use Ignite\Reception\Http\Requests\CheckinPatientRequest;
use Ignite\Reception\Http\Requests\CreateAppointmentRequest;
use Ignite\Reception\Http\Requests\CreatePatientRequest;
use Ignite\Reception\Library\ReceptionPipeline;
use Ignite\Reception\Repositories\ReceptionRepository;
use Ignite\Settings\Entities\Clinics;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Input;
use Ignite\Reception\Entities\Patients;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;
use Ignite\Reception\Entities\PatientDocuments;
use Ignite\Reception\Entities\PatientInsurance;
use Ignite\Evaluation\Entities\ExternalOrders;

class ReceptionController extends AdminBaseController
{

    /**
     * @var ReceptionRepository
     */
    protected $receptionRepository;
    protected $validator;

    /**
     * ReceptionController constructor.
     * @param ReceptionRepository $receptionRepository
     */
    public function __construct(ReceptionRepository $receptionRepository)
    {
        parent::__construct();
        $this->receptionRepository = $receptionRepository;
    }

    /**
     * Show form for creating patient
     * @param null|int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add_patient($id = null)
    {
        $this->data['patient'] = Patients::findOrNew($id);
        if (!empty($id)) {
            return view('reception::edit_patient', ['data' => $this->data]);
        }

        $this->data['use_id'] = (int) (Patients::all()->pluck('patient_no')->max() ?? m_setting('reception.patient_start_at')) + 1;

        return view('reception::add_patient', ['data' => $this->data]);
    }

    public function purge_patient(Request $request)
    {
        try {
            $patient = Patients::find($request->id);
            $this->deletePatientData($request->id);
            $patient->forceDelete();
            flash("Patient Information Deleted", 'success');
            return back();
        } catch (\Exception $ex) {
            flash("Patient Information Could not be deleted", 'error');
            return back();
        }
    }

    public function deletePatientData($id)
    {
        $visits = Visit::wherePatient($id)->get();
        foreach ($visits as $v) {
            $v->delete();
            $sale = InventoryBatchProductSales::wherePatient($id)
                ->orWhere('visit', '==', $v->id)
                ->get();
            foreach ($sale as $s) {
                $s->delete();
            }
        }
        $docs = PatientDocuments::wherePatient($id);
        foreach ($docs as $d) {
            $d->delete();
        }
    }

    /**
     * Checks if the patient exists and is available
     * @param Returns request/Requests
     * @return bool
     */

    public function checkif_registered(Request $request)
    {   
     

    // dd($request->mobile);

      $patient = Patients::select('id', 'created_at', 'last_name', 'first_name','mobile','dob', 'sex', 'age_in' )

        //check date of birth

        ->where('dob' ,$request->dob)

          //check id number

          ->where('id_no',  $request->id_number)           

          ->get();
       
        if($patient->count() >0) { 
            
            return false;

        }
              
        
        return true;
            
    }
    
    /**
     * Save patient information
     * @param CreatePatientRequest $request
     * @param int|null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save_patient(Request $request, $id = null)
    {  
    
       //do validation

        if($this-> doValidation($request) ==true)
            {

                //check if exists 

                if($this-> checkif_registered($request) == true)
                {
                    if ($this->receptionRepository->add_patient()) {
                              
                              flash("Patient Information saved", 'success');
                              if ($request->has('save_and_checkin')) {
                                  return redirect()->route('reception.checkin', \Session::get('patient_just_created'));
                              }
                          }
                          return redirect()->route('reception.add_patient');
                }

                else 
                {

                    flash("The patient already exist, Check details carefully", 'error');
                    return redirect()->back();

                }
            }
            else {
                flash("ERROR: Check the following fields need correction.", 'error');

                return redirect()->back()->withErrors($this->validator);
            }

}     


    //save patient Scheme...

    public function savePatientScheme(Request $request, $patient)
    {
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
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_patients(Request $request)
    {
        $this->data['patients'] = Patients::paginate(10);
        return view('reception::patients', ['data' => $this->data]);
    }

    /**
     * View Patient
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view_patient($id)
    {
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
    public function appointments()
    {
        $this->data['clinics'] = Clinics::all();
        $this->data['categories'] = AppointmentCategory::all();
        $this->data['doctors'] = \Ignite\Users\Entities\User::whereHas('roles', function ($query) {
            $query->whereRole_id(5);
        })->get();
        return view('reception::appointments', ['data' => $this->data]);
    }

    /**
     * Save to database
     * @param CreateAppointmentRequest $request
     * @param int|null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function appointments_save(CreateAppointmentRequest $request, $id = null)
    {
        if (!empty($id)) {
            $this->receptionRepository->reschedule_appointment($request, $id);
            return redirect()->route('reception.appointments');
        }
        if ($this->receptionRepository->add_appointment($request)) {
            return redirect()->route('reception.appointments');
        }
    }

    public function appointments_res(CreateAppointmentRequest $request)
    {
        $this->receptionRepository->reschedule_appointment($request, $request->id);
        return redirect()->route('reception.appointments');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function calendar()
    {
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

    public function documents()
    {
        $this->data['patients'] = Patients::paginate(10);
        return view('reception::patient_documents', ['data' => $this->data]);
    }

    public function view_image(Request $request)
    {
        $this->data['image'] = PatientDocuments::find($request->id);
        return view('reception::view_image', ['data' => $this->data]);
    }

    public function upload_doc(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            if ($this->receptionRepository->upload_document($id)) {
                flash("Scan complete, all patient related files have been uploaded");
                return back();
            }
        }
        $this->data['patient'] = Patients::find($id);
        $this->data['docs'] = PatientDocuments::wherePatient($id)->get();
        return view('reception::upload_doc', ['data' => $this->data]);
    }

    public function bulk_upload(Request $request)
    {
        if ($request->isMethod('post')) {
            if ($this->receptionRepository->scan_and_upload($request)) {
                return back();
            }
        }
        $this->data['patient'] = Patients::find($request->id);
        $this->data['docs'] = PatientDocuments::wherePatient($request->id)->get();
        return view('reception::bulk_upload', ['data' => $this->data]);
    }

    public function do_check(CheckinPatientRequest $request, $patient_id = null, $visit_id = null)
    {
        if ($request['inpatient'] == true) {
            $request['inpatient'] = 1;
        }

//        $visit = Visit::wherePatient($request->patient)->first();
//
//        if($visit)
//        {
//            return redirect()->route('evaluation.preview', ['visit' => $visit->id,  'department' => 'nursing']);
//        }

        $request['visit_id'] = $visit_id;
        $request['clinic'] = 1;
        $request['user'] = $request['patient'];

        if ($visit = $this->receptionRepository->checkin_patient()) {
            if ($request->has('gas')) {
                return redirect()->route('evaluation.preview', [$visit, 'nursing']);
            }
            return redirect()->route('reception.patients_queue');
        }
    }

    public function checkin(Request $request, $patient_id = null, $visit_id = null)
    {
        if (!empty($patient_id)) {
            if (!is_null($request->order_id)) {
                $this->data['external_order'] = ExternalOrders::find($request->order_id);
            }

            $this->data['visits'] = Visit::wherePatient($patient_id)->get();
            $this->data['patient'] = Patients::find($patient_id);
            $this->data['precharge'] = \Ignite\Evaluation\Entities\Procedures::where("precharge", "=", 1)->get();
            $this->data['partners'] = \Ignite\Evaluation\Entities\PartnerInstitution::all();
            $this->data['external_doctors'] = \Ignite\Users\Entities\User::whereHas('profile', function ($query) {
                $query->whereHas('partnerInstitution');
            })->get();

            return view('reception::checkin_patient', ['data' => $this->data]);
        }
        $this->data['patients'] = Patients::paginate(10);
        return view('reception::checkin', ['data' => $this->data]);
    }

    public function external_checkin(Request $request)
    {
        $this->data['external_order'] = $order = ExternalOrders::find($request->order_id);
        $patient_id = $order->patient->id;
        $this->data['visits'] = Visit::wherePatient($patient_id)->get();
        $this->data['patient'] = Patients::find($patient_id);
        $this->data['precharge'] = \Ignite\Evaluation\Entities\Procedures::where("precharge", "=", 1)->get();
        $this->data['partners'] = \Ignite\Evaluation\Entities\PartnerInstitution::all();
        $this->data['external_doctors'] = \Ignite\Users\Entities\User::whereHas('profile', function ($query) {
            $query->whereHas('partnerInstitution');
        })->get();

        return view('reception::checkin_patient', ['data' => $this->data]);
    }

    public function patients_queue()
    {
        $this->data['visits'] = Visit::whereHas('destinations', function ($query) {
            $query->whereCheckout(0);
        })->orderBy('created_at', 'asc')
            ->latest()->paginate(100);
        return view('reception::patients_queue', ['data' => $this->data]);
    }

    public function SearchPatient(Request $request)
    {
        $patients = Patients::all();//where('first_name', 'like', '%' . base64_encode($request->key) . '%')->get();
        $found = array();
        $search = $request->search;
        foreach ($patients as $p) {
            if (str_contains($p->id_no, $request->search) ||
                str_contains(strtolower($p->full_name), strtolower($request->search)) ||
                str_contains(strtolower($p->mobile), strtolower($request->search))
            ) {
                $found[] = $p;
            }
        }
        return view('reception::search_results', compact('found', 'search'));
    }

    public function document_viewer($id)
    {
        $file_meta = PatientDocuments::find($id);
        header("Content-type: $file_meta->mime");
        header("Content-Disposition: inline; filename='$file_meta->filename'");
        header("Content-Transfer-Encoding:binary");
        header("Accept-Ranges:bytes");
        echo base64_decode($file_meta->document);
    }

    public function external_order_queue(Request $request)
    {
        $this->data['orders'] = \Ignite\Evaluation\Entities\ExternalOrders::whereNull('status')->get();
        return view('reception::external.queue', ['data' => $this->data]);
    }

    // public function Skipper() {
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

    //\DB::transaction(function () {
    /*
      $spine = \Ignite\Reception\Entities\Spine::all();
      //dd($spine);
      foreach ($spine as $p) {
      //patient first
      $patient = Patients::findOrNew($p->id); //new Patients; //::findOrNew($p->Patient_ID);
      $patient->id = $p->id;
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
      if (isset($p->NOK_First_Name) && $p->NOK_First_Name !== '-') {
      //dd($patient->id);
      $nok = new NextOfKin;
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
      /* $s = new \Ignite\Settings\Entities\Schemes;
      $s->company = $p->PI_Insurance_Provider_ID;
      $s->name = $p->PI_Plan_Name;
      dd($p->PI_Plan_Name);
      $s->save();
     */
    /*
      $schemes = new PatientInsurance;
      $schemes->patient = $patient->id;
      $schemes->scheme = $p->PI_Insurance_Provider_ID;
      $schemes->policy_number = $p->PI_Policy_Number;
      $schemes->principal = ucwords($p->PI_Subscriber);
      $schemes->dob = new \Date($p->PI_Date_Of_Birth);
      $schemes->relationship = $p->PI_Relationship_ID;
      $schemes->save();
      }
      // }
      //flash()->success($patient->full_name . " details saved. $addon");
      }
      echo 'Done';
      //});
      }
     *
     */

    public function ImportRawPatientData(Request $request)
    {
        \DB::transaction(function () {
            $data = \Ignite\Reception\Entities\Temp::all();
            $n = 0;
            foreach ($data as $p) {
                $names = explode(" ", $p->names);
                $count = count($names);
                // dd($names[1]);
                try {
                    $patient = new Patients;
                    $patient->first_name = ucfirst($names[0]);
                    if ($count > 2) {
                        $patient->middle_name = ucfirst($names[1]);
                        $patient->last_name = ucfirst($names[2]);
                    } else {
                        //$patient->middle_name = ucfirst($names[1]);
                        $patient->last_name = ucfirst($names[1]);
                    }
                    $patient->mobile = $p->contact;
                    $patient->patient_no = $p->dacm_no;
                    $patient->save();
                    $n += 1;
                } catch (\Exception $e) {

                }
            }
            echo $n . ' Patient Records Created... Thank you';
        });
    }

    public function patient_encrypter()
    {
        \DB::transaction(function () {
            $n = 0;
            $patients = \Ignite\Reception\Entities\Spine::all();
            //dd($patients);
            foreach ($patients as $p) {
                //patient first
                $patient = new  Patients();//::findOrNew($p->id); //new Patients; //::findOrNew($p->Patient_ID);
                //$patient->id = $p->id;
                $patient->first_name = ucfirst($p->f_name);
                $patient->middle_name = ucfirst($p->m_name);
                $patient->last_name = ucfirst($p->l_name);
                //$patient->id_no = $p->ID_Passport_Number;
                $patient->patient_no = $p->no;
//                $patient->dob = new \Date($p->Date_Of_Birth);
//
//                $patient->sex = $p->Sex ? $p->Sex : 'male';
//                //$patient->telephone = $p->telephone;
                $patient->mobile = $p->contact;
//                //$patient->alt_number = $this->request->alt_number;
//                $patient->email = strtolower($p->Email_Address);
//                $patient->address = $p->Postal_Address;
//                $patient->post_code = $p->Post_Code;
//                //$patient->town = ucfirst($this->request->town);
//                //if ($this->request->has('imagesrc')) {
//                //  $patient->image = $this->request->imagesrc;
//                //}
                $patient->save();
                $n += 1;
            }
            echo $n . 'Patients Saved, Done';
        });

    }

//      public function Skipper() {
//      /*$ins = Spine::all();
//      $n = 0;
//
//      foreach ($ins as $i) {
//      $n+=1;
//      $firm = new \Ignite\Settings\Entities\Insurance;
//      $firm->id = $i->Insurance_ID;
//      $firm->name = $i->Insurance_Name;
//      $firm->post_code = $i->Post_Code;
//      $firm->street = $i->Street;
//      $firm->town = $i->City;
//      $firm->email = $i->Email;
//      $firm->telephone = $i->Telephone;
//      $firm->save();
//      echo $n . '<br/>';
//      }
//      */
//    \DB::transaction(function () {
//
//      $spine = \Ignite\Reception\Entities\Spine::all();
//      //dd($spine);
//      foreach ($spine as $p) {
//      //patient first
//      $patient = Patients::findOrNew($p->id); //new Patients; //::findOrNew($p->Patient_ID);
//      $patient->id = $p->id;
//      $patient->first_name = ucfirst($p->First_Name);
//      $patient->middle_name = ucfirst($p->Middle_Name);
//      $patient->last_name = ucfirst($p->Last_Name);
//
//      $patient->id_no = $p->ID_Passport_Number;
//      $patient->dob = new \Date($p->Date_Of_Birth);
//
//      $patient->sex = $p->Sex ? $p->Sex : 'male';
//      //$patient->telephone = $p->telephone;
//      $patient->mobile = $p->Mobile_Number;
//      //$patient->alt_number = $this->request->alt_number;
//      $patient->email = strtolower($p->Email_Address);
//      $patient->address = $p->Postal_Address;
//      $patient->post_code = $p->Post_Code;
//      //$patient->town = ucfirst($this->request->town);
//      //if ($this->request->has('imagesrc')) {
//      //  $patient->image = $this->request->imagesrc;
//      //}
//      $patient->save();
//      //next of kins
//     /*
//      if (isset($p->NOK_First_Name) && $p->NOK_First_Name !== '-') {
//      //dd($patient->id);
//      $nok = new NextOfKin;
//      $nok->patient = $patient->id;
//      $nok->first_name = ucfirst($p->NOK_First_Name);
//      $nok->middle_name = ucfirst($p->NOK_Middle_Name);
//      $nok->last_name = ucfirst($p->NOK_Last_Name);
//      $nok->mobile = $p->NOK_Mobile;
//      $nok->relationship = $p->NOK_Relationship_ID;
//      $nok->save();
//      }
//      */
//      //if ($patient->insured == 1) {
//      if ($p->PI_Insurance_Provider_ID > 0) {
//      //foreach ((array) $this->request->scheme1 as $key => $scheme) {
//      /* $s = new \Ignite\Settings\Entities\Schemes;
//      $s->company = $p->PI_Insurance_Provider_ID;
//      $s->name = $p->PI_Plan_Name;
//      dd($p->PI_Plan_Name);
//      $s->save();
//     */
//
//      $schemes = new PatientInsurance;
//      $schemes->patient = $patient->id;
//      $schemes->scheme = $p->PI_Insurance_Provider_ID;
//      $schemes->policy_number = $p->PI_Policy_Number;
//      $schemes->principal = ucwords($p->PI_Subscriber);
//      $schemes->dob = new \Date($p->PI_Date_Of_Birth);
//      $schemes->relationship = $p->PI_Relationship_ID;
//      $schemes->save();
//      }
//      // }
//      //flash()->success($patient->full_name . " details saved. $addon");
//      }
//      echo 'Done';
//      });
//      }

}
