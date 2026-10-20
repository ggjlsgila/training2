<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flyer extends Model
{
    protected $fillable = ['title',
    'thumbnail_image',
    'full_image'];
}
