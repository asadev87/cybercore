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
        $this->seedMalwareQuestions();
        $this->seedPasswordQuestions();
        $this->seedBrowsingQuestions();
    }

    private function seedMalwareQuestions(): void
    {
        $module = Module::firstOrCreate(['slug' => 'malware-101']);
        $questions = [
            [
                'type' => 'mcq',
                'stem' => 'What is the primary purpose of ransomware?',
                'options' => ['Steal data', 'Encrypt files for a ransom', 'Crash computer', 'Show ads'],
                'answer'  => ['Encrypt files for a ransom'],
                'explanation' => 'Ransomware holds files hostage until a payment is made.',
                'difficulty'  => 1,
            ],
            [
                'type' => 'truefalse',
                'stem' => 'Keeping your software updated is a key defense against malware.',
                'options' => null,
                'answer'  => ['true'],
                'explanation' => 'Updates often patch security holes that malware exploits.',
                'difficulty'  => 1,
            ],
            [
                'type' => 'mcq',
                'stem' => 'What is the most common way malware is spread?',
                'options' => ['Through infected USB drives', 'Through phishing emails and malicious downloads', 'Through outdated operating systems', 'Through slow internet connections'],
                'answer' => ['Through phishing emails and malicious downloads'],
                'explanation' => 'Phishing emails and malicious downloads are the most common vectors for malware.',
                'difficulty' => 2,
            ],
            [
                'type' => 'truefalse',
                'stem' => 'A firewall is sufficient to protect you from all types of malware.',
                'options' => null,
                'answer' => ['false'],
                'explanation' => 'A firewall is an important layer, but not a complete solution.',
                'difficulty' => 2,
            ],
            [
                'type' => 'fib',
                'stem' => 'A type of malware that disguises itself as legitimate software is called a ________.',
                'options' => null,
                'answer' => ['trojan'],
                'explanation' => 'A Trojan horse or Trojan is a type of malware that is often disguised as legitimate software.',
                'difficulty' => 3,
            ],
        ];

        foreach ($questions as $q) {
            Question::updateOrCreate(['module_id' => $module->id, 'stem' => $q['stem']], $q);
        }
    }

    private function seedPasswordQuestions(): void
    {
        $module = Module::firstOrCreate(['slug' => 'password-hygiene']);
        $questions = [
            [
                'type' => 'mcq',
                'stem' => 'What is a key feature of a strong password?',
                'options' => ['Short and easy to remember', 'A mix of letters, numbers, and symbols', 'A common dictionary word', 'Your pet\'s name'],
                'answer'  => ['A mix of letters, numbers, and symbols'],
                'explanation' => 'Complexity makes passwords harder to guess or crack.',
                'difficulty'  => 1,
            ],
            [
                'type' => 'fib',
                'stem' => 'The practice of using a different password for every online service is crucial for security. This can be managed with a password ________.',
                'options' => null,
                'answer'  => ['manager'],
                'explanation' => 'Password managers help create and store unique, complex passwords.',
                'difficulty'  => 2,
            ],
            [
                'type' => 'mcq',
                'stem' => 'What does "MFA" stand for in the context of cybersecurity?',
                'options' => ['Malicious File Attack', 'My First Account', 'Multi-Factor Authentication', 'Master File Access'],
                'answer' => ['Multi-Factor Authentication'],
                'explanation' => 'MFA adds a layer of protection to the sign-in process.',
                'difficulty' => 1,
            ],
            [
                'type' => 'truefalse',
                'stem' => 'You should change your passwords every 30 days, even if there\'s no sign of a breach.',
                'options' => null,
                'answer' => ['false'],
                'explanation' => 'Modern guidance suggests using a very strong, unique password is more important than changing it frequently without cause.',
                'difficulty' => 2,
            ],
            [
                'type' => 'fib',
                'stem' => 'A ________ ________ is a secure application for storing and managing all your unique passwords.',
                'options' => null,
                'answer' => ['password manager'],
                'explanation' => 'Password managers are essential tools for password hygiene.',
                'difficulty' => 2,
            ],
        ];

        foreach ($questions as $q) {
            Question::updateOrCreate(['module_id' => $module->id, 'stem' => $q['stem']], $q);
        }
    }

    private function seedBrowsingQuestions(): void
    {
        $module = Module::firstOrCreate(['slug' => 'safe-browsing']);
        $questions = [
            [
                'type' => 'truefalse',
                'stem' => 'Using public Wi-Fi for sensitive transactions (like banking) is safe as long as the website has HTTPS.',
                'options' => null,
                'answer'  => ['true'],
                'explanation' => 'HTTPS encrypts the connection, making it secure even on public networks.',
                'difficulty'  => 2,
            ],
            [
                'type' => 'mcq',
                'stem' => 'What does the "S" in HTTPS stand for?',
                'options' => ['Secure', 'Standard', 'Safe', 'Special'],
                'answer'  => ['Secure'],
                'explanation' => 'HTTPS stands for Hypertext Transfer Protocol Secure.',
                'difficulty'  => 1,
            ],
            [
                'type' => 'mcq',
                'stem' => 'When a website\'s URL starts with "HTTPS", what does the \'S\' signify?',
                'options' => ['The site is "Standard"', 'The site is "Simple"', 'The connection is "Secure"', 'The site is for "Shopping"'],
                'answer' => ['The connection is "Secure"'],
                'explanation' => 'The \'S\' in HTTPS stands for Secure.',
                'difficulty' => 1,
            ],
            [
                'type' => 'truefalse',
                'stem' => 'If a website has a padlock icon next to its URL, it means the website is legitimate and can be trusted completely.',
                'options' => null,
                'answer' => ['false'],
                'explanation' => 'It only means the connection is encrypted. Phishing sites can also have valid HTTPS certificates.',
                'difficulty' => 3,
            ],
            [
                'type' => 'fib',
                'stem' => 'Clearing your browser\'s ________ can help protect your privacy by removing stored data from websites you\'ve visited.',
                'options' => null,
                'answer' => ['cache', 'cookies'],
                'explanation' => 'Clearing your cache and cookies can help protect your privacy.',
                'difficulty' => 2,
            ],
        ];

        foreach ($questions as $q) {
            Question::updateOrCreate(['module_id' => $module->id, 'stem' => $q['stem']], $q);
        }
    }
}
