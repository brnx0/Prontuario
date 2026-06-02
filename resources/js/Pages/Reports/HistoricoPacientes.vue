<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { onMounted, computed } from 'vue';

const props = defineProps<{
    dados: any[];
    filtros: {
        nome?: string;
        cpf?: string;
        nascimentoInicio?: string;
        nascimentoFim?: string;
        med_cod?: string;
        med_nome?: string;
    };
}>();

const geradoEm = new Date().toLocaleString('pt-BR');

const fmtDay = (d?: string) =>
    d ? new Date(d + 'T00:00:00').toLocaleDateString('pt-BR') : null;

const filtrosAtivos = computed(() => {
    const f: string[] = [];
    if (props.filtros.nome) f.push(`Paciente: ${props.filtros.nome}`);
    if (props.filtros.cpf) f.push(`CPF: ${props.filtros.cpf}`);
    const di = fmtDay(props.filtros.nascimentoInicio);
    const df = fmtDay(props.filtros.nascimentoFim);
    if (di && df) f.push(`Nascimento: ${di} até ${df}`);
    else if (di) f.push(`Nascimento a partir de: ${di}`);
    else if (df) f.push(`Nascimento até: ${df}`);
    if (props.filtros.med_nome) f.push(`Médico: ${props.filtros.med_nome}`);
    return f;
});

const formatarData = (data: string) => {
    if (!data) return '-';
    const parts = data.split('-');
    return parts.length === 3 ? `${parts[0]}/${parts[1]}/${parts[2]}` : data;
};

const handlePrint = () => window.print();
const handleClose = () => window.close();

onMounted(() => {
    setTimeout(() => window.print(), 600);
});
</script>

<template>
    <Head title="Lista de Pacientes" />

    <!-- Barra de controle (oculta na impressão) -->
    <div class="print:hidden bg-gray-100 p-4 flex justify-between items-center shadow-md mb-8">
        <div>
            <h2 class="text-lg font-bold text-gray-700">Modo de Impressão</h2>
            <p class="text-sm text-gray-500 mt-1">{{ dados.length }} paciente(s) encontrado(s).</p>
        </div>
        <div class="space-x-2">
            <button @click="handleClose" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Fechar
            </button>
            <button @click="handlePrint" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Imprimir Novamente
            </button>
        </div>
    </div>

    <!-- Área imprimível -->
    <div class="max-w-6xl mx-auto p-8 bg-white text-black font-sans">

        <!-- Cabeçalho -->
        <div class="flex flex-col sm:flex-row items-center justify-center border-b-2 border-black pb-4 mb-6">
            <img src="/assets/img/brasao-municipio.png" alt="Brasão" class="h-24 sm:mr-6 mb-4 sm:mb-0" />
            <div class="text-center sm:text-left">
                <h1 class="text-2xl font-bold uppercase m-0 leading-tight">PREFEITURA MUNICIPAL DE SANTO AMARO</h1>
                <h2 class="text-lg font-semibold uppercase m-0 leading-snug">POLICLÍNICA MUNICIPAL REGIS PACHECO</h2>
                <p class="text-sm mt-1">Praça da Purificação, 51 - Centro - Santo Amaro da Purificação-BA</p>
            </div>
        </div>

        <!-- Título e metadados -->
        <div class="mb-4">
            <h3 class="text-center text-base font-bold uppercase tracking-widest mb-2">LISTA DE PACIENTES</h3>
            <div class="flex justify-between text-xs text-gray-600">
                <span>Gerado em: {{ geradoEm }}</span>
                <span>Total: {{ dados.length }} paciente(s)</span>
            </div>
            <div v-if="filtrosAtivos.length > 0" class="mt-1 text-xs text-gray-500">
                Filtros: {{ filtrosAtivos.join(' | ') }}
            </div>
        </div>

        <!-- Tabela -->
        <table class="w-full text-xs border-collapse border border-black">
            <thead>
                <tr class="bg-gray-200 print:bg-transparent">
                    <th class="border border-black px-2 py-2 text-center w-8">N°</th>
                    <th class="border border-black px-2 py-2 text-left">Nome</th>
                    <th class="border border-black px-2 py-2 text-left">CPF</th>
                    <th class="border border-black px-2 py-2 text-left">Nascimento</th>
                    <th class="border border-black px-2 py-2 text-left">Cartão SUS</th>
                    <th class="border border-black px-2 py-2 text-left">Telefone</th>
                    <th class="border border-black px-2 py-2 text-left">Médico(a)</th>
                    <th class="border border-black px-2 py-2 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(pac, idx) in dados" :key="pac.pac_cod"
                    :class="idx % 2 === 0 ? 'bg-white' : 'bg-gray-50 print:bg-transparent'">
                    <td class="border border-black px-2 py-1.5 text-center text-gray-500">{{ idx + 1 }}</td>
                    <td class="border border-black px-2 py-1.5 font-medium">{{ pac.nome }}</td>
                    <td class="border border-black px-2 py-1.5">{{ pac.cpf || '-' }}</td>
                    <td class="border border-black px-2 py-1.5 whitespace-nowrap">
                        {{ pac.nascimento ? formatarData(pac.nascimento) : '-' }}
                    </td>
                    <td class="border border-black px-2 py-1.5">{{ pac.cartao_sus || '-' }}</td>
                    <td class="border border-black px-2 py-1.5">{{ pac.tel_1 || '-' }}</td>
                    <td class="border border-black px-2 py-1.5">{{ pac.medico?.med_nome || '-' }}</td>
                    <td class="border border-black px-2 py-1.5 text-center">{{ pac.ativo === 'S' ? 'Ativo' : 'Inativo' }}</td>
                </tr>
                <tr v-if="dados.length === 0">
                    <td colspan="8" class="border border-black px-4 py-4 text-center text-gray-500">
                        Nenhum paciente encontrado.
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Rodapé -->
        <div class="mt-8 text-xs text-gray-500 text-center border-t pt-2">
            Documento gerado automaticamente pelo sistema em {{ geradoEm }}
        </div>

    </div>
</template>

<style>
@media print {
    body { margin: 0; padding: 0; background-color: white; }
    @page { margin: 1.5cm; size: landscape; }
}
</style>
