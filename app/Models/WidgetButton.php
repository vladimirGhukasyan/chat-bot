<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetButton extends Model
{
    protected $primaryKey = 'widget_id';
    protected $table = 'widgetButton';
    protected $fillable = [
        'widget_id',
        'backgroundImage',
        'backgroundSize',
        'backgroundColor',
        'color',
        'fontFamily',
        'fontSize',
        'text',
        'width',
        'height',
        'height',
        'textAlign',
        'shape',
        'padding',
    ];
    protected $hidden = ["widget_id", "created_at", "updated_at"];
    public function widget()
    {
        return $this->belongsTo('App\Models\Widget');
    }
}
