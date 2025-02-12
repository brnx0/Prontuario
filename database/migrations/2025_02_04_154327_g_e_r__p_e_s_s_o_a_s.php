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
        Schema::create('PROFISSAO', function(BluePrint $table){
            $table->uuid('PROF_COD')->primary();;
            $table->string('PROF_DESC');
            $table->string('Ativo'); /*onDelete('cascade')*/
        });

        Schema::create('PACIENTES', function(BluePrint $table){
        $table->uuid('PAC_COD')->primary();
        $table->string('NOME');
        $table->string('FILICAO_1')->nullable();
        $table->string('FILICAO_2')->nullable();
        $table->string('CEP')->nullable();
        $table->string('LOGRADOURO')->nullable();
        $table->string('CIDADE')->nullable();
        $table->string('UF')->nullable();
        $table->date('NASCIMENTO')->nullable();
        $table->string('TEL_1')->nullable();
        $table->string('TEL_2')->nullable();
        $table->string('EMAIL')->nullable();
        $table->string('CARTAO_SUS')->nullable();
        $table->string('ATIVO')->nullable()->default('S');
        });
       
        Schema::create('MEDICOS', function (Blueprint $table){
            $table->uuid('MED_COD')->primary();;
            $table->string('MED_NOME');
            $table->string('CRM')->nullable();;
            $table->string('ATIVO')->default('S');

        });
        Schema::create('ENFERMEIROS', function (Blueprint $table){
            $table->uuid('ENF_COD')->primary();;
            $table->string('ENF_NOME');
            $table->string('CRE')->nullable();;
            $table->string('ATIVO')->default('S');

        });
        
        Schema::create('ESPECIALIDADES', function(BluePrint $table){
            $table->uuid('ESP_COD')->primary();
            $table->string('ESCP_DESC');
            $table->string('ATIVO');
        });

        Schema::create('ATENDIMENTOS', function(BluePrint $table){
        $table->uuid('ATEND_COD')->primary();
        $table->dateTime('DT_ATENDIMENTO');
        $table->string('SITUCAO_QUEIXA')->nullable();
        $table->decimal('MMHG')->nullable();
        $table->decimal('BPM')->nullable();
        $table->decimal('RPM')->nullable();
        $table->decimal('SPO2')->nullable();
        $table->decimal('TEMP')->nullable();
        $table->decimal('KG')->nullable();
        $table->decimal('HGT')->nullable();
        $table->longText('DESC_CASO')->nullable();
        });


        Schema::table('PACIENTES', function(BluePrint $table){
            $table->uuid('PROF_COD')->nullable();
            $table->foreign('PROF_COD')->references('PROF_COD')->on('PROFISSAO');
        });
       
        Schema::table('ATENDIMENTOS', function(BluePrint $table){
            $table->uuid('ENF_COD')->nullable();
            $table->foreign('ENF_COD')->references('ENF_COD')->on('ENFERMEIROS');
        });
        Schema::table('ATENDIMENTOS', function(BluePrint $table){
            $table->uuid('ESP_COD')->nullable();
            $table->foreign('ESP_COD')->references('ESP_COD')->on('ESPECIALIDADES');
        });
        Schema::table('ATENDIMENTOS', function(BluePrint $table){
            $table->uuid('MED_COD')->nullable();
            $table->foreign('MED_COD')->references('MED_COD')->on('MEDICOS');
            
        });
        Schema::table('ATENDIMENTOS', function(BluePrint $table){
            $table->uuid('PAC_COD');
            $table->foreign('PAC_COD')->references('PAC_COD')->on('PACIENTES');
            
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
