<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetDialogueBody extends Model
{
    protected $primaryKey = 'widget_id';
    protected $table = 'widgetDialogueBody';
    protected $fillable = [
        'widget_id',
        'paddingLeft',
        'paddingRight',
    ];
    protected $hidden = ["widget_id", "created_at", "updated_at"];
    public function widget()
    {
        return $this->belongsTo('App\Models\Widget');
    }
}
