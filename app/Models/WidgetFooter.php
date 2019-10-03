<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetFooter extends Model
{
    protected $primaryKey = 'widget_id';
    protected $table = 'widgetFooter';
    protected $fillable = [
        'widget_id',
        'color',
        'backgroundColor',
        'send',
    ];
    protected $hidden = ["widget_id", "created_at", "updated_at"];
    public function widget()
    {
        return $this->belongsTo('App\Models\Widget');
    }
}
