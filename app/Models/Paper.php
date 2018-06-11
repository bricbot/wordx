<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    //
    public function assistant_confirms()
    {
        return $this->hasMany('App\Models\AssistantConfirm', 'paper_uuid', 'uuid');
    }

    public function self_corrections()
    {
        return $this->hasMany('App\Models\SelfCorrection', 'paper_uuid', 'uuid');
    }

    public function teacher_corrections()
    {
        return $this->hasMany('App\Models\TeacherCorrection', 'paper_uuid', 'uuid');
    }

    public function teacher()
    {
        return $this->hasOne('App\User', 'id', 'teacher_id');
    }

    public function assistant()
    {
        return $this->hasOne('App\User', 'id', 'assistant_id');
    }

    public function student()
    {
        return $this->hasOne('App\User', 'id', 'student_id');
    }
}
