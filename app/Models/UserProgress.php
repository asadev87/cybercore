<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model {
    protected $table = 'user_progress';
    protected $fillable = ['user_id','module_id','status','percent_complete','last_activity_at'];
    protected $casts = ['last_activity_at'=>'datetime'];
    public function user(){ return $this->belongsTo(User::class); }
    public function module(){ return $this->belongsTo(\App\Models\Module::class); }
}

