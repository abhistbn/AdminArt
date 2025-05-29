<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;
use Carbon\Carbon;

class ArticleController extends BaseController
{
    /**
     * Menampilkan semua artikel (GET /api/articles)
     * Bergabung dengan tabel kategori untuk mendapatkan nama kategori.
     */
    public function index()
    {
        try {
            $articles = DB::table('articles')
                        ->leftJoin('categories', 'articles.category_id', '=', 'categories.id')
                        ->select('articles.*', 'categories.name as category_name')
                        ->orderBy('articles.created_at', 'desc') // Urutkan dari terbaru
                        ->get();
            return response()->json(['articles' => $articles], 200);
        } catch (\Exception $e) {
            Log::error('Fetch Articles Failed: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data artikel.'], 500);
        }
    }

    /**
     * Menampilkan artikel spesifik (GET /api/articles/{id})
     */
    public function show($id)
    {
        try {
            $article = DB::table('articles')
                        ->leftJoin('categories', 'articles.category_id', '=', 'categories.id')
                        ->select('articles.*', 'categories.name as category_name')
                        ->where('articles.id', $id)
                        ->first();

            if (!$article) {
                return response()->json(['error' => 'Artikel tidak ditemukan.'], 404);
            }
            return response()->json(['article' => $article], 200);
        } catch (\Exception $e) {
            Log::error('Fetch Single Article Failed: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data artikel.'], 500);
        }
    }

    /**
     * Menyimpan artikel baru (POST /api/articles)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $articleId = DB::table('articles')->insertGetId([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'category_id' => $request->input('category_id'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Ambil artikel yang baru saja dibuat beserta nama kategorinya
            $newArticle = DB::table('articles')
                            ->leftJoin('categories', 'articles.category_id', '=', 'categories.id')
                            ->select('articles.*', 'categories.name as category_name')
                            ->where('articles.id', $articleId)
                            ->first();

            return response()->json([
                'message' => 'Artikel berhasil disimpan!',
                'article' => $newArticle
            ], 201);

        } catch (\Exception $e) {
            Log::error('Store Article Failed: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menyimpan artikel.'], 500);
        }
    }

    /**
     * Mengupdate artikel yang ada (PUT /api/articles/{id})
     */
    public function update(Request $request, $id)
    {
        $article = DB::table('articles')->find($id);
        if (!$article) {
            return response()->json(['error' => 'Artikel tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            DB::table('articles')
                ->where('id', $id)
                ->update([
                    'title' => $request->input('title'),
                    'content' => $request->input('content'),
                    'category_id' => $request->input('category_id'),
                    'updated_at' => Carbon::now(),
                ]);

            // Ambil artikel yang baru diupdate beserta nama kategorinya
            $updatedArticle = DB::table('articles')
                                ->leftJoin('categories', 'articles.category_id', '=', 'categories.id')
                                ->select('articles.*', 'categories.name as category_name')
                                ->where('articles.id', $id)
                                ->first();

            return response()->json([
                'message' => 'Artikel berhasil diupdate!',
                'article' => $updatedArticle
            ], 200);

        } catch (\Exception $e) {
            Log::error('Update Article Failed: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengupdate artikel.'], 500);
        }
    }

    /**
     * Menghapus artikel (DELETE /api/articles/{id})
     */
    public function destroy($id)
    {
        $article = DB::table('articles')->find($id);
        if (!$article) {
            return response()->json(['error' => 'Artikel tidak ditemukan.'], 404);
        }

        try {
            DB::table('articles')->where('id', $id)->delete();
            return response()->json(['message' => 'Artikel berhasil dihapus!'], 200);
        } catch (\Exception $e) {
            Log::error('Delete Article Failed: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghapus artikel.'], 500);
        }
    }
}