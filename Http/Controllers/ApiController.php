<?php

namespace Ignite\Reception\Http\Controllers;

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
        // $found = collect();
        $ret = [];
        $term = ucfirst($request->q);
        if (!empty($term)) {
            //$found = \Ignite\Reception\Entities\Patients::where('concat(first_name)', 'like', "%$term%")->get();
        }
        $found = \Ignite\Reception\Entities\Patients::where('first_name', 'like', "%$term%")->get();
        //$found = PatientInsurance::with('schemes')
        //        ->get();

        $build = [];
        foreach ($found as $patient) {
            $build[] = [
                'text' => $patient->full_name,
                'id' => $patient->id];
        }
        $ret['results'] = $build;
        return json_encode($ret);
    }

    public function get_checkin_patients(Request $request) {
        $rows = '';
        $patients = \Ignite\Reception\Entities\Patients::all();
        foreach ($patients as $patient) {
            if (str_contains($patient->fullname, ucfirst($request->term)) || str_contains($patient->id_no, ucfirst($request->term))) {
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

    public function delete_doc(Request $request) {
        try {
            $doc = \Ignite\Reception\Entities\PatientDocuments::find($request->id);
            $doc->delete();
        } catch (\Exception $ex) {
            echo 'Error deleting document';
        }
    }

}
