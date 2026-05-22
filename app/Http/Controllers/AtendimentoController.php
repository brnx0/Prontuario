<?php

namespace App\Http\Controllers;

use App\Services\AtendimentoService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AtendimentoController extends Controller
{
    protected $atendimentoService;

    public function __construct(AtendimentoService $atendimentoService)
    {
        $this->atendimentoService = $atendimentoService;
    }

    /**
     * Exibe o formulário de novo atendimento ou detalhes de um atendimento específico.
     */
    public function index($atend_cod = null)
    {
        try {
            if ($atend_cod) {
                $atendimento = $this->atendimentoService->getAtendimento($atend_cod);
                return Inertia::render("Atendimento/Show", [
                    'atendimento' => $atendimento, 
                    'paciente' => $atendimento->paciente,
                    'medico' => $atendimento->medico,
                    'enfermeiro' =>$atendimento->enfermeiro,
                    'especialidade' => $atendimento->especialidade        
                ]); 
            } else {
                $opcoes = $this->atendimentoService->getOpcoesFormulario();
                return Inertia::render("Atendimento/Index", $opcoes);
            } 
        } catch (\Throwable $th) {
            return abort(404);
        }
    }

    /**
     * Endpoint API para obter dados de um registro de atendimento para exibir.
     */
    public function registroAtendimento($atend_cod)
    {
        try {
            $atendimento = $this->atendimentoService->getRegistroAtendimento($atend_cod);
            return response()->json($atendimento); 
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }   

    /**
     * Salva o novo atendimento e redireciona.
     */
    public function store(Request $request)
    {
        if (empty($request->all())) {
            return redirect()->back()->with("error", "Requisição vazia.");
        }

        $request->validate([
            'pac_cod'       => 'required|string',
            'dtAtendimento' => 'required|date',
            'esp_cod'       => 'required|string|exists:especialidades,esp_cod',
        ], [
            'pac_cod.required'       => 'Paciente é obrigatório.',
            'dtAtendimento.required' => 'Data do atendimento é obrigatória.',
            'esp_cod.required'       => 'Especialidade é obrigatória.',
            'esp_cod.exists'         => 'Especialidade inválida.',
        ]);

        try {
            $atendimento = $this->atendimentoService->criarAtendimento($request->all());
            return redirect('/atendimento/'.$atendimento->atend_cod);
        } catch (Exception $th) {
            return redirect()->back()->with('error', 'Falha ao salvar atendimento: ' . $th->getMessage());
        }
    }

    /**
     * Lista o histórico completo de atendimentos com filtros de nome e data.
     */
    public function historico(Request $request)
    {
        $atendimentos = $this->atendimentoService->getHistoricoFiltrado($request->all());
        $opcoes = $this->atendimentoService->getOpcoesHistorico();
        return Inertia::render('Atendimento/Historico', [
            'atendimentos' => $atendimentos,
            'medicos'       => $opcoes['medicos'],
            'especialidades' => $opcoes['especialidades'],
        ]);
    }

    /**
     * Exporta o histórico filtrado como relatório imprimível (PDF via browser).
     */
    public function exportarHistorico(Request $request)
    {
        $dados = $this->atendimentoService->getHistoricoParaExportar($request->all());
        $opcoes = $this->atendimentoService->getOpcoesHistorico();
        $filtros = $request->only(['nome', 'dataInicio', 'dataFim', 'med_cod', 'esp_cod']);

        if (!empty($filtros['med_cod'])) {
            $medico = collect($opcoes['medicos'])->firstWhere('med_cod', $filtros['med_cod']);
            $filtros['med_nome'] = $medico?->med_nome ?? null;
        }
        if (!empty($filtros['esp_cod'])) {
            $esp = collect($opcoes['especialidades'])->firstWhere('esp_cod', $filtros['esp_cod']);
            $filtros['esp_nome'] = $esp?->escp_desc ?? null;
        }

        return Inertia::render('Reports/HistoricoAtendimentos', [
            'dados'   => $dados,
            'filtros' => $filtros,
        ]);
    }

    /**
     * Carrega a visão de Impressão (Ficha de Atendimento).
     */
    public function gerarfichaAtendimento($atend_cod)
    {
        try {
            $dados = $this->atendimentoService->getFichaAtendimento($atend_cod);
            return Inertia::render('Reports/FichaAtendimento', ['dados' => $dados]);
        } catch (Exception $th) {
            return response()->json(['error' => $th->getMessage() . " Line: " . $th->getLine()], 500);
        }
    }
    
    /**
     * Carrega a visão de Impressão (Receituário).
     */
    public function gerarReceita($atend_cod) 
    {
        try {
            $dados = $this->atendimentoService->getFichaAtendimento($atend_cod);
            return Inertia::render('Reports/Receituario', ['dados' => $dados]);
        } catch (Exception $th) {
            return response()->json(['error' => $th->getMessage() . " Line: " . $th->getLine()], 500);
        }
    }
}
