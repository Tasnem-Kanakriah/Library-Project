<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
    public function index(Request $request)
    {
        // // $categories = Category::withAvg('books', 'price')->get();
        // // $categories = Category::withMax('books', 'price')->get();

        // $categories = Category::withCount('books')->get();

        // $categories = Category::withCount('books')->whereHas('books')->get();

        // $categories = Category::withCount('books')->whereHas('books', function ($query) {
        //     return  $query->where('price', '=', 23.4);
        // })->get();

        // ? جيب التصنيفات يلي فيها كتب أغلى من 90 مع عدد الكتب الكلي لهالتصنيف بعيدا عن الشرط 
        // $categories = Category::withCount('books')
        // ->whereHas('books', fn ($query) => $query->where('price', '>', 90))
        // ->get();

        // ? جلب كل الكتب
        $categories = Category::all();

        // ? لجلب الكتب مع كل تصنيف
        // $categories = Category::with('books')->get(); 

        // ? لجلب الكتب يلي سعرها أعلى من سبعين التابعة لكل تصنيف 
        // $categories = Category::with(['books' => fn($q)=>$q->where('price','>',70)])->get();

        // ! يجلب فقط التصنيفات التي تحتوي كتب أعلى من 70 ويجلب كتبها التي تحقق نفس الشرط، كتب أغلى من 70
        // $categories = Category::with(['books' => fn($query) => $query->where('price', '>', 70)])
        //     ->whereHas('books', fn($query) => $query->where('price', '>', 70))
        //     ->get();

        // $categories = Category::pluck('name');

        // Middleware هاد الكود صار بالـ 
        // $locale = $request->header('Accept-Language') ?? 'ar';
        // $locale = $request->getLanguages();
        // return $locale[0] ?? 'ar';
        // // App::setLocale('en');
        // app()->setLocale($locale[0] ?? 'ar');

        return ResponseHelper::success(trans('library.all-categories'), $categories);
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

        // !
        $request->validate([
            'name' => 'required|max:50|unique:categories',
            'image' => 'nullable|image'
        ]);
        $category = Category::create([
            'name' => $request->name,
        ]);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . "." . $file->extension();
            Storage::putFileAs('categories-images', $file, $filename);
            $category->image = asset('storage/categories-images/' . $filename);
            $category->save();
            return ResponseHelper::success("تمت إضافة صنف جديد مع صورة ", $category);
        } else {
            return ResponseHelper::success("تمت إضافة صنف جديد بدون صورة ", $category);
        }

        //! حل الانسة
        // $request->validate([
        //     'name' => 'required|max:50|unique:categories',
        //     'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048'
        // ]);
        // $category = new Category();
        // $category->name = $request->name;
        // if ($request->hasFile('image')) {
        //     $file = $request->file('image');
        //     $filename = time() . '_' . $file->getClientOriginalName();
        //     $path = $file->storeAs('categories-images', $filename, 'public'); // تخزين في public disk
        //     $category->image = $path; // احفظ الـ path كامل
        // }
        // $category->save();
        // return ResponseHelper::success(
        //     isset($category->image) ? "تمت إضافة صنف جديد مع صورة" : "تمت إضافة صنف جديد بدون صورة",
        //     $category
        // );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => "required|max:50|unique:categories,name,$id",
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048'
        ]);
        $category = Category::findOrFail($id);
        $category->name = $request->name;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::uuid() . ".". $file->extension();
            Storage::putFileAs('categories-images', $file, $filename);
            // $path = $file->storeAs('categories-images', $filename, 'public');

            if ($category->image) {
                $oldPath = str_replace(asset('storage/'), '', $category->image);
                Storage::disk('public')->delete($oldPath);
            }
            $category->image = asset("storage/categories-images/$filename");
        }
        $category->save();
        return ResponseHelper::success("تم تعديل الصنف بنجاح", $category);
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
        } else {
            $category->delete();
            return ResponseHelper::success("تم حذف الصنف", $category);
        }
    }
}
