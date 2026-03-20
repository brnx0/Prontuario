<?php

namespace App\Services;

use App\Models\Paciente;
use Exception;

class PacienteService
{
    public function getPacientes(array $filtros)
    {
        $query = Paciente::query();
        
        if (!empty($filtros['nome'])) {
           $query->where('nome', 'LIKE', '%' . $filtros['nome'] . '%');
        }
        if (!empty($filtros['cpf'])) {
            $query->where('cpf', '=', $filtros['cpf']);
        }
        if (!empty($filtros['filtroData'])) {
            $query->where('nascimento', '=', $filtros['filtroData']);
        }
        
        return $query->orderBy('nome', 'asc')->paginate(10)->withQueryString();
    }

    public function criarPaciente(array $dados)
    {
        return Paciente::create([
            'nome' => $dados['nome'],
            'nascimento' => $dados['nascimento'],
            'cpf' => $dados['cpf'] ?? null,
            'cartao_sus' => $dados['cartao_sus'] ?? null,
            'filicao_1' => $dados['filicao_1'] ?? null,
            'filicao_2' => $dados['filicao_2'] ?? null,
            'cep' => $dados['cep'] ?? null,
            'logradouro' => $dados['logradouro'] ?? null,
            'cidade' => $dados['cidade'] ?? null,
            'uf' => $dados['uf'] ?? null,
            'tel_1' => $dados['tel_1'] ?? null,
            'tel_2' => $dados['tel_2'] ?? null,
            'email' => $dados['email'] ?? null,
        ]);
    }

    public function atualizarPaciente(string $id, array $dados)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->update([
            'nome' => $dados['nome'],
            'nascimento' => $dados['nascimento'],
            'cpf' => $dados['cpf'] ?? null,
            'cartao_sus' => $dados['cartao_sus'] ?? null,
            'filicao_1' => $dados['filicao_1'] ?? null,
            'filicao_2' => $dados['filicao_2'] ?? null,
            'cep' => $dados['cep'] ?? null,
            'logradouro' => $dados['logradouro'] ?? null,
            'cidade' => $dados['cidade'] ?? null,
            'uf' => $dados['uf'] ?? null,
            'tel_1' => $dados['tel_1'] ?? null,
            'tel_2' => $dados['tel_2'] ?? null,
            'email' => $dados['email'] ?? null,
        ]);
        return $paciente;
    }

    public function deletarPaciente(string $id)
    {
        return Paciente::destroy($id);
    }

    public function inativarPaciente($pac_cod, $status)
    {
        $paciente = Paciente::findOrFail($pac_cod);
        $paciente->update(['ativo' => $status]);
        return $paciente;
    }
}
