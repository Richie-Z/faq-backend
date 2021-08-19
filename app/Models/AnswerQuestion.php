<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerQuestion extends Model
{
    use HasFactory;
    protected $table = 'answer_question';
    protected $fillable = ['question', 'answer', 'is_verified', 'anonymous_add', 'faq_id'];
    public function faq()
    {
        return $this->belongsTo('App\Models\FaQ');
    }
}
