<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingHistory extends Model
{
    protected $fillable = ['title', 'content', 'documentation_image', 'date'];
}
