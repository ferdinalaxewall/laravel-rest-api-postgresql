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
        Schema::connection('pgsql-Mst')->create('produk_stok', function (Blueprint $table) {
            $table->uuid('produk_id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
            $table->integer('stok')->length(4)->default(0)->nullable();
            $table->timestamp('tgl_diubah')->default(now())->nullable();
            $table->foreign('produk_id')->references('produk_id')->on('Mst.produk')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_stok');
    }
};
