<?php

namespace App\Services;

use App\Models\Enfermeiro;

class EnfermeiroService
{
    public function getEnfermeiros(array $filtros)
    {
        $query = Enfermeiro::query();
        
        if (!empty($filtros['filtroNome'])) {
           $query->where('enf_nome', 'LIKE', '%' . $filtros['filtroNome'] . '%');
        }
        if (!empty($filtros['filtroCRE'])) {
            $query->where('cre', '=', $filtros['filtroCRE']);
        }
        
        return $query->orderBy('enf_nome', 'asc')->paginate(10)->withQueryString();
    }

    public function getEnfermeiro($id)
    {
        return Enfermeiro::findOrFail($id);
    }

    public function criarEnfermeiro(array $dados)
    {
        return Enfermeiro::create([
            'enf_nome' => $dados['nomeEnf'],
            'cre' => $dados['creEnf']
        ]);
    }

    public function atualizarEnfermeiro(string $id, array $dados)
    {
        $enfermeiro = Enfermeiro::findOrFail($id);
        $enfermeiro->update([
            'enf_nome' => $dados['nomeEnf'],
            'cre' => $dados['creEnf']
        ]);
        return $enfermeiro;
    }

    public function inativarEnfermeiro($id, $status)
    {
        $enfermeiro = Enfermeiro::findOrFail($id);
        $enfermeiro->update(['ativo' => $status]);
        return $enfermeiro;
    }

    public function deletarEnfermeiro($id)
    {
        return Enfermeiro::destroy($id);
    }
}
