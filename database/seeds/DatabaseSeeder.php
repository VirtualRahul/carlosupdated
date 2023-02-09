<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $email = 'admin@gmail.com';

        $user = User::where('email', $email)->first();
        if(!$user){
            $user = User::create([
                'name' => 'Application Admin', 
                'email' => $email,
                'password' => bcrypt('welcome')
            ]);
        }
    	

    	$role = Role::select('id')->where('name', 'Super Admin')->first();

    	if(!$role){
        	$role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
    	}

        $user->assignRole([$role->id]);
    }
}
