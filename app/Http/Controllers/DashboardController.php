<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atendimento;
use App\Models\Medico;
use App\Models\Enfermeiro;
use App\Models\Especialidade;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Se for o acesso inicial sem parâmetros (pelo menu), definimos o filtro para hoje
        if (empty($request->query())) {
            $request->merge([
                'start_date' => Carbon::today()->toDateString(),
                'end_date' => Carbon::today()->toDateString(),
            ]);
        }

        $query = Atendimento::query();

        // Aplicar Filtros
        if ($request->filled('start_date')) {
            $query->whereDate('dt_atendimento', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('dt_atendimento', '<=', $request->end_date);
        }
        if ($request->filled('esp_cod')) {
            $query->where('esp_cod', $request->esp_cod);
        }
        if ($request->filled('med_cod')) {
            $query->where('med_cod', $request->med_cod);
        }
        if ($request->filled('enf_cod')) {
            $query->where('enf_cod', $request->enf_cod);
        }

        // Métricas Totais
        $totalAtendimentos = (clone $query)->count();
        $uniquePacientes = (clone $query)->distinct('pac_cod')->count('pac_cod');

        // Média de Atendimentos por Dia
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : (clone $query)->min('dt_atendimento');
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date) : (clone $query)->max('dt_atendimento');
        
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::today();
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::today();
        
        $days = max(1, $startDate->diffInDays($endDate) + 1); // +1 because same day = 1 day of work
        $avgPorDia = round($totalAtendimentos / $days, 2);

        // Agrupamentos com Eager Loading para nomes
        $porEspecialidade = (clone $query)
            ->with('especialidade')
            ->select('esp_cod', DB::raw('count(*) as total'))
            ->groupBy('esp_cod')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->especialidade ? $item->especialidade->escp_desc : 'Sem Especialidade',
                    'total' => $item->total
                ];
            });

        $porMedico = (clone $query)
            ->with('medico')
            ->select('med_cod', DB::raw('count(*) as total'))
            ->groupBy('med_cod')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->medico ? $item->medico->med_nome : 'Sem Médico',
                    'total' => $item->total
                ];
            });

        $porEnfermeiro = (clone $query)
            ->with('enfermeiro')
            ->select('enf_cod', DB::raw('count(*) as total'))
            ->groupBy('enf_cod')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->enfermeiro ? $item->enfermeiro->enf_nome : 'Sem Enfermeiro',
                    'total' => $item->total
                ];
            });

        $medicos = Medico::select('med_cod as id', 'med_nome as name')->get();
        $enfermeiros = Enfermeiro::select('enf_cod as id', 'enf_nome as name')->get();
        $especialidades = Especialidade::select('esp_cod as id', 'escp_desc as name')->get();

        return Inertia::render('Dashboard', [
            'metrics' => [
                'totalAtendimentos' => $totalAtendimentos,
                'uniquePacientes' => $uniquePacientes,
                'avgPorDia' => $avgPorDia,
                'porEspecialidade' => $porEspecialidade, // list of obj: {name, total}
                'porMedico' => $porMedico,
                'porEnfermeiro' => $porEnfermeiro,
            ],
            'options' => [
                'medicos' => $medicos,
                'enfermeiros' => $enfermeiros,
                'especialidades' => $especialidades,
            ],
            'filters' => $request->only(['start_date', 'end_date', 'esp_cod', 'med_cod', 'enf_cod'])
        ]);
    }
}
