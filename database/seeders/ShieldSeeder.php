<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_applicant","view_any_applicant","create_applicant","update_applicant","delete_applicant","delete_any_applicant","view_apply","view_any_apply","create_apply","update_apply","delete_apply","delete_any_apply","view_category","view_any_category","create_category","update_category","delete_category","delete_any_category","view_department","view_any_department","create_department","update_department","delete_department","delete_any_department","view_employee::review","view_any_employee::review","create_employee::review","update_employee::review","delete_employee::review","delete_any_employee::review","view_final::interview::status","view_any_final::interview::status","create_final::interview::status","update_final::interview::status","delete_final::interview::status","delete_any_final::interview::status","view_interview::date","view_any_interview::date","create_interview::date","update_interview::date","delete_interview::date","delete_any_interview::date","view_jobs","view_any_jobs","create_jobs","update_jobs","delete_jobs","delete_any_jobs","view_leave::request","view_any_leave::request","create_leave::request","update_leave::request","delete_leave::request","delete_any_leave::request","view_projects","view_any_projects","create_projects","update_projects","delete_projects","delete_any_projects","view_reals","view_any_reals","create_reals","update_reals","delete_reals","delete_any_reals","view_review","view_any_review","create_review","update_review","delete_review","delete_any_review","view_services","view_any_services","create_services","update_services","delete_services","delete_any_services","view_shield::role","view_any_shield::role","create_shield::role","update_shield::role","delete_shield::role","delete_any_shield::role","view_skils","view_any_skils","create_skils","update_skils","delete_skils","delete_any_skils","view_team::members","view_any_team::members","create_team::members","update_team::members","delete_team::members","delete_any_team::members","view_users","view_any_users","create_users","update_users","delete_users","delete_any_users","view_vacations","view_any_vacations","create_vacations","update_vacations","delete_vacations","delete_any_vacations","widget_CalendarWidget","widget_InterviewDate","widget_StatsOverview"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
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
