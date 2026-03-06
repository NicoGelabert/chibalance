<?php

namespace App\Http\Controllers;

use App\Models\HomeHeroBanner;
use App\Models\Product;
use App\Models\About;
use App\Models\Article;
use App\Models\Service;
use App\Models\Tag;
use App\Models\Client;
use App\Models\Project;
use App\Models\Faq;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $homeherobanners = HomeHeroBanner::all();
        $products = Product::where('published',true)->get();
        $abouts = About::all();
        $articles = Article::orderBy('created_at', 'desc')->limit(5)->get();
        // $services = Service::all();
        // $tags = Tag::all();
        // $clients = Client::all();
        // $projects = Project::with('tags', 'clients')->whereHas('services', function($query) {
        //     $query->where('service_id', 2);
        // })->get();
        // $devprojects = Project::with('tags', 'clients')->whereHas('services', function($query) {
        //     $query->where('service_id', 1);
        // })->get();
        // $faqs = Faq::all();
        return view('welcome', compact(
            'homeherobanners',
            'products',
            'abouts',
            'articles',
            // 'services',
            // 'tags',
            // 'clients',
            // 'projects',
            // 'devprojects',
            // 'faqs'
        ));
    }
}
