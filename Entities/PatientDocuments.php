<?php

namespace Ignite\Reception\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Ignite\Reception\Entities\PatientDocuments
 *
 * @property integer $id
 * @property integer $patient
 * @property string $document_type
 * @property string $filename
 * @property string $mime
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property mixed $document
 * @property-read \Ignite\Reception\Entities\Patients $patients
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\PatientDocuments whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\PatientDocuments wherePatient($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\PatientDocuments whereDocumentType($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\PatientDocuments whereFilename($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\PatientDocuments whereMime($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\PatientDocuments whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\PatientDocuments whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\PatientDocuments whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\PatientDocuments whereDocument($value)
 * @mixin \Eloquent
 */
class PatientDocuments extends Model {

    public $table = 'reception_patient_documents';

    public function patients() {
        return $this->belongsTo(Patients::class, 'patient_id', 'patient_id');
    }

}
