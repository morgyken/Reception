<?php

namespace Ignite\Reception\Entities;

use Ignite\Reception\Entities\Appointments;
use Illuminate\Database\Eloquent\Model;

/**
 * Ignite\Reception\Entities\AppointmentCategory
 *
 * @property integer $id
 * @property string $name
 * @property string $payload
 * @property string $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Ignite\Reception\Entities\Appointments[] $appointments
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\AppointmentCategory whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\AppointmentCategory whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\AppointmentCategory wherePayload($value)
 * @method static \Illuminate\Database\Query\Builder|\Ignite\Reception\Entities\AppointmentCategory whereDeletedAt($value)
 * @mixin \Eloquent
 */
class AppointmentCategory extends Model {

    protected $fillable = [];
    public $timestamps = false;
    public $table = 'reception_appointment_categories';

    public function appointments() {
        return $this->hasMany(Appointments::class, 'category_id', 'category');
    }

}
