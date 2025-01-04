<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeEntry extends Model
{
    use HasFactory;
    protected $table = "time_entries";
    protected $primaryKey = 'time_entry_id';
    protected $guarded = ['time_entry_id'];
}
