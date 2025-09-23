<?php

use Spatie\Permission\Models\Role;

public function run(): void {
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'user']);
}
