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
             'note'=>'Skim a few recent emails so you can immediately apply the red flags covered here.',
             'is_active'=>true, 'pass_score'=>70],
            ['slug'=>'malware-101', 'title'=>'Malware 101',
             'description'=>'Ransomware, trojans & safe installs.',
             'note'=>'Have your device security checklist nearby to compare against recommended controls.',
             'is_active'=>true, 'pass_score'=>70],
            ['slug'=>'password-hygiene', 'title'=>'Password Hygiene',
             'description'=>'Strong, unique passwords & MFA.',
             'note'=>'Bring a couple of accounts you plan to strengthen so you can build unique passphrases on the spot.',
             'is_active'=>true, 'pass_score'=>70],
            ['slug'=>'safe-browsing', 'title'=>'Safe Browsing',
             'description'=>'Public Wi-Fi, HTTPS, trackers.',
             'note'=>'Think about the networks you use most often—public Wi-Fi examples will help the guidance stick.',
             'is_active'=>true, 'pass_score'=>70],
        ];

        foreach ($data as $m) {
            Module::firstOrCreate(['slug'=>$m['slug']], $m);
        }
    }
}
