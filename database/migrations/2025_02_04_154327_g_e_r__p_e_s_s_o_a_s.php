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
        Schema::create('profissao', function(BluePrint $table){
            $table->uuid('prof_cod')->primary();;
            $table->string('prof_desc');
            $table->string('ativo'); /*onDelete('cascade')*/
        });

        Schema::create('pacientes', function(BluePrint $table){
        $table->uuid('pac_cod')->primary();
        $table->string('nome');
        $table->string('cpf')->nullable();
        $table->string('filicao_1')->nullable();
        $table->string('filicao_2')->nullable();
        $table->string('cep')->nullable();
        $table->string('logradouro')->nullable();
        $table->string('cidade')->nullable();
        $table->string('uf')->nullable();
        $table->date('nascimento')->nullable();
        $table->string('tel_1')->nullable();
        $table->string('tel_2')->nullable();
        $table->string('email')->nullable();
        $table->string('cartao_sus')->nullable();
        $table->string('ativo')->nullable()->default('S');
        });
       
        Schema::create('medicos', function (Blueprint $table){
            $table->uuid('med_cod')->primary();;
            $table->string('med_nome');
            $table->string('crm')->nullable();;
            $table->string('ativo')->default('S');

        });
        Schema::create('enfermeiros', function (Blueprint $table){
            $table->uuid('enf_cod')->primary();;
            $table->string('enf_nome');
            $table->string('cre')->nullable();;
            $table->string('ativo')->default('S');

        });
        
        Schema::create('especialidades', function(BluePrint $table){
            $table->uuid('esp_cod')->primary();
            $table->string('escp_desc');
            $table->string('ativo');
        });

        Schema::create('atendimentos', function(BluePrint $table){
        $table->uuid('atend_cod')->primary();
        $table->datetime('dt_atendimento');
        $table->string('situcao_queixa')->nullable();
        $table->decimal('mmhg')->nullable();
        $table->decimal('bpm')->nullable();
        $table->decimal('rpm')->nullable();
        $table->decimal('spo2')->nullable();
        $table->decimal('temp')->nullable();
        $table->decimal('kg')->nullable();
        $table->decimal('hgt')->nullable();
        $table->longtext('desc_caso')->nullable();
        });


        Schema::table('pacientes', function(BluePrint $table){
            $table->uuid('prof_cod')->nullable();
            $table->foreign('prof_cod')->references('prof_cod')->on('profissao');
        });
       
        Schema::table('atendimentos', function(BluePrint $table){
            $table->uuid('enf_cod')->nullable();
            $table->foreign('enf_cod')->references('enf_cod')->on('enfermeiros');
        });
        Schema::table('atendimentos', function(BluePrint $table){
            $table->uuid('esp_cod')->nullable();
            $table->foreign('esp_cod')->references('esp_cod')->on('especialidades');
        });
        Schema::table('atendimentos', function(BluePrint $table){
            $table->uuid('med_cod')->nullable();
            $table->foreign('med_cod')->references('med_cod')->on('medicos');
            
        });
        Schema::table('atendimentos', function(BluePrint $table){
            $table->uuid('pac_cod');
            $table->foreign('pac_cod')->references('pac_cod')->on('pacientes');
            
        });


}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ATENDIMENTOS');
        Schema::dropIfExists('PACIENTES');
        Schema::dropIfExists('PROFISSAO');
        Schema::dropIfExists('ESPECIALIDADES');
        Schema::dropIfExists('ENFERMEIROS');
        Schema::dropIfExists('MEDICOS');
    }
};
