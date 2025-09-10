<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
// database/migrations/xxxx_xx_xx_create_products_table.php

Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->json('image')->nullable();
    $table->string('name');
    $table->decimal('price', 12, 2);
    $table->json('sizes')->nullable();   // untuk kategori dengan ukuran
    $table->integer('stock')->nullable(); // 🔥 kolom stok tunggal
    $table->text('description')->nullable();
    $table->string('category');
    $table->unsignedBigInteger('views')->default(0);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};