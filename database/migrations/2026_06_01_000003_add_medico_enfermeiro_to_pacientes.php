<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('med_cod')->nullable()->after('sintomas');
            $table->string('enf_cod')->nullable()->after('med_cod');
        });

        // Migrate from most recent atendimento per patient
        $atendimentos = DB::table('atendimentos as a')
            ->join(
                DB::raw('(SELECT pac_cod, MAX(dt_atendimento) as max_dt FROM atendimentos GROUP BY pac_cod) as latest'),
                function ($join) {
                    $join->on('a.pac_cod', '=', 'latest.pac_cod')
                         ->on('a.dt_atendimento', '=', 'latest.max_dt');
                }
            )
            ->select(['a.pac_cod', 'a.med_cod', 'a.enf_cod'])
            ->get();

        foreach ($atendimentos as $atend) {
            DB::table('pacientes')
                ->where('pac_cod', $atend->pac_cod)
                ->update([
                    'med_cod' => $atend->med_cod,
                    'enf_cod' => $atend->enf_cod,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn(['med_cod', 'enf_cod']);
        });
    }
};
