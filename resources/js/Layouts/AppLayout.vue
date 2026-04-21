<script setup lang="ts">
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import NavDropdown from '@/Components/NavDropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const showingNavigationDropdown = ref(false);
const page = usePage();

// Helper to access ziggy route from window, if it exists
const route = (window as any).route;

const user = page.props.auth?.user || { name: 'User' };

import { watch, onMounted } from 'vue';
import Swal from 'sweetalert2';

const isDark = ref(false);

const toggleTheme = () => {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
};

onMounted(() => {
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        isDark.value = true;
        document.documentElement.classList.add('dark');
    } else {
        isDark.value = false;
        document.documentElement.classList.remove('dark');
    }
});

watch(() => page.props.flash, (flash: any) => {
    if (flash?.success) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: flash.success,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: isDark.value ? '#1f2937' : '#fff',
            color: isDark.value ? '#fff' : '#1f2937'
        });
    } else if (flash?.error) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: flash.error,
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            background: isDark.value ? '#1f2937' : '#fff',
            color: isDark.value ? '#fff' : '#1f2937'
        });
    }
}, { deep: true, immediate: true });
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link href="/">
                                    <h1 class="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-cyan-600">Prontuário</h1>
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route ? route('dashboard') : '/dashboard'" :active="route ? route().current('dashboard') : false">
                                    Dashboard
                                </NavLink>

                                <NavDropdown label="Atendimentos" :active="route ? (route().current('atendimento') || route().current('historico')) : false">
                                    <DropdownLink href="/atendimento" :active="route ? route().current('atendimento') : false">
                                        Novo Atendimento
                                    </DropdownLink>
                                    <DropdownLink href="/historico" :active="route ? route().current('historico') : false">
                                        Histórico
                                    </DropdownLink>
                                </NavDropdown>

                                <NavDropdown label="Cadastros" :active="route ? (route().current('paciente') || route().current('medico') || route().current('enfermeiro') || route().current('especialidade')) : false">
                                    <DropdownLink href="/paciente" :active="route ? route().current('paciente') : false">
                                        Pacientes
                                    </DropdownLink>
                                    <DropdownLink href="/medico" :active="route ? route().current('medico') : false">
                                        Médicos
                                    </DropdownLink>
                                    <DropdownLink href="/enfermeiro" :active="route ? route().current('enfermeiro') : false">
                                        Enfermeiros
                                    </DropdownLink>
                                    <DropdownLink href="/especialidade" :active="route ? route().current('especialidade') : false">
                                        Especialidades
                                    </DropdownLink>
                                </NavDropdown>

                                <NavDropdown v-if="user.is_admin" label="Administração" :active="route ? route().current('admin.usuarios') : false">
                                    <DropdownLink href="/admin/usuarios" :active="route ? route().current('admin.usuarios') : false">
                                        Usuários
                                    </DropdownLink>
                                </NavDropdown>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Theme Toggle & Settings Dropdown -->
                            <div class="ms-3 relative flex items-center gap-4">
                                <!-- Theme Toggle Button -->
                                <button @click="toggleTheme" :aria-label="isDark ? 'Ativar modo claro' : 'Ativar modo escuro'" class="p-2 rounded-full text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline-none transition-colors duration-200">
                                    <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                                </button>
                                
                                <div class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ user.name }}</div>
                                <Link v-if="route" :href="route('logout')" method="post" as="button" class="text-sm font-medium text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                    Sair
                                </Link>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{ 'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{ 'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ 'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown }" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <Link :href="route ? route('dashboard') : '/dashboard'" class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600">
                            Dashboard
                        </Link>

                        <!-- Atendimentos -->
                        <div class="ps-3 pe-4 py-2 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Atendimentos</div>
                        <Link href="/atendimento" class="block w-full ps-6 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600">
                            Novo Atendimento
                        </Link>
                        <Link href="/historico" class="block w-full ps-6 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600">
                            Histórico
                        </Link>

                        <!-- Cadastros -->
                        <div class="ps-3 pe-4 py-2 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Cadastros</div>
                        <Link href="/paciente" class="block w-full ps-6 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600">
                            Pacientes
                        </Link>
                        <Link href="/medico" class="block w-full ps-6 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600">
                            Médicos
                        </Link>
                        <Link href="/enfermeiro" class="block w-full ps-6 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600">
                            Enfermeiros
                        </Link>
                        <Link href="/especialidade" class="block w-full ps-6 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600">
                            Especialidades
                        </Link>

                        <!-- Administração -->
                        <template v-if="user.is_admin">
                            <div class="ps-3 pe-4 py-2 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Administração</div>
                            <Link href="/admin/usuarios" class="block w-full ps-6 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600">
                                Usuários
                            </Link>
                        </template>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex items-center justify-between px-4">
                            <div>
                                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ user.name }}</div>
                            </div>
                            <button @click="toggleTheme" :aria-label="isDark ? 'Ativar modo claro' : 'Ativar modo escuro'" class="p-2 rounded-md text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline-none transition-colors duration-200">
                                <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                            </button>
                        </div>

                        <div class="mt-3 space-y-1">
                            <!-- Authentication -->
                            <Link v-if="route" :href="route('logout')" method="post" as="button" class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-gray-700 focus:border-gray-300 dark:focus:border-gray-600 transition duration-150 ease-in-out">
                                Sair
                            </Link>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>


        </div>
    </div>
</template>
