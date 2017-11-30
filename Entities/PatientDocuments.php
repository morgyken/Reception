<?php

namespace Ignite\Reception\Entities;

use Ignite\Users\Entities\User;
use Illuminate\Database\Eloquent\Model;


class PatientDocuments extends Model {

    public $table = 'reception_patient_documents';
    protected $guarded = [];

    public function patients() {
        return $this->belongsTo(Patients::class, 'patient');
    }

    public function users() {
        return $this->belongsTo(User::class, 'user');
    }

}
