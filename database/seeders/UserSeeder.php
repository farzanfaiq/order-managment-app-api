<?php

namespace Database\Seeders;

use App\Models\Permissions;
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
        $this->permission();
        $this->assignRole();
        $this->assignPermission();
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


    public function permission(){
        Permissions::create(
            [ 'name' => 'Dashboard', 'slug' => 'dashboard']
        );

        Permissions::create(
            [ 'name' => 'Manager List', 'slug' => 'manager-list']
        );

        
        Permissions::create(
            [ 'name' => 'Manager Create', 'slug' => 'manager-create']
        );

        
        Permissions::create(
            [ 'name' => 'Manager Delete', 'slug' => 'manager-delete']
        );


        Permissions::create(
            [ 'name' => 'Manager Update', 'slug' => 'manager-update']
        );


        Permissions::create(
            [ 'name' => 'Rider List', 'slug' => 'rider-list']
        );

        
        Permissions::create(
            [ 'name' => 'Rider Create', 'slug' => 'rider-create']
        );

        
        Permissions::create(
            [ 'name' => 'Rider Delete', 'slug' => 'rider-delete']
        );


        Permissions::create(
            [ 'name' => 'Rider Update', 'slug' => 'rider-update']
        );
        
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

    public function assignPermission(){
        $permissions = Permissions::all();
        $roles = Roles::find(1);

        foreach($permissions as $permission){
            $roles->permissions()->attach($permission);
        }


        $roles = Roles::find(3);

        foreach($permissions as $permission){
            if($permission->slug = 'rider-list' ||  $permission->slug = 'rider-create' ||  $permission->slug = 'rider-delete' ||  $permission->slug = 'rider-update'){
                $roles->permissions()->attach($permission);
            }
        }

    }
}
