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
        // ==================
        // ATENDIMENTOS
        // ==================
        Schema::table('atendimentos', function (Blueprint $table) {
            // Índices nas Foreign Keys (acelera JOINs)
            $table->index('pac_cod', 'idx_atend_pac_cod');
            $table->index('med_cod', 'idx_atend_med_cod');
            $table->index('enf_cod', 'idx_atend_enf_cod');
            $table->index('esp_cod', 'idx_atend_esp_cod');

            // Índice para filtros e ordenação por data
            $table->index('dt_atendimento', 'idx_atend_dt_atendimento');

            // Índice composto: histórico por data + paciente
            $table->index(['dt_atendimento', 'pac_cod'], 'idx_atend_dt_pac');
        });

        // ==================
        // PACIENTES
        // ==================
        Schema::table('pacientes', function (Blueprint $table) {
            $table->index('nome', 'idx_pac_nome');
            $table->index('cpf', 'idx_pac_cpf');
            $table->index('ativo', 'idx_pac_ativo');
            $table->index('prof_cod', 'idx_pac_prof_cod');

            // Composto: listagem de pacientes ativos ordenados por nome
            $table->index(['ativo', 'nome'], 'idx_pac_ativo_nome');
        });

        // ==================
        // MEDICOS
        // ==================
        Schema::table('medicos', function (Blueprint $table) {
            $table->index('ativo', 'idx_med_ativo');
            $table->index('med_nome', 'idx_med_nome');

            // Composto: listagem de médicos ativos ordenados por nome
            $table->index(['ativo', 'med_nome'], 'idx_med_ativo_nome');
        });

        // ==================
        // ENFERMEIROS
        // ==================
        Schema::table('enfermeiros', function (Blueprint $table) {
            $table->index('ativo', 'idx_enf_ativo');
            $table->index('enf_nome', 'idx_enf_nome');

            // Composto: listagem de enfermeiros ativos ordenados por nome
            $table->index(['ativo', 'enf_nome'], 'idx_enf_ativo_nome');
        });

        // ==================
        // ESPECIALIDADES
        // ==================
        Schema::table('especialidades', function (Blueprint $table) {
            $table->index('ativo', 'idx_esp_ativo');
        });

        // ==================
        // PROFISSAO
        // ==================
        Schema::table('profissao', function (Blueprint $table) {
            $table->index('ativo', 'idx_prof_ativo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atendimentos', function (Blueprint $table) {
            $table->dropIndex('idx_atend_pac_cod');
            $table->dropIndex('idx_atend_med_cod');
            $table->dropIndex('idx_atend_enf_cod');
            $table->dropIndex('idx_atend_esp_cod');
            $table->dropIndex('idx_atend_dt_atendimento');
            $table->dropIndex('idx_atend_dt_pac');
        });

        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropIndex('idx_pac_nome');
            $table->dropIndex('idx_pac_cpf');
            $table->dropIndex('idx_pac_ativo');
            $table->dropIndex('idx_pac_prof_cod');
            $table->dropIndex('idx_pac_ativo_nome');
        });

        Schema::table('medicos', function (Blueprint $table) {
            $table->dropIndex('idx_med_ativo');
            $table->dropIndex('idx_med_nome');
            $table->dropIndex('idx_med_ativo_nome');
        });

        Schema::table('enfermeiros', function (Blueprint $table) {
            $table->dropIndex('idx_enf_ativo');
            $table->dropIndex('idx_enf_nome');
            $table->dropIndex('idx_enf_ativo_nome');
        });

        Schema::table('especialidades', function (Blueprint $table) {
            $table->dropIndex('idx_esp_ativo');
        });

        Schema::table('profissao', function (Blueprint $table) {
            $table->dropIndex('idx_prof_ativo');
        });
    }
};
