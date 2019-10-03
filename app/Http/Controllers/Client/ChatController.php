<?php

namespace App\Http\Controllers\Client;

use App\Models\Botsapp;
use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\Widget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class ChatController extends Controller
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
            Chat::where('company_id',  $companyId)->get()
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
        $saveData['bot_id']    = $data['bot_id'];
        $saveData['widget_id'] = $data['widget_id'];
        $chat = null;

        //====================================
        if (isset($data['id'])){
            $chat = Chat::updateOrCreate([
                'id'         => $data['id'],
                'company_id' => $companyId,
            ], $saveData);
        } else {

            $saveData['secret'] = Str::random(60);

            $chat = Chat::create($saveData);
        }
        return response()->json($chat);
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
            Chat::where('id', $id)->where('company_id', $companyId)->first()
        );
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $companyId = auth()->check() ? auth()->user()->getPid() : 1;
        return response()->json(
            Chat::where('id', $id)->where('company_id', $companyId)->delete()
        );
    }
    public function addUser(Request $request){
        $request->validate([
            'botsapp_id'    => 'required|integer',
            'user_id'       => 'required|integer',
        ]);
        $companyId  = auth()->check() ? auth()->user()->getPid() : 1;
        $userId     = $request->input('user_id');
        $chatId     = $request->input('chat_id');

        (new Chat())
            ->where('id',  $chatId)
            ->where('company_id',  $companyId)
            ->firstOrFail();


        response()->json((new ChatUser())->create([
            'user_id'       =>  $userId,
            'botsapp_id'    =>  $chatId,
        ]));
    }
    public function deleteUser(Request $request, $chatId, $id){
        $companyId  = auth()->check() ? auth()->user()->getPid() : 1;
        (new Chat())
            ->where('id',  $chatId)
            ->where('company_id',  $companyId)
            ->firstOrFail();

        (new ChatUser())
            ->where('id',  $id)
            ->delete();

        return response()->json($id);
    }
    public function showByToken(Request $request,$token)
    {
        $chat = Chat::where('secret', $token)->first();
        if(!$chat){
            return response()->json(
                0, 404
            );
        }

        $result = $chat->toArray();
        $result['widget'] = (new Widget)->findWidget($chat->widget_id);
        $result['bot']    =  $chat->bot()->first()->getData();

        $client_id = $request->cookie("client-id", Str::uuid());

//        (new Redis())->set("{$client_id}.{$chat->secret}", json_encode([
//            'chat_id'   => $chat->id,
//            'room_id'   => $chat->company_id,
//            'client_id' => $client_id,
//        ]));


        $result['token'] = "{$client_id}.{$chat->secret}";

        return response()->json(
            $result
        )->header('Access-Control-Allow-Origin', '*')
         ->cookie("client-id", $client_id);
    }
}
