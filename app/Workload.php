<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workload extends Model
{
    protected $fillable = [
        'name', 'work_instruction_title', 'description', 'status', 'file', 'uploaded_date',
    ];
}
