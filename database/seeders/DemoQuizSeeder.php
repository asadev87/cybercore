<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Question;

class DemoQuizSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPhishingQuestions();
        $this->seedMalwareQuestions();
        $this->seedPasswordQuestions();
        $this->seedBrowsingQuestions();
        $this->seedCybersecurityFundamentalsQuestions();
    }

    private function seedCybersecurityFundamentalsQuestions(): void
    {
        $module = Module::firstOrCreate(['slug' => 'cybersecurity-fundamentals']);
        $questions = [
            [
                'type' => 'mcq',
                'stem' => 'What does the "CIA Triad" stand for in cybersecurity?',
                'options' => ['Central Intelligence Agency', 'Confidentiality, Integrity, Availability', 'Cyber Investigation Authority', 'Computer, Internet, Authentication'],
                'answer'  => ['Confidentiality, Integrity, Availability'],
                'explanation' => 'The CIA Triad is a model designed to guide policies for information security within an organization.',
                'difficulty'  => 1,
            ],
            [
                'type' => 'mcq',
                'stem' => 'Which principle of the CIA Triad ensures that data is not altered by unauthorized parties?',
                'options' => ['Confidentiality', 'Integrity', 'Availability', 'Authentication'],
                'answer'  => ['Integrity'],
                'explanation' => 'Integrity involves maintaining the consistency, accuracy, and trustworthiness of data over its entire life cycle.',
                'difficulty'  => 2,
            ],
            [
                'type' => 'mcq',
                'stem' => 'A ransomware attack that encrypts your files and demands payment primarily violates which part of the CIA Triad?',
                'options' => ['Confidentiality', 'Integrity', 'Availability', 'All of the above'],
                'answer'  => ['Availability'],
                'explanation' => 'By encrypting files, ransomware makes them unavailable to the user.',
                'difficulty'  => 2,
            ],
            [
                'type' => 'mcq',
                'stem' => 'Why is cybersecurity important for everyday internet users?',
                'options' => ['To prevent identity theft and financial loss', 'Only large companies are at risk', 'It’s only needed for government workers', 'Antivirus software makes it unnecessary'],
                'answer'  => ['To prevent identity theft and financial loss'],
                'explanation' => 'Cybersecurity is important for everyone to protect their personal and financial information.',
                'difficulty'  => 1,
            ],
            [
                'type' => 'mcq',
                'stem' => 'Which of the following is a *vulnerability*?',
                'options' => ['A hacker trying to guess your password', 'An unpatched software bug', 'Losing your laptop', 'Receiving a phishing email'],
                'answer'  => ['An unpatched software bug'],
                'explanation' => 'A vulnerability is a weakness which can be exploited by a threat actor.',
                'difficulty'  => 3,
            ],
        ];

        foreach ($questions as $q) {
            Question::updateOrCreate(['module_id' => $module->id, 'stem' => $q['stem']], $q);
        }
    }

    private function seedMalwareQuestions(): void
    {
        $module = Module::firstOrCreate(['slug' => 'malware-101']);
        $questions = [
            [
                'type' => 'mcq',
                'stem' => 'Which type of malware locks your files and demands payment to unlock them?',
                'options' => ['Adware', 'Spyware', 'Ransomware', 'Worm'],
                'answer'  => ['Ransomware'],
                'explanation' => 'Ransomware is a type of malware that threatens to publish the victim\'s data or perpetually block access to it unless a ransom is paid.',
                'difficulty'  => 1,
            ],
            [
                'type' => 'mcq',
                'stem' => 'What is the main difference between a virus and a worm?',
                'options' => ['Viruses are harmless; worms are dangerous', 'Worms can spread without user action; viruses need a host file', 'Viruses only affect phones', 'Worms require payment to remove'],
                'answer'  => ['Worms can spread without user action; viruses need a host file'],
                'explanation' => 'A computer worm is a standalone malware computer program that replicates itself in order to spread to other computers.',
                'difficulty'  => 2,
            ],
            [
                'type' => 'mcq',
                'stem' => 'Which malware secretly records your keystrokes to steal passwords?',
                'options' => ['Trojan', 'Keylogger', 'Botnet', 'Adware'],
                'answer'  => ['Keylogger'],
                'explanation' => 'Keystroke logging, often referred to as keylogging or keyboard capturing, is the action of recording the keys struck on a keyboard.',
                'difficulty'  => 2,
            ],
            [
                'type' => 'mcq',
                'stem' => 'How can you help prevent malware infections?',
                'options' => ['Click all pop-up offers for “free” software', 'Keep your operating system and apps updated', 'Disable your firewall for faster internet', 'Use the same password everywhere'],
                'answer'  => ['Keep your operating system and apps updated'],
                'explanation' => 'Software updates are important because they often include critical patches to security holes.',
                'difficulty'  => 1,
            ],
            [
                'type' => 'mcq',
                'stem' => 'A program that appears useful but secretly installs malicious code is called a:',
                'options' => ['Virus', 'Worm', 'Trojan horse', 'Ransomware'],
                'answer'  => ['Trojan horse'],
                'explanation' => 'In computing, a Trojan horse is any malware which misleads users of its true intent.',
                'difficulty'  => 2,
            ],
        ];

        foreach ($questions as $q) {
            Question::updateOrCreate(['module_id' => $module->id, 'stem' => $q['stem']], $q);
        }
    }

    private function seedPhishingQuestions(): void
    {
        $module = Module::firstOrCreate(['slug' => 'phishing-basics']);
        $questions = [
            [
                'type' => 'mcq',
                'stem' => 'Which of the following is a red flag for a phishing email?',
                'options' => ['It uses your correct name', 'It includes your company logo', 'The sender’s email is “support@amaz0n-deals.com”', 'It arrives during business hours'],
                'answer'  => ['The sender’s email is “support@amaz0n-deals.com”'],
                'explanation' => 'Look for subtle misspellings in domain names, which is a common phishing tactic.',
                'difficulty'  => 1,
            ],
            [
                'type' => 'mcq',
                'stem' => 'What is “smishing”?',
                'options' => ['Phishing via email', 'Phishing via social media', 'Phishing via text message', 'A type of malware'],
                'answer'  => ['Phishing via text message'],
                'explanation' => 'Smishing is a form of phishing that uses mobile phones as the attack platform.',
                'difficulty'  => 1,
            ],
            [
                'type' => 'truefalse',
                'stem' => 'Legitimate companies will often ask for your password via email for “verification.”',
                'options' => null,
                'answer'  => ['false'],
                'explanation' => 'Legitimate companies will never ask for your password or other sensitive information via email.',
                'difficulty'  => 1,
            ],
            [
                'type' => 'mcq',
                'stem' => 'What should you do if you accidentally click a suspicious link?',
                'options' => ['Ignore it—nothing will happen', 'Immediately run a security scan and change related passwords', 'Forward the email to friends as a warning', 'Reply asking them to stop'],
                'answer'  => ['Immediately run a security scan and change related passwords'],
                'explanation' => 'It is important to take immediate action to secure your accounts and devices.',
                'difficulty'  => 2,
            ],
            [
                'type' => 'mcq',
                'stem' => 'Which technique relies on tricking people rather than exploiting software?',
                'options' => ['SQL injection', 'DDoS attack', 'Social engineering', 'Encryption'],
                'answer'  => ['Social engineering'],
                'explanation' => 'Social engineering is the use of deception to manipulate individuals into divulging confidential or personal information that may be used for fraudulent purposes.',
                'difficulty'  => 2,
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
                'stem' => 'What is the **minimum recommended length** for a strong password in 2025?',
                'options' => ['6 characters', '8 characters', '12 characters', '4 characters'],
                'answer'  => ['12 characters'],
                'explanation' => 'Longer passwords are more secure and harder to crack.',
                'difficulty'  => 1,
            ],
            [
                'type' => 'mcq',
                'stem' => 'Which of the following is a strong password?',
                'options' => ['password123', 'iloveyou', 'Blue$ky!Rain7#Cloud', 'your pet’s name'],
                'answer'  => ['Blue$ky!Rain7#Cloud'],
                'explanation' => 'A strong password is long, complex, and unpredictable.',
                'difficulty'  => 2,
            ],
            [
                'type' => 'mcq',
                'stem' => 'Why should you use different passwords for different accounts?',
                'options' => ['It’s easier to remember', 'If one account is breached, others stay safe', 'Websites require it by law', 'It speeds up login time'],
                'answer'  => ['If one account is breached, others stay safe'],
                'explanation' => 'Using unique passwords for each account limits the damage if one account is compromised.',
                'difficulty'  => 2,
            ],
            [
                'type' => 'mcq',
                'stem' => 'What is the best way to manage multiple strong passwords?',
                'options' => ['Write them on a sticky note', 'Use a reputable password manager', 'Save them in a Word document', 'Reuse your favorite password with small changes'],
                'answer'  => ['Use a reputable password manager'],
                'explanation' => 'A password manager is a software application that is used to store and manage passwords.',
                'difficulty'  => 1,
            ],
            [
                'type' => 'truefalse',
                'stem' => 'Multi-factor authentication (MFA) adds an extra layer of security even if your password is stolen.',
                'options' => null,
                'answer'  => ['true'],
                'explanation' => 'MFA is a security enhancement that requires for two or more pieces of evidence to an authentication mechanism.',
                'difficulty'  => 2,
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
