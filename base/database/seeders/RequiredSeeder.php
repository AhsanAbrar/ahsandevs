<?php

namespace Database\Seeders;

use AhsanDev\Support\NestedCategory;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RequiredSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Cache::flush();

        $this->users();
        $this->categories();
        $this->options();
        $this->roles();
        $this->permissions();
        $this->pivots();
    }

    /**
     * Seed the application's database.
     */
    protected function users(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    /**
     * Seed the application's database.
     */
    protected function categories(): void
    {
        $categories = [
            //
        ];

        DB::table('categories')->insert(
            NestedCategory::make($categories)->get()
        );
    }

    /**
     * Seed the application's database.
     */
    protected function options(): void
    {
        option([
            'app_direction' => 'ltr',
            'app_locale' => 'en',
            'app_name' => 'App Name',
            'app_timezone' => 'UTC',
            'app_url' => 'https://your-domain.com',
            'date_format' => 'M d, Y',
            'default_color' => 'slate-500',
            'email_config' => false,
            'header_logo' => null,
            'mail_driver' => 'smtp',
            'mail_encryption' => 'null',
            'per_page' => 15,
            'app_updates' => [
                'update_available' => false,
                'version' => '1.0.0',
                'new_version' => null,
                'checked_at' => null,
            ],
        ]);
    }

    /**
     * Seed the application's database.
     */
    protected function roles(): void
    {
        $data = [
            'Admin',
            'User',
        ];

        $data = collect($data)->map(function ($value) {
            return ['name' => $value];
        })->all();

        DB::table('roles')->insert($data);
    }

    /**
     * Seed the application's database.
     */
    protected function permissions(): void
    {
        $data = [
            'setting',
            'user:create',
            'user:delete',
            'user:update',
            'user:view',
        ];

        $data = collect($data)->map(function ($value) {
            return ['name' => $value];
        })->all();

        DB::table('permissions')->insert($data);
    }

    /**
     * Seed the application's database.
     */
    protected function pivots(): void
    {
        // Assign permissions to the role.
        $role = Role::first();
        $permissions = Permission::get();
        $role->permissions()->sync($permissions);
        $user = User::first();
        $user->assignRole($role->name);
    }
}
