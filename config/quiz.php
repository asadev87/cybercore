<?php

return [
    // default # of questions per attempt
    'questions_per_attempt' => env('QUIZ_QUESTIONS_PER_ATTEMPT', 8),

    // null = no timer. Otherwise seconds (e.g., 600 = 10 minutes)
    'time_limit_sec' => env('QUIZ_TIME_LIMIT', null),

    // adaptive window: how many recent answers to consider
    'adaptive_window' => env('QUIZ_ADAPTIVE_WINDOW', 4),
];
