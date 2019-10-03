<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetBubbleClient extends Model
{
    protected $primaryKey = 'widget_id';
    protected $table = 'widgetBubbleClient';
    protected $fillable = [
        'widget_id',
        'color',
        'backgroundColor',
    ];
    protected $hidden = ["widget_id", "created_at", "updated_at"];
    public function widget()
    {
        return $this->belongsTo('App\Models\Widget');
    }
}
