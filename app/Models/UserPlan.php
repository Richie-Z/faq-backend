<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    use HasFactory;
    protected $table = "user_plan";
    protected $fillable = ['user_id', 'plan_id', 'expires_at'];
    // protected $with = ['plan'];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function plan()
    {
        return $this->belongsTo('App\Models\Plan');
    }
}
