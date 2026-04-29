<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

interface MddaCaso {
    id: string | null;
    atendimento_id: string | null;
    numero_ordem: number;
    data_atendimento: string;
    nome_paciente: string;
    procedencia: string;
    faixa_etaria: string;
    idade_display: string;
    zona: string;
    data_primeiros_sintomas: string;
    plano_tratamento: string;
}

interface MddaRelatorio {
    id: string;
    semana_epidemiologica: number;
    ano: number;
    municipio: string;
    unidade_saude: string;
    responsavel_nome: string;
    status: string;
    casos: MddaCaso[];
}

const props = defineProps<{ relatorio: MddaRelatorio }>();

// ── Cálculo de Semana Epidemiológica ──────────────────────────────────────────
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

function seToDateStr(semana: number, ano: number): string {
    const se1 = getStartOfSE1(ano);
    const inicio = new Date(se1.getTime() + (semana - 1) * 7 * 86400000);
    return inicio.toISOString().split('T')[0];
}

function formatDate(date: Date): string {
    return date.toLocaleDateString('pt-BR');
}

// Inicializa o datepicker com o primeiro dia da SE atual do relatório
const dataSemana = ref(seToDateStr(props.relatorio.semana_epidemiologica, props.relatorio.ano));

const rangeLabel = computed(() => {
    const se = calcularSE(dataSemana.value);
    if (!se) return '';
    const se1 = getStartOfSE1(se.ano);
    const inicio = new Date(se1.getTime() + (se.semana - 1) * 7 * 86400000);
    const fim = new Date(inicio.getTime() + 6 * 86400000);
    return `${formatDate(inicio)} a ${formatDate(fim)}`;
});
// ─────────────────────────────────────────────────────────────────────────────

const form = useForm({
    semana_epidemiologica: props.relatorio.semana_epidemiologica,
    ano:                   props.relatorio.ano,
    responsavel_nome:      props.relatorio.responsavel_nome,
    casos:                 props.relatorio.casos.map(c => ({ ...c })),
});

watch(dataSemana, (val) => {
    const se = calcularSE(val);
    if (se) {
        form.semana_epidemiologica = se.semana;
        form.ano = se.ano;
    }
});

// Atualiza o formulário quando o Inertia trouxer novos dados do servidor
// (ex: após sincronização de atendimentos)
watch(() => props.relatorio, (novo) => {
    form.semana_epidemiologica = novo.semana_epidemiologica;
    form.ano = novo.ano;
    form.responsavel_nome = novo.responsavel_nome;
    form.casos = novo.casos.map(c => ({ ...c }));
    dataSemana.value = seToDateStr(novo.semana_epidemiologica, novo.ano);
}, { deep: true });

const addLinha = () => {
    form.casos.push({
        id: null, atendimento_id: null,
        numero_ordem: form.casos.length + 1,
        data_atendimento: '', nome_paciente: '', procedencia: '',
        faixa_etaria: 'IGN', idade_display: '', zona: 'IGN',
        data_primeiros_sintomas: '', plano_tratamento: 'IGN',
    });
};

const removeLinha = (index: number) => {
    form.casos.splice(index, 1);
    form.casos.forEach((c: any, i: number) => { c.numero_ordem = i + 1; });
};

const errosValidacao = ref<string[]>([]);

const salvar = () => {
    errosValidacao.value = [];
    form.casos.forEach((caso: MddaCaso, i: number) => {
        if (!caso.data_atendimento) errosValidacao.value.push(`Linha ${i + 1}: data de atendimento é obrigatória.`);
        if (!caso.nome_paciente?.trim()) errosValidacao.value.push(`Linha ${i + 1}: nome do paciente é obrigatório.`);
    });
    if (errosValidacao.value.length > 0) return;
    form.put(`/mdda/${props.relatorio.id}`);
};

const finalizar = () => {
    if (confirm('Deseja finalizar este relatório?')) {
        router.post(`/mdda/${props.relatorio.id}/finalizar`);
    }
};

const sincronizando = ref(false);

const sincronizar = () => {
    if (!confirm('Sincronizar buscará atendimentos novos da semana e os adicionará ao relatório sem remover os registros existentes. Continuar?')) return;
    sincronizando.value = true;
    router.post(`/mdda/${props.relatorio.id}/sincronizar`, {}, {
        onFinish: () => { sincronizando.value = false; },
    });
};
</script>

<template>
    <Head title="Relatório MDDA" />
    <AppLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    MDDA — SE {{ form.semana_epidemiologica }}/{{ form.ano }}
                </h2>
                <span
                    :class="relatorio.status === 'finalizado'
                        ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100'
                        : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100'"
                    class="text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wide">
                    {{ relatorio.status }}
                </span>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-full mx-auto sm:px-4 lg:px-6">

                <!-- Identificação -->
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-4">
                        Identificação
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                        <!-- Datepicker de semana -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                Data da semana
                            </label>
                            <input v-model="dataSemana" type="date"
                                class="block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm" />
                        </div>

                        <!-- SE calculada -->
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded border border-blue-200 dark:border-blue-800">
                            <p class="text-sm font-semibold text-blue-800 dark:text-blue-200">
                                SE {{ form.semana_epidemiologica }} / {{ form.ano }}
                            </p>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-0.5">{{ rangeLabel }}</p>
                        </div>

                        <!-- Responsável -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Responsável</label>
                            <input v-model="form.responsavel_nome" type="text" maxlength="255"
                                class="block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm" />
                        </div>
                    </div>
                </div>

                <!-- Planilha de Casos -->
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                            Planilha de Casos ({{ form.casos.length }})
                        </h3>
                        <button type="button" @click="addLinha"
                            class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded shadow transition">
                            + Adicionar linha
                        </button>
                    </div>

                    <div v-if="form.casos.length === 0"
                        class="text-center text-gray-400 dark:text-gray-500 py-10 text-sm">
                        Nenhum atendimento encontrado para esta semana. Use o botão acima para adicionar manualmente.
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="w-full text-xs border-collapse min-w-[1200px]">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-center w-8">Nº</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left w-28">Data Atend.</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left min-w-[160px]">Nome</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left w-24">Faixa Etária</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left w-14">Idade</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left min-w-[180px]">Procedência</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left w-24">Zona</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left w-28">Data Sintomas</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left w-28">Plano</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 w-8"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(caso, index) in form.casos" :key="index"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="border border-gray-200 dark:border-gray-600 px-2 py-1 text-center text-gray-500">
                                        {{ caso.numero_ordem }}
                                    </td>
                                    <td class="border border-gray-200 dark:border-gray-600 px-1 py-1">
                                        <input v-model="caso.data_atendimento" type="date"
                                            :readonly="!!caso.atendimento_id"
                                            :class="caso.atendimento_id ? 'bg-gray-100 dark:bg-gray-600 cursor-not-allowed text-gray-500 dark:text-gray-400' : 'bg-transparent dark:text-white'"
                                            class="w-full text-xs rounded border-0 focus:ring-1 focus:ring-blue-500 px-1" />
                                    </td>
                                    <td class="border border-gray-200 dark:border-gray-600 px-1 py-1">
                                        <input v-model="caso.nome_paciente" type="text" required maxlength="255"
                                            :readonly="!!caso.atendimento_id"
                                            :class="caso.atendimento_id ? 'bg-gray-100 dark:bg-gray-600 cursor-not-allowed text-gray-500 dark:text-gray-400' : 'bg-transparent dark:text-white'"
                                            class="w-full text-xs rounded border-0 focus:ring-1 focus:ring-blue-500 px-1" />
                                    </td>
                                    <td class="border border-gray-200 dark:border-gray-600 px-1 py-1">
                                        <select v-model="caso.faixa_etaria"
                                            class="w-full text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                            <option value="&lt;1">&lt;1</option>
                                            <option value="1a4">1 a 4</option>
                                            <option value="5a9">5 a 9</option>
                                            <option value="10+">10+</option>
                                            <option value="IGN">IGN</option>
                                        </select>
                                    </td>
                                    <td class="border border-gray-200 dark:border-gray-600 px-1 py-1">
                                        <input v-model="caso.idade_display" type="text" placeholder="ex: 8 meses" maxlength="255"
                                            class="w-full text-xs rounded border-0 bg-transparent dark:text-white focus:ring-1 focus:ring-blue-500 px-1" />
                                    </td>
                                    <td class="border border-gray-200 dark:border-gray-600 px-1 py-1">
                                        <input v-model="caso.procedencia" type="text" placeholder="Rua, Bairro..." maxlength="255"
                                            class="w-full text-xs rounded border-0 bg-transparent dark:text-white focus:ring-1 focus:ring-blue-500 px-1" />
                                    </td>
                                    <td class="border border-gray-200 dark:border-gray-600 px-1 py-1">
                                        <select v-model="caso.zona"
                                            class="w-full text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                            <option value="Urbana">Urbana</option>
                                            <option value="Rural">Rural</option>
                                            <option value="IGN">IGN</option>
                                        </select>
                                    </td>
                                    <td class="border border-gray-200 dark:border-gray-600 px-1 py-1">
                                        <input v-model="caso.data_primeiros_sintomas" type="date"
                                            class="w-full text-xs rounded border-0 bg-transparent dark:text-white focus:ring-1 focus:ring-blue-500 px-1" />
                                    </td>
                                    <td class="border border-gray-200 dark:border-gray-600 px-1 py-1">
                                        <select v-model="caso.plano_tratamento"
                                            class="w-full text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="IGN">IGN</option>
                                        </select>
                                    </td>
                                    <td class="border border-gray-200 dark:border-gray-600 px-1 py-1 text-center">
                                        <button type="button" @click="removeLinha(index)"
                                            class="text-red-400 hover:text-red-600 font-bold text-base leading-none">
                                            ×
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Legenda -->
                    <div class="mt-4 text-xs text-gray-400 dark:text-gray-500 space-y-0.5">
                        <p>* Faixa etária: em dias até 1 mês, em meses até 1 ano, em anos para os demais.</p>
                        <p>** Plano A = sem desidratação/domiciliar; B = desidratação/observação (TRO); C = grave/reidratação venosa.</p>
                    </div>

                    <!-- Erros de validação -->
                    <div v-if="errosValidacao.length > 0"
                        class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-300 dark:border-red-700 rounded text-sm text-red-700 dark:text-red-300">
                        <p class="font-semibold mb-1">Corrija os seguintes erros antes de salvar:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            <li v-for="erro in errosValidacao" :key="erro">{{ erro }}</li>
                        </ul>
                    </div>

                    <!-- Ações -->
                    <div class="flex items-center gap-4 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="salvar" :disabled="form.processing"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow disabled:opacity-50 transition">
                            Salvar
                        </button>
                        <button type="button" @click="sincronizar" :disabled="sincronizando || form.processing"
                            class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-6 rounded shadow disabled:opacity-50 transition"
                            title="Adiciona atendimentos novos da semana sem remover os registros existentes">
                            {{ sincronizando ? 'Sincronizando...' : 'Sincronizar Atendimentos' }}
                        </button>
                        <button type="button" @click="finalizar"
                            :disabled="relatorio.status === 'finalizado' || form.processing"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow disabled:opacity-50 transition">
                            Finalizar
                        </button>
                        <a :href="`/mdda/${relatorio.id}/imprimir`" target="_blank"
                            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded shadow transition">
                            Imprimir / PDF
                        </a>
                        <a href="/mdda" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 ml-auto">
                            Voltar à lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
