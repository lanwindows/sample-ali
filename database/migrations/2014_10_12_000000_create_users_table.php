<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()//运行迁移时，up 方法会被调用
    {
        Schema::create('users', function (Blueprint $table) {
          /*
          通过调用 Schema 类的 create 方法来创建 users 表,create 方法会接收两个参数：一个是数据表的名称，另一个则是接收 $table（Blueprint 实例）的闭包。
          */
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();//记住我
            $table->timestamps();//timestamps 方法会创建了一个 created_at 和一个 updated_at 字段，分别用于保存用户的创建时间和更新时间。
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()//回滚迁移时，down 方法会被调用
    {
        Schema::dropIfExists('users');//通过调用 Schema 的 drop 方法来删除 users 表
    }
}
