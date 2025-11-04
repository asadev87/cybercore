<?php

namespace App\Console\Commands;

use App\Exceptions\InsufficientTokensException;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Console\Command;

class BackfillWallets extends Command
{
    protected $signature = 'wallet:backfill {--bonus : Grant signup bonus to users without it}';

    protected $description = 'Ensure every user has a wallet record and optionally grant the signup bonus.';

    public function handle(WalletService $walletService): int
    {
        $grantBonus = (bool) $this->option('bonus');
        $ensured = 0;
        $bonused = 0;
        $bonusAmount = (int) config('tokens.signup_bonus_tokens', 0);

        foreach (User::cursor() as $user) {
            $walletService->ensureWallet($user->id);
            $ensured++;

            if (! $grantBonus || $bonusAmount <= 0) {
                continue;
            }

            $alreadyCredited = WalletTransaction::query()
                ->where('user_id', $user->id)
                ->where('type', WalletTransaction::TYPE_TOPUP)
                ->where('reason', 'signup_bonus')
                ->exists();

            if ($alreadyCredited) {
                continue;
            }

            try {
                $walletService->topUp(
                    $user->id,
                    $bonusAmount,
                    ['source' => 'signup_bonus_cli'],
                    'signup_bonus'
                );
                $bonused++;
            } catch (InsufficientTokensException) {
                // Not expected for top ups; ignore.
            }
        }

        $this->info("Ensured wallets for {$ensured} users.");

        if ($grantBonus) {
            $this->info("Granted signup bonus to {$bonused} users.");
        }

        return self::SUCCESS;
    }
}
