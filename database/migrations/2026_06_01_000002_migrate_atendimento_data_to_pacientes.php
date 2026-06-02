<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For each patient, get their most recent atendimento and copy clinical data
        $atendimentos = DB::table('atendimentos as a')
            ->join(
                DB::raw('(SELECT pac_cod, MAX(dt_atendimento) as max_dt FROM atendimentos GROUP BY pac_cod) as latest'),
                function ($join) {
                    $join->on('a.pac_cod', '=', 'latest.pac_cod')
                         ->on('a.dt_atendimento', '=', 'latest.max_dt');
                }
            )
            ->select([
                'a.pac_cod',
                'a.situacao_queixa',
                'a.cid10',
                'a.mmhg',
                'a.bpm',
                'a.rpm',
                'a.spo2',
                'a.temp',
                'a.kg',
                'a.hgt',
                'a.desc_caso',
                'a.receituario',
            ])
            ->get();

        foreach ($atendimentos as $atend) {
            DB::table('pacientes')
                ->where('pac_cod', $atend->pac_cod)
                ->update([
                    'situacao'     => $atend->situacao_queixa,
                    'cid10'        => $atend->cid10,
                    'mmhg'         => $atend->mmhg,
                    'bpm'          => $atend->bpm,
                    'rpm'          => $atend->rpm,
                    'spo2'         => $atend->spo2,
                    'temp'         => $atend->temp,
                    'kg'           => $atend->kg,
                    'hgt'          => $atend->hgt,
                    'descricao_caso' => $atend->desc_caso,
                    'sintomas'     => $atend->receituario,
                ]);
        }
    }

    public function down(): void
    {
        // Clear migrated clinical data from pacientes
        DB::table('pacientes')->update([
            'situacao'       => null,
            'cid10'          => null,
            'mmhg'           => null,
            'bpm'            => null,
            'rpm'            => null,
            'spo2'           => null,
            'temp'           => null,
            'kg'             => null,
            'hgt'            => null,
            'descricao_caso' => null,
            'sintomas'       => null,
        ]);
    }
};
