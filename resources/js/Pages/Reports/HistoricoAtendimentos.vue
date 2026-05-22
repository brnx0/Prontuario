<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { onMounted, computed } from 'vue';

const props = defineProps<{
    dados: any[];
    filtros: {
        nome?: string;
        dataInicio?: string;
        dataFim?: string;
        med_cod?: string;
        esp_cod?: string;
        med_nome?: string;
        esp_nome?: string;
    };
}>();

const formatDate = (dt: string) => {
    if (!dt) return '-';
    const d = new Date(dt.replace(' ', 'T'));
    if (isNaN(d.getTime())) return dt;
    return (
        d.toLocaleDateString('pt-BR') +
        ' ' +
        d.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' })
    );
};

const geradoEm = new Date().toLocaleString('pt-BR');

const fmtDay = (d?: string) =>
    d ? new Date(d + 'T00:00:00').toLocaleDateString('pt-BR') : null;

const filtrosAtivos = computed(() => {
    const f: string[] = [];
    if (props.filtros.nome) f.push(`Paciente: ${props.filtros.nome}`);
    const di = fmtDay(props.filtros.dataInicio);
    const df = fmtDay(props.filtros.dataFim);
    if (di && df) f.push(`Período: ${di} até ${df}`);
    else if (di) f.push(`A partir de: ${di}`);
    else if (df) f.push(`Até: ${df}`);
    if (props.filtros.med_nome) f.push(`Médico: ${props.filtros.med_nome}`);
    if (props.filtros.esp_nome) f.push(`Especialidade: ${props.filtros.esp_nome}`);
    return f;
});

const handlePrint = () => window.print();
const handleClose = () => window.close();

onMounted(() => {
    setTimeout(handlePrint, 500);
});
</script>

<template>
    <Head title="Histórico de Atendimentos - Relatório" />

    <!-- Print Control Bar -->
    <div class="print:hidden bg-gray-100 p-4 flex justify-between items-center shadow-md mb-8">
        <div>
            <h2 class="text-lg font-bold text-gray-700">Relatório — Histórico de Atendimentos</h2>
            <p class="text-sm text-gray-500 mt-1">{{ dados.length }} registro(s) encontrado(s)</p>
        </div>
        <div class="space-x-2">
            <button @click="handleClose"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Fechar
            </button>
            <button @click="handlePrint"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Imprimir Novamente
            </button>
        </div>
    </div>

    <!-- Printable Area -->
    <div class="max-w-5xl mx-auto p-8 bg-white text-black font-sans leading-relaxed print:p-0 print:max-w-none">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-center justify-center border-b-2 border-black pb-4 mb-5">
            <img src="/assets/img/brasao-municipio.png" alt="Brasão" class="h-20 sm:mr-6 mb-4 sm:mb-0" />
            <div class="text-center sm:text-left">
                <h1 class="text-xl font-bold uppercase m-0 leading-tight">PREFEITURA MUNICIPAL DE SANTO AMARO</h1>
                <h2 class="text-base font-semibold uppercase m-0 leading-snug">POLICLÍNICA MUNICIPAL REGIS PACHECO</h2>
                <p class="text-sm mt-1">Praça da Purificação, 51 - Centro - Santo Amaro da Purificação-BA</p>
            </div>
        </div>

        <!-- Report Title -->
        <div class="text-center mb-5">
            <h3 class="text-base font-bold uppercase tracking-wide">RELATÓRIO DE HISTÓRICO DE ATENDIMENTOS</h3>
            <p class="text-xs text-gray-500 mt-1">Gerado em: {{ geradoEm }}</p>
            <div v-if="filtrosAtivos.length > 0" class="mt-1.5 text-xs text-gray-600">
                <span class="font-semibold">Filtros aplicados: </span>{{ filtrosAtivos.join(' · ') }}
            </div>
        </div>

        <!-- Table -->
        <table class="w-full text-xs border-collapse border border-black">
            <thead>
                <tr class="bg-gray-200 print:bg-gray-200">
                    <th class="border border-black px-2 py-2 text-center w-8">N°</th>
                    <th class="border border-black px-2 py-2 text-left">Paciente</th>
                    <th class="border border-black px-2 py-2 text-left w-32">Data</th>
                    <th class="border border-black px-2 py-2 text-left">Médico</th>
                    <th class="border border-black px-2 py-2 text-left">Especialidade</th>
                    <th class="border border-black px-2 py-2 text-left">Queixa</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(at, idx) in dados" :key="at.atend_cod"
                    :class="idx % 2 !== 0 ? 'bg-gray-50' : 'bg-white'">
                    <td class="border border-black px-2 py-1.5 text-center text-gray-500">{{ idx + 1 }}</td>
                    <td class="border border-black px-2 py-1.5 font-medium">{{ at.paciente?.nome || '-' }}</td>
                    <td class="border border-black px-2 py-1.5 whitespace-nowrap">{{ formatDate(at.dt_atendimento) }}
                    </td>
                    <td class="border border-black px-2 py-1.5">{{ at.medico?.med_nome || '-' }}</td>
                    <td class="border border-black px-2 py-1.5">{{ at.especialidade?.escp_desc || '-' }}</td>
                    <td class="border border-black px-2 py-1.5 max-w-xs break-words">
                        {{ at.situacao_queixa || '-' }}
                    </td>
                </tr>
                <tr v-if="dados.length === 0">
                    <td colspan="6" class="border border-black px-3 py-6 text-center text-gray-500">
                        Nenhum registro encontrado.
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="mt-6 pt-3 border-t border-gray-300 flex justify-between text-xs text-gray-500">
            <span>Total: <strong>{{ dados.length }}</strong> registro(s)</span>
            <span>{{ geradoEm }}</span>
        </div>

    </div>
</template>

<style>
@media print {
    body {
        margin: 0;
        padding: 0;
        background-color: white;
    }

    @page {
        margin: 1.5cm;
        size: A4 landscape;
    }
}
</style>
