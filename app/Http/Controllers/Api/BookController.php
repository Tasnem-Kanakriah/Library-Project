<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $books = Book::with('category')->with('authors')->get();
        // return ResponseHelper::success("جميع الكتب", $books); // كل الكتب مع الصنف مع المؤلفين

        // ? index function with select column without BookResource::collection
        // $books = Book::select('id', 'title', 'price', 'mortgage')->get();
        // return ResponseHelper::success("جميع الكتب", $books);

        // ?
        // $books = Book::select("ISBN", 'title', 'cover', 'price', 'mortgage')
        // ->get()
        // ->map(function ($book) {
        //     return [
        //         "ISBN" => $book->ISBN,
        //         'title' => $book->title,
        //         'price' => $book->price,
        //         'mortgage' => $book->mortgage,
        //         // 'cover' => asset("storage/books-images/$book->cover")
        //         'cover' => asset('storage/books-images/' . ($book->cover ?? 'no-image.jpeg'))
        //     ];
        // });
        // return ResponseHelper::success("جميع الكتب", $books);
        
        // ?
        // $books = Book::select('id', "ISBN", 'title', 'cover', 'price', 'mortgage')
        // ->with(['category', 'authors:name'])
        // ->get();
        // return ResponseHelper::success("جميع الكتب", $books);


        // ? index function with select column with BookResource::collection
        // $books = Book::select("id", "ISBN", 'title', 'cover', 'price', 'mortgage')->get();
        // return ResponseHelper::success("جميع الكتب", BookResource::collection($books));
        
        // ? index function with select column with BookResource::collection
        // $books = Book::select("id", "ISBN", 'title', 'cover', 'price', 'mortgage', 'category_id')->get();
        // return ResponseHelper::success("جميع الكتب", BookResource::collection($books));

        // ?
        // $books = Book::select('id', "ISBN", 'title', 'cover', 'price', 'mortgage', 'category_id')
        // ->with(['category', 'authors:name'])
        // ->orderBy('id')
        // ->get();
        // return ResponseHelper::success("جميع الكتب", BookResource::collection($books));

        // $title = $request->title;
        // $books = Book::select('id', "ISBN", 'title', 'cover', 'price', 'mortgage', 'category_id')
        // ->when($title, function ($query) use ($title) {
        //     return $query->where('title', 'like', "%$title%");
        // })
        // ->with(['category', 'authors:name'])
        // ->orderBy('id')
        // ->get();
        // // return ResponseHelper::success("جميع الكتب", $books);
        // return ResponseHelper::success("جميع الكتب", BookResource::collection($books));
        
        $title = $request->title;
        $books = Book::select('id', "ISBN", 'title', 'cover', 'price', 'mortgage', 'category_id')
        ->when($title, function ($query) use ($title) {
            return $query->where('title', 'like', "%$title%");
        })
        // ->with(['category'])
        ->with(['category', 'authors:name'])
        ->orderBy('id')
        // ->paginate(7);
        ->get();
        // ->paginate(7);
        // return $books;
        // return ResponseHelper::success("جميع الكتب", BookResource::collection($books));
        return ResponseHelper::success("جميع الكتب", $books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        // Book::create($request->all());
        // return $request->all();

        // $book = Book::create($request->all());
        // return ResponseHelper::success("تم إضافة كتاب", $book);
        // ! ---------------
        // $book = Book::create($request->all());
        // if ($request->hasFile('cover')) {
        //     $file = $request->file('cover');
        //     $filename = Str::uuid() . "." . $file->extension();
        //     Storage::putFileAs('books-images', $file, $filename);
        //     $book->cover = $filename;
        //     $book->save();
        // }
        // $book->authors()->attach($request->authors);
        // return ResponseHelper::success("تم إضافة كتاب", $book);
        // ! ---------------
        $validated = $request->validated();
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = Str::uuid() . "." . $file->extension();
            Storage::putFileAs('books-images', $file, $filename);
            $validated['cover'] = $filename;
        }
        $book = Book::create($validated);
        $book->authors()->attach($validated['authors'] ?? []);
        $book->load(['category', 'authors']);
        return ResponseHelper::success("تم إضافة كتاب", $book);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book = $book->load(['category:id,name', 'authors:name']);
        return ResponseHelper::success('تم إعادة معلومات الكتاب', new BookResource($book));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, Book $book)
    {
        // $book->update($request->except('cover'));
        // if ($request->hasFile('cover')) {
        //     if ($book->cover !== null) {
        //         Storage::delete('books-images/' . $book->cover);
        //     }
        //     $file = $request->file('cover');
        //     $filename = Str::uuid() . "." . $file->extension();
        //     Storage::putFileAs('books-images', $file, $filename);
        //     $book->cover = $filename;
        //     $book->save();
        // }
        // $book->authors()->sync($request->authors);
        // return ResponseHelper::success("تم تعديل الكتاب", $book);
        // ! ---------------
        $validated = $request->validated();
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = Str::uuid() . "." . $file->extension();
            if ($book->cover !== null) {
                Storage::delete("books-images/$book->cover");
            }
            Storage::putFileAs('books-images', $file, $filename);
            $validated['cover'] = $filename;
            $book->save();
        }
        $book->update($validated);
        $book->authors()->sync($validated['authors'] ?? []);
        $book->load(['category', 'authors']);
        return ResponseHelper::success("تم تعديل الكتاب", $book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->cover !== null) {
            Storage::delete('books-images/' . $book->cover);
        }
        $book->delete();
        return ResponseHelper::success("تم حذف الكتاب", $book);
    }
}
