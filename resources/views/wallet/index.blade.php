@extends('layouts.app')

@section('content')
  <div class="flex flex-col gap-6">
    <div class="flex flex-col gap-2">
      <h1 class="text-3xl font-semibold tracking-tight">Token Wallet</h1>
      <p class="text-muted-foreground text-sm">
        Each module attempt costs {{ $module_attempt_cost }} tokens. Tokens are mock credits while payments are disabled.
      </p>
    </div>

    @if (session('error'))
      <div class="rounded-xl border border-destructive/40 bg-destructive/10 px-4 py-3 text-sm text-destructive">
        {{ session('error') }}
      </div>
    @endif

    @if (session('status') && session('status') !== 'session-expired')
      <div class="rounded-xl border border-success/40 bg-success/10 px-4 py-3 text-sm text-success">
        {{ session('status') }}
      </div>
    @endif

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
      <div class="rounded-2xl border border-border/60 bg-card px-6 py-5 shadow-sm md:col-span-1 xl:col-span-1">
        <p class="text-sm text-muted-foreground">Current balance</p>
        <div class="mt-2 flex items-baseline gap-2">
          <span class="text-4xl font-semibold">{{ number_format($wallet->token_balance) }}</span>
          <span class="text-sm text-muted-foreground">tokens</span>
        </div>

        @if ($low_balance_threshold > 0 && $wallet->token_balance < $low_balance_threshold)
          <div class="mt-4 rounded-lg border border-amber-400/60 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-500/40 dark:bg-amber-500/10 dark:text-amber-200">
            Low balance — add more tokens before starting a new module.
          </div>
        @endif
      </div>

      <div class="rounded-2xl border border-border/60 bg-card px-6 py-5 shadow-sm md:col-span-1 xl:col-span-2">
        <h2 class="text-lg font-semibold">Mock top-up</h2>
        <p class="text-sm text-muted-foreground">Select a pack to instantly add mock tokens (no payment required).</p>

        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
          @foreach ($packs as $pack)
            <form method="POST" action="{{ route('wallet.mock_topup') }}" class="group">
              @csrf
              <input type="hidden" name="pack" value="{{ $pack['tokens'] }}">
              <button type="submit" class="w-full rounded-xl border border-border/70 bg-background px-4 py-5 text-left transition group-hover:border-primary/60 group-hover:shadow-md">
                <div class="text-2xl font-semibold">{{ $pack['tokens'] }}</div>
                <div class="text-sm text-muted-foreground">Approx. value: RM{{ $pack['price'] }}</div>
              </button>
            </form>
          @endforeach
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-border/60 bg-card shadow-sm">
      <div class="border-b border-border/60 px-6 py-4">
        <h2 class="text-lg font-semibold">Recent activity</h2>
      </div>
      <div class="divide-y divide-border/60">
        @forelse ($transactions as $txn)
          <div class="flex items-center justify-between px-6 py-4 text-sm">
            <div class="flex flex-col">
              <span class="font-medium capitalize">{{ str_replace('_', ' ', $txn->type) }}</span>
              @if ($txn->reason)
                <span class="text-muted-foreground">{{ $txn->reason }}</span>
              @endif
              <span class="text-muted-foreground">{{ $txn->created_at->format('d M Y, h:i A') }}</span>
            </div>
            <span class="font-medium {{ $txn->tokens > 0 ? 'text-success' : 'text-destructive' }}">
              {{ $txn->tokens > 0 ? '+' : '' }}{{ $txn->tokens }}
            </span>
          </div>
        @empty
          <div class="px-6 py-6 text-center text-sm text-muted-foreground">
            No transactions yet.
          </div>
        @endforelse
      </div>
    </div>
  </div>
@endsection
