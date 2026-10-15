<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nim', 20)->unique();
            $table->enum('tipe_risiko', ['Konservatif', 'Moderat', 'Agresif']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_profiles');
    }
};