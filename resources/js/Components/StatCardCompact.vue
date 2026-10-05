<script setup>
defineProps({
    label: String,
    value: String,
});
</script>

<template>
    <div class="bg-[#fafaf9] rounded-2xl p-3 border border-[#e7e5e4]">
        <div class="text-[22px] font-semibold text-[#0a0a0a] leading-none tracking-[-0.02em] tabular-nums">{{ value }}</div>
        <div class="text-[11px] text-[#a3a3a3] mt-1 font-medium">{{ label }}</div>
    </div>
</template>