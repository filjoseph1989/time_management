<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array(
            array('id' => '1','name' => 'Fil','email' => 'fil@email.com','email_verified_at' => NULL,'password' => '$2y$10$84B.ZkQjCabBT2yG4nHkiebPPoK0kURcQ8LKk9oBaq/k/2q4V.Rie','type' => 'user','bio' => NULL,'photo' => 'img/profile.png','remember_token' => NULL,'created_at' => '2019-02-19 01:37:18','updated_at' => '2019-02-19 01:37:18'),
            array('id' => '2','name' => 'Liz','email' => 'liz@email.com','email_verified_at' => '2019-02-20 02:24:21','password' => '$2y$10$84B.ZkQjCabBT2yG4nHkiebPPoK0kURcQ8LKk9oBaq/k/2q4V.Rie','type' => 'client','bio' => NULL,'photo' => 'img/profile.png','remember_token' => NULL,'created_at' => '2019-02-20 02:24:29','updated_at' => '2019-02-20 02:24:30')
        );

        DB::table('users')->insert($users);
    }
}
