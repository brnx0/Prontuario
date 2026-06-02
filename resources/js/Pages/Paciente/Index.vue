<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import Swal from 'sweetalert2';

const props = defineProps<{
    query: any;
    medicos: { med_cod: string; med_nome: string }[];
    enfermeiros: { enf_cod: string; enf_nome: string }[];
}>();

const pacientes = computed(() => Array.isArray(props.query) ? props.query : (props.query?.data || []));
const total = computed(() => props.query?.total ?? pacientes.value.length);

const filterForm = useForm({
    nome: '',
    cpf: '',
    nascimentoInicio: '',
    nascimentoFim: '',
    med_cod: '',
});

const hasActiveFilters = computed(() =>
    !!(filterForm.nome || filterForm.cpf || filterForm.nascimentoInicio || filterForm.nascimentoFim || filterForm.med_cod)
);

const submitFilter = () => {
    filterForm.get('/paciente', { preserveState: true });
};

const clearFilter = () => {
    filterForm.nome = '';
    filterForm.cpf = '';
    filterForm.nascimentoInicio = '';
    filterForm.nascimentoFim = '';
    filterForm.med_cod = '';
    filterForm.get('/paciente', { preserveState: true });
};

const exportarLista = () => {
    const params = new URLSearchParams();
    if (filterForm.nome) params.append('nome', filterForm.nome);
    if (filterForm.cpf) params.append('cpf', filterForm.cpf);
    if (filterForm.nascimentoInicio) params.append('nascimentoInicio', filterForm.nascimentoInicio);
    if (filterForm.nascimentoFim) params.append('nascimentoFim', filterForm.nascimentoFim);
    if (filterForm.med_cod) params.append('med_cod', filterForm.med_cod);
    window.open(`/paciente-exportar?${params.toString()}`, '_blank');
};

const exportarFicha = (pac_cod: string) => {
    window.open(`/paciente-ficha/${pac_cod}`, '_blank');
};

const showModal = ref(false);
const activePaciente = ref<any>({});

const abrirModal = (paciente: any) => {
    activePaciente.value = paciente;
    showModal.value = true;
};

const deletePaciente = (id: string) => {
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Você não poderá reverter esta ação!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sim, deletar!',
        cancelButtonText: 'Cancelar',
        background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
        color: document.documentElement.classList.contains('dark') ? '#fff' : '#1f2937',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/paciente/${id}`);
        }
    });
};

const toggleStatus = (id: string, status: string) => {
    router.put('/paciente-status', { pac_cod: id, status });
};

const formatarData = (data: string) => {
    if (!data) return '-';
    const parts = data.split('-');
    return parts.length === 3 ? `${parts[0]}/${parts[1]}/${parts[2]}` : data;
};
</script>

<template>
    <Head title="Pacientes" />

    <AppLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pacientes</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Consulta e gestão dos cadastros de pacientes.
                        </p>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                            {{ total }} paciente{{ total !== 1 ? 's' : '' }}
                        </span>
                        <button @click="exportarLista"
                            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Exportar PDF
                        </button>
                        <button @click="router.visit('/paciente/create')"
                            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow-sm transition-colors">
                            + Novo Paciente
                        </button>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                        </svg>
                        <h2 class="text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Filtros</h2>
                        <span v-if="hasActiveFilters"
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400">
                            Ativo
                        </span>
                    </div>
                    <form @submit.prevent="submitFilter" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Nome</label>
                            <input v-model="filterForm.nome" type="text"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm"
                                placeholder="Nome do paciente..." />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">CPF</label>
                            <input v-model="filterForm.cpf" type="text"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm"
                                placeholder="000.000.000-00" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Médico</label>
                            <select v-model="filterForm.med_cod"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm">
                                <option value="">Todos os médicos</option>
                                <option v-for="m in medicos" :key="m.med_cod" :value="m.med_cod">{{ m.med_nome }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Nascimento Início</label>
                            <input v-model="filterForm.nascimentoInicio" type="date"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Nascimento Fim</label>
                            <input v-model="filterForm.nascimentoFim" type="date"
                                :min="filterForm.nascimentoInicio || undefined"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm" />
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

                <!-- Tabela -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-900/50">
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">N°</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nome</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">CPF</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">Nascimento</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Médico</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-28">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                                <tr v-for="(pac, idx) in pacientes" :key="pac.pac_cod"
                                    class="hover:bg-blue-50/40 dark:hover:bg-blue-900/10 transition-colors">
                                    <td class="px-4 py-3 text-center text-xs text-gray-400 dark:text-gray-500 font-medium">
                                        {{ ((query?.current_page ?? 1) - 1) * (query?.per_page ?? 15) + idx + 1 }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ pac.nome }}</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ pac.cpf || '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ pac.nascimento ? formatarData(pac.nascimento) : '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ pac.medico?.med_nome || '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button v-if="pac.ativo === 'S'"
                                            @click="toggleStatus(pac.pac_cod, 'N')"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 hover:opacity-80 transition-opacity">
                                            Ativo
                                        </button>
                                        <button v-else
                                            @click="toggleStatus(pac.pac_cod, 'S')"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 hover:opacity-80 transition-opacity">
                                            Inativo
                                        </button>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <button @click="abrirModal(pac)"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/40 dark:text-blue-400 transition-colors"
                                                title="Ver dados">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button @click="deletePaciente(pac.pac_cod)"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 dark:text-red-400 transition-colors"
                                                title="Deletar">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="pacientes.length === 0">
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <svg class="w-14 h-14 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 font-medium">Nenhum paciente encontrado</p>
                                            <p class="text-sm text-gray-400 dark:text-gray-500">Tente ajustar os filtros de busca</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="px-5 py-3 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between gap-4">
                        <span class="text-xs text-gray-500 dark:text-gray-400 flex-shrink-0">
                            Página {{ query?.current_page ?? 1 }} de {{ query?.last_page ?? 1 }}
                        </span>
                        <Pagination :links="query.links" />
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal -->
        <Transition enter-active-class="ease-out duration-200" enter-from-class="opacity-0"
            enter-to-class="opacity-100" leave-active-class="ease-in duration-150" leave-from-class="opacity-100"
            leave-to-class="opacity-0">
            <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" @keydown.esc.window="showModal = false">
                <div class="flex items-center justify-center min-h-screen px-4 py-6">
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showModal = false"></div>

                    <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden">

                        <!-- Modal Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <div class="flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <h3 class="text-base font-semibold text-white truncate">
                                        {{ activePaciente.nome }}
                                    </h3>
                                    <p class="text-blue-200 text-xs mt-0.5">
                                        {{ activePaciente.nascimento ? formatarData(activePaciente.nascimento) : '' }}
                                        {{ activePaciente.cpf ? ' · CPF: ' + activePaciente.cpf : '' }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <button @click="router.visit(`/paciente/${activePaciente.pac_cod}`)"
                                        class="inline-flex items-center gap-1.5 bg-white/20 hover:bg-white/30 text-white text-xs font-medium py-1.5 px-3 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Editar
                                    </button>
                                    <button @click="exportarFicha(activePaciente.pac_cod)"
                                        class="inline-flex items-center gap-1.5 bg-white/20 hover:bg-white/30 text-white text-xs font-medium py-1.5 px-3 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Exportar Ficha
                                    </button>
                                    <button @click="showModal = false"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-white/20 hover:bg-white/30 text-white transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-6 space-y-5 max-h-[72vh] overflow-y-auto">

                            <!-- Identificação -->
                            <div>
                                <h4 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Identificação</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <div class="sm:col-span-2 bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Nome</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ activePaciente.nome }}</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Nascimento</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ activePaciente.nascimento ? formatarData(activePaciente.nascimento) : '-' }}</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">CPF</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ activePaciente.cpf || '-' }}</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Cartão SUS</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ activePaciente.cartao_sus || '-' }}</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Profissão</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ activePaciente.profissao || '-' }}</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Filiação 1 (Mãe)</p>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ activePaciente.filicao_1 || '-' }}</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Filiação 2 (Pai)</p>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ activePaciente.filicao_2 || '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Contato -->
                            <div>
                                <h4 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Contato e Endereço</h4>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Telefone 1</p>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ activePaciente.tel_1 || '-' }}</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Telefone 2</p>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ activePaciente.tel_2 || '-' }}</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">E-mail</p>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ activePaciente.email || '-' }}</p>
                                    </div>
                                    <div class="sm:col-span-3 bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Endereço</p>
                                        <p class="text-sm text-gray-900 dark:text-white">
                                            {{ [activePaciente.logradouro, activePaciente.cidade, activePaciente.uf].filter(Boolean).join(', ') || '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Equipe -->
                            <div>
                                <h4 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Equipe Médica</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Médico(a)</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ activePaciente.medico?.med_nome || '-' }}</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Enfermeiro(a)</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ activePaciente.enfermeiro?.enf_nome || '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Dados Clínicos -->
                            <div>
                                <h4 class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Dados Clínicos</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 mb-3">
                                    <div class="sm:col-span-3 bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Situação / Queixa</p>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ activePaciente.situacao || '-' }}</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">CID-10</p>
                                        <p class="text-sm font-mono font-bold text-gray-900 dark:text-white">{{ activePaciente.cid10 || '-' }}</p>
                                    </div>
                                </div>

                                <!-- Sinais Vitais -->
                                <div class="grid grid-cols-4 sm:grid-cols-7 gap-2 mb-3">
                                    <div v-for="sinal in [
                                        { label: 'Pressão', unit: 'mmHg', value: activePaciente.mmhg },
                                        { label: 'FC', unit: 'bpm', value: activePaciente.bpm },
                                        { label: 'FR', unit: 'rpm', value: activePaciente.rpm },
                                        { label: 'SPO2', unit: '%', value: activePaciente.spo2 },
                                        { label: 'Temp', unit: '°C', value: activePaciente.temp },
                                        { label: 'Peso', unit: 'kg', value: activePaciente.kg },
                                        { label: 'HGT', unit: 'mg/dl', value: activePaciente.hgt },
                                    ]" :key="sinal.label"
                                        class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-2.5 text-center">
                                        <p class="text-xs text-blue-500 dark:text-blue-400 font-semibold">{{ sinal.label }}</p>
                                        <p class="text-base font-bold text-gray-900 dark:text-white mt-0.5">{{ sinal.value || '-' }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ sinal.unit }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Alergias</p>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ activePaciente.alergias || 'Não informado' }}</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Medicamentos em Uso</p>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ activePaciente.medicamentos || 'Não informado' }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3 min-h-[80px]">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Descrição do Caso</p>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ activePaciente.descricao_caso || 'Não informado' }}</p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3 min-h-[80px]">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Sintomas</p>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ activePaciente.sintomas || 'Não informado' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-200 dark:border-gray-700 flex justify-end">
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
