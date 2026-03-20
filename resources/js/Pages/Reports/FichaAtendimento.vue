<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps<{
    dados: any;
}>();

const handlePrint = () => {
    window.print();
};

const fechar = () => {
    window.close();
};

onMounted(() => {
    // Print automatically after a slight delay to ensure fonts/styles load
    setTimeout(() => {
        handlePrint();
    }, 500);
});
</script>

<template>
    <Head title="Ficha de Atendimento" />

    <!-- Print Control Bar (Hidden when printing) -->
    <div class="print:hidden bg-gray-100 p-4 flex justify-between items-center shadow-md mb-8">
        <div>
            <h2 class="text-lg font-bold text-gray-700">Modo de Impressão</h2>
            <p class="text-sm text-gray-500 mt-1"><i class="fa-solid fa-pen"></i> Você pode clicar em qualquer texto abaixo para editar antes de imprimir.</p>
        </div>
        <div class="space-x-2">
            <button @click="fechar" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Fechar
            </button>
            <button @click="handlePrint" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fa-solid fa-print"></i> Imprimir Novamente
            </button>
        </div>
    </div>

    <!-- Printable Area (Editable before printing) -->
    <div contenteditable="true" spellcheck="false" class="max-w-4xl mx-auto p-8 bg-white text-black font-sans leading-relaxed outline-none focus:ring-2 focus:ring-blue-300 print:focus:ring-0 rounded">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-center justify-center border-b-2 border-black pb-4 mb-6">
            <img src="/assets/img/brasao-municipio.png" alt="Brasão" class="h-24 sm:mr-6 mb-4 sm:mb-0" />
            <div class="text-center sm:text-left">
                <h1 class="text-2xl font-bold uppercase m-0 leading-tight">PREFEITURA MUNICIPAL DE SANTO AMARO</h1>
                <h2 class="text-lg font-semibold uppercase m-0 leading-snug">POLICLÍNICA MUNICIPAL REGIS PACHECO</h2>
                <p class="text-sm mt-1">Praça da Purificação, 51 - Centro - Santo Amaro da Purificação-BA</p>
            </div>
        </div>

        <!-- Patient Info -->
        <div class="mb-6">
            <table class="w-full text-sm border-collapse border border-black p-2">
                <tbody>
                    <tr>
                        <td colspan="2" class="border border-black px-3 py-2">
                            <b>DATA DE ATENDIMENTO:</b> {{ dados.dt_atendimento || '' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black px-3 py-2 w-1/2">
                            <b>NOME DO PACIENTE:</b> {{ dados.nome || '' }}
                        </td>
                        <td class="border border-black px-3 py-2 w-1/2">
                            <b>PROFISSÃO:</b> Não informado
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black px-3 py-2">
                            <b>ENDEREÇO:</b> {{ dados.endereco || '' }}
                        </td>
                        <td class="border border-black px-3 py-2">
                            <b>NASCIMENTO:</b> {{ dados.nascimento || '' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black px-3 py-2">
                            <b>IDADE:</b> {{ dados.idade || '' }} anos
                        </td>
                        <td class="border border-black px-3 py-2">
                            <b>TELEFONE:</b> {{ dados.tel || '' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black px-3 py-2">
                            <b>DOC IDENTIFICAÇÃO:</b> Não informado
                        </td>
                        <td class="border border-black px-3 py-2">
                            <b>CARTÃO DO SUS:</b> {{ dados.cartao_sus || '' }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border border-black px-3 py-2">
                            <b>SITUAÇÃO/QUEIXA:</b> <br />
                            <span class="whitespace-pre-wrap">{{ dados.situacao_queixa || '' }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Clinical Data -->
        <div class="mb-6 mt-6">
            <table class="w-full text-sm border-collapse border border-black text-center">
                <thead>
                    <tr class="bg-gray-200 print:bg-transparent">
                        <th class="border border-black px-2 py-2">PRESSÃO<br>(mmHg)</th>
                        <th class="border border-black px-2 py-2">FC<br>(bpm)</th>
                        <th class="border border-black px-2 py-2">FR<br>(rpm)</th>
                        <th class="border border-black px-2 py-2">SPO2<br>(%)</th>
                        <th class="border border-black px-2 py-2">Temp<br>(°C)</th>
                        <th class="border border-black px-2 py-2">Peso<br>(kg)</th>
                        <th class="border border-black px-2 py-2">HGT<br>(mg/dl)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black px-2 py-3">{{ dados.mmhg || '' }}</td>
                        <td class="border border-black px-2 py-3">{{ dados.bpm || '' }}</td>
                        <td class="border border-black px-2 py-3">{{ dados.rpm || '' }}</td>
                        <td class="border border-black px-2 py-3">{{ dados.spo2 || '' }}</td>
                        <td class="border border-black px-2 py-3">{{ dados.temp || '' }}</td>
                        <td class="border border-black px-2 py-3">{{ dados.kg || '' }}</td>
                        <td class="border border-black px-2 py-3">{{ dados.hgt || '' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Case Description -->
        <div class="mb-12 mt-6">
            <h3 class="text-md font-bold mb-2 uppercase">DESCRIÇÃO DO CASO CLÍNICO</h3>
            <p class="whitespace-pre-wrap text-sm border border-gray-300 print:border-none p-2 min-h-[100px]">{{ dados.desc_caso || '' }}</p>
        </div>

        <!-- Signatures -->
        <div class="mt-24 w-full text-center text-sm grid grid-cols-2 gap-12">
            <div>
                <div class="border-t border-black w-4/5 mx-auto pt-2">
                    <p class="font-bold">Enfermeiro</p>
                    <p>Carimbo/Assinatura</p>
                </div>
            </div>
            <div>
                <div class="border-t border-black w-4/5 mx-auto pt-2">
                    <p class="font-bold">Médico</p>
                    <p>Carimbo/Assinatura</p>
                </div>
            </div>
        </div>

    </div>
</template>

<style>
/* Enforce black and white and proper printing margins */
@media print {
    body {
        margin: 0;
        padding: 0;
        background-color: white;
    }
    @page { margin: 1.5cm; }
}
</style>
