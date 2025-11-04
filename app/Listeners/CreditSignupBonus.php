<?php

namespace App\Listeners;

use App\Services\WalletService;
use App\Models\WalletTransaction;
use Illuminate\Auth\Events\Registered;

class CreditSignupBonus
{
    public function __construct(private WalletService $walletService)
    {
    }

    public function handle(Registered $event): void
    {
        $user = $event->user;
        $bonus = (int) config('tokens.signup_bonus_tokens', 0);

        if (! $user || $bonus <= 0) {
            return;
        }

        $userId = $user->getKey();

        if (! $userId) {
            return;
        }

        $this->walletService->ensureWallet($userId);

        $alreadyCredited = WalletTransaction::query()
            ->where('user_id', $userId)
            ->where('type', WalletTransaction::TYPE_TOPUP)
            ->where('reason', 'signup_bonus')
            ->exists();

        if ($alreadyCredited) {
            return;
        }

        $this->walletService->topUp(
            $userId,
            $bonus,
            ['source' => 'signup_bonus'],
            'signup_bonus'
        );
    }
}
