<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function paper_with_teacher()
    {
        return $this->belongsTo('App\Models\Paper', 'teacher_id', 'id');
    }

    public function paper_with_assistant()
    {
        return $this->belongsTo('App\Models\Paper', 'assistant_id', 'id');
    }
    
    public function paper_with_student()
    {
        return $this->belongsTo('App\Models\Paper', 'student_id', 'id');
    }
}
