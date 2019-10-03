<?php
namespace App\Http\Controllers\Client;

use App\Models\Bot;
use App\Models\BotBlock;
use App\Models\BotConnection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Chat;

class ArchiveController extends Controller
{
   public function store(Request $request){

       return $request->all();
   }
}
