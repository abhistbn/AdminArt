<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticleEditController extends Controller
{
    /**
     * Display a listing of articles
     */
    public function index()
    {
        // Nanti bisa diisi dengan logic untuk menampilkan daftar artikel
        return view('admin.articles.index');
    }

    /**
     * Show the form for creating a new article
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Store a newly created article
     */
    public function store(Request $request)
    {
        // Logic untuk menyimpan artikel baru
        // Validasi, simpan ke database, dll
        
        return redirect()->route('admin.articles.index')
                        ->with('success', 'Artikel berhasil dibuat!');
    }

    /**
     * Display the specified article
     */
    public function show($id)
    {
        // Logic untuk menampilkan detail artikel
        return view('admin.articles.show', compact('id'));
    }

    /**
     * Show the form for editing the specified article
     */
    public function edit($id)
    {
        // Dummy data - nanti diganti dengan data dari database
        $article = (object) [
            'id' => $id,
            'title' => 'Panduan Lengkap React untuk Pemula',
            'content' => 'React adalah library JavaScript yang dikembangkan oleh Facebook...',
            'author' => 'Ahmad Fadhil',
            'category' => 'Programming',
            'tags' => 'React, JavaScript, Frontend, Tutorial',
            'status' => 'published',
            'published_at' => '2024-03-15',
            'excerpt' => 'Pelajari konsep-konsep dasar React JavaScript library untuk membangun user interface yang interaktif dan modern.',
            'featured_image' => 'https://via.placeholder.com/800x400/3b82f6/ffffff?text=React+Tutorial',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return view('admin.edit_article', compact('article'));
    }

    /**
     * Update the specified article
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'author' => 'required|max:100',
            'category' => 'required',
            'status' => 'required|in:draft,published,archived',
            'excerpt' => 'nullable',
            'tags' => 'nullable',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|url'
        ]);

        // Logic untuk update artikel di database
        // Contoh:
        // $article = Article::findOrFail($id);
        // $article->update($validatedData);

        // Response JSON untuk AJAX request
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Artikel berhasil diupdate!',
                'data' => $validatedData
            ]);
        }

        // Redirect untuk form biasa
        return redirect()->route('admin.articles.edit', $id)
                        ->with('success', 'Artikel berhasil diupdate!');
    }

    /**
     * Remove the specified article
     */
    public function destroy($id)
    {
        // Logic untuk hapus artikel
        // Article::findOrFail($id)->delete();
        
        return redirect()->route('admin.articles.index')
                        ->with('success', 'Artikel berhasil dihapus!');
    }

    /**
     * Preview article
     */
    public function preview($id)
    {
        // Logic untuk preview artikel
        $article = (object) [
            'id' => $id,
            'title' => 'Panduan Lengkap React untuk Pemula',
            'content' => 'React adalah library JavaScript yang dikembangkan oleh Facebook...',
            'author' => 'Ahmad Fadhil',
            'category' => 'Programming',
            'tags' => 'React, JavaScript, Frontend, Tutorial',
            'status' => 'published',
            'published_at' => '2024-03-15',
            'excerpt' => 'Pelajari konsep-konsep dasar React JavaScript library untuk membangun user interface yang interaktif dan modern.',
            'featured_image' => 'https://via.placeholder.com/800x400/3b82f6/ffffff?text=React+Tutorial'
        ];

        return view('admin.articles.preview', compact('article'));
    }
}