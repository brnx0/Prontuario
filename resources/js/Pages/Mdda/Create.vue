<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

const props = defineProps<{
    semanaAtual: number;
    anoAtual: number;
    nomeUsuario: string;
}>();

function getStartOfSE1(year: number): Date {
    const jan4 = new Date(year, 0, 4);
    const se1 = new Date(jan4);
    se1.setDate(jan4.getDate() - jan4.getDay());
    return se1;
}

function calcularSE(dateStr: string): { semana: number; ano: number } | null {
    if (!dateStr) return null;
    const [y, m, d] = dateStr.split('-').map(Number);
    const date = new Date(y, m - 1, d);
    const se1 = getStartOfSE1(date.getFullYear());

    if (date < se1) {
        const prevSe1 = getStartOfSE1(date.getFullYear() - 1);
        return { semana: Math.floor((date.getTime() - prevSe1.getTime()) / (7 * 86400000)) + 1, ano: date.getFullYear() - 1 };
    }
    const nextSe1 = getStartOfSE1(date.getFullYear() + 1);
    if (date >= nextSe1) return { semana: 1, ano: date.getFullYear() + 1 };
    return { semana: Math.floor((date.getTime() - se1.getTime()) / (7 * 86400000)) + 1, ano: date.getFullYear() };
}

function formatDate(date: Date): string {
    return date.toLocaleDateString('pt-BR');
}

const dataSemana = ref('');
const seCalculada = ref<{ semana: number; ano: number } | null>(null);

const rangeLabel = computed(() => {
    if (!seCalculada.value) return '';
    const { semana, ano } = seCalculada.value;
    const se1 = getStartOfSE1(ano);
    const inicio = new Date(se1.getTime() + (semana - 1) * 7 * 86400000);
    const fim = new Date(inicio.getTime() + 6 * 86400000);
    return `${formatDate(inicio)} a ${formatDate(fim)}`;
});

watch(dataSemana, (val) => {
    seCalculada.value = calcularSE(val);
    if (seCalculada.value) {
        form.semana_epidemiologica = seCalculada.value.semana;
        form.ano = seCalculada.value.ano;
    }
});

const form = useForm({
    semana_epidemiologica: props.semanaAtual,
    ano: props.anoAtual,
    responsavel_nome: props.nomeUsuario,
});

const submit = () => {
    form.post('/mdda');
};
</script>

<template>
    <Head title="Novo Relatório MDDA" />
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Novo Relatório MDDA
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-8">

                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        Selecione qualquer dia da semana desejada. Os atendimentos serão filtrados pelas especialidades
                        marcadas em <a href="/especialidade" class="text-blue-600 hover:underline">Especialidades</a>.
                    </p>

                    <form @submit.prevent="submit">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Data da semana
                            </label>
                            <input
                                v-model="dataSemana"
                                type="date"
                                required
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm"
                            />
                        </div>

                        <div v-if="seCalculada"
                            class="mb-6 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-md border border-blue-200 dark:border-blue-800">
                            <p class="text-sm font-semibold text-blue-800 dark:text-blue-200">
                                SE {{ seCalculada.semana }} / {{ seCalculada.ano }}
                            </p>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-0.5">{{ rangeLabel }}</p>
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Responsável
                            </label>
                            <input
                                v-model="form.responsavel_nome"
                                type="text"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm"
                            />
                        </div>

                        <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button
                                type="submit"
                                :disabled="form.processing || !seCalculada"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow disabled:opacity-50 transition duration-150"
                            >
                                Iniciar Relatório
                            </button>
                            <a href="/mdda" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
