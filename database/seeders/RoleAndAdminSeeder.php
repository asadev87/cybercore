<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleAndAdminSeeder extends Seeder {
  public function run(): void {
    $admin = Role::firstOrCreate(['name'=>'admin']);
    $user  = Role::firstOrCreate(['name'=>'user']);

    $u = User::firstOrCreate(
      ['email' => 'admin@cybercore.local'],
      ['name' => 'CyberCore Admin', 'password'=>Hash::make('anas0807'), 'email_verified_at'=>now()]
    );
    $u->assignRole('admin');
  }
}

