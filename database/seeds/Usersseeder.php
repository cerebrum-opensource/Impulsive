<?php

use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Role;

class Usersseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void 
     */
    public function run(){
        $password = 'mind@123';
        $user = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'salutation' => 'Mr',
            'username' => 'superadmin',
            'email' => 'super_admin@winimi.impulsive-projects.de',
            'password' => bcrypt($password),
//			'user_type' => '0',
			'is_logged_in' => '0',
			'street' => 'Phase 8 Industrial Area',
			'postal_code' => '720062',
			'city' => 'Chandigarh',
			'country' => 'India',
			'additional_address' => '',
            'status' => '1',
            'profile_img' => 'dp.jpeg'
        ]);
        $role_r = Role::where('name', '=', SUPER_ADMIN)->firstOrFail();  
        $user->assignRole($role_r);
        $user1 = User::create([
            'first_name' => 'Impulsive',
            'last_name' => 'Dev',
            'salutation' => 'Mr',
            'username' => 'ipulsivedev',
            'email' => 'impulsive_dev@winimi.impulsive-projects.de',
            'password' => bcrypt($password),
//            'user_type' => '1',
            'is_logged_in' => '0',
            'street' => 'Phase 9 Industrial Area',
            'postal_code' => '720058',
            'city' => 'SBS Nagar',
            'country' => 'India',
            'additional_address' => '',
            'status' => '1',
            'profile_img' => 'dp.jpeg'
        ]);

        $role_r = Role::where('name', '=', USER)->firstOrFail();  
        $user1->assignRole($role_r);
    }

   
}