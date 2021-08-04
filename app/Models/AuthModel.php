<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthModel extends Model
{
    protected $table = 'auth';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(UsersModel::class, 'id', 'user_id');
    }
}
