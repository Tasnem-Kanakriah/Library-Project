<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\ResponseHelper;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();
        return ResponseHelper::success("جميع الكتب", $books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        // Book::create($request->all());
        // return $request->all();

        // $book = Book::create($request->all());
        // return ResponseHelper::success("تم إضافة كتاب", $book);

        $book = Book::create($request->all());
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = "$request->ISBN." . $file->extension();
            Storage::putFileAs('books-images', $file, $filename);
            $book->cover = $filename;
            $book->save();
        }
        return ResponseHelper::success("تم إضافة كتاب", $book);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        
        $book->update($request->except('cover'));
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = "$request->ISBN." . $file->extension();
            Storage::putFileAs('books-images', $file, $filename);
            $book->cover = $filename;
            $book->save();
        }
        return ResponseHelper::success("تم تعديل الكتاب", $book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return ResponseHelper::success("تم حذف الكتاب", $book);
    }
}
