<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atendimento;
use App\Models\Medico;
use App\Models\Enfermeiro;
use App\Models\Especialidade;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
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

        $query = Atendimento::query()->from('atendimentos as atend');

        // Aplicar Filtros
        if ($request->filled('start_date')) {
            $query->whereDate('atend.dt_atendimento', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('atend.dt_atendimento', '<=', $request->end_date);
        }
        if ($request->filled('esp_cod')) {
            $query->where('atend.esp_cod', $request->esp_cod);
        }
        if ($request->filled('med_cod')) {
            $query->where('atend.med_cod', $request->med_cod);
        }
        if ($request->filled('enf_cod')) {
            $query->where('atend.enf_cod', $request->enf_cod);
        }

        // Metricas Totais
        $totalAtendimentos = (clone $query)->count();
        $uniquePacientes = (clone $query)->distinct('pac_cod')->count('pac_cod');

        // Media de Atendimentos por Dia
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : (clone $query)->min('dt_atendimento');
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date) : (clone $query)->max('dt_atendimento');

        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::today();
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::today();

        $days = max(1, $startDate->diffInDays($endDate) + 1);
        $avgPorDia = round($totalAtendimentos / $days, 2);

        // Agrupamentos com leftJoin (1 query cada, sem N+1)
        $porEspecialidade = (clone $query)
            ->select('escp_desc as name', DB::raw('count(*) as total'))
            ->leftJoin('especialidades as espec', 'atend.esp_cod', '=', 'espec.esp_cod')
            ->groupBy('espec.escp_desc')
            ->get()
            ->map(fn ($item) => [
                'name' => $item->name ?? 'Sem Especialidade',
                'total' => $item->total,
            ]);

        $porMedico = (clone $query)
            ->select('med.med_nome as name', DB::raw('count(*) as total'))
            ->leftJoin('medicos as med', 'atend.med_cod', '=', 'med.med_cod')
            ->groupBy('med.med_nome')
            ->get()
            ->map(fn ($item) => [
                'name' => $item->name ?? 'Sem Medico',
                'total' => $item->total,
            ]);

        $porEnfermeiro = (clone $query)
            ->select('enf_nome as name', DB::raw('count(*) as total'))
            ->leftJoin('enfermeiros as enf', 'atend.enf_cod', '=', 'enf.enf_cod')
            ->groupBy('enf.enf_nome')
            ->get()
            ->map(fn ($item) => [
                'name' => $item->name ?? 'Sem Enfermeiro',
                'total' => $item->total,
            ]);

        // Cache das opcoes de filtro do dashboard (raramente mudam)
        $options = Cache::remember('dashboard_options', 86400, function () {
            return [
                'medicos' => Medico::select('med_cod as id', 'med_nome as name')->orderBy('med_nome')->get(),
                'enfermeiros' => Enfermeiro::select('enf_cod as id', 'enf_nome as name')->orderBy('enf_nome')->get(),
                'especialidades' => Especialidade::select('esp_cod as id', 'escp_desc as name')->orderBy('escp_desc')->get(),
            ];
        });

        return Inertia::render('Dashboard', [
            'metrics' => [
                'totalAtendimentos' => $totalAtendimentos,
                'uniquePacientes' => $uniquePacientes,
                'avgPorDia' => $avgPorDia,
                'porEspecialidade' => $porEspecialidade,
                'porMedico' => $porMedico,
                'porEnfermeiro' => $porEnfermeiro,
            ],
            'options' => $options,
            'filters' => $request->only(['start_date', 'end_date', 'esp_cod', 'med_cod', 'enf_cod'])
        ]);
    }
}
