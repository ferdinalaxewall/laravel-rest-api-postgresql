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
        Schema::connection('pgsql-Mst')->create('produk', function (Blueprint $table) {
            $table->uuid('produk_id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
            $table->string('nama', "100");
            $table->string('brand', "40");
            $table->integer('harga')->length(4);
            $table->string('slug', "100")->nullable();
            $table->timestamp('tgl_dibuat')->nullable()->default(now());
            $table->timestamp('tgl_diubah')->nullable();
            $table->timestamp('tgl_release')->nullable();
            $table->timestamp('tgl_dihapus')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('pgsql-Mst')->dropIfExists('produk');
    }
};
