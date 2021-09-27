<?php

use App\Models\Category;
use App\Models\OldPost;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Spatie\YamlFrontMatter\YamlFrontMatter;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('posts', [
        'posts' => Post::latest()->get(),
        'categories' => Category::all()
    ]);
});

Route::get('/test', function () {
    $coll = collect([1,2,4,5,6])
        ->map(fn($x) => $x +4)
        ->filter(fn($x) => $x > 5);
    dd($coll);
});

Route::get('posts/{post}', function (Post $post) {
    return view('post', [
        'post' => $post,
        'categories' => Category::all()
    ]);
});

Route::get('categories/{category:slug}', function (Category $category) {
   return view('posts', [
       'posts' => $category->posts,
       'currentCategory' => $category,
       'categories' => Category::all()
   ]);
});

Route::get('authors/{author:username}', function (User $author) {
    return view('posts', [
        'posts' => $author->posts,
        'categories' => Category::all()
    ]);
});
