<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::truncate();

        $permissions = [
            'dashboard',
            'permission-list',
            'permission-show',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'users-list',
            'user-create',
            'user-edit',
            'user-delete',
            'client-list',
            'client-create',
            'client-edit',
            'client-delete',
            'course-list',
            'course-create',
            'course-edit',
            'course-delete',
            'subcourse-list',
            'subcourse-create',
            'subcourse-edit',
            'subcourse-delete',
            'quiz-list',
            'quiz-create',
            'quiz-edit',
            'quiz-delete',
            'slider-list',
            'slider-create',
            'slider-edit',
            'slider-delete',
        ];
 
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
