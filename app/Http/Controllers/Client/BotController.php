<?php

namespace App\Http\Controllers\Client;

use App\Models\Bot;
use App\Models\BotBlock;
use App\Models\BotConnection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Chat;

class BotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companyId = auth()->check() ? auth()->user()->getPid() : 1;
        // $companyId = 1;
        return response()->json(
            Bot::where('company_id',  $companyId)->get()
        );
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

        $bot = isset($data['id'])
            ? Bot::updateOrCreate([
                'id'         => $data['id'],
                'company_id' => $companyId,
            ], $saveData)
            : Bot::create($saveData);

        return response()->json($bot);
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

        $bot = Bot::where('id',  $id)
            ->where('company_id',  $companyId)
            ->first();
        return response()->json($bot->getData());
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
        $bot = Bot::with(
            'botBlocks',
            'botConnection'
        )
            ->where('id',  $id)
            ->where('company_id',  auth()->user()->getPid())
            ->first();

        if (!$bot){
            return response()->json($bot, 404);
        }

        $bot->botBlocks()->delete();
        $bot->botConnection()->delete();
        $bot->delete();

        return response()->json($id);
    }

    public function storeTyping(Request $request, $botId) {
        $companyId = auth()->check() ? auth()->user()->getPid() : 1;

        $bot = Bot::where("id", $botId)
            ->where('company_id', $companyId)
            ->first();

        if (!$bot){
            return response()->json($bot, 404);
        }

        $data = $request->all();
        Bot::updateOrCreate([
            'id'         => $botId,
            'company_id' => $companyId,
        ], $data);

        return response()->json($botId);
    }

    public function storeBlock(Request $request, $id){
        //$request->request->add(['agentIdList'=>[1,9]]);

        $companyId = auth()->check() ? auth()->user()->getPid() : 1;

        $bot = Bot::where("id", $id)
                  ->where('company_id', $companyId)
               ->first();

        if (!$bot){
            return response()->json($bot, 404);
        }
        $data = $request->all();
        $data['bot_id'] = $bot->id;

        $data['agentExtraInfo'] = json_encode( $data['agentExtraInfo']);
        $data['range']          = json_encode( $data['range']);
        $data['pins']           = json_encode( $data['pins']);
        $data['ab']             = json_encode( $data['ab']);
        $data['agentIdList']    = json_encode( $data['agentIdList']);


        $block = null;
        if (isset($data['id'])){
            $block = (new BotBlock())->updateOrCreate([
                'id'         => $data['id'],
                'bot_id'     => $bot->id,
            ], $data);

        } else {
            $block = (new BotBlock())->create($data);
            //$block = (new BotBlock())->insertGetId($data);
        }

        $block = $block->toArray();
        $block['agentExtraInfo'] = json_decode( $block['agentExtraInfo'], true);
        $block['range']          = json_decode( $block['range'], true);
        $block['pins']           = json_decode( $block['pins'], true);
        $block['ab']             = json_decode( $block['ab'], true);

        return response()->json($block);
    }

    public function destroyBlock($botId, $id)
    {
        $bot = Bot::with(
            'botBlocks',
            'botConnection'
        )
            ->where('id',  $botId)
            ->where('company_id',  auth()->user()->getPid())
            ->first();

        if (!$bot){
            return response()->json($bot, 404);
        }

        $bot->botBlocks()->where('id', $id)->delete();

        return response()->json($id);
    }

    public function storeConnection(Request $request, $id){
        $companyId = auth()->check() ? auth()->user()->getPid() : 1;

        $bot = Bot::where("id", $id)
            ->where('company_id', $companyId)
            ->first();

        if (!$bot){
            return response()->json($bot, 404);
        }

        $data = $request->all();

        $bot->botConnection()->updateOrCreate([
            'bot_id'     => $bot->id,
        ], ["values" => json_encode($data)]);


        return response()->json($bot->botConnection);
    }

    public function copy(Request $request, $id){
        $companyId = auth()->check() ? auth()->user()->getPid() : 1;
        $bot = Bot::with('botConnection', 'botBlocks')
            ->where("id", $id)
            ->where('company_id', $companyId)
            ->first();

        $copyBot = (new Bot())->create([
            'company_id' => $bot->company_id,
            'name'       => $bot->name.'_copy',
        ]);
        if (is_null($copyBot)){
            return response()->json([
                'success' => false
            ]);
        }

        foreach ($bot->botBlocks as $botBlock) {
            (new BotBlock())->create([
                'bot_id'            => $copyBot->bot_id,
                'blockType'         => $botBlock->blockType,
                'content'           => $botBlock->content,
                'positionX'         => $botBlock->positionX,
                'positionY'         => $botBlock->positionY,
                'parameter'         => $botBlock->parameter,
                'externalLink'      => $botBlock->externalLink,
                'notificationType'  => $botBlock->notificationType,
                'agentExtraInfo'    => $botBlock->agentExtraInfo,
                'emojiId'           => $botBlock->emojiId,
                'agentId'           => $botBlock->agentId,
                'range'             => $botBlock->range,
                'pins'              => $botBlock->pins,
                'ab'                => $botBlock->ab,
            ]);
        }
        (new BotConnection())->create([
            'bot_id'    => $copyBot->bot_id,
            'values'    => $bot->botConnection->values,
        ]);

        return response()->json([
            'success' => true,
            'id'      => $copyBot->id,
        ]);
    }

    public function getUsers($bot_id){

        $chat = Chat::where("bot_id", $bot_id)->first();

        if (!$chat){
            return response()->json($chat, 404);
        }

        return response()->json($chat->getData());
    }
}
