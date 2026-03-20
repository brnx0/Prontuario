<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    links: any[];
}>();
</script>

<template>
    <div v-if="links && links.length > 3" class="mt-4 flex justify-end">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, p) in links" :key="p">
                <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border border-gray-200 dark:border-gray-700 rounded bg-white dark:bg-gray-800" v-html="link.label" />
                <Link v-else class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border border-gray-300 rounded hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 focus:border-indigo-500 focus:text-indigo-500" :class="{ 'bg-blue-600 text-white hover:bg-blue-700': link.active }" :href="link.url" v-html="link.label" />
            </template>
        </div>
    </div>
</template>
