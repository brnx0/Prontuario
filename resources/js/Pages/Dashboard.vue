<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Bem-vindo ao Prontuário</h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        O frontend foi migrado com sucesso para Vue.js e Tailwind CSS!
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
