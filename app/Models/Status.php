<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['content'];//在模型的 fillable 属性中允许更新 content 字段

    public function user()
    {
      return $this->belongsTo(User::class);//进行模型关联，指明一条微博属于一个用户，一对一关系
    }

}
