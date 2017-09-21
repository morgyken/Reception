<?php

namespace Ignite\Reception\Entities;

use Ignite\Users\Entities\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Ignite\Reception\Entities\PatientDocuments
 *
 * @property int $id
 * @property int $patient
 * @property string $document_type
 * @property string $filename
 * @property string $mime
 * @property string $description
 * @property int $user
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property mixed|null $document
 * @property-read \Ignite\Reception\Entities\Patients $patients
 * @property-read \Ignite\Users\Entities\User $users
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientDocuments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientDocuments whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientDocuments whereDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientDocuments whereDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientDocuments whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientDocuments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientDocuments whereMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientDocuments wherePatient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientDocuments whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Ignite\Reception\Entities\PatientDocuments whereUser($value)
 * @mixin \Eloquent
 */
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
