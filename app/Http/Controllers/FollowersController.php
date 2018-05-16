<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Auth;

class FollowersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }/*由于这两个动作都需要用户登录之后才能进行操作，因此我们为这两个动作都加上请求过滤*/

    public function store(User $user)
    {
        if (Auth::user()->id === $user->id) {
            return redirect('/');
        }/*用户不能对自己进行关注和取消关注，因此在 store 和 destroy 方法中都对用户身份做了判断，当执行关注和取消关注的用户对应的是当前的用户时，重定向到首页。*/

        if (!Auth::user()->isFollowing($user->id)) {//进行关注和取消关注操作之前，利用 isFollowing 方法来判断当前用户是否已关注了要进行操作的用户。
            Auth::user()->follow($user->id);
        }

        return redirect()->route('users.show', $user->id);
    }

    public function destroy(User $user)
    {
        if (Auth::user()->id === $user->id) {
            return redirect('/');
        }

        if (Auth::user()->isFollowing($user->id)) {
            Auth::user()->unfollow($user->id);
        }

        return redirect()->route('users.show', $user->id);
    }
}
