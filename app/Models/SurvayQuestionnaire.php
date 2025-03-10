<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurvayQuestionnaire extends Model
{
    use HasFactory;
    protected $table = "survay_questionnaire";
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
}
