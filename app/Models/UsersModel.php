<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersModel extends Model
{
    protected $table = 'users';

    public function alarm()
    {
        return $this->hasMany(AlarmModel::class, 'user_id');
    }
}
