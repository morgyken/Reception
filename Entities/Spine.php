<?php

namespace Ignite\Reception\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Ignite\Reception\Entities\Spine
 *
 * @mixin \Eloquent
 */
class Spine extends Model {

    protected $fillable = [];
    public $table = "patient_decrypted";

    //public $table = "schemes";
}
