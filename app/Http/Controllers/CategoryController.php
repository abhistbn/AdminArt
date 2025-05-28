<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Untuk logging error
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class CategoryController extends BaseController
{
    /**
     * Menampilkan semua kategori (GET /api/categories)
     */
    public function index()
    {
        try {
            $categories = DB::table('categories')->orderBy('name', 'asc')->get();
            return response()->json(['categories' => $categories], 200);
        } catch (\Exception $e) {
            Log::error('Fetch Categories Failed: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data kategori.'], 500);
        }
    }

    /**
     * Menyimpan kategori baru (POST /api/categories)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name|max:255',
        ]);

        // 2. Jika Gagal Validasi
        if ($validator->fails()) {
            $errorMessage = $validator->errors()->first('name');
            $statusCode = 400; // Bad Request

            if (str_contains($errorMessage, 'taken')) {
               $errorMessage = 'Nama kategori sudah ada.';
               $statusCode = 409; // Conflict
            } else if (str_contains($errorMessage, 'required')) {
               $errorMessage = 'Nama kategori tidak boleh kosong.';
            }

            return response()->json(['error' => $errorMessage], $statusCode);
        }

        // 3. Ambil Nama Kategori
        $name = $request->input('name');

        // 4. Simpan ke Database
        try {
            $id = DB::table('categories')->insertGetId(['name' => $name]);

            // 5. Beri Respons Sukses
            return response()->json([
                'id'      => $id,
                'name'    => $name,
                'message' => 'Kategori berhasil ditambahkan.'
            ], 201); // 201 Created

        } catch (\Exception $e) {
            Log::error('Store Category Failed: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan server saat menyimpan.'], 500);
        }
    }
}