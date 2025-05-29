<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('articles', function (Blueprint $table) {
        $table->increments('id');
        $table->string('title');
        $table->text('content');
        $table->unsignedInteger('category_id')->nullable(); // Kolom untuk ID kategori
        $table->timestamps(); // Kolom created_at dan updated_at

        // Opsional: Menambahkan foreign key constraint (jika database mendukungnya & Anda mau)
        // $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
