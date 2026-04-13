<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

defineProps<{
    label: string;
    active?: boolean;
}>();

const open = ref(false);
const dropdown = ref<HTMLElement | null>(null);

const toggle = () => {
    open.value = !open.value;
};

const close = (e: MouseEvent) => {
    if (dropdown.value && !dropdown.value.contains(e.target as Node)) {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('click', close));
onUnmounted(() => document.removeEventListener('click', close));
</script>

<template>
    <div ref="dropdown" class="relative flex items-center">
        <button
            @click="toggle"
            :class="[
                'inline-flex items-center gap-1 px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out h-full',
                active
                    ? 'border-indigo-400 dark:border-indigo-600 text-gray-900 dark:text-gray-100'
                    : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700'
            ]"
        >
            {{ label }}
            <svg
                class="w-4 h-4 transition-transform duration-200"
                :class="{ 'rotate-180': open }"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-1"
        >
            <div
                v-show="open"
                class="absolute top-full left-0 mt-1 w-48 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 z-50"
            >
                <div class="py-1" @click="open = false">
                    <slot />
                </div>
            </div>
        </Transition>
    </div>
</template>
