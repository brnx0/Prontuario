<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import Swal from 'sweetalert2';

const props = defineProps<{
    query: any;
}>();

// Ensure the data passed is always an array so we can iterate and is reactive
const pacientes = computed(() => Array.isArray(props.query) ? props.query : (props.query.data || []));

const filterForm = useForm({
    nome: '',
    cpf: '',
    filtroData: ''
});

const submitFilter = () => {
    filterForm.get('/paciente', { preserveState: true });
};

const showModal = ref(false);
const formTitle = ref('Cadastro de Paciente');

const form = useForm({
    pes_cod: '',
    nome: '',
    nascimento: '',
    cpf: '',
    cartao_sus: '',
    profissao: '',
    filicao_1: '',
    filicao_2: '',
    cep: '',
    logradouro: '',
    cidade: '',
    uf: '',
    tel_1: '',
    tel_2: '',
    email: ''
});

const isEditing = ref(false);

const openCreateModal = () => {
    isEditing.value = false;
    formTitle.value = 'Cadastro de Paciente';
    form.reset();
    form.clearErrors();
    showModal.value = true;
};

const editPaciente = (paciente: any) => {
    isEditing.value = true;
    formTitle.value = 'Editar Paciente';
    form.pes_cod = paciente.pac_cod;
    form.nome = paciente.nome;
    
    // Fix the d-m-Y format returned by accessor for the HTML input (requires Y-m-d)
    if (paciente.nascimento && paciente.nascimento.includes('-')) {
        const p = paciente.nascimento.split('-');
        if (p[0].length === 2) form.nascimento = `${p[2]}-${p[1]}-${p[0]}`;
        else form.nascimento = paciente.nascimento;
    } else {
        form.nascimento = paciente.nascimento;
    }

    form.cpf = paciente.cpf;
    form.cartao_sus = paciente.cartao_sus || paciente.sus;
    form.filicao_1 = paciente.filicao_1;
    form.filicao_2 = paciente.filicao_2;
    form.cep = paciente.cep;
    form.logradouro = paciente.logradouro;
    form.cidade = paciente.cidade;
    form.uf = paciente.uf;
    form.tel_1 = paciente.tel_1;
    form.tel_2 = paciente.tel_2;
    form.email = paciente.email;
    form.clearErrors();
    showModal.value = true;
};

const deletePaciente = (id: string | number) => {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Você não poderá reverter esta ação!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sim, deletar!',
        cancelButtonText: 'Cancelar',
        background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
        color: document.documentElement.classList.contains('dark') ? '#fff' : '#1f2937'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/paciente/${id}`);
        }
    });
};

const toggleStatus = (id: string | number, status: string) => {
    router.put('/paciente', { pac_cod: id, status: status });
};

const savePaciente = () => {
    if (isEditing.value) {
        form.put(`/paciente/${form.pes_cod}`, {
            preserveScroll: true,
            onSuccess: () => {
                showModal.value = false;
                form.reset();
                form.clearErrors();
            }
        });
    } else {
        form.post('/paciente', {
            preserveScroll: true,
            onSuccess: () => {
                showModal.value = false;
                form.reset();
                form.clearErrors();
            }
        });
    }
};
</script>

<template>
    <Head title="Pacientes" />

    <AppLayout>
        <template #header>
            <div class="flex justify-between w-full items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Pacientes
                </h2>
                <button @click="openCreateModal" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
                    Adicionar +
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Filters -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                    <form @submit.prevent="submitFilter" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
                            <input v-model="filterForm.nome" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Digite um nome">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">CPF</label>
                            <input v-model="filterForm.cpf" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Digite um CPF">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data de Nascimento</label>
                            <input v-model="filterForm.filtroData" type="date" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                                Filtrar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CPF</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nascimento</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cartão SUS</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="paciente in pacientes" :key="paciente.pac_cod">
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ paciente.nome }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ paciente.cpf }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ paciente.nascimento }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ paciente.cartao_sus || paciente.sus }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center space-x-2 flex justify-center">
                                        <button v-if="paciente.ativo === 'S'" @click="toggleStatus(paciente.pac_cod, 'N')" class="text-green-600 bg-green-100 hover:bg-green-200 p-2 rounded">
                                            Ativo
                                        </button>
                                        <button v-else @click="toggleStatus(paciente.pac_cod, 'S')" class="text-red-600 bg-red-100 hover:bg-red-200 p-2 rounded">
                                            Inativo
                                        </button>

                                        <button @click="editPaciente(paciente)" class="text-yellow-600 bg-yellow-100 hover:bg-yellow-200 p-2 rounded">
                                            Editar
                                        </button>

                                        <button @click="deletePaciente(paciente.pac_cod)" class="text-white bg-red-600 hover:bg-red-700 p-2 rounded">
                                            Deletar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Component -->
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                        <Pagination :links="query.links" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    <form @submit.prevent="savePaciente">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                {{ formTitle }}
                            </h3>
                            
                            <div class="mt-4 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome <span class="text-red-500">*</span></label>
                                    <input v-model="form.nome" type="text" required class="mt-1 block w-full border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md shadow-sm">
                                    <p v-if="form.errors.nome" class="mt-1 text-sm text-red-500">{{ form.errors.nome }}</p>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nascimento <span class="text-red-500">*</span></label>
                                    <input v-model="form.nascimento" type="date" required class="mt-1 block w-full border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md shadow-sm">
                                    <p v-if="form.errors.nascimento" class="mt-1 text-sm text-red-500">{{ form.errors.nascimento }}</p>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">CPF</label>
                                    <input v-model="form.cpf" type="text" class="mt-1 block w-full border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md shadow-sm">
                                    <p v-if="form.errors.cpf" class="mt-1 text-sm text-red-500">{{ form.errors.cpf }}</p>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cartão SUS</label>
                                    <input v-model="form.cartao_sus" type="text" class="mt-1 block w-full border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md shadow-sm">
                                    <p v-if="form.errors.cartao_sus" class="mt-1 text-sm text-red-500">{{ form.errors.cartao_sus }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" :disabled="form.processing" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Salvar
                            </button>
                            <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </AppLayout>
</template>
