<?php

namespace App\Http\Controllers;

use App\Services\EspecialidadeService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EspecialidadeController extends Controller
{
    protected $especialidadeService;

    public function __construct(EspecialidadeService $especialidadeService)
    {
        $this->especialidadeService = $especialidadeService;
    }

    public function index(Request $request) 
    {
        $especialidades = $this->especialidadeService->getEspecialidades($request->all());
        return Inertia::render('Especialidade/Index', ['especialidades' => $especialidades]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'descEspc' => 'required'
        ]);

        try {
            $this->especialidadeService->criarEspecialidade($request->all());
            return back()->with('success', 'Especialidade cadastrada com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao cadastrar: ' . $th->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'descEspc' => 'required'
        ]);

        try {
            $this->especialidadeService->atualizarEspecialidade($id, $request->all());
            return back()->with('success', 'Especialidade atualizada com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao atualizar: ' . $th->getMessage());
        }
    }

    public function updateStatus(Request $request)    
    {
        try {
             $this->especialidadeService->inativarEspecialidade($request->espc_cod, $request->status);
             return back()->with('success','Status atualizado com sucesso');
        } catch(QueryException $th) {
             return back()->with('error','Aconteceu um erro, tente novamente em alguns instantes');
        }
    }

    public function destroy(Request $request) 
    {
        try {
            $this->especialidadeService->deletarEspecialidade($request->espc_cod);
            return back()->with('success', 'Registro Excluído com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}

