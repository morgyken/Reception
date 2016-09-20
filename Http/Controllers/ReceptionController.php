<?php

namespace Ignite\Reception\Http\Controllers;
use Ignite\Core\Http\Controllers\AdminBaseController;
use Ignite\Reception\Entities\AppointmentCategory;
use Ignite\Reception\Entities\Appointments;
use Ignite\Reception\Http\Requests\CreateAppointmentRequest;
use Ignite\Reception\Http\Requests\CreatePatientRequest;
use Ignite\Reception\Library\ReceptionPipeline;
use Ignite\Settings\Entities\Clinics;
use Illuminate\Http\Request;
use Ignite\Reception\Entities\Patients;
use Ignite\Reception\Library\ReceptionFunctions;
use Ignite\Core\Library\Validation;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;
use Ignite\Reception\Entities\PatientDocuments;

class ReceptionController extends AdminBaseController {

    /**
     * Show form for creating patient
     * @param null|int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add_patient($id = null) {
        $this->data['patient'] = Patients::findOrNew($id);
        if (!empty($id)) {
            return view('reception::edit_patient')->with('data', $this->data);
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
        if (ReceptionFunctions::add_patient($request, $id)) {
            flash("Patient Information saved");
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
    public function save_appointment(CreateAppointmentRequest $request, $id = null) {
        if (!empty($id)) {
            ReceptionFunctions::reschedule_appointment($request, $id);
            return redirect()->route('reception.appointments');
        }
        $this->validate($request, Validation::validate_patient_schedule());
        if (ReceptionFunctions::add_appointment($request)) {
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
        return view('reception::patient_documents')->with('data', $this->data);
    }

    public function upload_doc(Request $request, $id) {
        if ($request->isMethod('post')) {
            // $this->validate($request, validate_patient_documents());
            if (ReceptionFunctions::upload_document($request, $id)) {
                $route = \Illuminate\Support\Facades\URL::previous(); // could be uploaded from anywhere, find where
                return redirect($route); // return redirect()->route('reception.upload_doc', $id);
            }
        }
        $this->data['patient'] = Patients::find($id);
        $this->data['docs'] = PatientDocuments::wherePatient($id)->get();
        return view('reception::upload_doc')->with('data', $this->data);
    }

    public function checkin(Request $request, $patient_id = null, $visit_id = null) {
        if (!empty($patient_id)) {
            if ($request->isMethod('post')) {
                $this->validate($request, Validation::validate_checkin());
                if (ReceptionFunctions::checkin_patient($request, $visit_id)) {
                    return redirect()->route('reception.patients_queue');
                }
            }
            $this->data['visits'] = \Ignite\Evaluation\Entities\PatientVisits::wherePatient($patient_id)->get();
// \Dervis\Model\Reception\Appointments::wherePatient($patient_id)->get();
            $this->data['patient'] = Patients::find($patient_id);
            return view('reception::checkin_patient')->with('data', $this->data);
        }
        $this->data['patients'] = Patients::all();
        return view('reception::checkin')->with('data', $this->data);
    }

    public function patients_queue() {
        $this->data['visits'] = \Ignite\Evaluation\Entities\PatientVisits::all();
        return view('reception::patients_queue')->with('data', $this->data);
    }

    public function document_viewer($id) {
        $file_meta = PatientDocuments::find($id);
        header("Content-type: $file_meta->mime");
        header("Content-Disposition: inline; filename='$file_meta->filename'");
        header("Content-Transfer-Encoding:binary");
        header("Accept-Ranges:bytes");
        echo base64_decode($file_meta->document);
    }

}
