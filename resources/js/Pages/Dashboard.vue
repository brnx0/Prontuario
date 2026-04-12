<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

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
    // Clear empty properties
    const query = Object.fromEntries(
        Object.entries(form.value).filter(([_, v]) => v !== '')
    );
    router.get('/dashboard', query, { preserveState: true, replace: true });
};

const clearFilters = () => {
    router.get('/dashboard', {}, { preserveState: true, replace: true });
};

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
                        <button @click="clearFilters" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                            Limpar
                        </button>
                        <button @click="applyFilters" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Filtrar
                        </button>
                    </div>
                </div>

                <!-- Main Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Total -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg text-center p-6 transform transition duration-300 hover:scale-105">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total de Atendimentos</dt>
                        <dd class="mt-2 text-4xl font-extrabold text-indigo-600 dark:text-indigo-400">
                            {{ metrics.totalAtendimentos }}
                        </dd>
                    </div>
                    
                    <!-- Unique Patients -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg text-center p-6 transform transition duration-300 hover:scale-105">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pacientes Únicos</dt>
                        <dd class="mt-2 text-4xl font-extrabold text-teal-600 dark:text-teal-400">
                            {{ metrics.uniquePacientes }}
                        </dd>
                    </div>

                    <!-- Avg per day -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg text-center p-6 transform transition duration-300 hover:scale-105">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Média Diária</dt>
                        <dd class="mt-2 text-4xl font-extrabold text-rose-600 dark:text-rose-400">
                            {{ metrics.avgPorDia }}
                        </dd>
                    </div>
                </div>

                <!-- Grouping Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Especialidade -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Por Especialidade</h3>
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700 h-64 overflow-y-auto pr-2 custom-scrollbar">
                            <li v-for="item in metrics.porEspecialidade" :key="item.name" class="py-3 flex justify-between items-center">
                                <span class="text-gray-700 dark:text-gray-300 text-sm font-medium">{{ item.name }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                    {{ item.total }}
                                </span>
                            </li>
                            <li v-if="metrics.porEspecialidade.length === 0" class="py-3 text-sm text-gray-500">Nenhum dado</li>
                        </ul>
                    </div>

                    <!-- Medico -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Por Médico</h3>
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700 h-64 overflow-y-auto pr-2 custom-scrollbar">
                            <li v-for="item in metrics.porMedico" :key="item.name" class="py-3 flex justify-between items-center">
                                <span class="text-gray-700 dark:text-gray-300 text-sm font-medium">{{ item.name }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-200">
                                    {{ item.total }}
                                </span>
                            </li>
                            <li v-if="metrics.porMedico.length === 0" class="py-3 text-sm text-gray-500">Nenhum dado</li>
                        </ul>
                    </div>

                    <!-- Enfermeiro -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Por Enfermeiro</h3>
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700 h-64 overflow-y-auto pr-2 custom-scrollbar">
                            <li v-for="item in metrics.porEnfermeiro" :key="item.name" class="py-3 flex justify-between items-center">
                                <span class="text-gray-700 dark:text-gray-300 text-sm font-medium">{{ item.name }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800 dark:bg-rose-900 dark:text-rose-200">
                                    {{ item.total }}
                                </span>
                            </li>
                            <li v-if="metrics.porEnfermeiro.length === 0" class="py-3 text-sm text-gray-500">Nenhum dado</li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.5);
    border-radius: 20px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(75, 85, 99, 0.5);
}
</style>
