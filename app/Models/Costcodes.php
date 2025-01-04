<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Costcodes extends Model
{
    use HasFactory;
    protected $table = "cost_codes";
    protected $primaryKey = 'cost_code_id';
    protected $guarded = ['cost_code_id'];
}
