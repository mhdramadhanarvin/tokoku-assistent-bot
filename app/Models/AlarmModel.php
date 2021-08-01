<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlarmModel extends Model
{
    protected $table = 'alarm';

    public function alarm()
    {
        return $this->belongsTo(UsersModel::class, 'user_id');
    }
}
