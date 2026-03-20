<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps<{
    modelValue: string | number | null;
    options: any[];
    labelKey: string;
    valueKey: string;
    placeholder?: string;
}>();

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);
const searchQuery = ref('');
const selectRef = ref<HTMLElement | null>(null);
const searchInputRef = ref<HTMLInputElement | null>(null);

const filteredOptions = computed(() => {
    if (!searchQuery.value) return props.options;
    const lowerQuery = searchQuery.value.toLowerCase();
    return props.options.filter(opt => 
        String(opt[props.labelKey] || '').toLowerCase().includes(lowerQuery)
    );
});

const selectedOption = computed(() => {
    return props.options.find(opt => opt[props.valueKey] === props.modelValue);
});

const selectOption = (option: any) => {
    emit('update:modelValue', option[props.valueKey]);
    isOpen.value = false;
    searchQuery.value = '';
};

const toggleOpen = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        searchQuery.value = '';
        setTimeout(() => {
            searchInputRef.value?.focus();
        }, 50);
    }
};

const closeDropdown = (e: MouseEvent) => {
    if (selectRef.value && !selectRef.value.contains(e.target as Node)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', closeDropdown);
});

onUnmounted(() => {
    document.removeEventListener('click', closeDropdown);
});
</script>

<template>
    <div class="relative w-full" ref="selectRef">
        <!-- Selected Value Display -->
        <div 
            @click="toggleOpen"
            class="mt-1 flex items-center justify-between w-full rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 cursor-pointer text-gray-700 dark:text-gray-300 min-h-[42px]"
        >
            <span v-if="selectedOption" class="truncate font-medium">{{ selectedOption[labelKey] }}</span>
            <span v-else class="text-gray-400">{{ placeholder || 'Selecione...' }}</span>
            
            <svg class="h-5 w-5 text-gray-400 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </div>

        <!-- Dropdown Options -->
        <transition leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="isOpen" class="absolute z-10 mt-1 w-full rounded-md bg-white dark:bg-gray-800 shadow-xl border border-gray-200 dark:border-gray-700">
                <div class="p-2 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-750">
                    <input 
                        type="text" 
                        v-model="searchQuery" 
                        ref="searchInputRef"
                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Pesquisar..." 
                        @click.stop
                    />
                </div>
                <ul class="max-h-60 overflow-auto py-1 text-base sm:text-sm">
                    <li v-if="filteredOptions.length === 0" class="cursor-default select-none py-2 px-4 text-gray-500 dark:text-gray-400">
                        Nenhum resultado encontrado.
                    </li>
                    <li 
                        v-for="option in filteredOptions" 
                        :key="option[valueKey]"
                        @click="selectOption(option)"
                        class="relative cursor-pointer select-none py-2.5 pl-4 pr-9 hover:bg-indigo-600 hover:text-white text-gray-900 dark:text-gray-200 group"
                        :class="{'bg-indigo-100 dark:bg-indigo-900': modelValue === option[valueKey]}"
                    >
                        <span class="block truncate font-normal" :class="{'font-semibold': modelValue === option[valueKey]}">
                            {{ option[labelKey] }}
                        </span>
                        
                        <span v-if="modelValue === option[valueKey]" class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600 group-hover:text-white">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </li>
                </ul>
            </div>
        </transition>
    </div>
</template>
