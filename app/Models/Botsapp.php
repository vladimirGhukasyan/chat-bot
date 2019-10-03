<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Botsapp extends Model
{
    protected $fillable = [
        'id',
        'company_id',
        'name',
    ];
    public function botAppUsers()
    {
        return $this->hasMany('App\Models\ChatUser');
    }
}
