<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps<{
    atendimentos: any;
    medicos: { med_cod: string; med_nome: string }[];
    especialidades: { esp_cod: string; escp_desc: string }[];
}>();

const dataLists = computed(() =>
    Array.isArray(props.atendimentos)
        ? props.atendimentos
        : props.atendimentos?.data || [],
);

const total = computed(() => props.atendimentos?.total ?? dataLists.value.length);

const filterForm = useForm({
    nome: "",
    dataInicio: "",
    dataFim: "",
    med_cod: "",
    esp_cod: "",
});

const hasActiveFilters = computed(() =>
    !!(filterForm.nome || filterForm.dataInicio || filterForm.dataFim || filterForm.med_cod || filterForm.esp_cod)
);

const submitFilter = () => {
    filterForm.get("/historico", { preserveState: true });
};

const clearFilter = () => {
    filterForm.nome = "";
    filterForm.dataInicio = "";
    filterForm.dataFim = "";
    filterForm.med_cod = "";
    filterForm.esp_cod = "";
    filterForm.get("/historico", { preserveState: true });
};

const exportarPDF = () => {
    const params = new URLSearchParams();
    if (filterForm.nome) params.append("nome", filterForm.nome);
    if (filterForm.dataInicio) params.append("dataInicio", filterForm.dataInicio);
    if (filterForm.dataFim) params.append("dataFim", filterForm.dataFim);
    if (filterForm.med_cod) params.append("med_cod", filterForm.med_cod);
    if (filterForm.esp_cod) params.append("esp_cod", filterForm.esp_cod);
    window.open(`/historico-exportar?${params.toString()}`, "_blank");
};

const showModal = ref(false);
const activeAtendimento = ref<any>({});

const abrirAtendimento = (atendimento: any) => {
    activeAtendimento.value = atendimento;
    showModal.value = true;
};

const imprimirFicha = (cod: string) => {
    window.open(`/relatorio/${cod}`, "_blank");
};

const imprimirReceita = (cod: string) => {
    window.open(`/receita/${cod}`, "_blank");
};

const formatDate = (dt: string) => {
    if (!dt) return "-";
    const d = new Date(dt.replace(" ", "T"));
    if (isNaN(d.getTime())) return dt;
    return (
        d.toLocaleDateString("pt-BR") +
        " " +
        d.toLocaleTimeString("pt-BR", { hour: "2-digit", minute: "2-digit" })
    );
};
</script>

<template>
    <Head title="Histórico de Atendimentos" />

    <AppLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Histórico de Atendimentos</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Visualização somente leitura dos registros de atendimento.
                        </p>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                            {{ total }} registro{{ total !== 1 ? 's' : '' }}
                        </span>
                        <button @click="exportarPDF"
                            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Exportar PDF
                        </button>
                    </div>
                </div>

                <!-- Filters -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                        </svg>
                        <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Filtros
                        </h2>
                        <span v-if="hasActiveFilters"
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400">
                            Ativo
                        </span>
                    </div>
                    <form @submit.prevent="submitFilter"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="lg:col-span-2">
                            <label
                                class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Paciente</label>
                            <input v-model="filterForm.nome" type="text"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Nome do paciente..." />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Data
                                Início</label>
                            <input v-model="filterForm.dataInicio" type="date"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Data
                                Fim</label>
                            <input v-model="filterForm.dataFim" type="date"
                                :min="filterForm.dataInicio || undefined"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Médico</label>
                            <select v-model="filterForm.med_cod"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todos os médicos</option>
                                <option v-for="m in medicos" :key="m.med_cod" :value="m.med_cod">{{ m.med_nome }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Especialidade</label>
                            <select v-model="filterForm.esp_cod"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todas as especialidades</option>
                                <option v-for="e in especialidades" :key="e.esp_cod" :value="e.esp_cod">{{
                                    e.escp_desc }}</option>
                            </select>
                        </div>
                        <div class="lg:col-span-2 flex gap-2 items-end">
                            <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow-sm transition-colors">
                                Filtrar
                            </button>
                            <button type="button" @click="clearFilter"
                                class="flex-1 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold py-2 px-4 rounded-lg transition-colors">
                                Limpar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-900/50">
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">
                                        N°</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Paciente</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                        Data</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Médico</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Especialidade</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Queixa</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-20">
                                        Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                                <tr v-for="(at, idx) in dataLists" :key="at.atend_cod"
                                    class="hover:bg-blue-50/40 dark:hover:bg-blue-900/10 transition-colors">
                                    <td
                                        class="px-4 py-3 text-center text-xs text-gray-400 dark:text-gray-500 font-medium">
                                        {{ ((atendimentos?.current_page ?? 1) - 1) * (atendimentos?.per_page ?? 10) + idx + 1 }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ at.paciente?.nome || "N/A" }}</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ formatDate(at.dt_atendimento) }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ at.medico?.med_nome || '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span v-if="at.especialidade?.escp_desc"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                            {{ at.especialidade.escp_desc }}
                                        </span>
                                        <span v-else class="text-xs text-gray-400">-</span>
                                    </td>
                                    <td class="px-4 py-3 max-w-xs">
                                        <span
                                            class="text-sm text-gray-600 dark:text-gray-400 line-clamp-1">{{ at.situacao_queixa || '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button @click="abrirAtendimento(at)"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/40 dark:text-blue-400 transition-colors"
                                            title="Ver detalhes">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="dataLists.length === 0">
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <svg class="w-14 h-14 text-gray-300 dark:text-gray-600" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 font-medium">Nenhum registro
                                                encontrado</p>
                                            <p class="text-sm text-gray-400 dark:text-gray-500">Tente ajustar os
                                                filtros de busca</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div
                        class="px-5 py-3 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between gap-4">
                        <span class="text-xs text-gray-500 dark:text-gray-400 flex-shrink-0">
                            Página {{ atendimentos?.current_page ?? 1 }} de {{ atendimentos?.last_page ?? 1 }}
                        </span>
                        <Pagination :links="atendimentos.links" />
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal -->
        <Transition enter-active-class="ease-out duration-200" enter-from-class="opacity-0"
            enter-to-class="opacity-100" leave-active-class="ease-in duration-150" leave-from-class="opacity-100"
            leave-to-class="opacity-0">
            <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto"
                @keydown.esc.window="showModal = false">
                <div class="flex items-center justify-center min-h-screen px-4 py-6">
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showModal = false"></div>

                    <div
                        class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden">

                        <!-- Modal Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <div class="flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <h3 class="text-base font-semibold text-white truncate">
                                        {{ activeAtendimento.paciente?.nome || 'Detalhes do Atendimento' }}
                                    </h3>
                                    <p class="text-blue-200 text-xs mt-0.5">{{ formatDate(activeAtendimento.dt_atendimento) }}</p>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <button @click="imprimirFicha(activeAtendimento.atend_cod)"
                                        class="inline-flex items-center gap-1.5 bg-white/20 hover:bg-white/30 text-white text-xs font-medium py-1.5 px-3 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Ficha
                                    </button>
                                    <button @click="imprimirReceita(activeAtendimento.atend_cod)"
                                        class="inline-flex items-center gap-1.5 bg-white/20 hover:bg-white/30 text-white text-xs font-medium py-1.5 px-3 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Receita
                                    </button>
                                    <button @click="showModal = false"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-white/20 hover:bg-white/30 text-white transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-6 space-y-5 max-h-[72vh] overflow-y-auto">

                            <!-- Equipe Médica -->
                            <div>
                                <h4
                                    class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">
                                    Equipe Médica</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Médico(a)</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{
                                            activeAtendimento.medico?.med_nome || '-' }}</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Especialidade</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{
                                            activeAtendimento.especialidade?.escp_desc || '-' }}</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Enfermeiro(a)</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{
                                            activeAtendimento.enfermeiro?.enf_nome || '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Clínico -->
                            <div>
                                <h4
                                    class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">
                                    Informações Clínicas</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                                    <div class="sm:col-span-3 bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Situação / Queixa</p>
                                        <p class="text-sm text-gray-900 dark:text-white">{{
                                            activeAtendimento.situacao_queixa || '-' }}</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">CID-10</p>
                                        <p class="text-sm font-mono font-bold text-gray-900 dark:text-white">{{
                                            activeAtendimento.cid10 || '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Sinais Vitais -->
                            <div>
                                <h4
                                    class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">
                                    Sinais Vitais</h4>
                                <div class="grid grid-cols-4 sm:grid-cols-7 gap-2">
                                    <div v-for="sinal in [
                                        { label: 'Pressão', unit: 'mmHg', value: activeAtendimento.mmhg },
                                        { label: 'FC', unit: 'bpm', value: activeAtendimento.bpm },
                                        { label: 'FR', unit: 'rpm', value: activeAtendimento.rpm },
                                        { label: 'SPO2', unit: '%', value: activeAtendimento.spo2 },
                                        { label: 'Temp', unit: '°C', value: activeAtendimento.temp },
                                        { label: 'Peso', unit: 'kg', value: activeAtendimento.kg },
                                        { label: 'HGT', unit: 'mg/dl', value: activeAtendimento.hgt },
                                    ]" :key="sinal.label"
                                        class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-2.5 text-center">
                                        <p class="text-xs text-blue-500 dark:text-blue-400 font-semibold">{{
                                            sinal.label }}</p>
                                        <p class="text-base font-bold text-gray-900 dark:text-white mt-0.5">{{
                                            sinal.value || '-' }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ sinal.unit }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Descrição e Receituário -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <h4
                                        class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                                        Descrição do Caso</h4>
                                    <div
                                        class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3 min-h-[80px]">
                                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{
                                            activeAtendimento.desc_caso || 'Não informado' }}</p>
                                    </div>
                                </div>
                                <div>
                                    <h4
                                        class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">
                                        Receituário</h4>
                                    <div
                                        class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3 min-h-[80px]">
                                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{
                                            activeAtendimento.receituario || 'Não informado' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div
                            class="px-6 py-4 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                            <button @click="showModal = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                Fechar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>

    </AppLayout>
</template>
