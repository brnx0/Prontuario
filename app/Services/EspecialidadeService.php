<?php

namespace App\Services;

use App\Models\Especialidade;

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
        return Especialidade::create([
            'escp_desc' => $dados['descEspc']
        ]);
    }

    public function atualizarEspecialidade(string $id, array $dados)
    {
        $especialidade = Especialidade::findOrFail($id);
        $especialidade->update([
            'escp_desc' => $dados['descEspc']
        ]);
        return $especialidade;
    }

    public function inativarEspecialidade($id, $status)
    {
        $especialidade = Especialidade::findOrFail($id);
        $especialidade->update(['ativo' => $status]);
        return $especialidade;
    }

    public function deletarEspecialidade($id)
    {
        return Especialidade::destroy($id);
    }
}
