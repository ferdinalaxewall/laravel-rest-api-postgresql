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
        Schema::connection('pgsql-Trx')->create('pesanan', function (Blueprint $table) {
            $table->uuid('pesanan_id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
            $table->foreignUuid('user_id')->references('user_id')->on('Usr.user')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamp('tgl_pesanan')->nullable()->default(now());
            $table->string('kode_voucher', 20)->nullable();
            $table->timestamp('tgl_pembayaran_lunas')->nullable();
            $table->timestamp('tgl_dibatalkan')->nullable();
            $table->string('no_pesanan', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
