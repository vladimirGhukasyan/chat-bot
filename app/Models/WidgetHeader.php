<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetHeader extends Model
{
    protected $primaryKey = 'widget_id';
    protected $table = 'widgetHeader';
    protected $fillable = [
        'widget_id',
        'fontFamily',
        'fontSize',
        'color',
        'backgroundSize',
        'backgroundImage',
        'backgroundColor',
    ];
    protected $hidden = ["widget_id", "created_at", "updated_at"];
    public function widget()
    {
        return $this->belongsTo('App\Models\Widget');
    }
}
