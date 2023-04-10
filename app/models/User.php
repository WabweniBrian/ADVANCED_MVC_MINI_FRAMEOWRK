<?php

namespace app\app\models;

use app\core\Model;

class User extends Model
{

    protected $table = 'users';
    protected $fillable = ['username', 'email', 'password'];
}
