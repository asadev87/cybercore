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
            [
                'type' => 'mcq',
                'stem' => 'What is the safest way to confirm if an email from IT asking you to reset your password is legitimate?',
                'options' => ['Reply and ask for additional proof', 'Use the link provided in the email', 'Call the helpdesk using a known internal number', 'Forward the email to your team'],
                'answer'  => ['Call the helpdesk using a known internal number'],
                'explanation' => 'Verify requests with trusted contact information, not the details in the suspicious message.',
                'difficulty'  => 2,
            ],
            [
                'type' => 'truefalse',
                'stem' => 'Legitimate organizations will never send password reset links by email.',
                'options' => null,
                'answer'  => ['false'],
                'explanation' => 'Some legitimate services send reset links; the key is to navigate to the site directly instead of trusting the email link.',
                'difficulty'  => 2,
            ],
            [
                'type' => 'fib',
                'stem' => 'Always inspect the sender\'s ________ to confirm it matches the organization\'s real domain.',
                'options' => null,
                'answer'  => ['domain', 'email domain'],
                'explanation' => 'Typosquatted sender domains are a classic phishing tell.',
                'difficulty'  => 2,
            ],
        ];

        foreach ($questions as $q) {
            $choices = $q['type'] === 'mcq'
                ? array_values($q['options'] ?? $q['choices'] ?? [])
                : null;

            Question::updateOrCreate(
                ['module_id' => $module->id, 'stem' => $q['stem']],
                [
                    'type'        => $q['type'],
                    'choices'     => $choices,
                    'options'     => $choices,
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
        $this->seedPhishingAwarenessQuestions();
        $this->seedStrongPasswordCourseQuestions();
        $this->seedSafeBrowsingHabitsQuestions();
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
            [
                'type' => 'mcq',
                'stem' => 'What practice helps prevent malware infections from USB drives?',
                'options' => ['Disable antivirus when using USBs', 'Auto-run any files found', 'Scan removable media before opening files', 'Format every USB before use'],
                'answer' => ['Scan removable media before opening files'],
                'explanation' => 'Scanning removable media reduces the risk of executing malicious files.',
                'difficulty' => 2,
            ],
            [
                'type' => 'truefalse',
                'stem' => 'Spyware silently collects information about a user without their knowledge.',
                'options' => null,
                'answer' => ['true'],
                'explanation' => 'Spyware is designed to operate covertly and exfiltrate data.',
                'difficulty' => 2,
            ],
            [
                'type' => 'fib',
                'stem' => 'Malware that can copy itself and spread to other devices without user action is called a ________.',
                'options' => null,
                'answer' => ['worm'],
                'explanation' => 'Worms self-propagate across networks, unlike viruses that need a host file.',
                'difficulty' => 2,
            ],
        ];

        foreach ($questions as $q) {
            $choices = $q['type'] === 'mcq'
                ? array_values($q['options'] ?? $q['choices'] ?? [])
                : null;

            $payload = array_merge($q, [
                'choices' => $choices,
                'options' => $choices,
            ]);

            Question::updateOrCreate(
                ['module_id' => $module->id, 'stem' => $q['stem']],
                $payload
            );
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
            [
                'type' => 'mcq',
                'stem' => 'After a data breach notification, what should you do first for the affected account?',
                'options' => ['Ignore it if you do not notice suspicious activity', 'Reuse the same password with a new symbol', 'Change the password to a new, unique one', 'Delete the account immediately'],
                'answer' => ['Change the password to a new, unique one'],
                'explanation' => 'Updating to a new unique password reduces the risk of credential stuffing.',
                'difficulty' => 2,
            ],
            [
                'type' => 'truefalse',
                'stem' => 'Long passphrases made from random words can be stronger than short complex passwords.',
                'options' => null,
                'answer' => ['true'],
                'explanation' => 'Length combined with unpredictability provides strong resistance to brute-force attacks.',
                'difficulty' => 2,
            ],
            [
                'type' => 'fib',
                'stem' => 'MFA combines something you know with something you ________ or are.',
                'options' => null,
                'answer' => ['have'],
                'explanation' => 'Possession factors like tokens or devices complement knowledge-based passwords.',
                'difficulty' => 2,
            ],
        ];

        foreach ($questions as $q) {
            $choices = $q['type'] === 'mcq'
                ? array_values($q['options'] ?? $q['choices'] ?? [])
                : null;

            $payload = array_merge($q, [
                'choices' => $choices,
                'options' => $choices,
            ]);

            Question::updateOrCreate(
                ['module_id' => $module->id, 'stem' => $q['stem']],
                $payload
            );
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
            [
                'type' => 'mcq',
                'stem' => 'What is the best way to verify that a URL in an email is safe before clicking?',
                'options' => ['Open it in a private browsing window', 'Hover to preview the full address and compare against the official site', 'Copy and paste it into a search engine', 'Forward it to a colleague to test'],
                'answer' => ['Hover to preview the full address and compare against the official site'],
                'explanation' => 'Previewing the full URL helps spot typosquatting or malicious redirects.',
                'difficulty' => 2,
            ],
            [
                'type' => 'truefalse',
                'stem' => 'Browser extensions should only be installed from reputable sources and after reviewing requested permissions.',
                'options' => null,
                'answer' => ['true'],
                'explanation' => 'Extensions can access sensitive data, so source and permissions matter.',
                'difficulty' => 2,
            ],
            [
                'type' => 'fib',
                'stem' => 'Always look for unexpected ________ or misspellings in a URL before entering credentials.',
                'options' => null,
                'answer' => ['characters'],
                'explanation' => 'Attackers frequently use extra characters or homographs to mimic legitimate domains.',
                'difficulty' => 2,
            ],
        ];

        foreach ($questions as $q) {
            $choices = $q['type'] === 'mcq'
                ? array_values($q['options'] ?? $q['choices'] ?? [])
                : null;

            $payload = array_merge($q, [
                'choices' => $choices,
                'options' => $choices,
            ]);

            Question::updateOrCreate(
                ['module_id' => $module->id, 'stem' => $q['stem']],
                $payload
            );
        }
    }

    private function seedPhishingAwarenessQuestions(): void
    {
        $module = Module::firstOrCreate(['slug' => 'phishing-awareness']);
        $questions = [
            [
                'type' => 'mcq',
                'stem' => 'Which subject line is the strongest indicator that an email might be phishing?',
                'options' => ['Quarterly Newsletter', 'Payroll Update - Action Required in 30 Minutes', 'Project Kickoff Notes', 'Team Lunch Invitation'],
                'answer' => ['Payroll Update - Action Required in 30 Minutes'],
                'explanation' => 'Urgent payroll requests are a common lure; verify through trusted channels before acting.',
                'difficulty' => 2,
            ],
            [
                'type' => 'truefalse',
                'stem' => 'Phishing messages frequently ask you to provide credentials by replying directly to the email.',
                'options' => null,
                'answer' => ['true'],
                'explanation' => 'Legitimate services direct you to secure portals; attackers often shortcut that process.',
                'difficulty' => 1,
            ],
            [
                'type' => 'mcq',
                'stem' => 'You receive an unexpected gift card request from an executive. What is the safest first response?',
                'options' => ['Purchase the cards immediately', 'Reply asking if it is legitimate', 'Call the executive using a known number to confirm', 'Forward the email to the whole team'],
                'answer' => ['Call the executive using a known number to confirm'],
                'explanation' => 'Always verify high-risk requests via trusted contact information, not the suspicious message.',
                'difficulty' => 2,
            ],
            [
                'type' => 'fib',
                'stem' => 'Inspecting the email header\'s ________ value can reveal the true sending address.',
                'options' => null,
                'answer' => ['return-path', 'return path'],
                'explanation' => 'The return-path exposes where replies would really go and often differs in forged emails.',
                'difficulty' => 3,
            ],
            [
                'type' => 'mcq',
                'stem' => 'How can you safely preview a suspicious link embedded in an email?',
                'options' => ['Click it quickly and close the tab', 'Hover over the link to read the full URL', 'Copy it into a document to open later', 'Forward it to a coworker to test'],
                'answer' => ['Hover over the link to read the full URL'],
                'explanation' => 'Hovering lets you inspect the destination without visiting it.',
                'difficulty' => 1,
            ],
        ];

        foreach ($questions as $q) {
            $choices = $q['type'] === 'mcq'
                ? array_values($q['options'] ?? $q['choices'] ?? [])
                : null;

            $payload = array_merge($q, [
                'choices' => $choices,
                'options' => $choices,
            ]);

            Question::updateOrCreate(
                ['module_id' => $module->id, 'stem' => $q['stem']],
                $payload
            );
        }
    }

    private function seedStrongPasswordCourseQuestions(): void
    {
        $module = Module::firstOrCreate(['slug' => 'creating-strong-passwords']);
        $questions = [
            [
                'type' => 'mcq',
                'stem' => 'Which password offers the best protection against brute-force attacks?',
                'options' => ['P@ssw0rd!', 'CorrectHorseBatteryStaple97', 'Summer2024!', 'FluffyCat123'],
                'answer' => ['CorrectHorseBatteryStaple97'],
                'explanation' => 'Length plus randomness creates far more entropy than minor symbol swaps.',
                'difficulty' => 2,
            ],
            [
                'type' => 'truefalse',
                'stem' => 'Storing unique passwords in a reputable password manager is safer than reusing a few you can memorize.',
                'options' => null,
                'answer' => ['true'],
                'explanation' => 'Password managers enable unique, complex credentials without relying on memory.',
                'difficulty' => 1,
            ],
            [
                'type' => 'fib',
                'stem' => 'A long, memorable password made from random words is often called a ________.',
                'options' => null,
                'answer' => ['passphrase'],
                'explanation' => 'Passphrases balance memorability with high entropy when words are unpredictable.',
                'difficulty' => 2,
            ],
            [
                'type' => 'mcq',
                'stem' => 'Which factor most increases the entropy of a password?',
                'options' => ['Length', 'Using only numbers', 'Replacing letters with symbols', 'Adding your birth year'],
                'answer' => ['Length'],
                'explanation' => 'Every additional character multiplies the possible combinations exponentially.',
                'difficulty' => 2,
            ],
            [
                'type' => 'fib',
                'stem' => 'Securely storing offline ________ codes ensures you can access accounts protected by MFA.',
                'options' => null,
                'answer' => ['backup'],
                'explanation' => 'Backup codes are critical if you lose access to your primary second factor.',
                'difficulty' => 3,
            ],
        ];

        foreach ($questions as $q) {
            $choices = $q['type'] === 'mcq'
                ? array_values($q['options'] ?? $q['choices'] ?? [])
                : null;

            $payload = array_merge($q, [
                'choices' => $choices,
                'options' => $choices,
            ]);

            Question::updateOrCreate(
                ['module_id' => $module->id, 'stem' => $q['stem']],
                $payload
            );
        }
    }

    private function seedSafeBrowsingHabitsQuestions(): void
    {
        $module = Module::firstOrCreate(['slug' => 'safe-browsing-habits']);
        $questions = [
            [
                'type' => 'mcq',
                'stem' => 'While on public Wi-Fi, what should you do before signing into corporate resources?',
                'options' => ['Disable your firewall', 'Connect through the company VPN', 'Turn off multi-factor authentication', 'Use any available browser extension'],
                'answer' => ['Connect through the company VPN'],
                'explanation' => 'A VPN encrypts your traffic end-to-end, reducing risks on untrusted networks.',
                'difficulty' => 2,
            ],
            [
                'type' => 'truefalse',
                'stem' => 'It is safe to ignore a browser warning about an invalid HTTPS certificate if you recognize the site name.',
                'options' => null,
                'answer' => ['false'],
                'explanation' => 'Certificate warnings signal possible interception or misconfiguration; verify before proceeding.',
                'difficulty' => 2,
            ],
            [
                'type' => 'fib',
                'stem' => 'Only download software from the vendor\'s official ________ to minimize tampering risks.',
                'options' => null,
                'answer' => ['website', 'site'],
                'explanation' => 'Official distribution channels reduce the chance of malicious modifications.',
                'difficulty' => 1,
            ],
            [
                'type' => 'mcq',
                'stem' => 'What should you do if a familiar website suddenly redirects to a login page on a different domain?',
                'options' => ['Enter your credentials quickly', 'Clear your cache and try again later', 'Close the tab and manually navigate to the known address', 'Turn off your antivirus to reduce interference'],
                'answer' => ['Close the tab and manually navigate to the known address'],
                'explanation' => 'Redirections to unfamiliar domains can indicate hijacking; use trusted bookmarks instead.',
                'difficulty' => 2,
            ],
            [
                'type' => 'fib',
                'stem' => 'Review browser ________ before installing extensions to understand what data they can access.',
                'options' => null,
                'answer' => ['permissions'],
                'explanation' => 'Permissions reveal the scope of access an extension will have to your browsing activity.',
                'difficulty' => 2,
            ],
        ];

        foreach ($questions as $q) {
            $choices = $q['type'] === 'mcq'
                ? array_values($q['options'] ?? $q['choices'] ?? [])
                : null;

            $payload = array_merge($q, [
                'choices' => $choices,
                'options' => $choices,
            ]);

            Question::updateOrCreate(
                ['module_id' => $module->id, 'stem' => $q['stem']],
                $payload
            );
        }
    }
}
