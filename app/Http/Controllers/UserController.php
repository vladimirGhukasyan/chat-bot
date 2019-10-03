<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function uploadAvatar(Request $request, $user){
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $avatarName = $user->id.'_avatar'.time().'.'.request()->avatar->getClientOriginalExtension();
        $request->avatar->storeAs('avatars',$avatarName);

        $user->avatar = $avatarName;

        return $user->save();
    }
    private function updateOrCreateUser(Request $request, $manager){
        $manager->name      = $request->input('name', $manager->name);
        $manager->user_name = $request->input('user_name', $manager->user_name);
        $manager->phone     = $request->input('phone', $manager->phone);

        if ($password = $request->input('password') !== null) {
            if ($password != $request->input('password_confirmation')) {
                return response()->json([
                    "success"       => false,
                    "description"   => "password != password_confirmation"
                ], 400);
            }
            $manager->password = Hash::make($password);

        }

        if ($request->input('avatar') !== null) {
            $is_success = $this->uploadAvatar($request, $manager);
        } else {
            $is_success = $manager->save();
        }

        return [
            "success"   => $is_success,
            "user"      => $manager,
        ];
    }
    //=============================================
    public function updateAvatar(Request $request){
        return response()->json([
            "success"   => $this->uploadAvatar($request, Auth::user()),
            "user"      => Auth::user()
        ]);
    }
    public function updateUser(Request $request){
        $request->validate([
            'id'        => 'required|integer',
            'name'      => 'string|max:255',
            'avatar'    => 'string',
            'user_name' => 'string|max:255',
            'phone'     => 'string|max:64',
            'password'  => 'string|max:255',
        ]);
        //==================================

        $id = $request->input('id');
        $user = Auth::user();
        $manager = App\User::where(
            [
                'id'  => $id,
                'pid' => $user->getPid(),
            ]
        )->firstOrFail();

        return $this->updateOrCreateUser($request, $manager);
    }
    public function createUser(Request $request){
        $request->validate([
            'name'                  => 'required|string|max:255',
            'avatar'                => 'string',
            'user_name'             => 'string|max:255',
            'phone'                 => 'required|string|max:64',
            'password'              => 'required|string|max:255',
            'password_confirmation' => 'required|string|max:255',
        ]);
        //==================================
        $user = Auth::user();

        $manager = (new User());
        $manager->pid = $user->getPid();
        //==================================

        return $this->updateOrCreateUser($request, $manager);
    }
    //=============================================
    public function listUsers(Request $request){
        $companyId = auth()->check() ? auth()->user()->getPid() : 1;
        return response()->json(
            (new User)->where('company_id',  $companyId)->get()
        );
    }
}
