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
        $opcoes    = $this->pacienteService->getOpcoesFormulario();
        return Inertia::render('Paciente/Index', array_merge(['query' => $pacientes], $opcoes));
    }

    public function exportarFicha(string $id)
    {
        try {
            $dados = $this->pacienteService->getFichaPaciente($id);
            return Inertia::render('Reports/FichaPaciente', ['dados' => $dados]);
        } catch (\Throwable $th) {
            return abort(404);
        }
    }

    public function exportarHistorico(Request $request)
    {
        $dados   = $this->pacienteService->getPacientesParaExportar($request->all());
        $opcoes  = $this->pacienteService->getOpcoesFormulario();
        $filtros = $request->only(['nome', 'cpf', 'nascimentoInicio', 'nascimentoFim', 'med_cod']);

        if (!empty($filtros['med_cod'])) {
            $medico = collect($opcoes['medicos'])->firstWhere('med_cod', $filtros['med_cod']);
            $filtros['med_nome'] = $medico?->med_nome ?? null;
        }

        return Inertia::render('Reports/HistoricoPacientes', [
            'dados'   => $dados,
            'filtros' => $filtros,
        ]);
    }

    public function verificarDuplicata(Request $request)
    {
        $duplicatas = $this->pacienteService->buscarDuplicatas(
            $request->query('nome'),
            $request->query('nascimento'),
            $request->query('excluir_id')
        );
        return response()->json($duplicatas);
    }

    public function create()
    {
        $opcoes = $this->pacienteService->getOpcoesFormulario();
        return Inertia::render('Paciente/Show', $opcoes);
    }

    public function show(string $id)
    {
        $paciente = $this->pacienteService->getPaciente($id);
        $opcoes   = $this->pacienteService->getOpcoesFormulario();
        return Inertia::render('Paciente/Show', array_merge(['paciente' => $paciente], $opcoes));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'nascimento' => 'required|date'
        ]);

        try {
            $paciente = $this->pacienteService->criarPaciente($request->all());
            return redirect()->route('paciente.show', $paciente->pac_cod)->with('success', 'Paciente cadastrado com sucesso!');
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
