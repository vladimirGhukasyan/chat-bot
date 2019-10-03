<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetBody extends Model
{
    protected $primaryKey = 'widget_id';
    protected $table = 'widgetBody';
    protected $fillable = [
        'widget_id',
        'backgroundImage',
        'backgroundSize',
        'backgroundColor',
    ];
    protected $hidden = ["widget_id", "created_at", "updated_at"];
    public function widget()
    {
        return $this->belongsTo('App\Models\Widget');
    }
}
