<?php

namespace App\Services;

use App\Models\Atendimento;
use App\Models\Enfermeiro;
use App\Models\Especialidade;
use App\Models\Medico;
use App\Models\Paciente;
use DateTime;
use Illuminate\Support\Facades\DB;
use Exception;

class AtendimentoService
{
    public function getAtendimento($atend_cod)
    {
        return Atendimento::with(['paciente', 'enfermeiro', 'medico', 'especialidade'])->findOrFail($atend_cod);
    }

    public function getOpcoesFormulario()
    {
        return [
            'enfermeiros' => Enfermeiro::select('enf_cod', 'enf_nome')->where('ativo', 'S')->orderBy('enf_nome', 'asc')->get(),
            'pacientes' => Paciente::select('pac_cod', 'nome')->where('ativo', 'S')->orderBy('nome', 'asc')->get(),
            'medicos' => Medico::select()->where('ativo', 'S')->orderBy('med_nome')->get(),
            'especialidades' => Especialidade::select()->where('ativo', 'S')->orderBy('escp_desc')->get(),
            'data' => (new DateTime())->format('Y-m-d H:i')
        ];
    }

    public function getRegistroAtendimento($atend_cod)
    {
        return Atendimento::select([
            'atendimentos.atend_cod',
            'atendimentos.dt_atendimento',
            'atendimentos.situacao_queixa',
            'atendimentos.mmhg',
            'atendimentos.bpm',
            'atendimentos.rpm',
            'atendimentos.spo2',
            'atendimentos.temp',
            'atendimentos.kg',
            'atendimentos.hgt',
            'pacientes.nome AS paciente_nome',
            'enfermeiros.enf_nome AS enfermeiro_nome',
            'medicos.med_nome AS medico_nome',
            'especialidades.escp_desc AS especialidade_desc'
        ])
        ->leftJoin('pacientes', 'atendimentos.pac_cod', '=', 'pacientes.pac_cod')
        ->leftJoin('enfermeiros', 'atendimentos.enf_cod', '=', 'enfermeiros.enf_cod')
        ->leftJoin('medicos', 'atendimentos.med_cod', '=', 'medicos.med_cod')
        ->leftJoin('especialidades', 'atendimentos.esp_cod', '=', 'especialidades.esp_cod')
        ->where('atendimentos.atend_cod', $atend_cod)
        ->first();
    }

    public function getHistoricoFiltrado(array $filtros)
    {
        $query = Atendimento::with(['paciente', 'enfermeiro', 'medico', 'especialidade'])->orderBy('dt_atendimento', 'desc');
        
        if (!empty($filtros['nome'])) {
            $query->whereHas('paciente', function($q) use ($filtros) {
                $q->where('nome', 'LIKE', '%' . $filtros['nome'] . '%');
            });
        }
        
        if (!empty($filtros['dataAtendimento'])) {
            $query->whereDate('dt_atendimento', $filtros['dataAtendimento']);
        }
        
        return $query->paginate(10);
    }

    public function getFichaAtendimento($atend_cod)
    {
        return Atendimento::select([
            'atendimentos.dt_atendimento',
            'pacientes.nome',
            DB::raw("COALESCE(CONCAT(pacientes.logradouro, ', ', pacientes.cidade, ', ', pacientes.uf), 'Não informado') AS endereco"),
            DB::raw("COALESCE(pacientes.filicao_1, pacientes.filicao_2, 'Não informado') AS filicao"),
            DB::raw("COALESCE(pacientes.cartao_sus, 'Não informado') AS cartao_sus"),
            'pacientes.nascimento',
            DB::raw("TIMESTAMPDIFF(YEAR, pacientes.nascimento, CURDATE()) AS idade"),
            DB::raw("COALESCE(pacientes.tel_1, pacientes.tel_2, 'Não informado') AS tel"),
            'atendimentos.situacao_queixa',
            'atendimentos.mmhg',
            'atendimentos.bpm',
            'atendimentos.rpm',
            'atendimentos.spo2',
            'atendimentos.temp',
            'atendimentos.kg',
            'atendimentos.hgt',
            'atendimentos.desc_caso',
            'atendimentos.receituario'
        ])
        ->leftJoin('pacientes', 'atendimentos.pac_cod', '=', 'pacientes.pac_cod')
        ->leftJoin('enfermeiros', 'atendimentos.enf_cod', '=', 'enfermeiros.enf_cod')
        ->leftJoin('medicos', 'atendimentos.med_cod', '=', 'medicos.med_cod')
        ->leftJoin('especialidades', 'atendimentos.esp_cod', '=', 'especialidades.esp_cod')
        ->where('atendimentos.atend_cod', $atend_cod)
        ->first();
    }

    public function criarAtendimento(array $dados)
    {
        DB::beginTransaction();
        try {
            $atendimento = Atendimento::create([
                'dt_atendimento'  => $dados['dtAtendimento'] ?? null,
                'situacao_queixa' => $dados['situacao'] ?? null,
                'mmhg'            => $dados['mmhg'] ?? null,
                'bpm'             => $dados['bpm'] ?? null,
                'spo2'            => $dados['spo2'] ?? null,
                'temp'            => $dados['temp'] ?? null,
                'rpm'             => $dados['rpm'] ?? null,
                'kg'              => $dados['kg'] ?? null,
                'hgt'             => $dados['hgt'] ?? null,
                'desc_caso'       => $dados['descricaoCaso'] ?? null,
                'enf_cod'         => $dados['enfermeiro'] ?? null,
                'esp_cod'         => $dados['esp_cod'] ?? null,
                'med_cod'         => $dados['med_cod'] ?? null,
                'pac_cod'         => $dados['pac_cod'] ?? null,
                'receituario'     => $dados['receituario'] ?? null
            ]);
            
            DB::commit();
            return $atendimento;
            
        } catch (Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
