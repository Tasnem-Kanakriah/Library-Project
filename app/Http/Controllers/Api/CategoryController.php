<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $categories = Category::all();
    //     // return $categories;
    //     return ResponseHelper::success("جميع الأصناف", $categories);
    // }
    public function index()
    {
        // $categories = Category::withAvg('books', 'price')->get();
        // $categories = Category::withMax('books', 'price')->get();
        $categories = Category::withCount('books')->get();
        return ResponseHelper::success("جميع الأصناف مع عدد الكتب لكل صنف", $categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ? first way
        // $category = Category::create([
        //     'name' => $request->name,
        // ]);
        // return response()->json($category, 201);
        // ? seconde way
        // $category = new Category();
        // $category->name = $request->name;
        // $category->save();
        // // return $category;
        // return "ok";
        // ? seconde way with validation
        // $request->validate([
        //     'name' => 'required|max:50|unique:categories'
        // ]);
        // $category = new Category();
        // $category->name = $request->name;
        // $category->save();
        // // return $category;
        // return ResponseHelper::success("تمت إضافة سجل", $category);
        
        $request->validate([
            'name' => 'required|max:50|unique:categories'
        ]);
        $category = Category::create([
            'name' => $request->name,
        ]);
        // todo : doesn't work !
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . "." . $file->extension();
            Storage::putFileAs('categories-images', $file, $filename);
            $category->image = $filename;
            $category->save();
            return ResponseHelper::success("تمت إضافة صنف جديد مع صورة ", $category);
        }
        else {
            return ResponseHelper::success("تمت إضافة صنف جديد بدون صورة ", $category);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => "required|max:50|unique:categories,name,$id"
        ]);
        $category = Category::find($id);
        $category->name = $request->name;
        $category->save();
        // return "update successful";
        return ResponseHelper::success("تم التعديل الصنف", $category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $category = Category::find($id);
        // $category->delete();
        // return ResponseHelper::success("تم حذف الصنف", $category);

        $category = Category::where('id', $id)->withCount('books')->first();
        if ($category->books_count > 0) {
            return ResponseHelper::failed("لا يمكن حذف هذا الصنف لوجود كتب مرتبطة به", $category);
        }
        else {
            $category->delete();
            return ResponseHelper::success("تم حذف الصنف", $category);
        }
    }
}