<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ArticleEditController extends BaseController
{
    /**
     * Menampilkan form untuk membuat artikel baru.
     * GET /admin/articles/create
     */
    public function create()
    {
        try {
            $categories = DB::table('categories')->orderBy('name', 'asc')->get();
            // Path ke view: resources/views/admin/articles/create.blade.php
            return view('admin.articles.create', compact('categories')); 
        } catch (\Exception $e) {
            Log::error('Error loading create article form: ' . $e->getMessage());
            // Redirect ke halaman daftar dengan pesan error
            return redirect('/article_management.html?error_message=' . urlencode('Gagal memuat form tambah artikel.'));
        }
    }

    /**
     * Menyimpan artikel baru dari form.
     * POST /admin/articles
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
            // Tambahkan validasi untuk field lain jika ada di form create Anda
        ]);

        if ($validator->fails()) {
            return redirect()->back() // Kembali ke form create
                        ->withErrors($validator)
                        ->withInput(); // Bawa kembali input yang sudah diisi
        }

        try {
            DB::table('articles')->insert([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'category_id' => $request->input('category_id'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                // Tambahkan field lain dari form create Anda
            ]);
            return redirect('/admin/articles/create?status=success&message=' . urlencode('Artikel baru berhasil dibuat!')); 
        } catch (\Exception $e) {
            Log::error('Store Article (from View) Failed: ' . $e->getMessage());
            return redirect()->back()
                        ->withInput()
                        ->with('error_message_param', urlencode('Gagal menyimpan artikel: Terjadi kesalahan server.'));
        }
    }

    /**
     * Menampilkan form untuk mengedit artikel.
     * GET /admin/articles/{id}/edit
     */
    public function edit($id)
    {
        try {
            $article = DB::table('articles')->find($id);

            if (!$article) {
                return redirect('/article_management.html?error_message=' . urlencode('Artikel dengan ID ' . $id . ' tidak ditemukan.'));
            }

            $categories = DB::table('categories')->orderBy('name', 'asc')->get();
            
            // Path ke view: resources/views/admin/articles/edit.blade.php
            return view('admin.articles.edit', compact('article', 'categories'));

        } catch (\Exception $e) {
            Log::error('Error loading edit article form for ID ' . $id . ': ' . $e->getMessage());
            return redirect('/article_management.html?error_message=' . urlencode('Gagal memuat form edit artikel.'));
        }
    }

    /**
     * Mengupdate artikel dari form edit.
     * PUT /admin/articles/{id}
     */
    public function update(Request $request, $id)
    {
        $article = DB::table('articles')->find($id);
        if (!$article) {
             return redirect('/article_management.html?status=error&message=' . urlencode('Artikel tidak ditemukan untuk diupdate.'));
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
            // Tambahkan validasi untuk field lain jika ada di form edit Anda
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            DB::table('articles')
                ->where('id', $id)
                ->update([
                    'title' => $request->input('title'),
                    'content' => $request->input('content'),
                    'category_id' => $request->input('category_id'),
                    'updated_at' => Carbon::now(),
                    // Tambahkan field lain dari form edit Anda
                ]);
            
            return redirect('/admin/articles/' . $id . '/edit?status=success&message=' . urlencode('Artikel berhasil diupdate!'));
        } catch (\Exception $e) {
            Log::error('Update Article (from View) Failed for ID ' . $id . ': ' . $e->getMessage());
             return redirect()->back()
                        ->withInput()
                        ->with('error_message_param', urlencode('Gagal mengupdate artikel: Terjadi kesalahan server.'));
        }
    }
}