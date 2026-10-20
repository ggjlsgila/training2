<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    protected $fillable = [
        'title',
        'cover_image',
        'short_description',
        'long_description',
    ];
}
