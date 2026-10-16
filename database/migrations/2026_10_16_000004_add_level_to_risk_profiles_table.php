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
        Schema::table('risk_profiles', function (Blueprint $table) {
            $table->enum('tipe_risiko', [
                'Sangat Konservatif',
                'Konservatif',
                'Moderat',
                'Agresif',
                'Sangat Agresif',
            ])->change();
            $table->integer('level')->default(0)->after('tipe_risiko');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risk_profiles', function (Blueprint $table) {
            $table->dropColumn('level');
            $table->enum('tipe_risiko', ['Konservatif', 'Moderat', 'Agresif'])->change();
        });
    }
};
