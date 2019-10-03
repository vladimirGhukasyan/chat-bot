<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $table = 'widgets';
    protected $fillable = [
        'id',
        'company_id',
        'name',
    ];
    public function widgetSizeAndLocation()
    {
        return $this->hasOne('App\Models\WidgetSizeAndLocation');
    }
    public function widgetHeader()
    {
        return $this->hasOne('App\Models\WidgetHeader');
    }
    public function widgetHeaderManagerPhoto()
    {
        return $this->hasOne('App\Models\WidgetHeaderManagerPhoto');
    }
    public function widgetBody()
    {
        return $this->hasOne('App\Models\WidgetBody');
    }
    public function widgetDialogueBody()
    {
        return $this->hasOne('App\Models\WidgetDialogueBody');
    }
    public function widgetFooter()
    {
        return $this->hasOne('App\Models\WidgetFooter');
    }
    public function widgetButton()
    {
        return $this->hasOne('App\Models\WidgetButton');
    }
    public function widgetBubbleMain()
    {
        return $this->hasOne('App\Models\WidgetBubbleMain');
    }
    public function widgetBubble()
    {
        return $this->hasOne('App\Models\WidgetBubble');
    }
    public function widgetBubbleAdmin()
    {
        return $this->hasOne('App\Models\WidgetBubbleAdmin');
    }
    public function widgetBubbleClient()
    {
        return $this->hasOne('App\Models\WidgetBubbleClient');
    }
    public function widgetBackgroundVideo()
    {
        return $this->hasOne('App\Models\WidgetBackgroundVideo');
    }

    public function findWidget(int $id, int $companyId = null){
        $widget = Widget::with(
            'widgetSizeAndLocation',
            'widgetHeader',
            'widgetHeaderManagerPhoto',
            'widgetBody',
            'widgetDialogueBody',
            'widgetFooter',
            'widgetButton',
            'widgetBubbleMain',
            'widgetBubble',
            'widgetBubbleAdmin',
            'widgetBubbleClient',
            'widgetBackgroundVideo'
        )
            ->where('id',  $id);

        if (!is_null($companyId)){
            $widget->where('company_id',  $companyId);
        }
        return $widget->firstOrFail();
    }

}
