<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import Swal from 'sweetalert2';

const props = defineProps<{
    especialidades: any;
}>();

const dataLists = computed(() => Array.isArray(props.especialidades) ? props.especialidades : (props.especialidades.data || []));

const filterForm = useForm({
    escp_desc: ''
});

const submitFilter = () => {
    filterForm.get('/especialidade', { preserveState: true });
};

const showModal = ref(false);
const formTitle = ref('Nova Especialidade');

const form = useForm({
    espc_cod: '',
    descEspc: ''
});

const isEditing = ref(false);

const openCreateModal = () => {
    isEditing.value = false;
    formTitle.value = 'Nova Especialidade';
    form.reset();
    form.clearErrors();
    showModal.value = true;
};

const editEspecialidade = (esp: any) => {
    isEditing.value = true;
    formTitle.value = 'Editar Especialidade';
    form.espc_cod = esp.esp_cod || esp.espc_cod;
    form.descEspc = esp.escp_desc;
    form.clearErrors();
    showModal.value = true;
};

const deleteEspecialidade = (id: string | number) => {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Esta especialidade será excluida permanentemente!",
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
            router.delete(`/especialidade`, { data: { espc_cod: id } });
        }
    });
};

const toggleStatus = (id: string | number, status: string) => {
    router.put('/especialidade-status', { espc_cod: id, status: status });
};

const saveEspecialidade = () => {
    if (isEditing.value) {
        form.put(`/especialidade/${form.espc_cod}`, {
            preserveScroll: true,
            onSuccess: () => {
                showModal.value = false;
                form.reset();
                form.clearErrors();
            }
        });
    } else {
        form.post('/especialidade', {
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
    <Head title="Especialidades" />

    <AppLayout>
        <template #header>
            <div class="flex justify-between w-full items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Especialidades
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
                    <form @submit.prevent="submitFilter" class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
                            <input v-model="filterForm.escp_desc" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Descrição da Especialidade">
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especialidade</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="esp in dataLists" :key="esp.esp_cod">
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ esp.escp_desc }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center space-x-2 flex justify-center">
                                        <button v-if="esp.ativo === 'S'" @click="toggleStatus(esp.esp_cod || esp.espc_cod, 'N')" class="text-green-600 bg-green-100 hover:bg-green-200 p-2 rounded">
                                            Ativo
                                        </button>
                                        <button v-else @click="toggleStatus(esp.esp_cod || esp.espc_cod, 'S')" class="text-red-600 bg-red-100 hover:bg-red-200 p-2 rounded">
                                            Inativo
                                        </button>

                                        <button @click="editEspecialidade(esp)" class="text-yellow-600 bg-yellow-100 hover:bg-yellow-200 p-2 rounded">
                                            Editar
                                        </button>

                                        <button @click="deleteEspecialidade(esp.esp_cod || esp.espc_cod)" class="text-white bg-red-600 hover:bg-red-700 p-2 rounded">
                                            Deletar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Component -->
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                        <Pagination :links="especialidades.links" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                    <form @submit.prevent="saveEspecialidade">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                {{ formTitle }}
                            </h3>
                            
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição <span class="text-red-500">*</span></label>
                                <input v-model="form.descEspc" type="text" required class="mt-1 block w-full border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md shadow-sm">
                                <p v-if="form.errors.descEspc" class="mt-1 text-sm text-red-500">{{ form.errors.descEspc }}</p>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" :disabled="form.processing" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Salvar
                            </button>
                            <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </AppLayout>
</template>
