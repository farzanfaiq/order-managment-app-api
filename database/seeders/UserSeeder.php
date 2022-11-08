<?php

namespace Database\Seeders;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use function Ramsey\Uuid\v1;

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
        Permission::create([ 'name' => 'dashboard']);
        Permission::create([ 'name' => 'manager-list']);
        Permission::create([ 'name' => 'manager-create']);
        Permission::create([ 'name' => 'manager-delete']);
        Permission::create([ 'name' => 'manager-update']);
        Permission::create([ 'name' => 'rider-list']);
        Permission::create([ 'name' => 'rider-create']);
        Permission::create([ 'name' => 'rider-delete']);
        Permission::create([ 'name' => 'rider-update']);
    }

    public function roles(){
        Role::create([ 'name' => 'admin']);
        Role::create([ 'name' => 'customer']);
        Role::create([ 'name' => 'manager']);
        Role::create([ 'name' => 'rider']);
    }


    public function assignRole(){
        $user = User::find(1);
        $user->assignRole('admin');

        $customer = User::find(2);
        $customer->assignRole('customer');
    }

    public function assignPermission(){
        $permissions = Permission::all();
        $roles = Role::find(1);

        foreach($permissions as $permission){
            $roles->givePermissionTo($permission);
        }
        
        $roles = Role::find(3);

        foreach($permissions as $permission){
            if($permission->name = 'rider-list' ||  $permission->name = 'rider-create' ||  $permission->name = 'rider-delete' ||  $permission->name = 'rider-update'){
                $roles->givePermissionTo($permission);
            }
        }

    }
}
