<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionNotesSeeder extends Seeder
{
    public function run(): void
    {
        $notes = [
            'Which password offers the best protection against brute-force attacks?' => [
                'core_concept' => <<<'TEXT'
High-entropy passwords—typically long passphrases or random manager-generated strings—expand the search space attackers must brute-force, making successful guessing computationally infeasible.
TEXT,
                'context' => <<<'TEXT'
Lena retired her old “Passw0rd!” after a security newsletter explained entropy. She now uses “VelvetGalaxy-Tunnel34”, inspired by a childhood memory, and even when one vendor leaked credentials the attackers still could not guess the new passphrase.
TEXT,
                'examples' => <<<'TEXT'
- NIST guidance promotes passphrases like “orchid-velvet-galaxy777” because length crushes brute-force tools.
- A 20-character manager-generated password dramatically outlasts shorter “complex” strings such as “P@ssw0rd!” when subjected to password-cracking rigs.
TEXT,
            ],
            'Storing unique passwords in a reputable password manager is safer than reusing a few you can memorize.' => [
                'core_concept' => <<<'TEXT'
Unique passwords isolate compromise to a single account. Password managers encrypt and store those unique credentials, eliminating dangerous reuse.
TEXT,
                'context' => <<<'TEXT'
When a hobby shopping site exposed customer logins, Ben simply revoked that account. His password manager had already generated distinct passwords everywhere else—unlike his coworker who reused “Sunshine1!” and lost multiple accounts.
TEXT,
                'examples' => <<<'TEXT'
- Managers such as 1Password, Bitwarden, or Dashlane auto-fill logins and keep them in encrypted vaults.
- Security teams rely on shared vaults with granular permissions to ensure account isolation and streamline access reviews.
TEXT,
            ],
            'A long, memorable password made from random words is often called a ________.' => [
                'core_concept' => <<<'TEXT'
A passphrase combines several unrelated words to produce high entropy while remaining easy to remember and reproduce.
TEXT,
                'context' => <<<'TEXT'
After attending a security briefing, Sara replaced “Qwerty123” with “MagentaCedar-PuzzleRocket”. She could recall it instantly, yet password-cracking tools faced astronomically more combinations.
TEXT,
                'examples' => <<<'TEXT'
- The classic “correct horse battery staple” XKCD example shows four random words beating shorter “complex” strings.
- Government agencies now coach employees to craft memorable, multi-word passphrases rather than short symbol-heavy passwords.
TEXT,
            ],
            'Which factor most increases the entropy of a password?' => [
                'core_concept' => <<<'TEXT'
Length contributes the greatest entropy gain because each extra character multiplies possible combinations exponentially.
TEXT,
                'context' => <<<'TEXT'
During training workshops, staff compared an 8-character “complex” password to a 20-character passphrase. Seeing cracking timelines, they realized length alone made the longer option vastly stronger.
TEXT,
                'examples' => <<<'TEXT'
- A 16-character random string resists brute-force for orders of magnitude longer than an 8-character “complex” password.
- Many organizations now mandate 14–16 character minimums after observing how length overwhelms automated attacks.
TEXT,
            ],
            'Securely storing offline ________ codes ensures you can access accounts protected by MFA.' => [
                'core_concept' => <<<'TEXT'
Offline backup codes act as one-time bypasses if MFA devices are lost or unavailable, preventing permanent lockout.
TEXT,
                'context' => <<<'TEXT'
When Maya dropped her phone in a lake, she used printed backup codes to access the analytics portal needed to finish customer reports on time.
TEXT,
                'examples' => <<<'TEXT'
- Google and Microsoft both generate backup codes; storing them in a safe or password manager keeps account recovery possible.
- Incident response teams keep sealed envelopes with backup codes so executives can regain access even if a token fails.
TEXT,
            ],
            'What is the primary purpose of ransomware?' => [
                'core_concept' => <<<'TEXT'
Ransomware encrypts victim data and demands payment for decryption, turning access to information into leverage.
TEXT,
                'context' => <<<'TEXT'
A regional hospital awoke to “Your files are locked. Pay 2 BTC.” Because they had resilient backups, they restored systems and refused to pay—highlighting the attackers’ business model.
TEXT,
                'examples' => <<<'TEXT'
- WannaCry crippled global networks until organizations patched and restored from backups.
- Colonial Pipeline paid a ransom to resume fuel transport, demonstrating the economic pressure ransomware applies.
TEXT,
            ],
            'Keeping your software updated is a key defense against malware.' => [
                'core_concept' => <<<'TEXT'
Patch management removes known vulnerabilities so malware cannot exploit them to establish persistence or escalate privileges.
TEXT,
                'context' => <<<'TEXT'
Alex ignored browser updates until a malicious site hijacked his session. The next patch’s release notes showed it fixed that exact flaw, underscoring why updates matter.
TEXT,
                'examples' => <<<'TEXT'
- Equifax’s breach traced back to a missed Apache Struts patch that had been available for months.
- Managed service providers rush out Patch Tuesday fixes to close publicly disclosed holes before malware authors weaponize them.
TEXT,
            ],
            'What is the most common way malware is spread?' => [
                'core_concept' => <<<'TEXT'
Phishing emails and malicious downloads remain primary infection vectors because they persuade victims to execute the payload themselves.
TEXT,
                'context' => <<<'TEXT'
An accounting clerk opened an “invoice” attachment that unleashed a trojan; analytics revealed dozens of identical phishing emails in users’ inboxes.
TEXT,
                'examples' => <<<'TEXT'
- Emotet campaigns distribute malicious Office documents with macros that fetch secondary payloads.
- Drive-by downloads trick users into installing fake updates, silently slipping spyware into systems.
TEXT,
            ],
            'A firewall is sufficient to protect you from all types of malware.' => [
                'core_concept' => <<<'TEXT'
Firewalls control network flows but cannot inspect every payload or stop endpoint-level exploits; layered defenses are required.
TEXT,
                'context' => <<<'TEXT'
Finance boasted about their next-gen firewall until a contractor’s infected USB bypassed the perimeter entirely and detonated inside the network.
TEXT,
                'examples' => <<<'TEXT'
- Endpoint detection and response tools catch suspicious behavior that firewalls miss.
- Malware delivered via encrypted HTTPS can traverse firewalls unless additional inspection exists.
TEXT,
            ],
            'A type of malware that disguises itself as legitimate software is called a ________.' => [
                'core_concept' => <<<'TEXT'
A trojan masquerades as benign software to trick users into installing it, hiding malicious code within seemingly useful programs.
TEXT,
                'context' => <<<'TEXT'
An “InvoiceTracker.exe” from an unknown sender installed remote access malware even though the icon and name looked professional.
TEXT,
                'examples' => <<<'TEXT'
- Pirated software often bundles trojans that steal credentials.
- Mobile APKs downloaded outside official stores frequently include trojanized adware or spyware.
TEXT,
            ],
            'What practice helps prevent malware infections from USB drives?' => [
                'core_concept' => <<<'TEXT'
Scanning removable media before opening files detects autorun malware or hidden payloads commonly planted on USB storage.
TEXT,
                'context' => <<<'TEXT'
Security tested a “found” USB drive in a sandbox; the autorun script attempted to launch ransomware, proving why scanning is essential.
TEXT,
                'examples' => <<<'TEXT'
- Industrial control environments quarantine contractor USBs for scanning before connecting to production systems.
- macOS Gatekeeper quarantines files from external drives until reviewed and approved.
TEXT,
            ],
            'Spyware silently collects information about a user without their knowledge.' => [
                'core_concept' => <<<'TEXT'
Spyware covertly records keystrokes, screenshots, or files and exports them to attackers, often without visible symptoms or notifications.
TEXT,
                'context' => <<<'TEXT'
IT flagged a laptop sending constant outbound traffic; forensic analysis revealed hidden spyware siphoning HR spreadsheets to an attacker.
TEXT,
                'examples' => <<<'TEXT'
- Commercial spyware like FinFisher has targeted journalists and dissidents.
- Browser extensions disguised as coupon apps have quietly captured browsing histories and credentials.
TEXT,
            ],
            'Malware that can copy itself and spread to other devices without user action is called a ________.' => [
                'core_concept' => <<<'TEXT'
A worm self-replicates across networks by automatically exploiting vulnerabilities, spreading without user interaction.
TEXT,
                'context' => <<<'TEXT'
During a scan, analysts watched a worm iterate through IP addresses, exploiting an outdated service and copying itself from machine to machine.
TEXT,
                'examples' => <<<'TEXT'
- SQL Slammer crippled ATMs and airline systems in minutes through self-propagation.
- WannaCry wormed through SMBv1 shares, encrypting endpoints worldwide.
TEXT,
            ],
            'What is a key feature of a strong password?' => [
                'core_concept' => <<<'TEXT'
Strong passwords are long, unpredictable, and not based on personal data, resisting dictionary, brute-force, and social engineering attacks.
TEXT,
                'context' => <<<'TEXT'
After repeated compromises, Paul switched to 18-character manager-generated passwords; subsequent breach simulations could no longer crack his accounts quickly.
TEXT,
                'examples' => <<<'TEXT'
- Security policies increasingly emphasize length and randomness over short symbol-heavy patterns.
- Pen testers regularly crack “Summer2024!” but struggle with 20-character random strings.
TEXT,
            ],
            'The practice of using a different password for every online service is crucial for security. This can be managed with a password ________.' => [
                'core_concept' => <<<'TEXT'
Password managers securely store unique credentials, eliminating dangerous reuse while relieving people from memorizing dozens of strings.
TEXT,
                'context' => <<<'TEXT'
When a shopping site leaked email addresses, Lucy reset only that account because her password manager had generated distinct logins everywhere else.
TEXT,
                'examples' => <<<'TEXT'
- Enterprises deploy password managers so teams can share secrets with audit trails instead of spreadsheets.
- Individuals use Bitwarden or 1Password to sync unique credentials across devices safely.
TEXT,
            ],
            'What does "MFA" stand for in the context of cybersecurity?' => [
                'core_concept' => <<<'TEXT'
Multi-Factor Authentication pairs different categories—knowledge, possession, inherence—to verify identity, stopping attackers who only possess one factor.
TEXT,
                'context' => <<<'TEXT'
After an online service leak, users with MFA enabled were safe because attackers lacked their physical tokens or biometric checks.
TEXT,
                'examples' => <<<'TEXT'
- Banks enforce MFA via passwords plus device-based approvals or OTPs.
- Corporate SSO portals commonly pair passwords with authenticator prompts or hardware keys.
TEXT,
            ],
            'You should change your passwords every 30 days, even if there\'s no sign of a breach.' => [
                'core_concept' => <<<'TEXT'
Current guidance favors strong unique passwords and monitoring rather than arbitrary frequent changes, which can lead to predictable patterns if no compromise exists.
TEXT,
                'context' => <<<'TEXT'
Employees forced to rotate monthly resorted to predictable increments (“Password1!”, “Password2!”). After adopting unique passphrases plus MFA, compromised accounts plummeted.
TEXT,
                'examples' => <<<'TEXT'
- Microsoft and NIST discourage routine rotations unless evidence of compromise appears.
- Organizations now combine password monitoring with breach alerts to trigger targeted resets.
TEXT,
            ],
            'A ________ ________ is a secure application for storing and managing all your unique passwords.' => [
                'core_concept' => <<<'TEXT'
Password managers encrypt and organize credentials, enabling unique complex passwords without human memory limits.
TEXT,
                'context' => <<<'TEXT'
After rolling out Keeper, the helpdesk saw password-reset tickets drop because employees no longer reused or forgot credentials.
TEXT,
                'examples' => <<<'TEXT'
- Families share streaming logins securely through manager vaults instead of email.
- SOC 2 audits review whether teams store secrets in approved password managers.
TEXT,
            ],
            'After a data breach notification, what should you do first for the affected account?' => [
                'core_concept' => <<<'TEXT'
Immediately change the password to a new unique value and review access logs so attackers cannot leverage exposed credentials.
TEXT,
                'context' => <<<'TEXT'
Lucy received a breach alert and promptly reset her password via the official site, revoking tokens before any suspicious activity occurred.
TEXT,
                'examples' => <<<'TEXT'
- Have I Been Pwned alerts prompt users to rotate credentials and invalidate sessions.
- Incident response playbooks instruct staff to reset passwords, check login history, and enable MFA post-breach.
TEXT,
            ],
            'Long passphrases made from random words can be stronger than short complex passwords.' => [
                'core_concept' => <<<'TEXT'
Passphrases achieve high entropy through length and word variety, making brute-force efforts far less likely to succeed than short symbol-based passwords.
TEXT,
                'context' => <<<'TEXT'
In a cracking demo, “TimeMachine-RiverGuitar78” withstood hours of attacks while “Tr0ub4dor!” fell almost instantly, convincing skeptics to adopt passphrases.
TEXT,
                'examples' => <<<'TEXT'
- AWS recommends passphrase-style passwords for console access because length directly increases entropy.
- Security programs encourage employees to build phrases from vivid imagery to remember them easily.
TEXT,
            ],
            'MFA combines something you know with something you ________ or are.' => [
                'core_concept' => <<<'TEXT'
Strong MFA mixes knowledge factors with possession (“have”) or inherence (“are”) so a stolen password alone cannot unlock the account.
TEXT,
                'context' => <<<'TEXT'
After Malik’s password leaked, attackers still failed because they lacked his hardware security key—something he physically had.
TEXT,
                'examples' => <<<'TEXT'
- Hardware keys like YubiKey provide the “have” factor for services such as GitHub or Google Workspace.
- Smartphone biometrics serve as the “are” factor, combined with possession of the device itself.
TEXT,
            ],
            'Which subject line is the strongest indicator that an email might be phishing?' => [
                'core_concept' => <<<'TEXT'
Phishing subject lines often create urgency or fear to override skepticism—phrases like “Payroll Update - Action Required” exploit emotion to prompt rash actions.
TEXT,
                'context' => <<<'TEXT'
HR spotted “Payroll suspension notice—respond now” and knew real payroll tickets never use such urgent language, so they flagged the email as suspicious.
TEXT,
                'examples' => <<<'TEXT'
- Threat actors mimic benefits notifications with hard deadlines to harvest credentials.
- Awareness training teaches employees to question urgent financial or HR requests arriving unexpectedly.
TEXT,
            ],
            'Phishing messages frequently ask you to provide credentials by replying directly to the email.' => [
                'core_concept' => <<<'TEXT'
Attackers avoid secure portals; they want victims to send credentials or personal data straight through email replies where there is no security control.
TEXT,
                'context' => <<<'TEXT'
An email claiming to be IT support asked staff to “reply with your password to verify directories.” Security immediately recognized the classic scheme.
TEXT,
                'examples' => <<<'TEXT'
- Business Email Compromise scams often request W-2 data or logins via direct reply.
- Fake bank messages ask for account details by email instead of directing to the official secure site.
TEXT,
            ],
            'You receive an unexpected gift card request from an executive. What is the safest first response?' => [
                'core_concept' => <<<'TEXT'
Always verify unusual executive requests using a trusted channel—call, chat, or in-person confirmation—before acting; executive spoofing is a common phishing tactic.
TEXT,
                'context' => <<<'TEXT'
Instead of replying to an “urgent CEO gift card” email, staff phoned the executive assistant, uncovering that the request was a scam.
TEXT,
                'examples' => <<<'TEXT'
- Phishers impersonate executives to solicit gift cards or wire transfers; policies require verification via known numbers.
- Security playbooks instruct employees to escalate suspicious requests to incident response teams before acting.
TEXT,
            ],
            'Inspecting the email header\'s ________ value can reveal the true sending address.' => [
                'core_concept' => <<<'TEXT'
The Return-Path header exposes where replies go; if it differs from the displayed address, the message may be spoofed or redirected.
TEXT,
                'context' => <<<'TEXT'
An alert from “support@company.com” actually had a Return-Path pointing to “support@company-security.ru”, revealing the forgery beneath the friendly display name.
TEXT,
                'examples' => <<<'TEXT'
- SOC analysts review Return-Path and Received headers to confirm an email’s origin.
- DMARC checks ensure the Return-Path aligns with authorized sending domains.
TEXT,
            ],
            'How can you safely preview a suspicious link embedded in an email?' => [
                'core_concept' => <<<'TEXT'
Hovering (or long-pressing on mobile) reveals the destination without visiting it, allowing inspection for suspicious or misspelled domains.
TEXT,
                'context' => <<<'TEXT'
Before clicking “Reset Password,” a user hovered the link and saw “login.company-security.ru” instead of the official domain, so they reported it.
TEXT,
                'examples' => <<<'TEXT'
- Email clients show destination URLs in the status bar or tooltip on hover.
- Mobile mail apps allow long-press URL previews so users can inspect links safely.
TEXT,
            ],
        ];

        foreach ($notes as $stem => $content) {
            Question::where('stem', $stem)->update(['notes' => $content]);
        }
    }
}
