<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchiveMessage extends Model
{
    protected $table = 'archive_messages';
    protected $fillable = [
        'archive_id',
        'message',
        'user_id',
        'type'
    ];


}
