<?php

namespace Ignite\Reception\Http\Controllers;

use Ignite\Reception\Entities\PatientDocuments;
use Ignite\Reception\Entities\Patients;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Ignite\Reception\Entities\Appointments;
use Ignite\Evaluation\Entities\Visit;
use Ignite\Core\Foundation\ShouldEncrypt;

class ApiController extends Controller {

    use ShouldEncrypt;

    public function get_schedule(Request $request) {
        $this->data['appointments'] = get_appointments($request);
        //return Response::json(get_appointments($request));
        return view('reception::partials.view_appointments', ['data' => $this->data]);
    }

    public function cancel_checkin(Request $request) {
        return Response::json(Visit::destroy($request->id));
    }

    public function reschedule(Request $request) {
        $this->data['form'] = Appointments::find($request->id);
        $this->data['id'] = $request->id;
        return view('reception::partials.reschedule')->with('data', $this->data);
    }

    public function cancel_appointment(Request $request) {
        $appointment = Appointments::destroy($request->id);
        return Response::json($appointment);
    }

    public function change_destination(Request $request) {
        $meta = Visit::find($request->id);
        $meta->destination = $request->new_dest;
        return Response::json($meta->save());
    }

    public function get_patients(Request $request) {
        $ret = [];
        $patients = \Ignite\Reception\Entities\Patients::all();
        $term = array_get($request->term, 'term');
        $term = ucfirst($request->q);
        if (!empty($term)) {
            //$found = \Ignite\Reception\Entities\Patients::where('concat(first_name)', 'like', "%$term%")->get();
        }
        $found = Patients::where('first_name', 'like', "%$term%")->get();
        //$found = PatientInsurance::with('schemes')
        //        ->get();

        //Add event listeners
        $build = [];
        foreach ($patients as $patient) {
            if (
                str_contains(strtolower($patient->full_name), strtolower($term))||
                str_contains($patient->id_no, $term)
            ){
                $name = $patient->fullname;
                $id = $patient->id;
                $build[] = [
                    'text' => $name,
                    'id' => $id];
            }
        }
        $ret['results'] = $build;
        return json_encode($ret);
    }

    public function get_checkin_patients(Request $request) {
        $rows = '';
        $patients = Patients::all();
        foreach ($patients as $patient) {
            if (
                str_contains($patient->id_no, $request->term)
                ||str_contains(strtolower($patient->full_name), strtolower($request->term))
            ) {
                $rows.= '<tr>
                    <td>' . $patient->id . '</td>
                    <td>' . $patient->id_no . '</td>
                    <td>' . $patient->fullname . '</td>
                    <td>' . $patient->checked_in_status . '</td>
                    <td><a href=' . route('reception.checkin', $patient->id) . '>
                            <i class="fa fa-sign-in"></i> Check in</a></td>
                </tr>';
            }
        }
        echo $rows;
    }

    public function get_patients_for_manage(Request $request) {
        $rows = '';
        $patients = Patients::all();
        $n = 0;
        $roles = get_this_user_roles();
        $term =  $request->term;
        foreach ($patients as $patient) {
            if ( str_contains($patient->id_no, $request->term)
                ||str_contains(strtolower($patient->full_name), strtolower($request->term))
            ) {
                $rows.= '
                    <tr>
                    <td>' . $patient->id . '</td>
                    <td>' . $patient->fullname . '</td>
                    <td>' . $patient->id_no . '</td>
                    <td>' . $patient->dob . '</td>
                    <td>' . $patient->mobile . '</td>
                    <td>
                      <a class="btn  btn-xs" href=' . route('reception.view_patient', $patient->id) . '>
                            <i class="fa fa-eye-slash"></i> View</a>

                        <a class="btn  btn-xs" href=' . route('reception.add_patient', $patient->id) . '>
                            <i class="fa fa-pencil-square-o"></i> Edit
                        </a>

                        <a href=' . route('reception.checkin', $patient->id) . ' class="btn btn-xs">
                            <i class="fa fa-sign-in"></i> Check in</a>';

                if (in_array(5, $roles)) {
                    $rows.='<a style = "color: red" href = ' . route('reception.purge_patient', $patient->id) . ' class = "btn btn-xs">
                <i class = "fa fa-trash"></i>delete</a >';
                }

                $rows.='
                    </td>
                </tr>';
            }
        }
        echo $rows;
    }

    public function delete_doc(Request $request) {
        try {
            $doc = PatientDocuments::find($request->id);
            $doc->delete();
        } catch (\Exception $ex) {
            echo 'Error deleting document';
        }
    }

}
