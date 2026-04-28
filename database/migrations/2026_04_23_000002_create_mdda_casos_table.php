<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mdda_casos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('mdda_relatorio_id');
            $table->uuid('atendimento_id')->nullable();
            $table->smallInteger('numero_ordem');
            $table->date('data_atendimento');
            $table->string('nome_paciente');
            $table->string('procedencia')->nullable();
            $table->string('faixa_etaria')->default('IGN');
            $table->string('idade_display')->nullable();
            $table->string('zona')->default('IGN');
            $table->date('data_primeiros_sintomas')->nullable();
            $table->string('plano_tratamento')->default('IGN');

            $table->foreign('mdda_relatorio_id')
                  ->references('id')
                  ->on('mdda_relatorios')
                  ->onDelete('cascade');

            $table->foreign('atendimento_id')
                  ->references('atend_cod')
                  ->on('atendimentos')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mdda_casos');
    }
};
