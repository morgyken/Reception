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

use Ignite\Reception\Entities\AppointmentCategory;
use Ignite\Reception\Entities\Appointments;
use Ignite\Reception\Entities\PatientInsurance;
use Ignite\Reception\Entities\Patients;
use Ignite\Settings\Entities\Rooms;
use Illuminate\Http\Request;

if (!function_exists('get_appointments')) {

    /**
     * Get appointment
     * @param Request $request
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    function get_appointments(Request $request)
    {
        $builder = Appointments::where(function ($query) {
            $query->orWhere('status', 1);
            $query->orWhere('status', 2);
        });
        if ($request->has('clinic')) {
            if (!empty($request->clinic)) {
                $builder->where('clinic', $request->clinic);
            }
        }
        if ($request->has('category')) {
            if (!empty($request->category)) {
                $builder->where('category', $request->category);
            }
        }
        if ($request->has('doctor')) {
            if (!empty($request->doctor)) {
                $builder->where('doctor', $request->doctor);
            }
        }
        if ($request->has('start')) {
            $builder->where('time', '>=', $request->start);
        }
        if ($request->has('end')) {
            $builder->where('time', '<=', $request->start);
        }
        return $builder->get();
    }

}
if (!function_exists('get_schedule_cat')) {
    /**
     * @return \Illuminate\Support\Collection
     */
    function get_schedule_cat()
    {
        return AppointmentCategory::all()->pluck('name', 'id');
    }

}
if (!function_exists('get_destinations')) {
    function get_destinations($order)
    {
        $types = array();
        foreach ($order->details as $item) {
            $types[] = $item->type;
        }
        //return json_encode(array_flatten(array_unique($types)));
        return array_unique($types);
    }
}
if (!function_exists('get_patients')) {

    /**
     * @return \Illuminate\Support\Collection
     */
    function get_patients()
    {
        return Patients::all()->pluck('full_name', 'id');
    }

}

if (!function_exists('get_external_institutions')) {

    /**
     * @return \Illuminate\Support\Collection
     */
    function get_external_institutions()
    {
        return PartnerInstitution::all()->pluck('name', 'id');
    }

}

if (!function_exists('auto_complete_patient_names')) {

    function auto_complete_patient_names()
    {
        return Patients::all()->pluck('full_name');
    }

}

if (!function_exists('get_patient_age')) {

    function get_patient_age($dob)
    {
        if ((new Date($dob))->age > 0) {
            if ((new Date($dob))->diff(Carbon\Carbon::now())->format('%m') > 0) {
                $age = (new Date($dob))->diff(Carbon\Carbon::now())->format('%y year(s), %m month(s)');
            } else {
                $age = (new Date($dob))->diff(Carbon\Carbon::now())->format('%y year(s)');
            }
        } else {
            if ((new Date($dob))->diff(Carbon\Carbon::now())->format('%m') > 0) {
                $age = (new Date($dob))->diff(Carbon\Carbon::now())->format('%m month(s) , %d day(s)');
            } else {
                $age = (new Date($dob))->diff(Carbon\Carbon::now())->format('%d day(s)');
            }
        }
        return $age;
    }

}

if (!function_exists('calendar_options')) {

    /**
     * @return array Calender view options
     */
    function calendar_options()
    {
        return [
            'firstDay' => 0,
            'aspectRatio' => 2.5,
            'theme' => true,
            'nowIndicator' => true,
            // 'hiddenDays' => [6, 0],
            'businessHours' => [
                'start' => '8:00',
                'end' => '18:00',
                'dow' => [1, 2, 3, 4, 5],
            ]
        ];
    }

}
if (!function_exists('get_checkin_destinations')) {

    /**
     * @return array The check-in destinations
     */
    function get_checkin_destinations()
    {
        $first = [];
        $roles = m_setting('reception.checkin_destinations');
        $rooms_enabled = (bool)m_setting('reception.checkin_to_rooms');
        $show_doctors = !$rooms_enabled;
        if ($rooms_enabled) {
            $_rooms = Rooms::all();
            foreach ($_rooms as $room) {
                $first['R-' . $room->id] = $room->desc;
            }
        }
        $places = m_setting('reception.checkin_places');
        $interests = json_decode($places);
        foreach ($interests as $one) {
            $first[$one] = mconfig('reception.options.destinations.' . $one);
        }
        $next = [];
        if ($show_doctors) {
            $next = users_in(json_decode($roles))->pluck('profile.full_name', 'id')->toArray();
        }
        return array_replace($first, $next);
    }

}
if (!function_exists('get_patient_insurance_schemes')) {

    /**
     * Get the Patient Insurance schemes / covers
     * @param int $patient_id
     * @return mixed
     */
    function get_patient_insurance_schemes($patient_id)
    {
        return PatientInsurance::with('schemes')
            ->wherePatient($patient_id)->get()->pluck('schemes.name', 'id');
    }

}

if (!function_exists('get_patient_schemes')) {

    /**
     * Get the Patient Insurance schemes / covers
     * @param int $patient_id
     * @return mixed
     */
    function get_patient_schemes($patient_id)
    {
        $schemes = PatientInsurance::wherePatient($patient_id)->get();
        return $schemes;
    }

}


if (!function_exists('get_color_code')) {
    /**
     * @param $category_id
     * @return mixed|string
     */
    function get_color_code($category_id)
    {
        $color = config('system.colors.' . $category_id);
        if (empty($color)) {
            $color = 'green';
        }
        return $color;
    }

}


if (!function_exists('get_precharged_fees')) {
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    function get_precharged_fees()
    {
        return Ignite\Evaluation\Entities\Procedures::where("precharge", "=", 1)->get();
    }

}

if (!function_exists('get_this_user_roles')) {
    /**
     * @return array
     */
    function get_this_user_roles()
    {
        $user = auth()->user();
        $roles = array();
        foreach ($user->roles as $role) {
            $roles[] = $role->role_id;
        }
        return $roles;
    }

}
