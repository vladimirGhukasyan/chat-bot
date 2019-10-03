<?php

namespace App\Http\Controllers\Client;

use App\Models\Bot;
use App\Models\Botsapp;
use App\Models\ChatUser;
use App\Models\Widget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BotsAppController extends Controller
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
            (new Botsapp())
                ->with('botAppUsers', 'botAppUsers.user')
                ->where('company_id',  $companyId)
                ->get()
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

        $botApp = isset($data['id'])
            ? (new Botsapp)->updateOrCreate([
                'id'         => $data['id'],
                'company_id' => $companyId,
            ], $saveData)
            : (new Botsapp)->create($saveData);

        return response()->json(
            $botApp
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
        // $companyId = 1;
        return response()->json(
            (new Botsapp())
                ->with('botAppUsers', 'botAppUsers.user')
                ->where('id',  $id)
                ->where('company_id',  $companyId)
                ->first()
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

        (new Botsapp())
            ->where('id',  $id)
            ->where('company_id',  $companyId)
            ->delete();

        return response()->json($id);
    }
    //=====================================================
    public function addUser(Request $request){
        $request->validate([
            'botsapp_id'    => 'required|integer',
            'user_id'       => 'required|integer',
        ]);
        $companyId  = auth()->check() ? auth()->user()->getPid() : 1;
        $userId    = $request->input('user_id');
        $botsappId = $request->input('botsapp_id');

        (new Botsapp())
            ->where('id',  $botsappId)
            ->where('company_id',  $companyId)
            ->firstOrFail();

        response()->json((new ChatUser())->create([
            'user_id'       =>  $userId,
            'botsapp_id'    =>  $botsappId,
        ]));
    }
    public function deleteUser(Request $request, $id){
        $companyId  = auth()->check() ? auth()->user()->getPid() : 1;
        (new Botsapp())
            ->where('id',  $id)
            ->where('company_id',  $companyId)
            ->delete();

        return response()->json($id);
    }

}
