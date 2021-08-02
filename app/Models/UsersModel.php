<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersModel extends Model
{
    protected $table = 'users';

    public function token()
    {
        return $this->hasMany(TokenModel::class, 'user_id');
    }
}
