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

use Ignite\Core\Entities\User;
use Ignite\Reception\Entities\Appointments;
use Ignite\Reception\Entities\PatientInsurance;
use Ignite\Reception\Entities\Patients;
use Ignite\Setup\Entities\AppointmentCategory;
use Illuminate\Http\Request;

if (!function_exists('get_appointments')) {

    /**
     * Get appointment
     * @param Request $request
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    function get_appointments(Request $request) {
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

    function get_schedule_cat() {
        return AppointmentCategory::all()->pluck('name', 'id');
    }

}
if (!function_exists('get_patients')) {

    /**
     * @return \Illuminate\Support\Collection
     */
    function get_patients() {
        return Patients::all()->pluck('full_name', 'id');
    }

}
if (!function_exists('auto_complete_patient_names')) {

    function auto_complete_patient_names() {
        return Patients::all()->pluck('full_name');
    }

}
if (!function_exists('calendar_options')) {

    /**
     * @return array Calender view options
     */
    function calendar_options() {
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
    function get_checkin_destinations() {
        $first = config('system.destinations');
        $next = User::with(['profile', 'role'])
                        ->whereIn('group_id', [5, 12])->get()->pluck('profile.full_name', 'id')->toArray();
        // dd($next);
        return array_replace($first, $next);
    }

}
if (!function_exists('get_patient_insurance_schemes')) {

    /**
     * Get the Patient Insurance schemes / covers
     * @param int $patient_id
     * @return mixed
     */
    function get_patient_insurance_schemes($patient_id) {
        return PatientInsurance::with('schemes')
                        ->wherePatient($patient_id)->get()->pluck('schemes.name', 'id');
    }

}
if (!function_exists('get_color_code')) {

    function get_color_code($category_id) {
        $color = config('system.colors.' . $category_id);
        if (empty($color)) {
            $color = 'green';
        }
        return $color;
    }

}