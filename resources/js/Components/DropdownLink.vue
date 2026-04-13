<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    href: string;
    active?: boolean;
}>();

const classes = computed(() => {
    return props.active
        ? 'block px-4 py-2 text-sm font-medium text-indigo-600 dark:text-indigo-400 bg-gray-50 dark:bg-gray-600'
        : 'block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white';
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
