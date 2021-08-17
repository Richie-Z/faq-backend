<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'price'];
    public function users()
    {
        return $this->hasMany('App\Models\UserPlan');
    }
    public function scopeFreeId()
    {
        return $this->where('name', 'like', '%free%')->first()->id;
    }
}
