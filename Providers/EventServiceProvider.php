<?php

namespace Ignite\Reception\Providers;

use Ignite\Reception\Events\AppointmentCreated;
use Ignite\Reception\Events\AppointmentRescheduled;
use Ignite\Reception\Events\Handlers\AppointmentCreatedListener;
use Ignite\Reception\Events\Handlers\AppointmentRescheduledListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AppointmentCreated::class => [AppointmentCreatedListener::class,],
        AppointmentRescheduled::class => [AppointmentRescheduledListener::class,]
    ];
}