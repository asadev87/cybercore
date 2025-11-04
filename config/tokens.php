<?php

return [
    'price_per_token_myr'      => env('TOKENS_PRICE_PER_TOKEN_MYR', 0.30),
    'signup_bonus_tokens'      => env('TOKENS_SIGNUP_BONUS', 100),
    'module_attempt_cost'      => env('TOKENS_MODULE_ATTEMPT_COST', 15),
    'packs'                    => [
        20,
        50,
        100,
        150,
        200,
    ],
    'mock_enabled'             => (bool) env('TOKENS_MOCK', true),
    'low_balance_threshold'    => env('TOKENS_LOW_BALANCE_THRESHOLD', 15),
];
