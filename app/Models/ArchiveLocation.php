<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchiveLocation extends Model
{
    protected $table = 'archive_location';
    protected $fillable = [
        'archive_id',
        'ip',
        'browser',
        'os',
        'country',
        'region',
        'city',
        'key',
        'email',
        'phone',
    ];

}
