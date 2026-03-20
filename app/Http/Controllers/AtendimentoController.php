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
        return Inertia::render('Atendimento/Historico', [
            'atendimentos' => $atendimentos
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
