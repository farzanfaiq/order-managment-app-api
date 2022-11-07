<?php

namespace Database\Seeders;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $this->users();
        $this->roles();
        $this->assignRole();
    }

    public function users(){
        User::create([
            'name' => 'admin',
            'email' => 'admin@order.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'), // password
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'name' => 'customer',
            'email' => 'customer@order.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'), // password
            'remember_token' => Str::random(10),
            'phone_number' => '0990009090', 
            'gender' => 'male'
        ]);


        
    }


    public function roles(){
        Roles::create(
            [ 'name' => 'Admin', 'slug' => 'admin']
        );

        Roles::create(
            [ 'name' => 'Customer', 'slug' => 'customer']
        );


        Roles::create(
            [ 'name' => 'Manager', 'slug' => 'manager']
        );


        Roles::create(
            [ 'name' => 'Rider', 'slug' => 'rider']
        );
        
    }


    public function assignRole(){
        $user = User::find(1);
        $user->assignRole('admin');

        $customer = User::find(2);
        $customer->assignRole('customer');
    }
}
