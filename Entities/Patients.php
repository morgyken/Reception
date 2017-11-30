<?php

namespace Ignite\Reception\Entities;

use Carbon\Carbon;
use Ignite\Core\Foundation\ShouldEncrypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ignite\Users\Entities\UserProfile;
use Ignite\Evaluation\Entities\Visit;
use Ignite\Finance\Entities\PatientAccount;
use Ignite\Inpatient\Entities\AdmissionRequest;
use Ignite\Inpatient\Entities\Bed;
use Ignite\Inpatient\Entities\Admission;
use Ignite\Finance\Entities\PatientInvoice;
use Ignite\Inventory\Entities\InventoryBatchProductSales;


class Patients extends Model
{

    protected $fillable = [];
    public $table = "reception_patients";
    protected $dates = ['dob'];

    use ShouldEncrypt;

    use SoftDeletes;

    
    protected $shouldEncrypt = ['first_name', 'middle_name', 'last_name', 'mobile', 'email', 'id_no', 'telephone', 'alt_number'];

    protected $hidden = ['image'];

    protected $with = ['schemes'];

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

    public function getNumberAttribute()
    {
        if (empty($this->patient_no)) {
            return $this->id;
        }
        return m_setting('reception.patient_number') . $this->patient_no;
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
        return $this->hasMany(AdmissionRequest::class, 'patient_id');
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
