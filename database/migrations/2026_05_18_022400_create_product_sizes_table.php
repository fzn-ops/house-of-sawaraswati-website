<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_sizes', function (Blueprint $table) {
            $table->id('product_size_id');
            $table->foreignId('product_id')->constrained('products', 'product_id')->onDelete('cascade');
            $table->string('size', 50);
            $table->integer('stok')->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'size']);
        });

        $products = DB::table('products')->get();
        foreach ($products as $product) {
            $size = !empty($product->size) ? $product->size : 'All Size';
            DB::table('product_sizes')->insert([
                'product_id' => $product->product_id,
                'size'       => $size,
                'stok'       => $product->stok ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (Schema::hasColumn('products', 'size')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('size');
            });
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'size')) {
                $table->string('size')->nullable()->after('category');
            }
        });

        $sizes = DB::table('product_sizes')->get()->groupBy('product_id');
        foreach ($sizes as $productId => $rows) {
            DB::table('products')->where('product_id', $productId)->update([
                'size' => $rows->first()->size,
            ]);
        }

        Schema::dropIfExists('product_sizes');
    }
};
