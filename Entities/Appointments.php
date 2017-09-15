<?php

namespace Ignite\Reception\Entities;

use Ignite\Settings\Entities\Clinics;
use Ignite\Users\Entities\UserProfile;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Jenssegers\Date\Date;
use MaddHatter\LaravelFullcalendar\Event;

class Appointments extends Model implements Event
{

    protected $dates = ['time'];
    public $table = 'reception_appointments';

    public function patients()
    {
        return $this->belongsTo(Patients::class, 'patient');
    }

    public function doctors()
    {
        return $this->belongsTo(UserProfile::class, 'doctor', 'user_id');
    }

    public function categories()
    {
        return $this->belongsTo(AppointmentCategory::class, 'category', 'id');
    }

    public function getMobileAttribute()
    {
        return $this->phone ?: $this->patients->mobile;
    }

    public function clinics()
    {
        return $this->belongsTo(Clinics::class, 'clinic');
    }

    public function getIsGuestAttribute()
    {
        return empty($this->patient);
    }

    public function getIsFutureAttribute()
    {
        return $this->time->gt(Carbon::now());
    }

    /**
     * Get the event's id number
     *
     * @return int
     */
    public function getId()
    {
        return $this->schedule_id;
    }

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle()
    {
        if ($this->is_guest)
            return $this->guest . ' | ' . $this->doctors->full_name;
        return /* $this->procedures->name . ' | ' . */
            $this->patients->full_name . ' |' . $this->doctors->full_name;
    }

    /**
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay()
    {
        return false;
    }

    /**
     * Get the start time
     *
     * @return DateTime
     */
    public function getStart()
    {
        return $this->time;
    }

    /**
     * Get the end time
     *
     * @return DateTime
     */
    public function getEnd()
    {
        return (new Date($this->time))->add('30 minutes');
    }

    /**
     * Optional FullCalendar.io settings for this event
     *
     * @return array
     */
    public function getEventOptions()
    {
        $color = 'black';
        switch ($this->status) {
            case 1:
                $color = 'green';
                break;
            case 2:
                $color = 'blue';
                break;
            case 5:
                $color = 'orange';
                break;
            default :
                break;
        }
        return [
            //'color' => $color,
            'color' => get_color_code($this->category)
            //etc
        ];
        /*
          1 => 'Scheduled',
          2 => 'Checked In',
          3 => 'Checked Out',
          3 => 'Cancelled',
          4 => 'Rescheduled',
          5 => 'Proposed',
          6 => 'No show',
         */
    }

}
