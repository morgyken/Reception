<?php

namespace Ignite\Reception\Events\Handlers;

use Ignite\Reception\Events\AppointmentCreated;

class AppointmentCreatedListener
{

    /**
     * Handle the event.
     *
     * @param AppointmentCreated $event
     * @return void
     */
    public function handle(AppointmentCreated $event)
    {
        $appointment = $event->appointment;
    }
}
