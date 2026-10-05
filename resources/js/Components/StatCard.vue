<script setup>
defineProps({
    label: String,
    value: String,
    unit: String,
    sparkline: Array,
});
</script>

<template>
    <div class="bg-white rounded-2xl p-4 border border-[#e7e5e4]">
        <div class="text-[11px] font-semibold text-[#525252] mb-3">{{ label }}</div>

        <div v-if="sparkline" class="flex items-end gap-0.5 h-8 mb-3">
            <div
                v-for="(h, i) in sparkline"
                :key="i"
                class="flex-1 rounded-sm bg-[#166534]"
                :style="{ height: h + '%' }"
            ></div>
        </div>

        <div class="flex items-baseline gap-1.5">
            <span class="text-[22px] font-semibold text-[#0a0a0a] tracking-[-0.02em] leading-none tabular-nums">{{ value }}</span>
            <span v-if="unit" class="text-[11px] text-[#a3a3a3]">{{ unit }}</span>
        </div>
    </div>
</template>