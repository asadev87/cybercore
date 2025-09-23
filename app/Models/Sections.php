<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model {
    protected $fillable = ['module_id','title','slug','description','order','is_active'];
    public function module(){ return $this->belongsTo(Module::class); }
    public function questions(){ return $this->hasMany(Question::class); }
}
