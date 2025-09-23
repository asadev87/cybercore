<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
protected $fillable = ['slug','name','icon','description','criteria','is_active'];
    protected $casts = ['criteria'=>'array','is_active'=>'boolean'];
    public function users() {
        return $this->belongsToMany(User::class, 'user_badges')->withTimestamps()->withPivot('awarded_at');
    }
}
