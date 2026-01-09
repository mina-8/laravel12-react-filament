<?php

namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;
use App\Enums\RolesEnum;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = [];
        foreach (RolesEnum::cases() as $role) {
            $permissions = $this->getPermissionsForRole($role);
            $rolesWithPermissions[] = [
                'name' => $role->value,
                'guard_name' => 'admin',
                'permissions' => $permissions,
            ];
        }

        static::makeRolesWithPermissions($rolesWithPermissions);
        $directPermissions = '[]';

        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(array $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = $rolesWithPermissions)) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    protected function getPermissionsForRole(RolesEnum $role): array
    {
        return match($role) {
            RolesEnum::SUPER_ADMIN => $this->getAllPermissions(),
            RolesEnum::ADMIN => $this->getAdminPermissions(),
            // RolesEnum::WRITER => $this->getWriterPermissions(),
            RolesEnum::CRM => $this->getCrmPermissions(),
        };
    }

    protected function getAllPermissions(): array
    {
        return PermissionsEnum::SuperAdminPermissions();
    }

    protected function getAdminPermissions(): array
    {
        // Same as super_admin for now, you can customize
        return $this->getAllPermissions();
    }

    // protected function getWriterPermissions(): array
    // {
    //     return PermissionsEnum::WriterPermissions();
    // }

    protected function getCrmPermissions(): array
    {
        return PermissionsEnum::CrmPermissions();
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}