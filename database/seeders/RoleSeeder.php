<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin',    'label' => 'Administrator'],
            ['name' => 'lecturer', 'label' => 'Lecturer'],
            ['name' => 'learner',  'label' => 'Learner'],
        ];

        $guard = config('auth.defaults.guard', 'web');
        $hasLabelColumn = Schema::hasColumn('roles', 'label');

        foreach ($roles as $role) {
            $values = ['guard_name' => $guard];
            if ($hasLabelColumn) {
                $values['label'] = $role['label'];
            }

            $model = Role::firstOrCreate(
                ['name' => $role['name'], 'guard_name' => $guard],
                $values
            );

            if ($hasLabelColumn && $model->label !== $role['label']) {
                $model->label = $role['label'];
                $model->save();
            }
        }
    }
}
