<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $table = 'archives';

    protected $fillable = [
        'company_id',
        'token',
        'name',
        'email',
    ];

    public function messages()
    {
        return $this->hasMany('App\Models\ArchiveMessage');
    }

    public function location()
    {
        return $this->hasOne('App\Models\ArchiveLocation');
    }



}
