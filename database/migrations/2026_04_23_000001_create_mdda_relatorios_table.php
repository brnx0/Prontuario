<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mdda_relatorios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->smallInteger('semana_epidemiologica');
            $table->smallInteger('ano');
            $table->string('municipio');
            $table->string('unidade_saude');
            $table->string('responsavel_nome')->nullable();
            $table->string('status')->default('rascunho');
            $table->timestamps();

            $table->unique(['semana_epidemiologica', 'ano']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mdda_relatorios');
    }
};
