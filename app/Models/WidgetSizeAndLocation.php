<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetSizeAndLocation extends Model
{
    protected $primaryKey = 'widget_id';
    protected $table = 'widgetSizeAndLocation';
    protected $fillable = [
        'widget_id',
        'embedType',
        'width',
        'height',
        'boxShadow',
        'align',
    ];
    protected $hidden = ["widget_id", "created_at", "updated_at"];
    public function widget()
    {
        return $this->belongsTo('App\Models\Widget');
    }
}
