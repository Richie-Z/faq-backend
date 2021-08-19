<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaQ extends Model
{
    use HasFactory;
    protected $table = "faqs";
    protected $fillable = ['name', 'group_id'];
    // protected $with = ['answerQuestion'];
    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }
    public function answerQuestion()
    {
        return $this->hasMany('App\Models\AnswerQuestion', 'faq_id');
    }
}
