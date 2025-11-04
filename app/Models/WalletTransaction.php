<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    public const TYPE_TOPUP = 'topup';
    public const TYPE_SPEND = 'spend';
    public const TYPE_EXPIRE = 'expire';
    public const TYPE_ADJUST = 'adjust';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'type',
        'tokens',
        'reason',
        'meta',
    ];

    protected $casts = [
        'tokens' => 'integer',
        'meta'   => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
