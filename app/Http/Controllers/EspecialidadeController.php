<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Services\EspecialidadeService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
            'descEspc' => [
                'required',
                Rule::unique('especialidades', 'escp_desc')->whereNull('deleted_at'),
            ]
        ], [
            'descEspc.unique' => 'Já existe uma especialidade com esta descrição.',
        ]);

        try {
            $this->especialidadeService->criarEspecialidade($request->all());
            Cache::forget('dashboard_options');
            return back()->with('success', 'Especialidade cadastrada com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao cadastrar: ' . $th->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'descEspc' => [
                'required',
                Rule::unique('especialidades', 'escp_desc')->ignore($id, 'esp_cod'),
            ]
        ], [
            'descEspc.unique' => 'Já existe uma especialidade com esta descrição.',
        ]);

        try {
            $this->especialidadeService->atualizarEspecialidade($id, $request->all());
            Cache::forget('dashboard_options');
            return back()->with('success', 'Especialidade atualizada com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao atualizar: ' . $th->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $this->especialidadeService->inativarEspecialidade($request->espc_cod, $request->status);
            Cache::forget('dashboard_options');
            return back()->with('success','Status atualizado com sucesso');
        } catch(QueryException $th) {
            return back()->with('error','Aconteceu um erro, tente novamente em alguns instantes');
        }
    }

    public function updateMdda(Request $request)
    {
        try {
            $this->especialidadeService->toggleMdda($request->espc_cod, $request->incluir_mdda);
            return back()->with('success', 'Configuração MDDA atualizada.');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao atualizar: ' . $th->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $count = Atendimento::where('esp_cod', $request->espc_cod)->count();
        if ($count > 0) {
            return back()->with('error', "Não é possível excluir: esta especialidade está vinculada a {$count} atendimento(s).");
        }

        try {
            $this->especialidadeService->deletarEspecialidade($request->espc_cod);
            Cache::forget('dashboard_options');
            return back()->with('success', 'Registro Excluído com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao excluir: ' . $th->getMessage());
        }
    }
}

