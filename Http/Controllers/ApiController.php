<?php

namespace Ignite\Reception\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller {

    public function get_schedule(Request $request) {
        $this->data['appointments'] = get_appointments($request);
        //return Response::json(get_appointments($request));
        return view('reception::partials.view_appointments', ['data' => $this->data]);
    }

    public function cancel_checkin(Request $request) {
        return Response::json(PatientVisits::destroy($request->id));
    }

    public function change_destination(Request $request) {
        $meta = PatientVisits::find($request->id);
        $meta->destination = $request->new_dest;
        return Response::json($meta->save());
    }

    public function get_patients(Request $request) {
        // $found = collect();
        $ret = [];
        $term = $request->q;
        if (!empty($term)) {
            //$found = \Ignite\Reception\Entities\Patients::where('concat(first_name)', 'like', "%$term%")->get();
        }
        $found = \Ignite\Reception\Entities\Patients::all();
        $build = [];
        foreach ($found as $patient) {
            $build[] = [
                'text' => $patient->full_name,
                'id' => $patient->id];
        }
        $ret['results'] = $build;
        return json_encode($ret);
    }

}
