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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->unique()->constrained('transaksi')->onDelete('cascade');
            $table->enum('status_pembayaran', ['belum_bayar', 'sudah_bayar', 'gagal'])->default('belum_bayar');
            $table->date('tanggal_bayar')->nullable();
            $table->enum('metode_pembayaran', ['transfer_bank', 'cod', 'ewallet']);
            $table->string('bukti_pembayaran')->nullable(); // path foto bukti transfer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
