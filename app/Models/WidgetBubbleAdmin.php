<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetBubbleAdmin extends Model
{
    protected $primaryKey = 'widget_id';
    protected $table = 'widgetBubbleAdmin';
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
