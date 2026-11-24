<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etl_import_histories', function (Blueprint $table) {
            $table->id();
            $table->string('file_name', 255);
            $table->string('import_method', 20); // replace | append
            $table->unsignedInteger('total_rows')->default(0);
            $table->unsignedInteger('valid_rows')->default(0);
            $table->unsignedInteger('invalid_rows')->default(0);
            $table->unsignedInteger('loaded_rows')->default(0);
            $table->string('status', 20); // success | failed
            $table->string('failed_stage', 30)->nullable(); // extract | transform | load
            $table->text('error_message')->nullable();
            $table->decimal('duration_seconds', 8, 2)->default(0);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etl_import_histories');
    }
};
