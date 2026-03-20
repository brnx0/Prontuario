<?php

namespace App\Http\Controllers;

use App\Services\PacienteService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PacienteController extends Controller
{
    protected $pacienteService;

    public function __construct(PacienteService $pacienteService)
    {
        $this->pacienteService = $pacienteService;
    }

    public function index(Request $request)
    {
        $pacientes = $this->pacienteService->getPacientes($request->all());
        return Inertia::render('Paciente/Index', ['query' => $pacientes]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'nascimento' => 'required|date'
        ]);

        try {
            $this->pacienteService->criarPaciente($request->all());
            return back()->with('success', 'Paciente cadastrado com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao cadastrar: ' . $th->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nome' => 'required',
            'nascimento' => 'required|date'
        ]);

        try {
            $this->pacienteService->atualizarPaciente($id, $request->all());
            return back()->with('success', 'Paciente atualizado com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao atualizar: ' . $th->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->pacienteService->deletarPaciente($id);
            return redirect()->back()->with('success','Paciente deletado!');
        } catch (QueryException $th) {
            return redirect()->back()->with('error','Erro ao deletar o registro. '.$th->errorInfo[2]);
        }  
    }

    public function inativarPaciente(Request $request)
    {
        try {
            $this->pacienteService->inativarPaciente($request->pac_cod, $request->status);
            return back()->with('success','Status atualizado com sucesso');
        } catch (QueryException $th) {
            return back()->with('error','Aconteceu um erro, tente novamente em alguns instantes');
        }
    }
}
