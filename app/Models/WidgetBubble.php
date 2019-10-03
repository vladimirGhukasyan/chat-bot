<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetBubble extends Model
{
    protected $primaryKey = 'widget_id';
    protected $table = 'widgetBubble';
    protected $fillable = [
        'widget_id',
        'fontFamily',
        'fontSize',
        'boxShadow',
        'backgroundImage',
        'backgroundColor',
        'shape',
        'animation',
    ];
    protected $hidden = ["widget_id", "created_at", "updated_at"];
    public function widget()
    {
        return $this->belongsTo('App\Models\Widget');
    }
}
