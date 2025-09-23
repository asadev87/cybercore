<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Question;

class DemoQuizSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Create a demo module
        $module = Module::firstOrCreate(
            ['slug' => 'phishing-basics'],
            [
                'title'       => 'Phishing Basics',
                'description' => 'Learn to spot common phishing tactics in emails and websites.',
                'is_active'   => true,
                'pass_score'  => 70,
            ]
        );

        // 2) Seed some questions (mix of types & difficulties)
        $questions = [
            [
                'type' => 'mcq',
                'stem' => 'Which is a common sign of a phishing email?',
                'options' => ['Generic greeting', 'Proper corporate domain', 'No links', 'Encrypted attachment from IT'],
                'answer'  => ['Generic greeting'],
                'explanation' => 'Phish often use generic greetings like “Dear user”.',
                'difficulty'  => 1,
            ],
            [
                'type' => 'truefalse',
                'stem' => 'Shortened URLs (bit.ly) can hide the real destination and should be treated with caution.',
                'options' => null,
                'answer'  => ['true'],
                'explanation' => 'Shorteners can mask malicious destinations; preview or avoid.',
                'difficulty'  => 1,
            ],
            [
                'type' => 'mcq',
                'stem' => 'Best immediate action when you suspect a phishing email?',
                'options' => ['Click to see where it goes', 'Reply asking to verify', 'Report using the company phish button', 'Forward to colleagues'],
                'answer'  => ['Report using the company phish button'],
                'explanation' => 'Reporting helps security block similar messages.',
                'difficulty'  => 2,
            ],
            [
                'type' => 'fib',
                'stem' => 'Type the browser feature that blocks known malicious sites: _______ browsing.',
                'options' => null,
                'answer'  => ['safe', 'safebrowsing', 'safe browsing'],
                'explanation' => '“Safe Browsing” lists are used by modern browsers.',
                'difficulty'  => 2,
            ],
            [
                'type' => 'truefalse',
                'stem' => 'A domain like acme-support.secure-mail.example.co is the same as example.co.',
                'options' => null,
                'answer'  => ['false'],
                'explanation' => 'Real domain is the right-most registered domain (example.co); the rest are subdomains.',
                'difficulty'  => 2,
            ],
            [
                'type' => 'mcq',
                'stem' => 'Which padlock statement is most accurate?',
                'options' => ['Padlock means safe site guaranteed', 'Padlock only means the connection is encrypted', 'No padlock means site is malware', 'Padlock verifies the site owner is trustworthy'],
                'answer'  => ['Padlock only means the connection is encrypted'],
                'explanation' => 'TLS = encryption in transit; it does not vouch for content.',
                'difficulty'  => 3,
            ],
            [
                'type' => 'mcq',
                'stem' => 'Which header is often spoofed in phishing?',
                'options' => ['From', 'Date', 'Message-ID', 'All of the above'],
                'answer'  => ['All of the above'],
                'explanation' => 'Multiple headers can be forged to appear legitimate.',
                'difficulty'  => 3,
            ],
            [
                'type' => 'fib',
                'stem' => 'Enter the 2FA method that uses 6-digit rotating codes: _______ app.',
                'options' => null,
                'answer'  => ['authenticator', 'otp', 'totp'],
                'explanation' => 'Authenticator apps generate TOTP codes.',
                'difficulty'  => 3,
            ],
            [
                'type' => 'truefalse',
                'stem' => 'Hovering a link to preview its real destination is a safe first step.',
                'options' => null,
                'answer'  => ['true'],
                'explanation' => 'Preview before clicking, especially on suspicious emails.',
                'difficulty'  => 1,
            ],
            [
                'type' => 'mcq',
                'stem' => 'Which is the best password hygiene practice?',
                'options' => ['Use same strong password everywhere', 'Use a manager & unique passwords', 'Change password daily', 'Share password with manager'],
                'answer'  => ['Use a manager & unique passwords'],
                'explanation' => 'Password managers enable unique, strong passwords across sites.',
                'difficulty'  => 2,
            ],
        ];

       foreach ($questions as $q) {
            Question::updateOrCreate(
                ['module_id' => $module->id, 'stem' => $q['stem']],
              [     'type'        => $q['type'],
                    'options'     => $q['options'],
                    'answer'      => $q['answer'],
                    'explanation' => $q['explanation'] ?? '',
                    'difficulty'  => $q['difficulty'] ?? 2,
                    'is_active'   => true,
                    ]
            );
        }
    }
}
