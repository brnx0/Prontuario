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
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <p class="text-gray-500 mb-4">Página de detalhes migrada para Vue.</p>
                    
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

                    <div class="mt-6">
                        <Link href="/atendimento" class="text-indigo-600 hover:text-indigo-900">Voltar para a Lista</Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
