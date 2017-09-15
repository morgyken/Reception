<?php

namespace Ignite\Reception\Entities;

use Ignite\Core\Foundation\ShouldEncrypt;
use Illuminate\Database\Eloquent\Model;

/**
 * Ignite\Reception\Entities\NextOfKin
 *
 * @property int $patient
 * @property mixed $first_name
 * @property mixed|null $middle_name
 * @property mixed $last_name
 * @property string|null $relationship
 * @property mixed $mobile
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $full_name
 * @property-read \Ignite\Reception\Entities\Patients $patients
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\NextOfKin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\NextOfKin whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\NextOfKin whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\NextOfKin whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\NextOfKin whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\NextOfKin wherePatient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\NextOfKin whereRelationship($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\NextOfKin whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class NextOfKin extends Model {

    use ShouldEncrypt;

    /**
     * The attributes that we should encrypt
     * @var array
     */
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
