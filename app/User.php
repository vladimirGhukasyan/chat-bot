<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    private $roles = [
        1 => 'Support manager',
        2 => 'Owner',
        9 => 'Manager',
        10 => 'Admin',
    ];

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'company_size','email', 'password', 'privilege', 'api_token',  'avatar', 'user_name', 'recent_visit_at','api_key_push'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roleDescription(){
        return $this->roles[$this->privilege];

    }
    public function getRoomID(){
        return $this->pid != 0 ? $this->pid: $this->id;
    }

    public function getPid(){
        return $this->pid != 0 ? $this->pid: $this->id;
    }

}
