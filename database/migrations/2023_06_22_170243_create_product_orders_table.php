<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('pgsql-Trx')->create('pesanan_produk', function (Blueprint $table) {
            $table->uuid('pesanan_produk_id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
            $table->foreignUuid('pesanan_id')->references('pesanan_id')->on('Trx.pesanan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('produk_id')->references('produk_id')->on('Mst.produk')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('jumlah')->length(4)->default(1);
            $table->timestamp('tgl_dibuat')->nullable()->default(now());
            $table->timestamp('tgl_diubah')->nullable();
            $table->timestamp('tgl_dihapus')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_produk');
    }
};
