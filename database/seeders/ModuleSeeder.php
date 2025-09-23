<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['slug'=>'phishing-basics', 'title'=>'Phishing Basics',
             'description'=>'Spot suspicious emails and links; stay safe.',
             'is_active'=>true, 'pass_score'=>70],
            ['slug'=>'malware-101', 'title'=>'Malware 101',
             'description'=>'Ransomware, trojans & safe installs.',
             'is_active'=>true, 'pass_score'=>70],
            ['slug'=>'password-hygiene', 'title'=>'Password Hygiene',
             'description'=>'Strong, unique passwords & MFA.',
             'is_active'=>true, 'pass_score'=>70],
            ['slug'=>'safe-browsing', 'title'=>'Safe Browsing',
             'description'=>'Public Wi-Fi, HTTPS, trackers.',
             'is_active'=>true, 'pass_score'=>70],
        ];

        foreach ($data as $m) {
            Module::firstOrCreate(['slug'=>$m['slug']], $m);
        }
    }
}
