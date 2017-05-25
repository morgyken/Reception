<?php

namespace Ignite\Reception\Widgets;

use Ignite\Core\Library\BaseDashboardWidgets;
use Ignite\Reception\Entities\Patients;

class PatientCount extends BaseDashboardWidgets {

    /**
     * Get the widget name
     * @return string
     */
    protected function name() {
        return 'PatientCount';
    }

    /**
     * Get the widget view
     * @return string
     */
    protected function view() {
        return 'reception::widgets.patient_count';
    }

    /**
     * Get the widget data to send to the view
     * @return string
     */
    protected function data() {
        return ['patientCount' => Patients::all()->count()];
    }

    /**
     * Get the widget type
     * @return string
     */
    protected function options() {
        return [
            'width' => '2',
            'height' => '2',
            'x' => '6',
            'y' => '0',
        ];
    }

}
