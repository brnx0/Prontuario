<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

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
    } else {
        isDark.value = false;
    }
});
</script>

<template>
    <Head title="Registrar" />

    <div class="min-h-screen flex items-center justify-center p-4 transition-colors duration-300 relative overflow-hidden bg-gray-50 dark:bg-gray-900">
        
        <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] rounded-full bg-indigo-500/20 dark:bg-indigo-600/30 blur-3xl pointer-events-none transition-colors duration-500"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[600px] h-[600px] rounded-full bg-blue-500/20 dark:bg-purple-600/20 blur-3xl pointer-events-none transition-colors duration-500"></div>

        <button @click="toggleTheme" class="absolute top-6 right-6 p-3 rounded-full bg-white/50 dark:bg-gray-800/50 backdrop-blur-md shadow-lg border border-gray-200/50 dark:border-gray-700/50 text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 focus:outline-none transition-all duration-300 hover:scale-110 z-50">
            <svg v-if="isDark" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
        </button>

        <div class="w-full max-w-md bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-white/20 dark:border-gray-700/30 p-8 sm:p-10 relative z-10 transition-colors duration-300 my-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-500 text-white mb-6 shadow-xl shadow-indigo-500/30 transform hover:scale-105 transition-transform duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Criar Conta</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Junte-se ao sistema de Prontuário</p>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 ml-1 mb-1">Nome Completo</label>
                    <input id="name" type="text" v-model="form.name" required autofocus class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white/50 dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-500/50 transition-all shadow-sm placeholder-gray-400" placeholder="João da Silva" />
                    <p v-if="form.errors.name" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 ml-1 mb-1">E-mail</label>
                    <input id="email" type="email" v-model="form.email" required class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white/50 dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-500/50 transition-all shadow-sm placeholder-gray-400" placeholder="seu@email.com" />
                    <p v-if="form.errors.email" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ form.errors.email }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 ml-1 mb-1">Senha</label>
                        <input id="password" type="password" v-model="form.password" required class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white/50 dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-500/50 transition-all shadow-sm placeholder-gray-400" placeholder="••••••••" />
                        <p v-if="form.errors.password" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ form.errors.password }}</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 ml-1 mb-1">Confirmar</label>
                        <input id="password_confirmation" type="password" v-model="form.password_confirmation" required class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white/50 dark:bg-gray-900/50 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-500/50 transition-all shadow-sm placeholder-gray-400" placeholder="••••••••" />
                        <p v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ form.errors.password_confirmation }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4">
                    <Link href="/login" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">
                        Já tem conta? Entrar
                    </Link>

                    <button type="submit" :disabled="form.processing" class="inline-flex justify-center py-3 px-6 border border-transparent rounded-xl shadow-lg shadow-indigo-500/30 text-base font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 focus:outline-none transition-all duration-200" :class="{ 'opacity-75 cursor-not-allowed': form.processing }">
                        Cadastrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
