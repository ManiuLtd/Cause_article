<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admin';

    protected $guarded = ['_token','_method'];

    public function group()
    {
        return $this->belongsTo('\App\Model\AdminGroup','gid');
    }
}
