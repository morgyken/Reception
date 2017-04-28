<?php

namespace Ignite\Reception\Entities;

use Ignite\Settings\Entities\Schemes;
use Illuminate\Database\Eloquent\Model;

/**
 * Ignite\Reception\Entities\PatientInsurance
 *
 * @property integer $id
 * @property integer $patient
 * @property integer $scheme
 * @property string $policy_number
 * @property string $principal
 * @property string $dob
 * @property integer $relationship
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Ignite\Reception\Entities\Patients $patients
 * @property-read \Ignite\Settings\Entities\Schemes $schemes
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\PatientInsurance whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\PatientInsurance wherePatient($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\PatientInsurance whereScheme($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\PatientInsurance wherePolicyNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\PatientInsurance wherePrincipal($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\PatientInsurance whereDob($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\PatientInsurance whereRelationship($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\PatientInsurance whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\PatientInsurance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PatientInsurance extends Model {

    public $table = 'reception_patient_schemes';

    public function patients() {
        return $this->belongsTo(Patients::class, 'patient', 'id');
    }

    public function schemes() {
        return $this->belongsTo(Schemes::class, 'scheme');
    }

}
