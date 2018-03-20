<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(UsersTableSeeder::class);//在 DatabaseSeeder中调用 call方法来指定要运行假数据填充的文件
        Model::reguard();
    }
}
