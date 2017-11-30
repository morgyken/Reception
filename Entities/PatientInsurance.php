<?php

namespace Ignite\Reception\Entities;

use Ignite\Settings\Entities\Schemes;
use Illuminate\Database\Eloquent\Model;


class PatientInsurance extends Model
{

    public $table = 'reception_patient_schemes';

    protected $with = ['schemes']; 

    public function patients()
    {
        return $this->belongsTo(Patients::class, 'patient', 'id');
    }

    public function schemes()
    {
        return $this->belongsTo(Schemes::class, 'scheme');
    }

    public function getIsCopayAttribute()
    {
        return $this->schemes->type === 3;
    }

    public function getDescAttribute()
    {
        $s = $this->schemes;
        $x = $s->companies->name . ' - ' .
            $s->name;
        if ($s->type == 3) {
            $x .= '  (Copay - ' . $this->schemes->amount . ')';
        } elseif ($s->type == 2) {
            $x .= '  (Capitation - ' . $this->schemes->amount . ')';
        } else {
            $x .= '  (Full)';
        }
        return $x;
    }
}
