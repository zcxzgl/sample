<?php

namespace App\Models;
//消息通知
use Illuminate\Notifications\Notifiable;
//授权
use Illuminate\Foundation\Auth\User as Authenticatable;

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
}
