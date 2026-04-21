<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    atendimento: any;
    paciente: any;
    medico: any;
    enfermeiro: any;
    especialidade: any;
}>();

const imprimirFicha = (cod: number) => {
    window.open(`/relatorio/${cod}`, "_blank");
};

const imprimirReceita = (cod: number) => {
    window.open(`/receita/${cod}`, "_blank");
};

</script>

<template>

    <Head title="Detalhes do Atendimento" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detalhes do Atendimento #{{ atendimento?.atend_cod }}
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

                    <!-- Barra de ações -->
                    <div
                        class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                        <Link href="/historico"
                            class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            Voltar para a Lista
                        </Link>

                        <div class="flex items-center gap-3">
                            <button @click="imprimirFicha(atendimento?.atend_cod)"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:scale-95 shadow-sm shadow-emerald-500/30 transition-all duration-150"
                                title="Imprimir Ficha de Atendimento">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Imprimir Ficha
                            </button>

                            <button @click="imprimirReceita(atendimento?.atend_cod)"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 active:scale-95 shadow-sm shadow-blue-500/30 transition-all duration-150"
                                title="Imprimir Receita Médica">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Imprimir Receita
                            </button>
                        </div>
                    </div>

                    <!-- Conteúdo -->
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h3 class="font-bold text-gray-700 dark:text-gray-300">Paciente</h3>
                                <p class="text-gray-600 dark:text-gray-400">{{ paciente?.nome }}</p>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-700 dark:text-gray-300">Médico</h3>
                                <p class="text-gray-600 dark:text-gray-400">{{ medico?.med_nome }}</p>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-700 dark:text-gray-300">Enfermeiro</h3>
                                <p class="text-gray-600 dark:text-gray-400">{{ enfermeiro?.enf_nome }}</p>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-700 dark:text-gray-300">Data</h3>
                                <p class="text-gray-600 dark:text-gray-400">{{ atendimento?.dt_atendimento }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
