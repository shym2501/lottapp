<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('form_builders', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('form_id')->constrained()->onDelete('cascade'); // Hubungkan ke tabel forms
            $table->string('label');          // Label yang akan ditampilkan ke peserta
            $table->string('name');           // Nama field (misal: nama_lengkap)
            $table->enum('type', ['text', 'textarea', 'select', 'checkbox', 'file']); // Tipe input
            $table->text('options')->nullable();    // Untuk tipe select/checkbox (format: dipisah koma)
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('position')->default(0); // Untuk pengurutan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_builders');
    }
};
