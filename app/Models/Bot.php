<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    protected $table = 'bots';
    protected $fillable = [
        'id',
        'company_id',
        'name',
        'typing_emulation_is_active',
        'typing_emulation_animation_id',
        'typing_emulation_delay',
        'typing_emulation_max_delay',
        'typing_emulation_speed',
        'typing_emulation_is_custom',
        'typing_emulation_mode'
    ];
    public function botBlocks()
    {
        return $this->hasMany('App\Models\BotBlock');
    }
    public function botConnection()
    {
        return $this->hasOne('App\Models\BotConnection');
    }
    public function getData(){
        $result = $this->toArray();
        $result['blocks']       = [];
        $result['connections']  = [];

        $collection = $this->botBlocks()->get();

        foreach ($collection as $item) {
            $item = $item->toArray();

            $item['agentExtraInfo'] = json_decode( $item['agentExtraInfo'], true);
            $item['range']          = json_decode( $item['range'], true);
            $item['pins']           = json_decode( $item['pins'], true);
            $item['ab']             = json_decode( $item['ab'], true);
            $item['agentIdList']    = json_decode( $item['agentIdList'], true);
            $result['blocks'][] = $item;
        }

        $connection = $this->botConnection()->first();
        if ($connection) {
            $result['connections']  = json_decode( $connection->values, true);
        }
        /*$result['typing'] = [
            'typing_emulation_is_active' => 'sas'
        ];*/
        return $result;
    }
}
