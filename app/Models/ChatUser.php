<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatUser extends Model
{

    protected $table = 'chat_users';

    protected $fillable = [
        'id',
        'chat_id',
        'user_id',
    ];
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
    public function chat()
    {
        return $this->belongsTo('App\Models\Chat');
    }

}
