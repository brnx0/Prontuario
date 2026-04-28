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
        Schema::table('especialidades', function (Blueprint $table) {
            $table->string('incluir_mdda')->default('N')->after('ativo');
        });
    }

    public function down(): void
    {
        Schema::table('especialidades', function (Blueprint $table) {
            $table->dropColumn('incluir_mdda');
        });
    }
};
