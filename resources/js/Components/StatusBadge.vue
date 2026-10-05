<script setup>
defineProps({
    label: String,
    color: { type: String, default: 'neutral' },
});
</script>

<template>
    <span
        class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-medium"
        :class="{
            'bg-emerald-50 text-emerald-700': color === 'emerald',
            'bg-[#dcfce7] text-[#166534]': color === 'bottle',
            'bg-amber-50 text-amber-700': color === 'amber',
            'bg-red-50 text-red-700': color === 'red',
            'bg-neutral-100 text-neutral-600': color === 'neutral',
        }"
    >
        <span class="w-1.5 h-1.5 rounded-full"
              :class="{
                  'bg-emerald-500': color === 'emerald',
                  'bg-[#166534]': color === 'bottle',
                  'bg-amber-500': color === 'amber',
                  'bg-red-500': color === 'red',
                  'bg-neutral-400': color === 'neutral',
              }"></span>
        {{ label }}
    </span>
</template>