<?php

namespace App\Http\Controllers;

use App\Services\WalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function __construct(private WalletService $walletService)
    {
    }

    public function index(): View
    {
        $user = Auth::user();
        $wallet = $this->walletService->ensureWallet($user->id);

        $transactions = $user->walletTransactions()
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        $packs = collect((array) config('tokens.packs', []))
            ->map(function (int $tokens) {
                $price = $tokens * (float) config('tokens.price_per_token_myr', 0.0);

                return [
                    'tokens' => $tokens,
                    'price'  => number_format($price, 2),
                ];
            })
            ->values();

        return view('wallet.index', [
            'wallet'                => $wallet,
            'transactions'          => $transactions,
            'packs'                 => $packs,
            'module_attempt_cost'   => (int) config('tokens.module_attempt_cost', 0),
            'low_balance_threshold' => (int) config('tokens.low_balance_threshold', 0),
        ]);
    }

    public function mockTopup(Request $request): RedirectResponse
    {
        abort_unless(config('tokens.mock_enabled'), 403);

        $validated = $request->validate([
            'pack' => ['required', 'integer', 'in:' . implode(',', (array) config('tokens.packs', []))],
        ]);

        $tokens = (int) $validated['pack'];

        $this->walletService->topUp(
            Auth::id(),
            $tokens,
            ['source' => 'mock_topup'],
            'mock_topup'
        );

        return redirect()
            ->route('wallet.index')
            ->with('status', __('Added :tokens tokens to your wallet.', ['tokens' => $tokens]));
    }
}
