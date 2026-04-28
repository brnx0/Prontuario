<?php

namespace App\Http\Controllers;

use App\Helpers\EpidemiologicalWeek;
use App\Models\MddaRelatorio;
use App\Services\MddaService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MddaController extends Controller
{
    public function __construct(protected MddaService $mddaService) {}

    public function index()
    {
        $relatorios = MddaRelatorio::orderByDesc('ano')
            ->orderByDesc('semana_epidemiologica')
            ->paginate(15);

        return Inertia::render('Mdda/Index', [
            'relatorios' => $relatorios,
        ]);
    }

    public function create()
    {
        return Inertia::render('Mdda/Create', [
            'semanaAtual'   => EpidemiologicalWeek::getCurrent(),
            'anoAtual'      => EpidemiologicalWeek::getCurrentYear(),
            'nomeUsuario'   => auth()->user()?->name ?? '',
        ]);
    }

    private const MUNICIPIO     = 'PREFEITURA MUNICIPAL DE SANTO AMARO';
    private const UNIDADE_SAUDE = 'POLICLÍNICA MUNICIPAL REGIS PACHECO';

    public function store(Request $request)
    {
        $request->validate([
            'semana_epidemiologica' => 'required|integer|min:1|max:53',
            'ano'                   => 'required|integer|min:2000|max:2100',
            'responsavel_nome'      => 'nullable|string|max:255',
        ]);

        try {
            $result = $this->mddaService->findOrInit(
                (int) $request->semana_epidemiologica,
                (int) $request->ano
            );

            $relatorio = $result['relatorio'];

            if ($result['isNew']) {
                $relatorio->update([
                    'municipio'        => self::MUNICIPIO,
                    'unidade_saude'    => self::UNIDADE_SAUDE,
                    'responsavel_nome' => $request->responsavel_nome,
                ]);

                $this->mddaService->popularCasos($relatorio);
            }

            return redirect("/mdda/{$relatorio->id}/editar")
                ->with($result['isNew'] ? 'success' : 'info',
                    $result['isNew']
                        ? 'Relatório criado com os atendimentos da semana.'
                        : 'Já existe um relatório para esta semana. Redirecionado para edição.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao criar relatório: ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        try {
            $relatorio = MddaRelatorio::findOrFail($id);
            $dados = $this->mddaService->prepararParaFrontend($relatorio);

            return Inertia::render('Mdda/Form', [
                'relatorio' => $dados,
            ]);
        } catch (Exception $e) {
            return abort(404);
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'semana_epidemiologica'    => 'required|integer|min:1|max:53',
            'ano'                      => 'required|integer|min:2000|max:2100',
            'responsavel_nome'         => 'nullable|string|max:255',
            'casos'                    => 'required|array',
            'casos.*.nome_paciente'    => 'required|string|max:255',
            'casos.*.data_atendimento' => 'required|date',
        ]);

        try {
            $relatorio = MddaRelatorio::findOrFail($id);

            $relatorio->update([
                'semana_epidemiologica' => $request->semana_epidemiologica,
                'ano'                   => $request->ano,
                'responsavel_nome'      => $request->responsavel_nome,
            ]);

            $this->mddaService->syncCasos($relatorio, $request->casos);

            return redirect("/mdda/{$relatorio->id}/editar")->with('success', 'Relatório salvo.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao salvar: ' . $e->getMessage());
        }
    }

    public function finalizar(string $id)
    {
        try {
            $relatorio = MddaRelatorio::findOrFail($id);
            $relatorio->update(['status' => 'finalizado']);

            return redirect("/mdda/{$relatorio->id}/editar")->with('success', 'Relatório finalizado.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao finalizar: ' . $e->getMessage());
        }
    }

    public function print(string $id)
    {
        try {
            $relatorio = MddaRelatorio::findOrFail($id);
            $dados = $this->mddaService->prepararParaFrontend($relatorio);

            return Inertia::render('Mdda/Print', [
                'relatorio' => $dados,
            ]);
        } catch (Exception $e) {
            return abort(404);
        }
    }
}
