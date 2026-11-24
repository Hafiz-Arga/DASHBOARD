<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Membuat ulang tabel sample_superstore dengan tipe data yang benar.
 * Nama tabel dan nama kolom TETAP sama dengan header CSV Superstore.
 *
 * PERHATIAN: tabel lama (yang datanya rusak) akan dihapus.
 * Isi tabel baru diisi lewat halaman /etl.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('sample_superstore');

        Schema::create('sample_superstore', function (Blueprint $table) {
            $table->charset = 'utf8mb4';

            $table->unsignedInteger('Row ID')->primary();
            $table->string('Order ID', 20);
            $table->date('Order Date');
            $table->date('Ship Date');
            $table->string('Ship Mode', 30);
            $table->string('Customer ID', 20);
            $table->string('Customer Name', 100);
            $table->string('Segment', 30);
            $table->string('Country', 50);
            $table->string('City', 100);
            $table->string('State', 100);
            $table->string('Postal Code', 10);   // teks, agar angka 0 di depan tidak hilang
            $table->string('Region', 20);
            $table->string('Product ID', 30);
            $table->string('Category', 50);
            $table->string('Sub-Category', 50);
            $table->string('Product Name', 255);
            $table->decimal('Sales', 12, 4);
            $table->unsignedSmallInteger('Quantity');
            $table->decimal('Discount', 4, 2);   // pecahan: 0.20 = 20%
            $table->decimal('Profit', 12, 4);

            $table->index('Order ID', 'idx_order_id');
            $table->index('Order Date', 'idx_order_date');
            $table->index('Region', 'idx_region');
            $table->index('Category', 'idx_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sample_superstore');
    }
};
