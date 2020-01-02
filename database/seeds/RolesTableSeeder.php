<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('roles')->insert([
        	[
	            'name' => 'SUPER_ADMIN',
	            'guard_name' => 'web',
	            'created_at' => Carbon::now(),
	            'updated_at' => Carbon::now()
        	],
        	[
	            'name' => 'ADMIN',
	            'guard_name' => 'web',
	            'created_at' => Carbon::now(),
	            'updated_at' => Carbon::now()
        	],
        	[
	            'name' => 'SELLER',
	            'guard_name' => 'web',
	            'created_at' => Carbon::now(),
	            'updated_at' => Carbon::now()
        	],
        	[
	            'name' => 'USER',
	            'guard_name' => 'web',
	            'created_at' => Carbon::now(),
	            'updated_at' => Carbon::now()
        	]
        ]);
    }
}
