<?php

namespace App\Services;

use App\Models\Especialidade;
use App\Services\AtendimentoService;

class EspecialidadeService
{
    public function getEspecialidades(array $filtros)
    {
        $query = Especialidade::query();
        
        if (!empty($filtros['escp_desc'])) {
           $query->where('escp_desc', 'LIKE', '%' . $filtros['escp_desc'] . '%');
        }
        
        return $query->orderBy('escp_desc', 'asc')->paginate(10)->withQueryString();
    }

    public function criarEspecialidade(array $dados)
    {
        AtendimentoService::limparCacheOpcoes();
        return Especialidade::create([
            'escp_desc' => $dados['descEspc']
        ]);
    }

    public function atualizarEspecialidade(string $id, array $dados)
    {
        AtendimentoService::limparCacheOpcoes();
        $especialidade = Especialidade::findOrFail($id);
        $especialidade->update([
            'escp_desc' => $dados['descEspc']
        ]);
        return $especialidade;
    }

    public function inativarEspecialidade($id, $status)
    {
        AtendimentoService::limparCacheOpcoes();
        $especialidade = Especialidade::findOrFail($id);
        $especialidade->update(['ativo' => $status]);
        return $especialidade;
    }

    public function deletarEspecialidade($id)
    {
        AtendimentoService::limparCacheOpcoes();
        return Especialidade::destroy($id);
    }
}
