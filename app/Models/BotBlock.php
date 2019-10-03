<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BotBlock extends Model
{
  //  protected $primaryKey = 'bot_id';
    protected $table = 'botBlock';
    protected $fillable = [
        'bot_id',
        'id',
        'blockType',
        'content',
        'positionX',
        'positionY',
        'parameter',
        'externalLink',
        'notificationType',
        'agentExtraInfo',
        'emojiId',
        'agentId',
        'agentIdList',
        'range',
        'pins',
        'ab',
    ];
    protected $hidden = ["bot_id", "created_at", "updated_at"];
    //----------------------------------
    public function setAgentExtraInfo($value)
    {
        $this->attributes['agentExtraInfo'] = json_encode($value);
    }
    public function getAgentExtraInfo()
    {
        return json_decode($this->attributes['agentExtraInfo'], true);
    }
    //-----------------------------------
    public function setRange($value)
    {
        $this->attributes['range'] = json_encode($value);
    }
    public function getRange()
    {
        return json_decode($this->attributes['range'], true);
    }
    //-----------------------------------
    public function setPins($value)
    {
        $this->attributes['pins'] = json_encode($value);
    }
    public function getPins()
    {
        return json_decode($this->attributes['pins'], true);
    }
    //-----------------------------------
    public function setAb($value)
    {
        $this->attributes['ab'] = json_encode($value);
    }
    public function getAb()
    {
        return json_decode($this->attributes['ab'], true);
    }

    //-----------------------------------
    public function setAgentIdList($value)
    {
        $this->attributes['agentIdList'] = json_encode($value);
    }

    public function getAgentIdList()
    {
        return json_decode($this->attributes['agentIdList'], true);
    }

    public function bot()
    {
        return $this->belongsTo('App\Models\Bot');
    }


}
