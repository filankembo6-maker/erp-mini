<script setup>
defineProps({
    title: String,
    subtitle: String,
});
</script>

<template>
    <div class="flex flex-wrap items-end justify-between gap-6 mb-6">
        <div>
            <h1 class="text-[24px] font-semibold text-[#0a0a0a] tracking-[-0.02em] leading-tight">{{ title }}</h1>
            <p v-if="subtitle" class="text-[13px] text-[#525252] mt-1.5">{{ subtitle }}</p>
        </div>
        <div>
            <slot name="actions" />
        </div>
    </div>
</template>