<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import SearchableSelect from '@/Components/SearchableSelect.vue';

const props = defineProps<{
    pacientes: any[];
    data: string;
    medicos: any[];
    enfermeiros: any[];
    especialidades: any[];
}>();

const form = useForm({
    pac_cod: '',
    dtAtendimento: props.data || '',
    med_cod: '',
    esp_cod: '',
    enfermeiro: '',
    situacao: '',
    mmhg: '',
    bpm: '',
    rpm: '',
    spo2: '',
    temp: '',
    kg: '',
    hgt: '',
    descricaoCaso: '',
    receituario: ''
});

const submitAtendimento = () => {
    form.post('/atendimento', {
        preserveScroll: true,
        onSuccess: () => {
            // Form is successfully submitted and toast will appear via AppLayout
            form.reset('situacao', 'mmhg', 'bpm', 'rpm', 'spo2', 'temp', 'kg', 'hgt', 'descricaoCaso', 'receituario');
        }
    });
};
</script>

<template>
    <Head title="Novo Atendimento" />

    <AppLayout>
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Novo Atendimento
            </h2>
        </template> -->

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8">
                    
                    <form @submit.prevent="submitAtendimento">
                        <!-- Primeira Linha -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Paciente <span class="text-red-500">*</span>
                                </label>
                                <SearchableSelect 
                                    v-model="form.pac_cod"
                                    :options="pacientes"
                                    labelKey="nome"
                                    valueKey="pac_cod"
                                    placeholder="🔍 Buscar paciente..."
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Data do Atendimento <span class="text-red-500">*</span>
                                </label>
                                <input v-model="form.dtAtendimento" type="datetime-local" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                            </div>
                        </div>

                        <!-- Segunda Linha -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Médico(a)</label>
                                <SearchableSelect 
                                    v-model="form.med_cod"
                                    :options="medicos"
                                    labelKey="med_nome"
                                    valueKey="med_cod"
                                    placeholder="🔍 Buscar médico(a)..."
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Especialidade</label>
                                <SearchableSelect 
                                    v-model="form.esp_cod"
                                    :options="especialidades"
                                    labelKey="escp_desc"
                                    valueKey="esp_cod"
                                    placeholder="🔍 Buscar especialidade..."
                                />
                            </div>
                        </div>

                        <!-- Terceira Linha -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Enfermeiro(a)</label>
                            <SearchableSelect 
                                v-model="form.enfermeiro"
                                :options="enfermeiros"
                                labelKey="enf_nome"
                                valueKey="enf_cod"
                                placeholder="🔍 Buscar enfermeiro(a)..."
                            />
                        </div>

                        <!-- Situação / Queixa -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Situação/Queixa</label>
                            <input v-model="form.situacao" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                        </div>

                        <!-- Sinais Vitais -->
                        <div class="grid grid-cols-2 md:grid-cols-7 gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Pressão (mmHg)</label>
                                <input v-model="form.mmhg" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">FC (bpm)</label>
                                <input v-model="form.bpm" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">FC (rpm)</label>
                                <input v-model="form.rpm" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">SPO2 (%)</label>
                                <input v-model="form.spo2" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Temp &deg;C</label>
                                <input v-model="form.temp" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Peso (Kg)</label>
                                <input v-model="form.kg" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">HGT (mg/dl)</label>
                                <input v-model="form.hgt" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                            </div>
                        </div>

                        <!-- Descrição do Caso Textarea -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição do Caso Clínico</label>
                            <textarea v-model="form.descricaoCaso" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm"></textarea>
                        </div>

                        <!-- Receituario -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Receituário</label>
                            <textarea v-model="form.receituario" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm placeholder-gray-400" placeholder="Escreva a receita médica aqui..."></textarea>
                        </div>

                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" :disabled="form.processing" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow disabled:opacity-50 transition duration-150">
                                Salvar Atendimento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
