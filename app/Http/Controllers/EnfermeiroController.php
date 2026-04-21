<?php

namespace App\Http\Controllers;

use App\Services\EnfermeiroService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\QueryException;
use Inertia\Inertia;

class EnfermeiroController extends Controller
{
    protected $enfermeiroService;

    public function __construct(EnfermeiroService $enfermeiroService)
    {
        $this->enfermeiroService = $enfermeiroService;
    }

    public function index(Request $request) 
    {
        $enfermeiros = $this->enfermeiroService->getEnfermeiros($request->all());
        return Inertia::render('Enfermeiro/Index', [
            'enfermeiros' => $enfermeiros
        ]);
    }

    public function getEnfermeiro($request)    
    {
        try {
            $enfermeiro = $this->enfermeiroService->getEnfermeiro($request);
            return json_encode($enfermeiro);
        } catch(\Exception $th) {
            return json_encode(['error' => $th->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomeEnf' => 'required',
            'creEnf' => 'required'
        ]);

        try {
            $this->enfermeiroService->criarEnfermeiro($request->all());
            Cache::forget('dashboard_options');
            return back()->with('success', 'Enfermeiro(a) cadastrado(a) com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao cadastrar: ' . $th->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nomeEnf' => 'required',
            'creEnf' => 'required'
        ]);

        try {
            $this->enfermeiroService->atualizarEnfermeiro($id, $request->all());
            Cache::forget('dashboard_options');
            return back()->with('success', 'Enfermeiro(a) atualizado(a) com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao atualizar: ' . $th->getMessage());
        }
    }
    
    public function updateStatus(Request $request)    
    {
        try {
            $this->enfermeiroService->inativarEnfermeiro($request->enf_cod, $request->status);
            Cache::forget('dashboard_options');
            return back()->with('success','Status atualizado com sucesso');
        } catch(QueryException $th) {
            return back()->with('error','Erro ao processar');
        }
    }

    public function destroy(Request $request)    
    {
        if(!$request->enf_cod){
            return back()->with('error','Erro: Requisição inválida.');
        }
        try{
            $this->enfermeiroService->deletarEnfermeiro($request->enf_cod);
            Cache::forget('dashboard_options');
            return back()->with('success','Registro deletado.');
        }catch(QueryException $th){
            return back()->with('error', 'Não foi possivel excluir o registro, tente inativar');
        }
    }
}
