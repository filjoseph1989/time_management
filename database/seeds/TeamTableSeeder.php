<?php

use Illuminate\Database\Seeder;

class TeamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teams = array(
            array('id' => '2','name' => 'team 1','project_id' => '4','created_at' => '2019-02-20 01:00:51','updated_at' => '2019-02-19 19:21:27'),
            array('id' => '3','name' => 'team 2','project_id' => '3','created_at' => '2019-02-20 01:06:42','updated_at' => '2019-02-19 19:20:56')
        );

        DB::table('teams')->insert($users);
    }
}
