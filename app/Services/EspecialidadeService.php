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
            'escp_desc'    => $dados['descEspc'],
            'incluir_mdda' => $dados['incluirMdda'] ?? 'N',
        ]);
    }

    public function atualizarEspecialidade(string $id, array $dados)
    {
        AtendimentoService::limparCacheOpcoes();
        $especialidade = Especialidade::findOrFail($id);
        $especialidade->update([
            'escp_desc'    => $dados['descEspc'],
            'incluir_mdda' => $dados['incluirMdda'] ?? $especialidade->incluir_mdda,
        ]);
        return $especialidade;
    }

    public function toggleMdda(string $id, string $valor): void
    {
        Especialidade::findOrFail($id)->update(['incluir_mdda' => $valor]);
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
