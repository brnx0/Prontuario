<?php

namespace App\Services;

use App\Helpers\EpidemiologicalWeek;
use App\Models\Atendimento;
use App\Models\MddaCaso;
use App\Models\MddaRelatorio;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MddaService
{
    public function findOrInit(int $semana, int $ano): array
    {
        $relatorio = MddaRelatorio::where('semana_epidemiologica', $semana)
            ->where('ano', $ano)
            ->first();

        if ($relatorio) {
            return ['relatorio' => $relatorio, 'isNew' => false];
        }

        $relatorio = MddaRelatorio::create([
            'semana_epidemiologica' => $semana,
            'ano'                  => $ano,
            'municipio'            => '',
            'unidade_saude'        => '',
            'responsavel_nome'     => null,
            'status'               => 'rascunho',
        ]);

        return ['relatorio' => $relatorio, 'isNew' => true];
    }

    public function extrairDaSemana(int $semana, int $ano): Collection
    {
        $range = EpidemiologicalWeek::getRange($semana, $ano);

        return Atendimento::with('paciente')
            ->whereBetween('dt_atendimento', [
                $range['start']->format('Y-m-d H:i:s'),
                $range['end']->format('Y-m-d H:i:s'),
            ])
            ->orderBy('dt_atendimento')
            ->get();
    }

    public function calcularFaixaEtaria(Atendimento $atendimento): array
    {
        $nascimentoRaw = $atendimento->paciente?->getRawOriginal('nascimento');

        if (!$nascimentoRaw) {
            return ['faixa' => 'IGN', 'display' => ''];
        }

        $nascimento    = Carbon::createFromFormat('Y-m-d', $nascimentoRaw)->startOfDay();
        $dataAtendimento = Carbon::parse($atendimento->getRawOriginal('dt_atendimento'))->startOfDay();

        $dias   = $nascimento->diffInDays($dataAtendimento);
        $meses  = $nascimento->diffInMonths($dataAtendimento);
        $anos   = $nascimento->diffInYears($dataAtendimento);

        if ($dias <= 30) {
            return ['faixa' => '<1', 'display' => "{$dias} dias"];
        }

        if ($meses < 12) {
            return ['faixa' => '<1', 'display' => "{$meses} meses"];
        }

        if ($anos < 5) {
            return ['faixa' => '1a4', 'display' => "{$anos} anos"];
        }

        if ($anos < 10) {
            return ['faixa' => '5a9', 'display' => "{$anos} anos"];
        }

        return ['faixa' => '10+', 'display' => "{$anos} anos"];
    }

    public function popularCasos(MddaRelatorio $relatorio): void
    {
        $atendimentos = $this->extrairDaSemana(
            $relatorio->semana_epidemiologica,
            $relatorio->ano
        );

        DB::transaction(function () use ($relatorio, $atendimentos) {
            $relatorio->casos()->delete();

            foreach ($atendimentos as $index => $atendimento) {
                $faixa = $this->calcularFaixaEtaria($atendimento);
                $dtAtend = Carbon::parse($atendimento->getRawOriginal('dt_atendimento'));

                MddaCaso::create([
                    'mdda_relatorio_id'     => $relatorio->id,
                    'atendimento_id'        => $atendimento->atend_cod,
                    'numero_ordem'          => $index + 1,
                    'data_atendimento'      => $dtAtend->format('Y-m-d'),
                    'nome_paciente'         => $atendimento->paciente?->nome ?? '',
                    'procedencia'           => null,
                    'faixa_etaria'          => $faixa['faixa'],
                    'idade_display'         => $faixa['display'],
                    'zona'                  => 'IGN',
                    'data_primeiros_sintomas' => null,
                    'plano_tratamento'      => 'IGN',
                ]);
            }
        });
    }

    public function syncCasos(MddaRelatorio $relatorio, array $casos): void
    {
        DB::transaction(function () use ($relatorio, $casos) {
            $relatorio->casos()->delete();

            foreach ($casos as $index => $caso) {
                MddaCaso::create([
                    'mdda_relatorio_id'       => $relatorio->id,
                    'atendimento_id'          => $caso['atendimento_id'] ?? null,
                    'numero_ordem'            => $index + 1,
                    'data_atendimento'        => $caso['data_atendimento'],
                    'nome_paciente'           => $caso['nome_paciente'],
                    'procedencia'             => $caso['procedencia'] ?? null,
                    'faixa_etaria'            => $caso['faixa_etaria'] ?? 'IGN',
                    'idade_display'           => $caso['idade_display'] ?? '',
                    'zona'                    => $caso['zona'] ?? 'IGN',
                    'data_primeiros_sintomas' => $caso['data_primeiros_sintomas'] ?: null,
                    'plano_tratamento'        => $caso['plano_tratamento'] ?? 'IGN',
                ]);
            }
        });
    }

    public function prepararParaFrontend(MddaRelatorio $relatorio): array
    {
        $relatorio->load('casos');

        return [
            'id'                    => $relatorio->id,
            'semana_epidemiologica' => $relatorio->semana_epidemiologica,
            'ano'                   => $relatorio->ano,
            'municipio'             => $relatorio->municipio,
            'unidade_saude'         => $relatorio->unidade_saude,
            'responsavel_nome'      => $relatorio->responsavel_nome ?? '',
            'status'                => $relatorio->status,
            'casos'                 => $relatorio->casos->map(fn($c) => [
                'id'                      => $c->id,
                'atendimento_id'          => $c->atendimento_id,
                'numero_ordem'            => $c->numero_ordem,
                'data_atendimento'        => $c->data_atendimento,
                'nome_paciente'           => $c->nome_paciente,
                'procedencia'             => $c->procedencia ?? '',
                'faixa_etaria'            => $c->faixa_etaria,
                'idade_display'           => $c->idade_display ?? '',
                'zona'                    => $c->zona,
                'data_primeiros_sintomas' => $c->data_primeiros_sintomas ?? '',
                'plano_tratamento'        => $c->plano_tratamento,
            ])->values(),
        ];
    }
}
