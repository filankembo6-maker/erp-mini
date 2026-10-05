<script setup>
defineProps({
    type: { type: String, default: 'submit' },
    disabled: Boolean,
});
</script>

<template>
    <button
        :type="type"
        :disabled="disabled"
        class="inline-flex items-center justify-center gap-2 h-10 px-4 rounded-xl text-[13px] font-semibold bg-[#166534] hover:bg-[#14532d] active:bg-[#052e16] text-white transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-[#166534] focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
    >
        <slot />
    </button>
</template>