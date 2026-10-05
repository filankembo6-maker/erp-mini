<script setup>
import { onMounted, ref } from 'vue';

const emit = defineEmits(['finished']);

const visible = ref(true);

onMounted(() => {
    setTimeout(() => {
        visible.value = false;
        setTimeout(() => emit('finished'), 600);
    }, 2200);
});
</script>

<template>
    <transition name="splash">
        <div
            v-if="visible"
            class="fixed inset-0 z-[9999] flex items-center justify-center overflow-hidden bg-[#0a0a0a]"
        >
            <!-- Halo vert discret -->
            <div class="absolute inset-0 opacity-30"
                 style="background: radial-gradient(circle at 50% 50%, rgba(22,101,52,0.4) 0%, transparent 55%);">
            </div>

            <!-- Grain fin -->
            <div class="absolute inset-0 opacity-[0.015]"
                 style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 4px 4px;">
            </div>

            <!-- Logo central -->
            <div class="relative z-10">
                <div class="splash-logo">
                    <img
                        src="/images/logo-splash.png"
                        alt="BISALELI TECH"
                        class="h-20 w-auto brightness-0 invert"
                    />
                </div>

                <!-- Ligne sous le logo -->
                <div class="splash-line mx-auto mt-8 h-px w-16 bg-[#166534]"></div>

                <!-- Nom -->
                <div class="text-center mt-6 splash-sub">
                    <p class="text-[11px] text-[#525252] tracking-[0.4em] uppercase font-medium">
                        BISALELI TECH
                    </p>
                </div>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.splash-leave-active {
    transition: opacity 0.6s ease;
}
.splash-leave-to {
    opacity: 0;
}

.splash-logo {
    animation: logoSettle 1.2s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    opacity: 0;
    transform: translateY(8px);
}

@keyframes logoSettle {
    0% { opacity: 0; transform: translateY(8px); }
    100% { opacity: 1; transform: translateY(0); }
}

.splash-line {
    animation: lineGrow 0.9s ease-out 0.6s forwards;
    transform-origin: center;
    transform: scaleX(0);
}

@keyframes lineGrow {
    0% { transform: scaleX(0); opacity: 0; }
    100% { transform: scaleX(1); opacity: 1; }
}

.splash-sub {
    animation: textFadeIn 0.8s ease-out 1.1s forwards;
    opacity: 0;
}

@keyframes textFadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
}
</style>