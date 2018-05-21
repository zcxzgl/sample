<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Auth;

class UsersController extends Controller
{   /**
    *__construct 是 PHP 的构造器方法，当一个类对象被创建之前该方法将会被调用
    */
    public function __construct(){
        $this->middleware('auth',[
            'except'=>['show','create','store','index']
        ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
    public function index(){
        $users = User::paginate(10);
        return view('users.index',compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
    /**
    *数据验证
    *@validator 两个参数 输入数据 验证规则
    *@required 验证是否为空 
    *@min 和 max限制长度 
    *@unique 唯一性验证：是否被使用
    *@confirmed 密码匹配验证，两次输入的密码一致
    */
    public function store(Request $request){
    	$this->validate($request,[
    		'name' => 'required|max:50',
    		'email' => 'required|email|unique:users|max:255',
    		'password' => 'required|confirmed|min:6'
    	]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);
        session()->flash('success','欢迎，您将在这里开启一段新的旅程~');
    	return redirect()->route('users.show',[$user]);
    }

    /**
    *编辑用户
    */
    public function edit(User $user){
        $this->authorize('update', $user);
        return view('users.edit',compact('user'));
    }

    /**
    *跟新用户
    */
    public function update(User $user,Request $request){
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $this->authorize('update', $user);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $user->id);
    }

    public function destroy(User $user){
        $this->authorize('destroy', $user);

        $user->delete();

        session()->flash('success','成功删除用户！');

        return back();
    }
}