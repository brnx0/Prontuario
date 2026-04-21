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

    <Head title="Receituário" />

    <!-- Print Control Bar (Hidden when printing) -->
    <div class="print:hidden bg-gray-100 p-4 flex justify-between items-center shadow-md mb-8">
        <div>
            <h2 class="text-lg font-bold text-gray-700">Modo de Impressão</h2>
            <p class="text-sm text-gray-500 mt-1"><i class="fa-solid fa-pen"></i> Você pode clicar em qualquer texto
                abaixo para editar livremente antes de imprimir.</p>
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

    <!-- Printable Area -->
    <div contenteditable="true" spellcheck="false"
        class="max-w-4xl mx-auto p-12 bg-white text-black font-sans leading-relaxed outline-none focus:ring-2 focus:ring-blue-300 print:focus:ring-0 rounded mt-4">

        <!-- Header -->
        <div class="flex items-center justify-center mb-8 border-b-2 border-black pb-4">
            <img src="/assets/img/brasao-municipio.png" alt="Brasão" class="h-24 mr-6" />
            <div class="text-center">
                <h1 class="text-3xl font-extrabold uppercase m-0 leading-tight">Receituário</h1>
            </div>
        </div>

        <!-- Patient Info -->
        <div class="mb-8 text-lg">
            <p><strong>Paciente:</strong> {{ dados.nome || '' }}</p>
            <p class="mt-2"><strong>Data do Atendimento:</strong> {{ dados.dt_atendimento || '' }}</p>
        </div>

        <!-- Prescription Content -->
        <div class="mb-16 min-h-[400px]">
            <h3 class="text-xl font-bold mb-4 uppercase">Prescrição:</h3>
            <p class="whitespace-pre-wrap text-lg leading-loose mx-4">{{ dados.receituario || '' }}</p>
        </div>

        <!-- Footer Signatures -->
        <div class="mt-32 w-full text-center flex flex-col items-center">
            <div class="border-t border-black w-64 pt-2">
                <p class="font-bold text-lg">Assinatura / Carimbo</p>
            </div>
            <p class="mt-4 text-gray-600">{{ dados.dt_atendimento || '' }}</p>
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

    @page {
        margin: 2cm;
    }
}
</style>
