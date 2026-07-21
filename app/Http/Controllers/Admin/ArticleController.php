<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $q = Article::with('penulis')
                ->when($request->search,   fn($q) => $q->where('judul', 'ilike', "%{$request->search}%"))
                ->when($request->kategori, fn($q) => $q->where('kategori', $request->kategori))
                ->when($request->status,   fn($q) => $q->where('status', $request->status))
                ->latest();

            $articles = $q->paginate(12);
            $stats = [
                'total'     => Article::count(),
                'published' => Article::where('status', 'published')->count(),
                'draft'     => Article::where('status', 'draft')->count(),
            ];
            return response()->json(array_merge($articles->toArray(), ['stats' => $stats]));
        }

        return view('admin.articles.index');
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['penulis_id'] = auth()->id();
        $data['slug'] = Article::generateSlug($data['judul']);

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('articles', 'public');
        }

        $article = Article::create($data);
        return response()->json(['success' => true, 'message' => 'Artikel berhasil ditambahkan!', 'id' => $article->id]);
    }

    public function show(Article $article)
    {
        return response()->json(['success' => true, 'data' => $article->load('penulis')]);
    }

    public function update(Request $request, Article $article)
    {
        $data = $this->validatedData($request, false);
        $data['slug'] = Article::generateSlug($data['judul'], $article->id);

        if ($data['status'] === 'published' && ! $article->published_at) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('thumbnail')) {
            if ($article->thumbnail) Storage::disk('public')->delete($article->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('articles', 'public');
        }

        $article->update($data);
        return response()->json(['success' => true, 'message' => 'Artikel berhasil diperbarui!']);
    }

    public function destroy(Article $article)
    {
        if ($article->thumbnail) Storage::disk('public')->delete($article->thumbnail);
        $article->delete();
        return response()->json(['success' => true, 'message' => 'Artikel berhasil dihapus!']);
    }

    private function validatedData(Request $request, bool $withThumb = true): array
    {
        $rules = [
            'judul'     => 'required|string|max:255',
            'ringkasan' => 'nullable|string|max:500',
            'konten'    => 'required|string',
            'kategori'  => 'required|in:tips,berita,akademik,promo,lainnya',
            'status'    => 'required|in:draft,published',
        ];
        if ($withThumb) {
            $rules['thumbnail'] = 'nullable|image|max:4096';
        } else {
            $rules['thumbnail'] = 'nullable|image|max:4096';
        }
        return $request->validate($rules);
    }
}
