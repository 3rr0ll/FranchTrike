<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::latest()->paginate(10);
        return view('admin.faq.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faq.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
            'category' => 'nullable|string|max:100',
        ]);

        \App\Helpers\ActivityLogger::log(
            'faq',
            'created',
            'FAQ created by admin.',
            [
                'question' => $request->question,
                'answer' => $request->answer,
                'created by' => Auth::user()->name,
            ]
        );

        Faq::create($request->all());

        return redirect()->route('admin.faq.index')
            ->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faq.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
            'category' => 'nullable|string|max:100',
        ]);


        \App\Helpers\ActivityLogger::log(
            'faq',
            'update',
            'FAQ updated by admin.',
            [
                'question' => $request->question,
                'answer' => $request->answer,
                'update by' =>  Auth::user()->name,
            ]
        );

        $faq->update($request->all());

        return redirect()->route('admin.faq.index')
            ->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {

      
        $faq->delete();

        \App\Helpers\ActivityLogger::log(
            'faq',
            'Admin deleted a FAQ',
            'FAQ deleted by admin.',
            [
                'faq id' => $faq->id,
                'question' => $faq->question,
                'answer' => $faq -> answer,
                'deleted by' => Auth::user()->name,
            ]
        );

        return redirect()->route('admin.faq.index')
            ->with('success', 'FAQ deleted successfully.');
    }
}
