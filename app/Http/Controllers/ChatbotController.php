<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;

class ChatBotController extends Controller
{
    public function index()
    {
        return view('chatbot');
    }

    public function categories()
    {
        $categories = Faq::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->pluck('category');

        return response()->json($categories);
    }

    public function getCategories()
{
    $categories =Faq::select('category')
        ->distinct()
        ->pluck('category')
        ->filter() // remove null or empty
        ->values();

    return response()->json($categories);
}

    public function questions($category)
    {
        $questions = Faq::where('category', $category)
            ->select('id', 'question')
            ->get();

        return response()->json($questions);
    }

    public function answer($id)
    {
        $faq = Faq::find($id);

        if ($faq) {
            return response()->json(['answer' => $faq->answer]);
        }

        return response()->json(['answer' => 'Sorry, I don\'t have an answer for that yet.']);
    }
}