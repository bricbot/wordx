<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelfCorrection extends Model
{
    //
    public function paper()
    {
        return $this->belongsTo('App\Models\Paper', 'paper_uuid', 'uuid');
    }
}
