<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'code', 'user_id'];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function faq()
    {
        return $this->hasMany('App\Models\FaQ');
    }
}
