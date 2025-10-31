<?php

return [
    'topics' => [
        'phishing'   => 'Phishing & Social Engineering',
        'malware'    => 'Malware Defense',
        'passwords'  => 'Passwords & MFA',
        'browsing'   => 'Secure Browsing & Privacy',
        'orientation'=> 'Platform Orientation',
    ],

    'roles' => [
        'everyone'    => 'All Employees',
        'leadership'  => 'Leaders & Managers',
        'it'          => 'IT & Security',
        'finance'     => 'Finance & Operations',
        'support'     => 'Customer & Support Teams',
        'field'       => 'Field & Remote Teams',
    ],

    'module_tags' => [
        'cybercore-phish-survival-101' => [
            'topics' => ['phishing'],
            'roles'  => ['everyone', 'support'],
        ],
        'cybercore-password-forge' => [
            'topics' => ['passwords'],
            'roles'  => ['everyone', 'it'],
        ],
        'cybercore-safe-browsing-badge' => [
            'topics' => ['browsing'],
            'roles'  => ['everyone', 'field'],
        ],
        'phishing-basics' => [
            'topics' => ['phishing'],
            'roles'  => ['everyone', 'support'],
        ],
        'demo-module' => [
            'topics' => ['orientation'],
            'roles'  => ['everyone'],
        ],
        'malware-101' => [
            'topics' => ['malware'],
            'roles'  => ['it', 'everyone'],
        ],
        'password-hygiene' => [
            'topics' => ['passwords'],
            'roles'  => ['everyone'],
        ],
        'safe-browsing' => [
            'topics' => ['browsing'],
            'roles'  => ['everyone', 'field'],
        ],
        'cybercore-phish-survival-101-legacy' => [
            'topics' => ['phishing'],
            'roles'  => ['everyone'],
        ],
        'cybercore-password-forge-legacy' => [
            'topics' => ['passwords'],
            'roles'  => ['everyone'],
        ],
        'cybercore-safe-browsing-badge-legacy' => [
            'topics' => ['browsing'],
            'roles'  => ['everyone'],
        ],
        'phishing-awareness' => [
            'topics' => ['phishing'],
            'roles'  => ['leadership', 'everyone'],
        ],
        'creating-strong-passwords' => [
            'topics' => ['passwords'],
            'roles'  => ['everyone', 'it'],
        ],
        'safe-browsing-habits' => [
            'topics' => ['browsing'],
            'roles'  => ['field', 'everyone'],
        ],
    ],
];
