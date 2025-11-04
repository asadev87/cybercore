<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLogin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'context',
        'remember',
        'ip_address',
        'user_agent',
        'logged_in_at',
    ];

    protected $casts = [
        'remember' => 'boolean',
        'logged_in_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
