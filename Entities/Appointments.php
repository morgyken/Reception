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

    
    public function getId()
    {
        return $this->schedule_id;
    }

    
    public function getTitle()
    {
        if ($this->is_guest)
            return $this->guest . ' | ' . $this->doctors->full_name;
        return 
            $this->patients->full_name . ' |' . $this->doctors->full_name;
    }

    
    public function isAllDay()
    {
        return false;
    }

    
    public function getStart()
    {
        return $this->time;
    }

    
    public function getEnd()
    {
        return (new Date($this->time))->add('30 minutes');
    }

    
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
        
    }

}
