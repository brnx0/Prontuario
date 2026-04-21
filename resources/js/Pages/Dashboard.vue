<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import { Bar } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const props = defineProps<{
    metrics: {
        totalAtendimentos: number;
        uniquePacientes: number;
        avgPorDia: number;
        porEspecialidade: { name: string; total: number }[];
        porMedico: { name: string; total: number }[];
        porEnfermeiro: { name: string; total: number }[];
    };
    options: {
        medicos: { id: string; name: string }[];
        enfermeiros: { id: string; name: string }[];
        especialidades: { id: string; name: string }[];
    };
    filters: {
        start_date?: string;
        end_date?: string;
        esp_cod?: string;
        med_cod?: string;
        enf_cod?: string;
    };
}>();

const form = ref({
    start_date: props.filters.start_date || '',
    end_date: props.filters.end_date || '',
    esp_cod: props.filters.esp_cod || '',
    med_cod: props.filters.med_cod || '',
    enf_cod: props.filters.enf_cod || '',
});

watch(() => props.filters, (newFilters) => {
    form.value = {
        start_date: newFilters.start_date || '',
        end_date: newFilters.end_date || '',
        esp_cod: newFilters.esp_cod || '',
        med_cod: newFilters.med_cod || '',
        enf_cod: newFilters.enf_cod || '',
    };
}, { deep: true });

const applyFilters = () => {
    const query = Object.fromEntries(
        Object.entries(form.value).filter(([_, v]) => v !== '')
    );
    router.get('/dashboard', query, { preserveState: true, replace: true });
};

const clearFilters = () => {
    router.get('/dashboard', {}, { preserveState: true, replace: true });
};

const isDark = computed(() => document.documentElement.classList.contains('dark'));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: { mode: 'index' as const, intersect: false },
    },
    scales: {
        x: { ticks: { maxRotation: 30, font: { size: 11 } } },
        y: { beginAtZero: true, ticks: { stepSize: 1 } },
    },
};

const especialidadeChart = computed(() => ({
    labels: props.metrics.porEspecialidade.map(i => i.name),
    datasets: [{
        label: 'Atendimentos',
        data: props.metrics.porEspecialidade.map(i => i.total),
        backgroundColor: 'rgba(99, 102, 241, 0.7)',
        borderRadius: 4,
    }],
}));

const medicoChart = computed(() => ({
    labels: props.metrics.porMedico.map(i => i.name),
    datasets: [{
        label: 'Atendimentos',
        data: props.metrics.porMedico.map(i => i.total),
        backgroundColor: 'rgba(20, 184, 166, 0.7)',
        borderRadius: 4,
    }],
}));

const enfermeiroChart = computed(() => ({
    labels: props.metrics.porEnfermeiro.map(i => i.name),
    datasets: [{
        label: 'Atendimentos',
        data: props.metrics.porEnfermeiro.map(i => i.total),
        backgroundColor: 'rgba(244, 63, 94, 0.7)',
        borderRadius: 4,
    }],
}));
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Painel de Métricas
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Filters -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Filtros</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data Inicial</label>
                            <input type="date" v-model="form.start_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data Final</label>
                            <input type="date" v-model="form.end_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Especialidade</label>
                            <select v-model="form.esp_cod" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Todas</option>
                                <option v-for="esp in options.especialidades" :key="esp.id" :value="esp.id">{{ esp.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Médico</label>
                            <select v-model="form.med_cod" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Todos</option>
                                <option v-for="med in options.medicos" :key="med.id" :value="med.id">{{ med.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Enfermeiro</label>
                            <select v-model="form.enf_cod" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Todos</option>
                                <option v-for="enf in options.enfermeiros" :key="enf.id" :value="enf.id">{{ enf.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button @click="clearFilters" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none transition ease-in-out duration-150">
                            Limpar
                        </button>
                        <button @click="applyFilters" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none transition ease-in-out duration-150">
                            Filtrar
                        </button>
                    </div>
                </div>

                <!-- KPI Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg text-center p-6 transition duration-300">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total de Atendimentos</dt>
                        <dd class="mt-2 text-4xl font-extrabold text-indigo-600 dark:text-indigo-400">
                            {{ metrics.totalAtendimentos }}
                        </dd>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg text-center p-6 transition duration-300">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pacientes Únicos</dt>
                        <dd class="mt-2 text-4xl font-extrabold text-teal-600 dark:text-teal-400">
                            {{ metrics.uniquePacientes }}
                        </dd>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg text-center p-6 transition duration-300">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Média Diária</dt>
                        <dd class="mt-2 text-4xl font-extrabold text-rose-600 dark:text-rose-400">
                            {{ Number(metrics.avgPorDia).toFixed(1) }}
                        </dd>
                    </div>
                </div>

                <!-- Charts -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Por Especialidade -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Por Especialidade</h3>
                        <div v-if="metrics.porEspecialidade.length > 0" class="h-64">
                            <Bar :data="especialidadeChart" :options="chartOptions" />
                        </div>
                        <p v-else class="py-3 text-sm text-gray-500 text-center">Nenhum dado</p>
                    </div>

                    <!-- Por Médico -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Por Médico</h3>
                        <div v-if="metrics.porMedico.length > 0" class="h-64">
                            <Bar :data="medicoChart" :options="chartOptions" />
                        </div>
                        <p v-else class="py-3 text-sm text-gray-500 text-center">Nenhum dado</p>
                    </div>

                    <!-- Por Enfermeiro -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Por Enfermeiro</h3>
                        <div v-if="metrics.porEnfermeiro.length > 0" class="h-64">
                            <Bar :data="enfermeiroChart" :options="chartOptions" />
                        </div>
                        <p v-else class="py-3 text-sm text-gray-500 text-center">Nenhum dado</p>
                    </div>

                </div>

            </div>
        </div>
    </AppLayout>
</template>
