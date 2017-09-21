<?php

namespace Ignite\Reception\Events;

use Ignite\Reception\Entities\Appointments;
use Illuminate\Queue\SerializesModels;

/**
 * Class AppointmentRescheduled
 * @package Ignite\Reception\Events
 */
class AppointmentRescheduled
{
    use SerializesModels;
    /**
     * @var Appointments
     */
    public $appointment;

    /**
     * Create a new event instance.
     *
     * @param Appointments $appointment
     */
    public function __construct(Appointments $appointment)
    {
        $this->appointment = $appointment;
    }
}
