<?php

namespace App\Models;
//消息通知
use Illuminate\Notifications\Notifiable;
//授权
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Notifications\ResetPassword;

class User extends Authenticatable
{
    use Notifiable;

    /**
    *表名
    *
    */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *只有包含在该属性中的字段才能够被正常更新
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     *对用户密码或其它敏感信息在用户实例通过数组或 JSON 显示时进行隐藏
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot(){
        parent::boot();

        static::creating(function($user){
            $user->activation_token = str_random(30);
        });
    }

    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function statuses(){
        return $this->hasMany(Status::class);
    }

    public function feed(){
        return $this->statuses()
                    ->orderBy('created_at', 'desc');
    }
}
