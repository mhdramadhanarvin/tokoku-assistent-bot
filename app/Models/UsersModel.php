<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersModel extends Model
{
    protected $table = 'users';

    public function toko()
    {
        return $this->hasOne(AuthModel::class, 'user_id', 'id');
    }
}
