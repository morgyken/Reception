<?php

namespace Ignite\Reception\Entities;

use Ignite\Evaluation\Entities\Visit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Ignite\Reception\Entities\Patients
 *
 * @property integer $id
 * @property mixed $first_name
 * @property mixed $middle_name
 * @property mixed $last_name
 * @property string $dob
 * @property string $sex
 * @property mixed $mobile
 * @property mixed $id_no
 * @property mixed $email
 * @property mixed $telephone
 * @property mixed $alt_number
 * @property mixed $address
 * @property string $post_code
 * @property string $town
 * @property integer $status
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property mixed $image
 * @property-read mixed $full_name
 * @property-read mixed $name
 * @property-read mixed $checked_in_status
 * @property-read mixed $is_insured
 * @property-read \Ignite\Reception\Entities\NextOfKin $nok
 * @property-read \Illuminate\Database\Eloquent\Collection|\Ignite\Reception\Entities\PatientInsurance[] $schemes
 * @property-read \Illuminate\Database\Eloquent\Collection|\Ignite\Reception\Entities\Appointments[] $appointments
 * @property-read \Illuminate\Database\Eloquent\Collection|\Ignite\Reception\Entities\PatientDocuments[] $documents
 * @property-read \Illuminate\Database\Eloquent\Collection|\Ignite\Evaluation\Entities\Visit[] $visits
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients whereMiddleName($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients whereDob($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients whereSex($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients whereMobile($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients whereIdNo($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients whereTelephone($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients whereAltNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients wherePostCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients whereTown($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients whereImage($value)
 * @mixin \Eloquent
 */
class Spine_Visit extends Model {

    protected $fillable = [];
    public $table = "spine_visits";

}