<?php

namespace App\Http\Controllers\Client;

use App\Models\WidgetBackgroundVideo;
use App\Models\WidgetBody;
use App\Models\WidgetBubble;
use App\Models\WidgetBubbleAdmin;
use App\Models\WidgetBubbleClient;
use App\Models\WidgetBubbleMain;
use App\Models\WidgetButton;
use App\Models\WidgetDialogueBody;
use App\Models\WidgetFooter;
use App\Models\WidgetHeader;
use App\Models\WidgetHeaderManagerPhoto;
use App\Models\WidgetSizeAndLocation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Widget;

class WidgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companyId = auth()->check() ? auth()->user()->getPid() : 1;

        return response()->json(Widget::with(
            'WidgetHeaderManagerPhoto',
            'widgetHeader',
            'widgetBody',
            'widgetBubbleAdmin'
        )
            ->where('company_id',  $companyId)
            ->get()
        );
    }

    function clearNull(array $items){
        foreach ($items as $key => $item) {
            if (is_null($items[$key])){
                $items[$key] = "";
            }
        }
        return $items;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $companyId = auth()->check() ? auth()->user()->getPid() : 1;
        $saveData = [
            'company_id' => $companyId,
        ];
        if (isset($data['name'])){
            $saveData['name'] = $data['name'];
        }

        $widget = isset($data['id'])
            ? Widget::updateOrCreate([
                'id'         => $data['id'],
                'company_id' => $companyId,
            ], $saveData)
            : Widget::create($saveData);

        $findWidget = [
            'widget_id' => $widget->id
        ];

        if (isset($data['widget_size_and_location'])) {
            $widget->widgetSizeAndLocation()
                ->updateOrCreate($findWidget, $this->clearNull($data['widget_size_and_location']));
        }
        if (isset($data['widget_header'])) {
            $widget->widgetHeader()
                ->updateOrCreate($findWidget, $this->clearNull($data['widget_header']));
        }
        if (isset($data['widget_header_manager_photo'])) {
            $widget->widgetHeaderManagerPhoto()
                ->updateOrCreate($findWidget, $this->clearNull($data['widget_header_manager_photo']));
        }
        if (isset($data['widget_body'])) {
            $widget->widgetBody()
                ->updateOrCreate($findWidget, $this->clearNull($data['widget_body']));
        }
        if (isset($data['widget_dialogue_body'])) {
            $widget->widgetDialogueBody()
                ->updateOrCreate($findWidget, $this->clearNull($data['widget_dialogue_body']));
        }
        if (isset($data['widget_footer'])) {
            $widget->widgetFooter()
                ->updateOrCreate($findWidget, $this->clearNull($data['widget_footer']));
        }
        if (isset($data['widget_button'])) {
            $widget->widgetButton()
                ->updateOrCreate($findWidget, $this->clearNull($data['widget_button']));
        }
        if (isset($data['widget_bubble_main'])) {
            $widget->widgetBubbleMain()
                ->updateOrCreate($findWidget, $this->clearNull($data['widget_bubble_main']));
        }
        if (isset($data['widget_bubble'])) {
            $widget->widgetBubble()
                ->updateOrCreate($findWidget, $this->clearNull($data['widget_bubble']));
        }
        if (isset($data['widget_bubble_admin'])) {
            $widget->widgetBubbleAdmin()
                ->updateOrCreate($findWidget, $this->clearNull($data['widget_bubble_admin']));
        }
        if (isset($data['widget_bubble_client'])) {
            $widget->widgetBubbleClient()
                ->updateOrCreate($findWidget, $this->clearNull($data['widget_bubble_client']));
        }
        if (isset($data['widget_background_video'])) {
            $widget->widgetBackgroundVideo()
                ->updateOrCreate($findWidget, $this->clearNull($data['widget_background_video']));
        }

        return response()->json(
            (new Widget)->findWidget($widget->id)
        );

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $companyId = auth()->check() ? auth()->user()->getPid() : 1;
        return response()->json(
            (new Widget)->findWidget($id,$companyId)
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $companyId = auth()->check() ? auth()->user()->getPid() : 1;
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
            ->where('id',  $id)
            ->where('company_id',  $companyId)
            ->first();

        $widget->widgetSizeAndLocation()->delete();
        $widget->widgetHeader()->delete();
        $widget->widgetHeaderManagerPhoto()->delete();
        $widget->widgetBody()->delete();
        $widget->widgetDialogueBody()->delete();
        $widget->widgetFooter()->delete();
        $widget->widgetButton()->delete();
        $widget->widgetBubbleMain()->delete();
        $widget->widgetBubble()->delete();
        $widget->widgetBubbleAdmin()->delete();
        $widget->widgetBubbleAdmin()->delete();
        $widget->widgetBubbleClient()->delete();
        $widget->widgetBackgroundVideo()->delete();
        $widget->delete();

        return response()->json($id);
    }
    public function copy(Request $request, $id){
        $companyId = auth()->check() ? auth()->user()->getPid() : 1;
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
            ->where('id',  $id)
            ->where('company_id',  $companyId)
            ->first();


        $copyWidget = (new Widget())->create([
            'company_id' => $widget->company_id,
            'name'       => $widget->name.'_copy',
        ]);

        if (is_null($copyWidget)){
            return response()->json([
                'success' => false
            ]);
        }
        (new WidgetSizeAndLocation())->create([
            'widget_id' =>  $copyWidget->id,
            'embedType' =>  $widget->widgetSizeAndLocation->embedType,
            'width'     =>  $widget->widgetSizeAndLocation->width,
            'height'    =>  $widget->widgetSizeAndLocation->height,
            'boxShadow' =>  $widget->widgetSizeAndLocation->boxShadow,
            'align'     =>  $widget->widgetSizeAndLocation->align,
        ]);
        (new WidgetHeader())->create([
            'widget_id'     =>  $copyWidget->id,
            'fontFamily'    =>  $widget->widgetHeader->fontFamily,
            'fontSize'    =>  $widget->widgetHeader->fontSize,
            'color'    =>  $widget->widgetHeader->color,
            'backgroundSize'    =>  $widget->widgetHeader->backgroundSize,
            'backgroundImage'    =>  $widget->widgetHeader->backgroundImage,
            'backgroundColor'    =>  $widget->widgetHeader->backgroundColor,
        ]);
        (new WidgetHeaderManagerPhoto())->create([
            'widget_id'         =>  $copyWidget->id,
            'backgroundImage'   =>  $widget->widgetHeaderManagerPhoto->backgroundImage,
            'backgroundSize'   =>  $widget->widgetHeaderManagerPhoto->backgroundSize,
        ]);
        (new WidgetBody())->create([
            'widget_id'         =>  $copyWidget->id,
            'backgroundImage'   =>  $widget->widgetBody->backgroundImage,
            'backgroundColor'   =>  $widget->widgetBody->backgroundColor,
            'backgroundSize'   =>  $widget->widgetBody->backgroundSize,
        ]);
        (new WidgetDialogueBody())->create([
            'widget_id'         =>  $copyWidget->id,
            'paddingLeft'   =>  $widget->widgetDialogueBody->paddingLeft,
            'paddingRight'   =>  $widget->widgetDialogueBody->paddingRight,
        ]);
        (new WidgetFooter())->create([
            'widget_id'         =>  $copyWidget->id,
            'color'              =>  $widget->widgetFooter->color,
            'backgroundColor'   =>  $widget->widgetFooter->backgroundColor,
            'send'              =>  $widget->widgetFooter->send,
        ]);
        (new WidgetButton())->create([
            'widget_id'         =>  $copyWidget->id,
            'backgroundImage'  =>  $widget->widgetButton->backgroundImage,
            'backgroundSize'   =>  $widget->widgetButton->backgroundSize,
            'backgroundColor'  =>  $widget->widgetButton->backgroundColor,
            'color'         =>  $widget->widgetButton->color,
            'fontFamily'    =>  $widget->widgetButton->fontFamily,
            'fontSize'      =>  $widget->widgetButton->fontSize,
            'text'      =>  $widget->widgetButton->text,
            'width'      =>  $widget->widgetButton->width,
            'height'      =>  $widget->widgetButton->height,
            'textAlign'      =>  $widget->widgetButton->textAlign,
            'shape'      =>  $widget->widgetButton->shape,
            'padding'      =>  $widget->widgetButton->padding,
        ]);
        (new WidgetBubbleMain())->create([
            'widget_id'         =>  $copyWidget->id,
            'maxWidth'          =>  $widget->widgetBubbleMain->maxWidth,
            'backgroundSize'    =>  $widget->widgetBubbleMain->backgroundSize,
        ]);
        (new WidgetBubble())->create([
            'widget_id'         =>  $copyWidget->id,
            'fontFamily'        =>  $widget->widgetBubble->fontFamily,
            'fontSize'          =>  $widget->widgetBubble->fontSize,
            'boxShadow'         =>  $widget->widgetBubble->boxShadow,
            'backgroundImage'   =>  $widget->widgetBubble->backgroundImage,
            'backgroundColor'   =>  $widget->widgetBubble->backgroundColor,
            'shape'             =>  $widget->widgetBubble->shape,
            'animation'         =>  $widget->widgetBubble->animation,
        ]);
        (new WidgetBubbleAdmin())->create([
            'widget_id'         =>  $copyWidget->id,
            'color'             =>  $widget->widgetBubbleAdmin->color,
            'backgroundColor'   =>  $widget->widgetBubbleAdmin->backgroundColor,
        ]);
        (new WidgetBubbleClient())->create([
            'widget_id'         =>  $copyWidget->id,
            'color'             =>  $widget->widgetBubbleClient->color,
            'backgroundColor'   =>  $widget->widgetBubbleClient->backgroundColor,
        ]);
        (new WidgetBackgroundVideo())->create([
            'widget_id'         =>  $copyWidget->id,
            'url'             =>  $widget->widgetBackgroundVideo->url,
        ]);

        return response()->json([
            'success' => true,
            'id' => $copyWidget->id
        ]);
    }
}
