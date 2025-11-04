<?php

namespace App\Services;

use App\Exceptions\InsufficientTokensException;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use LogicException;

class WalletService
{
    public function getBalance(int $userId): int
    {
        return $this->ensureWallet($userId)->token_balance;
    }

    public function ensureWallet(int $userId): Wallet
    {
        return Wallet::firstOrCreate(
            ['user_id' => $userId],
            ['token_balance' => 0]
        );
    }

    public function canSpend(int $userId, int $tokens): bool
    {
        if ($tokens <= 0) {
            return true;
        }

        return $this->ensureWallet($userId)->token_balance >= $tokens;
    }

    public function topUp(int $userId, int $tokens, array $meta = [], ?string $reason = null): WalletTransaction
    {
        if ($tokens <= 0) {
            throw new InvalidArgumentException('Top up amount must be positive.');
        }

        return DB::transaction(function () use ($userId, $tokens, $meta, $reason) {
            $wallet = $this->lockWallet($userId, true);
            $wallet->token_balance += $tokens;
            $wallet->save();

            return $this->createTransaction(
                $wallet,
                WalletTransaction::TYPE_TOPUP,
                $tokens,
                $reason,
                $meta
            );
        });
    }

    /**
     * @throws InsufficientTokensException
     */
    public function spend(int $userId, int $tokens, string $reason, array $meta = []): WalletTransaction
    {
        if ($tokens <= 0) {
            throw new InvalidArgumentException('Spend amount must be positive.');
        }

        return DB::transaction(function () use ($userId, $tokens, $reason, $meta) {
            $wallet = $this->lockWallet($userId, true);

            if ($wallet->token_balance < $tokens) {
                throw new InsufficientTokensException('Wallet balance too low.');
            }

            $wallet->token_balance -= $tokens;
            $wallet->save();

            return $this->createTransaction(
                $wallet,
                WalletTransaction::TYPE_SPEND,
                -$tokens,
                $reason,
                $meta
            );
        });
    }

    private function lockWallet(int $userId, bool $createIfMissing = false): Wallet
    {
        $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->first();

        if ($wallet) {
            return $wallet;
        }

        if (! $createIfMissing) {
            throw new LogicException('Wallet not found.');
        }

        $wallet = Wallet::create([
            'user_id'       => $userId,
            'token_balance' => 0,
        ]);

        return Wallet::whereKey($wallet->id)->lockForUpdate()->firstOrFail();
    }

    private function createTransaction(
        Wallet $wallet,
        string $type,
        int $tokens,
        ?string $reason,
        array $meta
    ): WalletTransaction {
        return WalletTransaction::create([
            'user_id' => $wallet->user_id,
            'type'    => $type,
            'tokens'  => $tokens,
            'reason'  => $reason,
            'meta'    => $meta ?: null,
        ]);
    }
}
