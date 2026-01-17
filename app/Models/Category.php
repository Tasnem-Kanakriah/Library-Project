<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // ? with first way in CategoryController.php
    protected $fillable = ['name', 'image'];
    public $timestamps = false;

    function books() {
        return $this->hasMany(Book::class);
    }
}