<?php

namespace App\Services;

use App\Models\Enfermeiro;
use App\Models\Medico;
use App\Models\Paciente;
use App\Services\AtendimentoService;
use Exception;

class PacienteService
{
    public function getPacientes(array $filtros)
    {
        $query = Paciente::with(['medico', 'enfermeiro']);

        if (!empty($filtros['nome'])) {
            $query->where('nome', 'LIKE', '%' . $filtros['nome'] . '%');
        }
        if (!empty($filtros['cpf'])) {
            $query->where('cpf', '=', $filtros['cpf']);
        }
        if (!empty($filtros['filtroData'])) {
            $query->where('nascimento', '=', $filtros['filtroData']);
        }
        if (!empty($filtros['med_cod'])) {
            $query->where('med_cod', '=', $filtros['med_cod']);
        }
        if (!empty($filtros['nascimentoInicio'])) {
            $query->where('nascimento', '>=', $filtros['nascimentoInicio']);
        }
        if (!empty($filtros['nascimentoFim'])) {
            $query->where('nascimento', '<=', $filtros['nascimentoFim']);
        }

        return $query->orderBy('nome', 'asc')->paginate(15)->withQueryString();
    }

    public function getPacientesParaExportar(array $filtros)
    {
        $query = Paciente::with(['medico', 'enfermeiro']);

        if (!empty($filtros['nome'])) {
            $query->where('nome', 'LIKE', '%' . $filtros['nome'] . '%');
        }
        if (!empty($filtros['cpf'])) {
            $query->where('cpf', '=', $filtros['cpf']);
        }
        if (!empty($filtros['med_cod'])) {
            $query->where('med_cod', '=', $filtros['med_cod']);
        }
        if (!empty($filtros['nascimentoInicio'])) {
            $query->where('nascimento', '>=', $filtros['nascimentoInicio']);
        }
        if (!empty($filtros['nascimentoFim'])) {
            $query->where('nascimento', '<=', $filtros['nascimentoFim']);
        }

        return $query->orderBy('nome', 'asc')->get();
    }

    public function getFichaPaciente(string $id)
    {
        $paciente = Paciente::with(['medico', 'enfermeiro'])->findOrFail($id);

        $nascimento = $paciente->nascimento; // already d-m-Y from accessor
        $idade = null;
        if ($nascimento) {
            try {
                $idade = Carbon::createFromFormat('d-m-Y', $nascimento)->age;
            } catch (\Throwable) {}
        }

        return [
            'pac_cod'       => $paciente->pac_cod,
            'nome'          => $paciente->nome,
            'nascimento'    => $nascimento,
            'idade'         => $idade,
            'cpf'           => $paciente->cpf,
            'cartao_sus'    => $paciente->cartao_sus,
            'profissao'     => $paciente->profissao,
            'filicao_1'     => $paciente->filicao_1,
            'filicao_2'     => $paciente->filicao_2,
            'endereco'      => trim(($paciente->logradouro ?? '') . ', ' . ($paciente->cidade ?? '') . ' - ' . ($paciente->uf ?? ''), ', -'),
            'logradouro'    => $paciente->logradouro,
            'cidade'        => $paciente->cidade,
            'uf'            => $paciente->uf,
            'cep'           => $paciente->cep,
            'tel_1'         => $paciente->tel_1,
            'tel_2'         => $paciente->tel_2,
            'email'         => $paciente->email,
            'med_nome'      => $paciente->medico?->med_nome,
            'enf_nome'      => $paciente->enfermeiro?->enf_nome,
            'situacao'      => $paciente->situacao,
            'cid10'         => $paciente->cid10,
            'mmhg'          => $paciente->mmhg,
            'bpm'           => $paciente->bpm,
            'rpm'           => $paciente->rpm,
            'spo2'          => $paciente->spo2,
            'temp'          => $paciente->temp,
            'kg'            => $paciente->kg,
            'hgt'           => $paciente->hgt,
            'alergias'      => $paciente->alergias,
            'medicamentos'  => $paciente->medicamentos,
            'descricao_caso'=> $paciente->descricao_caso,
            'sintomas'      => $paciente->sintomas,
            'ativo'         => $paciente->ativo,
        ];
    }

    public function buscarDuplicatas(string $nome, string $nascimento, ?string $excluirId = null)
    {
        return Paciente::where('nome', $nome)
            ->where('nascimento', $nascimento)
            ->when($excluirId, fn($q) => $q->where('pac_cod', '!=', $excluirId))
            ->get(['pac_cod', 'nome', 'nascimento', 'cpf']);
    }

    public function getPaciente(string $id)
    {
        return Paciente::findOrFail($id);
    }

    public function getOpcoesFormulario(): array
    {
        return [
            'medicos'     => Medico::where('ativo', 'S')->orderBy('med_nome')->get(['med_cod', 'med_nome']),
            'enfermeiros' => Enfermeiro::where('ativo', 'S')->orderBy('enf_nome')->get(['enf_cod', 'enf_nome']),
        ];
    }

    public function criarPaciente(array $dados)
    {
        AtendimentoService::limparCacheOpcoes();
        return Paciente::create([
            'nome' => $dados['nome'],
            'nascimento' => $dados['nascimento'],
            'cpf' => $dados['cpf'] ?? null,
            'cartao_sus' => $dados['cartao_sus'] ?? null,
            'profissao' => $dados['profissao'] ?? null,
            'filicao_1' => $dados['filicao_1'] ?? null,
            'filicao_2' => $dados['filicao_2'] ?? null,
            'cep' => $dados['cep'] ?? null,
            'logradouro' => $dados['logradouro'] ?? null,
            'cidade' => $dados['cidade'] ?? null,
            'uf' => $dados['uf'] ?? null,
            'tel_1' => $dados['tel_1'] ?? null,
            'tel_2' => $dados['tel_2'] ?? null,
            'email' => $dados['email'] ?? null,
            'situacao' => $dados['situacao'] ?? null,
            'cid10' => $dados['cid10'] ?? null,
            'mmhg' => $dados['mmhg'] ?? null,
            'bpm' => $dados['bpm'] ?? null,
            'rpm' => $dados['rpm'] ?? null,
            'spo2' => $dados['spo2'] ?? null,
            'temp' => $dados['temp'] ?? null,
            'kg' => $dados['kg'] ?? null,
            'hgt' => $dados['hgt'] ?? null,
            'alergias' => $dados['alergias'] ?? null,
            'medicamentos' => $dados['medicamentos'] ?? null,
            'descricao_caso' => $dados['descricao_caso'] ?? null,
            'sintomas' => $dados['sintomas'] ?? null,
            'med_cod' => $dados['med_cod'] ?? null,
            'enf_cod' => $dados['enf_cod'] ?? null,
        ]);
    }

    public function atualizarPaciente(string $id, array $dados)
    {
        AtendimentoService::limparCacheOpcoes();
        $paciente = Paciente::findOrFail($id);
        $paciente->update([
            'nome' => $dados['nome'],
            'nascimento' => $dados['nascimento'],
            'cpf' => $dados['cpf'] ?? null,
            'cartao_sus' => $dados['cartao_sus'] ?? null,
            'profissao' => $dados['profissao'] ?? null,
            'filicao_1' => $dados['filicao_1'] ?? null,
            'filicao_2' => $dados['filicao_2'] ?? null,
            'cep' => $dados['cep'] ?? null,
            'logradouro' => $dados['logradouro'] ?? null,
            'cidade' => $dados['cidade'] ?? null,
            'uf' => $dados['uf'] ?? null,
            'tel_1' => $dados['tel_1'] ?? null,
            'tel_2' => $dados['tel_2'] ?? null,
            'email' => $dados['email'] ?? null,
            'situacao' => $dados['situacao'] ?? null,
            'cid10' => $dados['cid10'] ?? null,
            'mmhg' => $dados['mmhg'] ?? null,
            'bpm' => $dados['bpm'] ?? null,
            'rpm' => $dados['rpm'] ?? null,
            'spo2' => $dados['spo2'] ?? null,
            'temp' => $dados['temp'] ?? null,
            'kg' => $dados['kg'] ?? null,
            'hgt' => $dados['hgt'] ?? null,
            'alergias' => $dados['alergias'] ?? null,
            'medicamentos' => $dados['medicamentos'] ?? null,
            'descricao_caso' => $dados['descricao_caso'] ?? null,
            'sintomas' => $dados['sintomas'] ?? null,
            'med_cod' => $dados['med_cod'] ?? null,
            'enf_cod' => $dados['enf_cod'] ?? null,
        ]);
        return $paciente;
    }

    public function deletarPaciente(string $id)
    {
        AtendimentoService::limparCacheOpcoes();
        return Paciente::destroy($id);
    }

    public function inativarPaciente($pac_cod, $status)
    {
        AtendimentoService::limparCacheOpcoes();
        $paciente = Paciente::findOrFail($pac_cod);
        $paciente->update(['ativo' => $status]);
        return $paciente;
    }
}
