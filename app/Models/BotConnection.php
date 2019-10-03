<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BotConnection extends Model
{
    protected $primaryKey = 'bot_id';
    protected $table = 'botConnections';
    protected $fillable = [
        'bot_id',
        'values',
    ];
    protected $hidden = ["bot_id", "created_at", "updated_at"];
    public function setValues($value)
    {
        $this->attributes['values'] = json_encode($value);
    }
    public function getValues()
    {
        return json_decode($this->attributes['values'], true);
    }
    public function bot()
    {
        return $this->belongsTo('App\Models\Bot');
    }

}
