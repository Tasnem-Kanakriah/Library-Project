<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// 1-m *********** One to Many relation 

Route::get('1-m-parent/{book}', function (Book $book) {
    return $book->category;
});

Route::get('1-m-child', function () {
    $category = Category::find(1);
    return $category->books; // بترجع الكتب يلي الـ id = 5
});

Route::get('1-m-parent', function () {
    // $book = Book::find(61);
    // return $book;

    // // $book = Book::where("ISBN", '9781646696574')->get();
    // $book = Book::where("ISBN", '9781646696574')->first();
    // return $book;

    // $book = Book::where("ISBN", '9781646696574')->first();
    // // return $book;
    // return $book->category;

    $book = Book::find(61);
    // return $book;
    return $book->category;
});

Route::get('1-m-child-where', function () {
    $category = Category::find(5);
    // return $category->books;
    // return $category->books()->get();

    // return $category->books()->where('price', '>=', 10)->get();
    return $category->books()->where('price', '>=', 10)->get();
});

Route::get('all-books-price-greater-than-90', function () {
    return Book::where('price', '>=', 90)->get();
});

Route::get('1-m-child-update', function () {
    $category = Category::find(1);
    $category->books()->update(['price' => 11.03]);
    // return $category->books;
    return $category->books()->get();
});

// Route::get('1-m-child/{id}', function ($id) {
//     // $category = Category::find($id);
//     $category = Category::findOrFail($id);
//     return $category->books; 
// });

Route::get('1-m-child/{category}', function (Category $category) {
    return $category->books;
});

Route::get('1-m-child-delete', function () {
    $category = Category::find(1);
    // dd($category->books());
    $category->books()->delete();
    $category->delete();
    return "\ncategory deleted successfully";
});

Route::get('1-m-child-create', function () {
    $category = Category::create([
        'name' => "نفسية",
    ])->books()->create([
        "ISBN" => "1112223334444",
        "title" => "تيسير الأمور في ملء القدور",
        "price" => 1,
        "mortgage" => 10,
    ]);
    return $category;
});

// Many to Many relation

Route::get('m-m-authors/{book}', function (Book $book) {
    return $book->authors;
});

Route::get('m-m-books/{author}', function (Author $author) {
    return $author->books;
});

Route::get('attach/{author}', function (Author $author) {
    $author->books()->attach(16);
    return  $author->load('books');
});

Route::get('detach/{author}', function (Author $author) {
    $author->books()->detach(16);
    return  $author->load('books');
});
