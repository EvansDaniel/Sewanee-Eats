<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function showArticle($id)
    {
        $article = Article::findOrFail($id);
        return view('home.article', compact('article'));
    }
}
