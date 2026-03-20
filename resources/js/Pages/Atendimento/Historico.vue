<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps<{
    atendimentos: any;
}>();

const dataLists = computed(() => Array.isArray(props.atendimentos) ? props.atendimentos : (props.atendimentos?.data || []));

const filterForm = useForm({
    nome: '',
    dataAtendimento: ''
});

const submitFilter = () => {
    filterForm.get('/historico', { preserveState: true });
};

const showModal = ref(false);
const activeAtendimento = ref<any>({});

const abrirAtendimento = (atendimento: any) => {
    activeAtendimento.value = atendimento;
    showModal.value = true;
};

const imprimirFicha = (cod: number) => {
    window.open(`/relatorio/${cod}`, '_blank');
};

const imprimirReceita = (cod: number) => {
    window.open(`/receita/${cod}`, '_blank');
};
</script>

<template>
    <Head title="Histórico de Atendimentos" />

    <AppLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Filters -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                    <form @submit.prevent="submitFilter" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome do Paciente</label>
                            <input v-model="filterForm.nome" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Digite o nome">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data do Atendimento</label>
                            <input v-model="filterForm.dataAtendimento" type="date" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                                Filtrar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Visualizar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome do Paciente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data do Atendimento</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Queixa Relatada</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="at in dataLists" :key="at.atend_cod">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <button @click="abrirAtendimento(at)" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded transition">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                        {{ at.paciente?.nome || 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                        {{ at.dt_atendimento }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-900 dark:text-gray-100 truncate max-w-sm">
                                        {{ at.situacao_queixa }}
                                    </td>
                                </tr>
                                <tr v-if="dataLists.length === 0">
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Nenhum registro encontrado.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visualizaiton Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                            <h3 class="text-xl leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Detalhes do Atendimento #{{ activeAtendimento.atend_cod }}
                            </h3>
                            <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Modal Body details -->
                        <div class="space-y-4">
                            <!-- Primeira Linha: Paciente, Data, Ações Impressao -->
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                                <div class="col-span-12 sm:col-span-7">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Paciente</label>
                                    <input type="text" disabled :value="activeAtendimento.paciente?.nome" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                </div>
                                <div class="col-span-12 sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data do Atendimento</label>
                                    <input type="text" disabled :value="activeAtendimento.dt_atendimento" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                </div>
                                <div class="col-span-12 sm:col-span-2 flex flex-col justify-end space-y-2">
                                    <button @click="imprimirFicha(activeAtendimento.atend_cod)" class="bg-green-600 hover:bg-green-700 text-white p-2 rounded shadow w-full flex items-center justify-center gap-2" title="Imprimir Ficha">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        Ficha
                                    </button>
                                    <button @click="imprimirReceita(activeAtendimento.atend_cod)" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded shadow w-full flex items-center justify-center gap-2" title="Imprimir Receita">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Receita
                                    </button>
                                </div>
                            </div>

                            <!-- Segunda Linha: Médico, Especialidade -->
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                                <div class="col-span-12 sm:col-span-8">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Médico(a)</label>
                                    <input type="text" disabled :value="activeAtendimento.medico?.med_nome" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                </div>
                                <div class="col-span-12 sm:col-span-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Especialidade</label>
                                    <input type="text" disabled :value="activeAtendimento.especialidade?.escp_desc" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                </div>
                            </div>

                            <!-- Terceira Linha: Enfermeiro -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Enfermeiro(a)</label>
                                <input type="text" disabled :value="activeAtendimento.enfermeiro?.enf_nome" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            </div>

                            <!-- Quarta Linha: Situação -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Situação/Queixa</label>
                                <input type="text" disabled :value="activeAtendimento.situacao_queixa" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            </div>

                            <!-- Sinais Vitais -->
                            <div class="grid grid-cols-2 md:grid-cols-7 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Pressão</label>
                                    <input type="text" disabled :value="activeAtendimento.mmhg" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-center">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">BPM</label>
                                    <input type="text" disabled :value="activeAtendimento.bpm" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-center">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">RPM</label>
                                    <input type="text" disabled :value="activeAtendimento.rpm" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-center">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">SPO2 (%)</label>
                                    <input type="text" disabled :value="activeAtendimento.spo2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-center">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Temp &deg;C</label>
                                    <input type="text" disabled :value="activeAtendimento.temp" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-center">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Peso (Kg)</label>
                                    <input type="text" disabled :value="activeAtendimento.kg" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-center">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">HGT</label>
                                    <input type="text" disabled :value="activeAtendimento.hgt" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-center">
                                </div>
                            </div>
                            
                            <!-- Descricao -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Descrição do Caso</label>
                                <textarea rows="3" disabled :value="activeAtendimento.descricaoCaso" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300"></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-600">
                        <button type="button" @click="showModal = false" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 sm:w-auto sm:text-sm">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </AppLayout>
</template>
