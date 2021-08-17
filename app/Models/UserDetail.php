<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;
    protected $table = "user_detail";
    protected $fillable = ['user_id', 'name'];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
