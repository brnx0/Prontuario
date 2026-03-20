<?php

namespace App\Http\Controllers;

use App\Services\MedicoService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MedicoController extends Controller
{
    protected $medicoService;

    public function __construct(MedicoService $medicoService)
    {
        $this->medicoService = $medicoService;
    }

    public function index(Request $request) 
    {
        $medicos = $this->medicoService->getMedicos($request->all());
        return Inertia::render('Medico/Index', ['medicos' => $medicos]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomeMedico' => 'required',
            'crmMedico' => 'required'
        ]);

        try {
            $this->medicoService->criarMedico($request->all());
            return back()->with('success', 'Médico cadastrado com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao cadastrar: ' . $th->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nomeMedico' => 'required',
            'crmMedico' => 'required'
        ]);

        try {
            $this->medicoService->atualizarMedico($id, $request->all());
            return back()->with('success', 'Médico atualizado com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao atualizar: ' . $th->getMessage());
        }
    }
    
    public function updateStatus(Request $request)    
    {
       try {
            $this->medicoService->inativarMedico($request->med_cod, $request->status);
            return back()->with('success','Status atualizado com sucesso');
       } catch(QueryException $th) {
            return back()->with('error','Aconteceu um erro, tente novamente em alguns instantes');
       }
    }

    public function destroy(Request $request)    
    {
        if(!$request->med_cod) {
            return back()->with('error','Erro: Requisição inválida.');
        }
        try {
            $this->medicoService->deletarMedico($request->med_cod);
            return back()->with('success','Registro deletado.');
        } catch(QueryException $th) {
            return back()->with('error', 'Não foi possivel excluir o registro, tente inativar');
        }
    }
}
