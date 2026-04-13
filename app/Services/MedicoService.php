<?php

namespace App\Services;

use App\Models\Medico;
use App\Services\AtendimentoService;

class MedicoService
{
    public function getMedicos(array $filtros)
    {
        $query = Medico::query();
        
        if (!empty($filtros['nome'])) {
           $query->where('med_nome', 'LIKE', '%' . $filtros['nome'] . '%');
        }
        if (!empty($filtros['crm'])) {
            $query->where('crm', '=', $filtros['crm']);
        }
        
        return $query->orderBy('med_nome', 'asc')->paginate(10)->withQueryString();
    }

    public function criarMedico(array $dados)
    {
        AtendimentoService::limparCacheOpcoes();
        return Medico::create([
            'med_nome' => $dados['nomeMedico'],
            'crm' => $dados['crmMedico'],
        ]);
    }

    public function atualizarMedico(string $id, array $dados)
    {
        AtendimentoService::limparCacheOpcoes();
        $medico = Medico::findOrFail($id);
        $medico->update([
            'med_nome' => $dados['nomeMedico'],
            'crm' => $dados['crmMedico'],
        ]);
        return $medico;
    }

    public function inativarMedico($id, $status)
    {
        AtendimentoService::limparCacheOpcoes();
        $medico = Medico::findOrFail($id);
        $medico->update(['ativo' => $status]);
        return $medico;
    }

    public function deletarMedico($id)
    {
        AtendimentoService::limparCacheOpcoes();
        return Medico::destroy($id);
    }
}
