<script setup>
defineProps({
    title: String,
    description: String,
});
</script>

<template>
    <div class="flex flex-col items-center justify-center py-20 px-6 text-center">
        <div class="w-14 h-14 rounded-2xl bg-[#fafaf9] border border-[#e7e5e4] flex items-center justify-center mb-5">
            <svg class="w-7 h-7 text-[#a3a3a3]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
        </div>
        <h3 class="text-[15px] font-semibold text-[#0a0a0a]">{{ title }}</h3>
        <p class="text-[13px] text-[#525252] mt-1.5 max-w-sm">{{ description }}</p>
        <div class="mt-6">
            <slot name="action" />
        </div>
    </div>
</template>