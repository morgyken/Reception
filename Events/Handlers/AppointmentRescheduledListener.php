<?php

namespace Ignite\Reception\Events\Handlers;

use Ignite\Reception\Events\AppointmentRescheduled;

class AppointmentRescheduledListener
{

    /**
     * Handle the event.
     *
     * @param AppointmentRescheduled $event
     * @return void
     */
    public function handle(AppointmentRescheduled $event)
    {
        $appointment = $event->appointment;
    }
}
