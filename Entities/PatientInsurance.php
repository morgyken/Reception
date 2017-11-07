<?php

namespace Ignite\Reception\Entities;

use Ignite\Settings\Entities\Schemes;
use Illuminate\Database\Eloquent\Model;

/**
 * Ignite\Reception\Entities\PatientInsurance
 *
 * @property int $id
 * @property int $patient
 * @property int $scheme
 * @property string|null $policy_number
 * @property string|null $principal
 * @property string|null $dob
 * @property int|null $relationship
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $desc
 * @property-read mixed $is_copay
 * @property-read \Ignite\Reception\Entities\Patients $patients
 * @property-read \Ignite\Settings\Entities\Schemes $schemes
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientInsurance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientInsurance whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientInsurance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientInsurance wherePatient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientInsurance wherePolicyNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientInsurance wherePrincipal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientInsurance whereRelationship($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientInsurance whereScheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientInsurance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PatientInsurance extends Model
{

    public $table = 'reception_patient_schemes';

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
        $x = $this->schemes->companies->name . ' | ' .
            $this->schemes->name;
        if ($this->is_copay) {
            $x .= '  ( Copay -' . $this->schemes->amount . ')';
        }
        return $x;
    }
}
