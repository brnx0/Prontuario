<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

interface Relatorio {
    id: string;
    semana_epidemiologica: number;
    ano: number;
    municipio: string;
    unidade_saude: string;
    responsavel_nome: string | null;
    status: string;
    created_at: string;
}

interface Paginate<T> {
    data: T[];
    current_page: number;
    last_page: number;
    prev_page_url: string | null;
    next_page_url: string | null;
}

defineProps<{ relatorios: Paginate<Relatorio> }>();
</script>

<template>
    <Head title="Relatórios MDDA" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    MDDA — Monitorização das Doenças Diarreicas Agudas
                </h2>
                <a href="/mdda/novo"
                    class="ml-3 bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-5 rounded shadow transition">
                    + Novo Relatório
                </a>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg overflow-hidden">

                    <div v-if="relatorios.data.length === 0" class="text-center py-16 text-gray-400 dark:text-gray-500">
                        Nenhum relatório cadastrado ainda.
                    </div>

                    <table v-else class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wide">
                            <tr>
                                <th class="px-6 py-3 text-left">SE</th>
                                <th class="px-6 py-3 text-left">Ano</th>
                                <th class="px-6 py-3 text-left">Município</th>
                                <th class="px-6 py-3 text-left">Unidade de Saúde</th>
                                <th class="px-6 py-3 text-left">Responsável</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="rel in relatorios.data" :key="rel.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-6 py-3 font-semibold">{{ rel.semana_epidemiologica }}</td>
                                <td class="px-6 py-3">{{ rel.ano }}</td>
                                <td class="px-6 py-3">{{ rel.municipio || '—' }}</td>
                                <td class="px-6 py-3">{{ rel.unidade_saude || '—' }}</td>
                                <td class="px-6 py-3 text-gray-500">{{ rel.responsavel_nome || '—' }}</td>
                                <td class="px-6 py-3">
                                    <span
                                        :class="rel.status === 'finalizado'
                                            ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100'
                                            : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100'"
                                        class="text-xs font-semibold px-2.5 py-0.5 rounded-full uppercase">
                                        {{ rel.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right space-x-3">
                                    <a :href="`/mdda/${rel.id}/editar`"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 font-medium text-xs">
                                        Editar
                                    </a>
                                    <a :href="`/mdda/${rel.id}/imprimir`" target="_blank"
                                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 font-medium text-xs">
                                        Imprimir
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Paginação -->
                    <div v-if="relatorios.last_page > 1" class="flex justify-center gap-4 p-4 border-t border-gray-200 dark:border-gray-700">
                        <a v-if="relatorios.prev_page_url" :href="relatorios.prev_page_url"
                            class="text-sm text-blue-600 hover:underline">Anterior</a>
                        <span class="text-sm text-gray-500">
                            Página {{ relatorios.current_page }} de {{ relatorios.last_page }}
                        </span>
                        <a v-if="relatorios.next_page_url" :href="relatorios.next_page_url"
                            class="text-sm text-blue-600 hover:underline">Próxima</a>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
