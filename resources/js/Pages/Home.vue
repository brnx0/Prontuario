<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout>
        <Head title="Início" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 dark:border-gray-700">
                    <div class="p-8 sm:p-12 text-gray-900 dark:text-gray-100 text-center">
                        
                        <img src="/assets/img/brasao-municipio.png" alt="Brasão" class="w-32 h-32 mx-auto object-contain drop-shadow-lg mb-8 opacity-80" />
                        
                        <h2 class="text-3xl font-extrabold text-teal-600 dark:text-teal-400 mb-4 tracking-tight">Sistema de Prontuário Digital</h2>
                        
                        <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                            Bem-vindo(a) à página inicial!
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
