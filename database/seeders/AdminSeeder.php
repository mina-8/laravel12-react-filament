<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء Admin
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@email.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        // إنشاء دور super_admin إذا لم يكن موجوداً (Shield يستخدم guard_name admin)
        $role = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'admin']
        );

         // منح الدور جميع الصلاحيات من Shield
        $permissions = Permission::where('guard_name', 'admin')->get();
        if ($permissions->isNotEmpty()) {
            $role->syncPermissions($permissions);
        }

        // تعيين الدور للأدمن وإزالة أي أدوار أخرى
        $admin->syncRoles([$role]);
    }
}
