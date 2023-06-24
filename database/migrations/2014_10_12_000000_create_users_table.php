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
        // DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');
        Schema::connection('pgsql-Usr')->create('user', function (Blueprint $table) {
            $table->uuid('user_id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
            $table->string('nama_depan', 30);
            $table->string('nama_belakang', 30);
            $table->string('alamat', 200)->nullable();
            $table->string('nomor_hp', 15)->nullable();
            $table->char('jk', 1);
            $table->date('tgl_lahir');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
