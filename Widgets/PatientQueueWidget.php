<?php

/*
 * =============================================================================
 *
 * Collabmed Solutions Ltd
 * Project: Collabmed Health Platform
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 *
 * =============================================================================
 */

namespace Ignite\Reception\Widgets;

use Ignite\Core\Library\BaseDashboardWidgets;
use Ignite\Reception\Entities\Patients;

/**
 * Description of PatientQueueWidget
 *
 * @author samuel
 */
class PatientQueueWidget extends BaseDashboardWidgets {

    /**
     * @var \Illuminate\Database\Eloquent\Collection|static[]
     */
    protected $data;

    /**
     * PatientQueueWidget constructor.
     */
    public function __construct() {
        $this->data['patients'] = Patients::all()->take(10);
    }

    /**
     * Get the widget name
     * @return string
     */
    protected function name() {
        return 'Patient Queue Widget';
    }

    /**
     * Return an array of widget options
     * Possible options:
     *  x, y, width, height
     * @return array
     */
    protected function options() {
        return [
            'width' => '6',
            'height' => '2',
            'x' => '2',
        ];
    }

    /**
     * Get the widget view
     * @return string
     */
    protected function view() {
        return 'reception::widgets.patients';
    }

    /**
     * Get the widget data to send to the view
     * @return array
     */
    protected function data() {
        return ['data' => $this->data];
    }

}
