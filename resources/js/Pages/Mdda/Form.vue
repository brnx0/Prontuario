<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

interface MddaCaso {
    id: string | null;
    atendimento_id: string | null;
    pac_cod: string | null;
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

interface Paciente {
    pac_cod: string;
    nome: string;
    nascimento: string | null; // d-m-Y from accessor
}

const props = defineProps<{
    relatorio: MddaRelatorio;
    pacientes: Paciente[];
}>();

// ── Semana Epidemiológica ─────────────────────────────────────────────────────
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

// ── Cálculo de faixa etária (nascimento d-m-Y ou Y-m-d + data_atendimento Y-m-d) ──
function calcularFaixaEtariaLocal(nascimentoRaw: string | null, dataAtendimento: string): { faixa: string; display: string } {
    if (!nascimentoRaw || !dataAtendimento) return { faixa: 'IGN', display: '' };

    let nascDate: Date;
    if (nascimentoRaw.includes('-')) {
        const parts = nascimentoRaw.split('-');
        if (parts[0].length === 2) {
            // d-m-Y
            nascDate = new Date(Number(parts[2]), Number(parts[1]) - 1, Number(parts[0]));
        } else {
            // Y-m-d
            nascDate = new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]));
        }
    } else {
        return { faixa: 'IGN', display: '' };
    }

    const atendParts = dataAtendimento.split('-').map(Number);
    const atendDate = new Date(atendParts[0], atendParts[1] - 1, atendParts[2]);

    const diffMs = atendDate.getTime() - nascDate.getTime();
    if (diffMs < 0) return { faixa: 'IGN', display: '' };

    const dias = Math.floor(diffMs / 86400000);
    const meses = Math.floor(dias / 30.4375);
    const anos = Math.floor(dias / 365.25);

    if (dias <= 30) return { faixa: '<1', display: `${dias} dias` };
    if (meses < 12) return { faixa: '<1', display: `${meses} meses` };
    if (anos < 5)  return { faixa: '1a4', display: `${anos} anos` };
    if (anos < 10) return { faixa: '5a9', display: `${anos} anos` };
    return { faixa: '10+', display: `${anos} anos` };
}
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

watch(() => props.relatorio, (novo) => {
    form.semana_epidemiologica = novo.semana_epidemiologica;
    form.ano = novo.ano;
    form.responsavel_nome = novo.responsavel_nome;
    form.casos = novo.casos.map(c => ({ ...c }));
    dataSemana.value = seToDateStr(novo.semana_epidemiologica, novo.ano);
}, { deep: true });

// ── Patient picker ────────────────────────────────────────────────────────────
const pickerAberto = ref<number | null>(null);
const pickerQuery = ref('');

const pacientesFiltrados = computed(() => {
    if (!pickerQuery.value) return props.pacientes.slice(0, 10);
    const q = pickerQuery.value.toLowerCase();
    return props.pacientes.filter(p => p.nome.toLowerCase().includes(q)).slice(0, 10);
});

const abrirPicker = (index: number) => {
    pickerAberto.value = index;
    pickerQuery.value = '';
};

const fecharPicker = () => {
    pickerAberto.value = null;
    pickerQuery.value = '';
};

const selecionarPaciente = (index: number, paciente: Paciente) => {
    const caso = form.casos[index];
    caso.pac_cod       = paciente.pac_cod;
    caso.nome_paciente = paciente.nome;

    const faixa = calcularFaixaEtariaLocal(paciente.nascimento, caso.data_atendimento);
    caso.faixa_etaria  = faixa.faixa;
    caso.idade_display = faixa.display;

    fecharPicker();
};

const limparPaciente = (index: number) => {
    form.casos[index].pac_cod = null;
};

// Recalcula faixa quando data_atendimento muda e há pac_cod vinculado
const onDataAtendimentoChange = (index: number) => {
    const caso = form.casos[index];
    if (!caso.pac_cod) return;
    const pac = props.pacientes.find(p => p.pac_cod === caso.pac_cod);
    if (!pac) return;
    const faixa = calcularFaixaEtariaLocal(pac.nascimento, caso.data_atendimento);
    caso.faixa_etaria  = faixa.faixa;
    caso.idade_display = faixa.display;
};
// ─────────────────────────────────────────────────────────────────────────────

const addLinha = () => {
    form.casos.push({
        id: null, atendimento_id: null, pac_cod: null,
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
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Data da semana</label>
                            <input v-model="dataSemana" type="date"
                                class="block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm" />
                        </div>
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded border border-blue-200 dark:border-blue-800">
                            <p class="text-sm font-semibold text-blue-800 dark:text-blue-200">
                                SE {{ form.semana_epidemiologica }} / {{ form.ano }}
                            </p>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-0.5">{{ rangeLabel }}</p>
                        </div>
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
                        Nenhum paciente adicionado. Use o botão acima para inserir.
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="w-full text-xs border-collapse min-w-[1200px]">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-center w-8">Nº</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left w-28">Data Atend.</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left min-w-[200px]">Nome</th>
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

                                    <!-- Data Atendimento -->
                                    <td class="border border-gray-200 dark:border-gray-600 px-1 py-1">
                                        <input v-model="caso.data_atendimento" type="date"
                                            @change="onDataAtendimentoChange(index)"
                                            class="w-full text-xs rounded border-0 bg-transparent dark:text-white focus:ring-1 focus:ring-blue-500 px-1" />
                                    </td>

                                    <!-- Nome com picker de paciente -->
                                    <td class="border border-gray-200 dark:border-gray-600 px-1 py-1 relative">
                                        <div class="flex items-center gap-1">
                                            <!-- Indicador de paciente vinculado -->
                                            <span v-if="caso.pac_cod"
                                                class="flex-shrink-0 w-4 h-4 bg-blue-500 rounded-full flex items-center justify-center"
                                                title="Paciente cadastrado vinculado">
                                                <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                                </svg>
                                            </span>
                                            <input v-model="caso.nome_paciente" type="text" required maxlength="255"
                                                @focus="fecharPicker"
                                                class="flex-1 min-w-0 text-xs rounded border-0 bg-transparent dark:text-white focus:ring-1 focus:ring-blue-500 px-1" />
                                            <!-- Botão picker -->
                                            <button type="button" @click.stop="abrirPicker(index)"
                                                class="flex-shrink-0 text-blue-400 hover:text-blue-600 dark:text-blue-500 dark:hover:text-blue-300"
                                                title="Buscar paciente cadastrado">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                                                </svg>
                                            </button>
                                            <!-- Limpar vínculo -->
                                            <button v-if="caso.pac_cod" type="button" @click="limparPaciente(index)"
                                                class="flex-shrink-0 text-gray-400 hover:text-red-500"
                                                title="Desvincular paciente">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Dropdown picker -->
                                        <div v-if="pickerAberto === index"
                                            class="absolute left-0 top-full mt-1 z-50 w-72 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded shadow-xl">
                                            <div class="p-2 border-b border-gray-200 dark:border-gray-700">
                                                <input v-model="pickerQuery" type="text" placeholder="Buscar paciente..."
                                                    class="block w-full text-xs rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-2 py-1.5"
                                                    @click.stop
                                                    autofocus />
                                            </div>
                                            <ul class="max-h-48 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
                                                <li v-for="pac in pacientesFiltrados" :key="pac.pac_cod"
                                                    @click="selecionarPaciente(index, pac)"
                                                    class="px-3 py-2 cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/20 text-xs">
                                                    <p class="font-medium text-gray-900 dark:text-white">{{ pac.nome }}</p>
                                                    <p class="text-gray-500 dark:text-gray-400">
                                                        Nasc: {{ pac.nascimento || '-' }}
                                                    </p>
                                                </li>
                                                <li v-if="pacientesFiltrados.length === 0"
                                                    class="px-3 py-3 text-xs text-gray-400 dark:text-gray-500 text-center">
                                                    Nenhum paciente encontrado
                                                </li>
                                            </ul>
                                            <div class="p-2 border-t border-gray-200 dark:border-gray-700 text-right">
                                                <button type="button" @click="fecharPicker"
                                                    class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400">
                                                    Fechar
                                                </button>
                                            </div>
                                        </div>
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
                        <p>💡 Clique em 🔍 na coluna Nome para buscar um paciente cadastrado. A faixa etária é calculada automaticamente ao selecionar.</p>
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

        <!-- Overlay para fechar o picker ao clicar fora -->
        <div v-if="pickerAberto !== null" class="fixed inset-0 z-40" @click="fecharPicker"></div>
    </AppLayout>
</template>
