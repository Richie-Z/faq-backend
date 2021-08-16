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
    public function getFreeId()
    {
        return $this->where('name', 'Free')->first()->id;
    }
}
