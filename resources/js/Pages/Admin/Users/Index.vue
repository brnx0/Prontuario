<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import Swal from 'sweetalert2';

const props = defineProps<{
    users: any;
}>();

const dataLists = computed(() => Array.isArray(props.users) ? props.users : (props.users.data || []));

const filterForm = useForm({
    nome: '',
    email: ''
});

const submitFilter = () => {
    filterForm.get('/admin/usuarios', { preserveState: true });
};

const showModal = ref(false);
const formTitle = ref('Cadastro de Usuário');

const form = useForm({
    id: '',
    name: '',
    email: '',
    password: '',
    is_admin: false
});

const isEditing = ref(false);
const showPassword = ref(false);

const openCreateModal = () => {
    isEditing.value = false;
    formTitle.value = 'Cadastro de Usuário';
    form.reset();
    form.clearErrors();
    showPassword.value = false;
    showModal.value = true;
};

const editUser = (user: any) => {
    isEditing.value = true;
    formTitle.value = 'Editar Usuário';
    form.id = user.id;
    form.name = user.name;
    form.email = user.email;
    form.password = '';
    form.is_admin = user.is_admin;
    form.clearErrors();
    showPassword.value = false;
    showModal.value = true;
};

const deleteUser = (id: string | number) => {
    Swal.fire({
        title: 'Tem certeza?',
        text: "O usuário será removido permanentemente!",
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
            router.delete('/admin/usuarios', { data: { id: id } });
        }
    });
};

const saveUser = () => {
    if (isEditing.value) {
        form.put(`/admin/usuarios/${form.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                showModal.value = false;
                form.reset();
                form.clearErrors();
            }
        });
    } else {
        form.post('/admin/usuarios', {
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
    <Head title="Gerenciar Usuários" />

    <AppLayout>
        <template #header>
            <div class="flex justify-between w-full items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Gerenciar Usuários
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
                    <form @submit.prevent="submitFilter" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
                            <input v-model="filterForm.nome" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Digite um nome">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input v-model="filterForm.email" type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Digite um email">
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Perfil</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="u in dataLists" :key="u.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ u.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ u.email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span v-if="u.is_admin" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                            Administrador
                                        </span>
                                        <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                            Usuário
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center space-x-2 flex justify-center">
                                        <button @click="editUser(u)" class="text-yellow-600 bg-yellow-100 hover:bg-yellow-200 p-2 rounded">
                                            Editar
                                        </button>
                                        <button @click="deleteUser(u.id)" class="text-white bg-red-600 hover:bg-red-700 p-2 rounded">
                                            Deletar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Component -->
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                        <Pagination :links="users.links" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form @submit.prevent="saveUser">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                {{ formTitle }}
                            </h3>

                            <div class="mt-4 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome <span class="text-red-500">*</span></label>
                                    <input v-model="form.name" type="text" required class="mt-1 block w-full border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md shadow-sm">
                                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">{{ form.errors.name }}</p>
                                </div>

                                <div class="sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email <span class="text-red-500">*</span></label>
                                    <input v-model="form.email" type="email" required class="mt-1 block w-full border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md shadow-sm">
                                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-500">{{ form.errors.email }}</p>
                                </div>

                                <div class="sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Senha <span v-if="!isEditing" class="text-red-500">*</span>
                                        <span v-if="isEditing" class="text-xs text-gray-400">(deixe em branco para manter)</span>
                                    </label>
                                    <div class="relative">
                                        <input v-model="form.password" :type="showPassword ? 'text' : 'password'" :required="!isEditing" minlength="6" class="mt-1 block w-full border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md shadow-sm pr-10">
                                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                            <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" /></svg>
                                        </button>
                                    </div>
                                    <p v-if="form.errors.password" class="mt-1 text-sm text-red-500">{{ form.errors.password }}</p>
                                </div>

                                <div class="sm:col-span-3 flex items-end">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input v-model="form.is_admin" type="checkbox" class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500 dark:bg-gray-700 dark:border-gray-600 h-5 w-5">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Administrador</span>
                                    </label>
                                </div>
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
