<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chats';
    protected $fillable = [
        'id',
        'company_id',
        'secret',
        'name',
        'bot_id',
        'widget_id',
    ];
    public function bot()
    {
        return $this->hasOne('App\Models\Bot', 'id', 'bot_id');
    }
    public function widget()
    {
        return $this->hasOne('App\Models\Widget', 'widget_id', 'id');
    }
    public function chatUsers()
    {
        return $this->hasMany('App\Models\ChatUser');
    }

    public function getData()
    {

        $array['users'] = [];
        $array['bot'] =$this->bot;
        $array['manager_id'] =$this->company_id;
        $users = $this->chatUsers()->with(array('user' => function ($query){
            return $query->select('id','name','avatar','phone','email','api_key_push');
        }))->get();

        foreach ($users as $item){
            array_push($array['users'],$item->user);
        }

        return $array;
    }
}
