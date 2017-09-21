<?php

namespace Ignite\Reception\Http\Controllers;

use Ignite\Core\Http\Controllers\AdminBaseController;
use Ignite\Reception\Entities\AppointmentCategory;
use Ignite\Reception\Http\Requests\CreateScheduleRequest;

class SetupController extends AdminBaseController {

    public function app_category($id = null) {
        $this->data['categories'] = AppointmentCategory::all();
        $this->data['model'] = AppointmentCategory::findOrNew($id);
        return view('reception::setup.appointment_categories', ['data' => $this->data]);
    }

    /**
     * @todo Complete this saving process
     * @param CreateScheduleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    
    public function save_app_category(CreateScheduleRequest $request) {
        if (SetupFunctions::add_schedule_category($request)) {
            return redirect()->route('setup.schedule_cat');
        }
    }

}
