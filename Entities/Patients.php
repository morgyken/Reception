<?php

namespace Ignite\Reception\Entities;

use Carbon\Carbon;
use Ignite\Core\Foundation\ShouldEncrypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ignite\Users\Entities\UserProfile;
use Ignite\Evaluation\Entities\Visit;
use Ignite\Finance\Entities\PatientAccount;
use Ignite\Inpatient\Entities\RequestAdmission;
use Ignite\Inpatient\Entities\Bed;
use Ignite\Inpatient\Entities\Admission;
use Ignite\Finance\Entities\PatientInvoice;
use Ignite\Inventory\Entities\InventoryBatchProductSales;

/**
 * Ignite\Reception\Entities\Patients
 *
 * @property int $id
 * @property string|null $patient_no
 * @property mixed $first_name
 * @property mixed|null $middle_name
 * @property mixed $last_name
 * @property \Carbon\Carbon|null $dob
 * @property string|null $age
 * @property string|null $age_in
 * @property string|null $sex
 * @property mixed $mobile
 * @property mixed $id_no
 * @property mixed|null $email
 * @property mixed|null $telephone
 * @property mixed|null $alt_number
 * @property mixed|null $address
 * @property string|null $post_code
 * @property string|null $town
 * @property int $status
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property mixed|null $image
 * @property int|null $external_institution
 * @property-read \Ignite\Finance\Entities\PatientAccount $account
 * @property-read \Ignite\Inpatient\Entities\Admission $admission
 * @property-read \Ignite\Inpatient\Entities\RequestAdmission $admissionRequest
 * @property-read \Illuminate\Database\Eloquent\Collection|\Ignite\Reception\Entities\Appointments[] $appointments
 * @property-read \Ignite\Inpatient\Entities\Bed $bed
 * @property-read \Illuminate\Database\Eloquent\Collection|\Ignite\Reception\Entities\PatientDocuments[] $documents
 * @property-read \Illuminate\Database\Eloquent\Collection|\Ignite\Inventory\Entities\InventoryBatchProductSales[] $drug_purchases
 * @property-read mixed $checked_in_status
 * @property-read mixed $full_name
 * @property-read mixed $insured
 * @property-read mixed $is_insured
 * @property-read mixed $registered
 * @property-read \Illuminate\Database\Eloquent\Collection|\Ignite\Finance\Entities\PatientInvoice[] $invoices
 * @property-read \Ignite\Reception\Entities\NextOfKin $nok
 * @property-read \Ignite\Users\Entities\UserProfile $profile
 * @property-read \Illuminate\Database\Eloquent\Collection|\Ignite\Reception\Entities\PatientInsurance[] $schemes
 * @property-read \Illuminate\Database\Eloquent\Collection|\Ignite\Evaluation\Entities\Visit[] $visits
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereAgeIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereAltNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereExternalInstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereIdNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients wherePatientNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients wherePostCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereTown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\Patients whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\Patients withoutTrashed()
 * @mixin \Eloquent
 */
class Patients extends Model
{

    protected $fillable = [];
    public $table = "reception_patients";
    protected $dates = ['dob'];

    use ShouldEncrypt;

    use SoftDeletes;

    /**
     * The attributes that we should encrypt
     * @var array
     */
    protected $shouldEncrypt = ['first_name', 'middle_name', 'last_name', 'mobile', 'email', 'id_no', 'telephone', 'alt_number'];

    protected $hidden = ['image'];

    public function getSexAttribute($value)
    {
        return ucfirst($value);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . " " . $this->middle_name . " " . $this->last_name;
    }

    public function getCheckedInStatusAttribute()
    {
        return $this->appointments->where('status', 2)->count() > 0 ? 'Checked In' : 'Not checked in';
    }

    public function getIsInsuredAttribute()
    {
        return $this->schemes->count() > 0;
    }

    public function getInsuredAttribute()
    {
        return $this->schemes->count();
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['dob'])->age;
    }

    public function getRegisteredAttribute()
    {
        return Carbon::parse($this->created_at)->format('m/d/y');
    }

    public function nok()
    {
        return $this->hasOne(NextOfKin::class, 'patient');
    }

    public function schemes()
    {
        return $this->hasMany(PatientInsurance::class, 'patient', 'id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointments::class, 'patient', 'patient_id');
    }

    public function documents()
    {
        return $this->hasMany(PatientDocuments::class, 'patient');
    }

    public function visits()
    {
        return $this->hasMany(Visit::class, 'patient');
    }

    public function drug_purchases()
    {
        return $this->hasMany(InventoryBatchProductSales::class, 'patient');
    }

    public function invoices()
    {
        return $this->hasMany(PatientInvoice::class, 'patient_id');
    }

    public function paginateCount($conditions = null, $recursive = 0, $extra = array())
    {
        return 1000;
    }

    public function admissionRequest()
    {
        return $this->hasOne(RequestAdmission::class, 'admissionRequest');
    }

    public function bed()
    {
        return $this->hasOne(Bed::class, 'id');
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    public function account()
    {
        return $this->hasOne(PatientAccount::class, 'patient');
    }

    public function admission()
    {
        return $this->hasOne(Admission::class, 'patient_id');
    }


}
