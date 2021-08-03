<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthModel extends Model
{
    protected $table = 'auth';

    public function user()
    {
        return $this->belongsTo(UsersModel::class, 'user_id');
    }
}
