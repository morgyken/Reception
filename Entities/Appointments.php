<?php

namespace Ignite\Reception\Entities;

use Ignite\Settings\Entities\Clinics;
use Ignite\Users\Entities\UserProfile;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Jenssegers\Date\Date;
use MaddHatter\LaravelFullcalendar\Event;

/**
 * Ignite\Reception\Entities\Appointments
 *
 * @property int $id
 * @property int|null $patient
 * @property string|null $guest
 * @property string|null $phone
 * @property \Carbon\Carbon $time
 * @property int $category
 * @property int $doctor
 * @property string|null $instructions
 * @property int $status
 * @property int|null $clinic
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Ignite\Reception\Entities\AppointmentCategory $categories
 * @property-read \Ignite\Settings\Entities\Clinics|null $clinics
 * @property-read \Ignite\Users\Entities\UserProfile $doctors
 * @property-read mixed $is_future
 * @property-read mixed $is_guest
 * @property-read mixed $mobile
 * @property-read \Ignite\Reception\Entities\Patients|null $patients
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Appointments whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Appointments whereClinic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Appointments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Appointments whereDoctor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Appointments whereGuest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Appointments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Appointments whereInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Appointments wherePatient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Appointments wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Appointments whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Appointments whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Appointments whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
