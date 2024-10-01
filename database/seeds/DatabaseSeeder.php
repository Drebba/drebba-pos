<?php

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Branch;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Settings;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        if(Settings::count() == 0){
            $this->call(AppSettings::class);
        }

        if (Role::count() == 0){
            $this->call(RoleSeeder::class);
        }

        if(Permission::count() == 0){
            $this->call(PermissionSeeder::class);
        }

        if(User::count() > 0 && Employee::count() == 0 && Branch::count() == 0){
            $this->call(EmployeeSeeder::class);
        }
    }
}
