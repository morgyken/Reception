<?php

namespace Ignite\Reception\Entities;

use Ignite\Core\Foundation\ShouldEncrypt;
use Illuminate\Database\Eloquent\Model;


class NextOfKin extends Model {

    use ShouldEncrypt;

    
    protected $shouldEncrypt = ['first_name', 'middle_name', 'last_name', 'mobile'];
    public $incrementing = false;
    public $table = 'reception_patients_nok';
    public $primaryKey = 'patient';

    public function patients() {
        return $this->belongsTo(Patients::class, 'patient');
    }

    public function getFullNameAttribute() {
        return $this->first_name . " " . $this->middle_name . ' ' . $this->last_name;
    }

}
