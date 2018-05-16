<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;//Notifiable 是消息通知相关功能引用
use Illuminate\Foundation\Auth\User as Authenticatable;//Authenticatable 是授权相关功能的引用
use App\Notifications\ResetPassword;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot()
    {
      parent::boot();
      /*boot 方法会在用户模型类完成初始化之后进行加载，因此我们对事件的监听需要放在该方法中。*/

      static::creating(function ($user) {
        $user->activation_token = str_random(30);
      });
      /*
      如果我们需要在模型被创建之前进行一些设置，则可以通过监听 creating 方法来做到。该方法是由 Eloquent 模型触发的一个事件。事件是 Laravel 提供一种简单的监听器实现，我们可以对事件进行监听和订阅，从而在事件被触发时接收到响应并执行一些指定操作。Eloquent 模型默认提供了多个事件，我们可以通过其提供的事件来监听到模型的创建，更新，删除，保存等操作。creating 用于监听模型被创建之前的事件，created 用于监听模型被创建之后的事件。
      */
    }

    public function gravatar($size = '100')
    //定义一个 gravatar 方法，用来生成用户的头像
    {
      $hash = md5(strtolower(trim($this->attributes['email'])));
      return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    public function sendPasswordResetNotification($token)
    {
      $this->notify(new ResetPassword($token));
    }

    public function statuses()
    {
      return $this->hasMany(Status::class);//进行模型关联，指明一个用户拥有多条微博，一对多关系
    }

    public function feed()
    {
      $user_ids = Auth::user()->followings->pluck('id')->toArray();
      array_push($user_ids, Auth::user()->id);
      return Status::whereIn('user_id', $user_ids)
                            ->with('user')
                            ->orderBy('created_at', 'desc');

        /*
        通过 followings 方法取出所有关注用户的信息，再借助 pluck 方法将 id 进行分离并赋值给 user_ids；将当前用户的 id 加入到 user_ids 数组中；使用 Laravel 提供的 查询构造器 whereIn 方法取出所有用户的微博动态并进行倒序排序；我们使用了 Eloquent 关联的 预加载 with 方法，预加载避免了 N+1 查找的问题，大大提高了查询效率。
        */

        /*
        这里需要注意的是 Auth::user()->followings 的用法。我们在 User 模型里定义了关联方法 followings()，关联关系定义好后，我们就可以通过访问 followings 属性直接获取到关注用户的 集合。这是 Laravel Eloquent 提供的「动态属性」属性功能，我们可以像在访问模型中定义的属性一样，来访问所有的关联方法。

        还有一点需要注意的是 $user->followings 与 $user->followings() 调用时返回的数据是不一样的， $user->followings 返回的是 Eloquent：集合 。而 $user->followings() 返回的是 数据库请求构建器 ，followings() 的情况下，你需要使用：

        $user->followings()->get()
        或者 ：

        $user->followings()->paginate()
        方法才能获取到最终数据。可以简单理解为 followings 返回的是数据集合，而 followings() 返回的是数据库查询语句。如果使用 get() 方法的话：

        $user->followings == $user->followings()->get() // 等于 true
        */

      /*
      return $this->statuses()
                  ->orderBy('created_at', 'desc');//定义一个 feed 方法，该方法将当前用户发布过的所有微博从数据库中取出，并根据创建时间来倒序排序
      */
    }

    public function followers()
    {
      return $this->belongsToMany(User::Class, 'followers', 'user_id', 'follower_id');
    }

    /*
    使用 belongsToMany 来关联模型之间的多对多关系,在 Laravel 中会默认将两个关联模型的名称进行合并并按照字母排序，因此生成的关联关系表名称会是 followers_user。也可以自定义生成的名称，把关联表名改为 followers。也可以通过传递额外参数至 belongsToMany 方法来自定义数据表里的字段名称.belongsToMany 方法的第三个参数 user_id 是定义在关联中的模型外键名，而第四个参数 follower_id 则是要合并的模型外键名。
    */

    public function followings()
    {
      return $this->belongsToMany(User::Class, 'followers', 'follower_id', 'user_id');
    }

    public function follow($user_ids)
    {
      if(!is_array($user_ids)) {
        $user_ids = compact('user_ids');
      }
      $this->followings()->sync($user_ids, false);
    }

    public function unfollow($user_ids)
    {
      if(!is_array($user_ids)) {
        $user_ids = compact('user_ids');
      }
      $this->followings()->detach($user_ids);
    }

    /*
    is_array 用于判断参数是否为数组，如果已经是数组，则没有必要再使用 compact 方法。我们并没有给 sync 和 detach 指定传递参数为用户的 id，这两个方法会自动获取数组中的 id。
    */

    public function isFollowing($user_id)
    {
      return $this->followings->contains($user_id);//用到 contains 方法来判断当前登录的用户 A 是否关注了用户 B
    }
}
