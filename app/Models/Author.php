<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory;
    public $timestamps = false;
    protected $hidden = ['pivot'];
    
    function books() {
        return $this->belongsToMany(Book::class);
    }
}