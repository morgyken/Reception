<?php

namespace Ignite\Reception\Entities;

use Ignite\Reception\Entities\Appointments;
use Illuminate\Database\Eloquent\Model;


class AppointmentCategory extends Model {

    protected $fillable = [];
    public $timestamps = false;
    public $table = 'reception_appointment_categories';

    public function appointments() {
        return $this->hasMany(Appointments::class, 'category_id', 'category');
    }

}
