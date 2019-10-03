<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetBackgroundVideo extends Model
{
    protected $primaryKey = 'widget_id';
    protected $table = 'widgetBackgroundVideo';
    protected $fillable = [
        'widget_id',
        'url',
    ];
    protected $hidden = ["widget_id", "created_at", "updated_at"];
    public function widget()
    {
        return $this->belongsTo('App\Models\Widget');
    }
}
