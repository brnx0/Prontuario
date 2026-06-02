<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps<{
    dados: any;
}>();

onMounted(() => {
    setTimeout(() => window.print(), 500);
});
</script>

<template>
    <Head title="Ficha do Paciente" />

    <!-- Barra de controle (oculta na impressão) -->
    <div class="print:hidden bg-gray-100 p-4 flex justify-between items-center shadow-md mb-8">
        <div>
            <h2 class="text-lg font-bold text-gray-700">Modo de Impressão</h2>
            <p class="text-sm text-gray-500 mt-1">Você pode clicar em qualquer texto abaixo para editar antes de imprimir.</p>
        </div>
        <div class="space-x-2">
            <button @click="window.close()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Fechar
            </button>
            <button @click="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Imprimir Novamente
            </button>
        </div>
    </div>

    <!-- Área imprimível -->
    <div contenteditable="true" spellcheck="false"
        class="max-w-4xl mx-auto p-8 bg-white text-black font-sans leading-relaxed outline-none focus:ring-2 focus:ring-blue-300 print:focus:ring-0 rounded">

        <!-- Cabeçalho -->
        <div class="flex flex-col sm:flex-row items-center justify-center border-b-2 border-black pb-4 mb-6">
            <img src="/assets/img/brasao-municipio.png" alt="Brasão" class="h-24 sm:mr-6 mb-4 sm:mb-0" />
            <div class="text-center sm:text-left">
                <h1 class="text-2xl font-bold uppercase m-0 leading-tight">PREFEITURA MUNICIPAL DE SANTO AMARO</h1>
                <h2 class="text-lg font-semibold uppercase m-0 leading-snug">POLICLÍNICA MUNICIPAL REGIS PACHECO</h2>
                <p class="text-sm mt-1">Praça da Purificação, 51 - Centro - Santo Amaro da Purificação-BA</p>
            </div>
        </div>

        <h3 class="text-center text-base font-bold uppercase mb-4 tracking-widest">FICHA DO PACIENTE</h3>

        <!-- Identificação -->
        <div class="mb-4">
            <table class="w-full text-sm border-collapse border border-black">
                <tbody>
                    <tr>
                        <td colspan="3" class="border border-black px-3 py-2">
                            <b>NOME:</b> {{ dados.nome || '' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black px-3 py-2">
                            <b>DATA DE NASCIMENTO:</b> {{ dados.nascimento || '' }}
                        </td>
                        <td class="border border-black px-3 py-2">
                            <b>IDADE:</b> {{ dados.idade ?? '' }} anos
                        </td>
                        <td class="border border-black px-3 py-2">
                            <b>CPF:</b> {{ dados.cpf || '' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black px-3 py-2">
                            <b>CARTÃO SUS:</b> {{ dados.cartao_sus || '' }}
                        </td>
                        <td colspan="2" class="border border-black px-3 py-2">
                            <b>PROFISSÃO:</b> {{ dados.profissao || '' }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border border-black px-3 py-2">
                            <b>ENDEREÇO:</b> {{ dados.endereco || '' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black px-3 py-2">
                            <b>TELEFONE:</b> {{ dados.tel_1 || '' }}{{ dados.tel_2 ? ' / ' + dados.tel_2 : '' }}
                        </td>
                        <td colspan="2" class="border border-black px-3 py-2">
                            <b>E-MAIL:</b> {{ dados.email || '' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black px-3 py-2">
                            <b>FILIAÇÃO 1 (MÃE):</b> {{ dados.filicao_1 || '' }}
                        </td>
                        <td colspan="2" class="border border-black px-3 py-2">
                            <b>FILIAÇÃO 2 (PAI):</b> {{ dados.filicao_2 || '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Equipe Médica -->
        <div class="mb-4">
            <table class="w-full text-sm border-collapse border border-black">
                <tbody>
                    <tr>
                        <td class="border border-black px-3 py-2 w-1/2">
                            <b>MÉDICO(A):</b> {{ dados.med_nome || '' }}
                        </td>
                        <td class="border border-black px-3 py-2 w-1/2">
                            <b>ENFERMEIRO(A):</b> {{ dados.enf_nome || '' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black px-3 py-2">
                            <b>SITUAÇÃO/QUEIXA:</b> {{ dados.situacao || '' }}
                        </td>
                        <td class="border border-black px-3 py-2">
                            <b>CID-10:</b> {{ dados.cid10 || '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Sinais Vitais -->
        <div class="mb-4 mt-4">
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

        <!-- Alergias e Medicamentos -->
        <div class="mb-4">
            <table class="w-full text-sm border-collapse border border-black">
                <tbody>
                    <tr>
                        <td class="border border-black px-3 py-2 w-1/2 align-top">
                            <b>ALERGIAS CONHECIDAS:</b><br />
                            <span class="whitespace-pre-wrap">{{ dados.alergias || '' }}</span>
                        </td>
                        <td class="border border-black px-3 py-2 w-1/2 align-top">
                            <b>MEDICAMENTOS EM USO CONTÍNUO:</b><br />
                            <span class="whitespace-pre-wrap">{{ dados.medicamentos || '' }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Descrição do Caso -->
        <div class="mb-4 mt-4">
            <h3 class="text-md font-bold mb-2 uppercase">DESCRIÇÃO DO CASO CLÍNICO</h3>
            <p class="whitespace-pre-wrap text-sm border border-gray-300 print:border-none p-2 min-h-[80px]">{{ dados.descricao_caso || '' }}</p>
        </div>

        <!-- Sintomas -->
        <div class="mb-12 mt-4">
            <h3 class="text-md font-bold mb-2 uppercase">SINTOMAS</h3>
            <p class="whitespace-pre-wrap text-sm border border-gray-300 print:border-none p-2 min-h-[80px]">{{ dados.sintomas || '' }}</p>
        </div>

        <!-- Assinaturas -->
        <div class="mt-24 w-full text-center text-sm grid grid-cols-2 gap-12">
            <div>
                <div class="border-t border-black w-4/5 mx-auto pt-2">
                    <p class="font-bold">Enfermeiro(a)</p>
                    <p>Carimbo/Assinatura</p>
                </div>
            </div>
            <div>
                <div class="border-t border-black w-4/5 mx-auto pt-2">
                    <p class="font-bold">Médico(a)</p>
                    <p>Carimbo/Assinatura</p>
                </div>
            </div>
        </div>

    </div>
</template>

<style>
@media print {
    body { margin: 0; padding: 0; background-color: white; }
    @page { margin: 1.5cm; }
}
</style>
