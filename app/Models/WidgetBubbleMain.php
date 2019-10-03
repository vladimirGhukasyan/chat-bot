<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetBubbleMain extends Model
{
    protected $primaryKey = 'widget_id';
    protected $table = 'widgetBubbleMain';
    protected $fillable = [
        'widget_id',
        'maxWidth',
        'backgroundSize',
    ];
    protected $hidden = ["widget_id", "created_at", "updated_at"];
    public function widget()
    {
        return $this->belongsTo('App\Models\Widget');
    }
}
