<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout>
        <Head title="Início" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                <!-- Hero -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 dark:border-gray-700">
                    <div class="p-8 sm:p-12 text-gray-900 dark:text-gray-100 text-center">
                        <img src="/assets/img/brasao-municipio.png" alt="Brasão" class="w-28 h-28 mx-auto object-contain drop-shadow-lg mb-6 opacity-80" />
                        <h2 class="text-3xl font-extrabold text-teal-600 dark:text-teal-400 mb-3 tracking-tight">Sistema de Prontuário Digital</h2>
                        <p class="text-base text-gray-600 dark:text-gray-300 max-w-xl mx-auto">
                            Bem-vindo(a)! Utilize os atalhos abaixo para acessar as principais funcionalidades.
                        </p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <Link href="/atendimento" class="group bg-white dark:bg-gray-800 shadow-md hover:shadow-lg sm:rounded-xl border border-gray-100 dark:border-gray-700 p-6 flex flex-col items-center text-center transition-all duration-200 hover:border-teal-400 dark:hover:border-teal-500">
                        <div class="w-12 h-12 bg-teal-100 dark:bg-teal-900/40 rounded-full flex items-center justify-center mb-3 group-hover:bg-teal-200 dark:group-hover:bg-teal-900/70 transition-colors">
                            <svg class="w-6 h-6 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-800 dark:text-gray-100 text-sm">Novo Atendimento</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">Registrar consulta</span>
                    </Link>

                    <Link href="/historico" class="group bg-white dark:bg-gray-800 shadow-md hover:shadow-lg sm:rounded-xl border border-gray-100 dark:border-gray-700 p-6 flex flex-col items-center text-center transition-all duration-200 hover:border-blue-400 dark:hover:border-blue-500">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center mb-3 group-hover:bg-blue-200 dark:group-hover:bg-blue-900/70 transition-colors">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-800 dark:text-gray-100 text-sm">Histórico</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">Consultar atendimentos</span>
                    </Link>

                    <Link href="/paciente" class="group bg-white dark:bg-gray-800 shadow-md hover:shadow-lg sm:rounded-xl border border-gray-100 dark:border-gray-700 p-6 flex flex-col items-center text-center transition-all duration-200 hover:border-indigo-400 dark:hover:border-indigo-500">
                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center mb-3 group-hover:bg-indigo-200 dark:group-hover:bg-indigo-900/70 transition-colors">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-800 dark:text-gray-100 text-sm">Pacientes</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">Gerenciar cadastros</span>
                    </Link>

                    <Link href="/dashboard" class="group bg-white dark:bg-gray-800 shadow-md hover:shadow-lg sm:rounded-xl border border-gray-100 dark:border-gray-700 p-6 flex flex-col items-center text-center transition-all duration-200 hover:border-rose-400 dark:hover:border-rose-500">
                        <div class="w-12 h-12 bg-rose-100 dark:bg-rose-900/40 rounded-full flex items-center justify-center mb-3 group-hover:bg-rose-200 dark:group-hover:bg-rose-900/70 transition-colors">
                            <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <span class="font-semibold text-gray-800 dark:text-gray-100 text-sm">Dashboard</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">Métricas e relatórios</span>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
