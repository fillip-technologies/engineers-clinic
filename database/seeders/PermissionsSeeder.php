<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // College management
            ['name' => 'college.payment.verify', 'label' => 'Verify college payments'],
            ['name' => 'college.payment.approve', 'label' => 'Approve college payments'],
            ['name' => 'college.payment.reject', 'label' => 'Reject college payments'],

            // Internship seats
            ['name' => 'internship.seat.allocate', 'label' => 'Allocate internship seats'],
            ['name' => 'internship.seat.revoke', 'label' => 'Revoke internship seat allocation'],
            ['name' => 'internship.seat.purchase', 'label' => 'Purchase internship seats (college)'],

            // Enrollment management
            ['name' => 'enrollment.status.override', 'label' => 'Override enrollment status'],
            ['name' => 'enrollment.create', 'label' => 'Create enrollments'],
            ['name' => 'enrollment.delete', 'label' => 'Delete enrollments'],

            // Reports
            ['name' => 'report.revenue.view', 'label' => 'View revenue reports'],
            ['name' => 'report.seats.view', 'label' => 'View seat utilization reports'],
            ['name' => 'report.enrollments.view', 'label' => 'View enrollment funnel reports'],

            // API access
            ['name' => 'api.access', 'label' => 'Access REST API'],
        ];

        foreach ($permissions as $data) {
            Permission::firstOrCreate(['name' => $data['name']], ['label' => $data['label']]);
        }

        // Assign all new permissions to the admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $allPermissions = Permission::whereIn('name', collect($permissions)->pluck('name'))->get();
            foreach ($allPermissions as $permission) {
                RolePermission::firstOrCreate([
                    'role_id' => $adminRole->id,
                    'permission_id' => $permission->id,
                ]);
            }
        }

        // Assign seat-related permissions to college role
        $collegeRole = Role::where('name', 'college')->first();
        if ($collegeRole) {
            $collegePermissions = Permission::whereIn('name', [
                'internship.seat.allocate',
                'internship.seat.revoke',
                'internship.seat.purchase',
                'enrollment.create',
                'api.access',
            ])->get();

            foreach ($collegePermissions as $permission) {
                RolePermission::firstOrCreate([
                    'role_id' => $collegeRole->id,
                    'permission_id' => $permission->id,
                ]);
            }
        }
    }
}
