<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            if (!Schema::hasColumn('pacientes', 'profissao')) {
                $table->string('profissao', 100)->nullable()->after('cartao_sus');
            }
            $table->string('situacao', 500)->nullable();
            $table->string('cid10', 20)->nullable();
            $table->string('mmhg', 20)->nullable();
            $table->string('bpm', 20)->nullable();
            $table->string('rpm', 20)->nullable();
            $table->string('spo2', 20)->nullable();
            $table->string('temp', 20)->nullable();
            $table->string('kg', 20)->nullable();
            $table->string('hgt', 20)->nullable();
            $table->text('descricao_caso')->nullable();
            $table->text('sintomas')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn(['profissao', 'situacao', 'cid10', 'mmhg', 'bpm', 'rpm', 'spo2', 'temp', 'kg', 'hgt', 'descricao_caso', 'sintomas']);
        });
    }
};
