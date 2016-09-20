<?php

namespace Ignite\Reception\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller {

    public function get_schedule(Request $request) {
        //$this->data['appointments'] = get_appointments($request);
        return Response::json(get_appointments($request));
        //return view('system.ajax.clinic_appointments')->with('data', $this->data);
    }

    public function cancel_checkin(Request $request) {
        return Response::json(PatientVisits::destroy($request->id));
    }

    public function change_destination(Request $request) {
        $meta = PatientVisits::find($request->id);
        $meta->destination = $request->new_dest;
        return Response::json($meta->save());
    }

}
