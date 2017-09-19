<?php

namespace Ignite\Reception\Entities;

use Ignite\Core\Foundation\ShouldEncrypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Ignite\Users\Entities\UserProfile;
use Ignite\Inpatient\Entities\Visit;
use Ignite\Finance\Entities\PatientAccount;
use Ignite\Inpatient\Entities\RequestAdmission;
use Ignite\Inpatient\Entities\Ward;
use Ignite\Inpatient\Entities\Bed;
use Ignite\Inpatient\Entities\Admission;
use Ignite\Finance\Entities\PatientInvoice;
use Ignite\Inventory\Entities\InventoryBatchProductSales;

class Patients extends Model {

    protected $fillable = [];
    public $table = "reception_patients";

    use ShouldEncrypt;

    use SoftDeletes;

    /**
     * The attributes that we should encrypt
     * @var array
     */
    protected $shouldEncrypt = ['first_name', 'middle_name', 'last_name', 'mobile', 'email', 'id_no', 'telephone', 'alt_number'];

    protected $hidden = ['image'];

    public function getSexAttribute($value) {
        return ucfirst($value);
    }

    public function getFullNameAttribute() {
        return $this->first_name . " " . $this->middle_name . " " . $this->last_name;
    }

    public function getCheckedInStatusAttribute() {
        return $this->appointments->where('status', 2)->count() > 0 ? 'Checked In' : 'Not checked in';
    }

    public function getIsInsuredAttribute() {
        return $this->schemes->count() > 0;
    }

    public function getInsuredAttribute() {
        return $this->schemes->count();
    }

    public function getAgeAttribute() {
        $age = \Carbon\Carbon::parse($this->attributes['dob'])->age;
        return $age;
    }

    public function getRegisteredAttribute(){
        return \Carbon\Carbon::parse($this->created_at)->format('m/d/y');
    }

    public function nok() {
        return $this->hasOne(NextOfKin::class, 'patient');
    }

    public function schemes() {
        return $this->hasMany(PatientInsurance::class, 'patient', 'id');
    }

    public function appointments() {
        return $this->hasMany(Appointments::class, 'patient', 'patient_id');
    }

    public function documents() {
        return $this->hasMany(PatientDocuments::class, 'patient');
    }

    public function visits() {
        return $this->hasMany(Visit::class, 'patient');
    }

    public function drug_purchases() {
        return $this->hasMany(InventoryBatchProductSales::class, 'patient');
    }

    public function invoices() {
        return $this->hasMany(PatientInvoice::class, 'patient_id');
    }

    public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
        return 1000;
    }

    public function admissionRequest(){
        return $this->hasOne(RequestAdmission::class, 'admissionRequest');
    }

    public function bed(){
        return $this->hasOne(Bed::class, 'id');
    }

    public function profile(){
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    public function account(){
        return $this->hasOne(PatientAccount::class,'patient');
    }

    public function admission(){
        return $this->hasOne(Admission::class,'patient_id');
    }

   
}
