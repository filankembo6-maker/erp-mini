<script setup>
defineProps({
    modelValue: String,
    placeholder: { type: String, default: 'Rechercher...' },
});

defineEmits(['update:modelValue']);
</script>

<template>
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
            <svg class="w-4 h-4 text-[#6e7681]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :placeholder="placeholder"
            type="search"
            class="block w-full h-8 pl-8 pr-3 rounded-md border border-[#2d333b] bg-[#0d1117] text-sm text-[#e6edf3] placeholder-[#6e7681] focus:outline-none focus:border-[#2f81f7] focus:ring-1 focus:ring-[#2f81f7]"
        />
    </div>
</template>