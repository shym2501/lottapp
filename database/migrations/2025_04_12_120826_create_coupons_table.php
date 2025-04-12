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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(false);
            $table->boolean('use_prefix')->default(false);
            $table->string('prefix')->nullable();
            $table->enum('number_type', ['random', 'sequential'])->default('sequential');
            $table->enum('estimasi_peserta', ['10', '100', '1000', '10000', '100000'])->default('100');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
