<script setup>
import { onMounted, ref } from 'vue';

const emit = defineEmits(['finished']);

const visible = ref(true);
const showLogo = ref(false);

onMounted(() => {
    setTimeout(() => { showLogo.value = true; }, 400);

    setTimeout(() => {
        visible.value = false;
        emit('finished');
    }, 3200);
});
</script>

<template>
    <transition name="splash">
        <div
            v-if="visible"
            class="fixed inset-0 z-[9999] flex items-center justify-center overflow-hidden"
            style="background: linear-gradient(135deg, #1e40af 0%, #22d3ee 50%, #10b981 100%);"
        >
            <!-- Halo blanc central lumineux -->
            <div
                class="absolute w-[900px] h-[900px] rounded-full blur-3xl opacity-60 animate-pulse-slow"
                style="background: radial-gradient(circle, rgba(255,255,255,0.7) 0%, rgba(255,255,255,0.3) 40%, transparent 75%);"
            ></div>

            <!-- Halos décoratifs colorés -->
            <div
                class="absolute w-[900px] h-[900px] rounded-full blur-[120px] opacity-40 animate-pulse-slow"
                style="background: radial-gradient(circle, #22d3ee 0%, transparent 70%);"
            ></div>
            <div
                class="absolute w-[800px] h-[800px] rounded-full blur-[100px] opacity-30 animate-pulse-slow"
                style="background: radial-gradient(circle, #10b981 0%, transparent 70%); animation-delay: 1.5s;"
            ></div>

            <!-- Logo central -->
            <div class="relative z-10">
                <div v-if="showLogo" class="relative">
                    <div
                        class="absolute inset-0 rounded-full blur-2xl opacity-50"
                        style="background: radial-gradient(circle, #ffffff 0%, transparent 70%); transform: scale(1.4);"
                    ></div>
                    <img
                        src="/images/logo-splash.png"
                        alt="BISALELI TECH"
                        class="relative h-[500px] w-auto splash-logo-zoom"
                    />
                </div>
            </div>

            <!-- Barre de chargement -->
            <div
                v-if="showLogo"
                class="absolute bottom-20 flex flex-col items-center gap-3 splash-text-appear"
            >
                <div class="w-64 h-1 bg-white/30 rounded-full overflow-hidden backdrop-blur">
                    <div class="splash-load-bar h-full rounded-full"></div>
                </div>
                <p class="text-xs text-white/80 tracking-[0.3em] uppercase font-medium">
                    Chargement
                </p>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.splash-leave-active { transition: opacity 0.8s ease; }
.splash-leave-to { opacity: 0; }

.splash-logo-zoom {
    animation: logoZoom 1.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    opacity: 0;
    transform: scale(0.4);
}

@keyframes logoZoom {
    0% { opacity: 0; transform: scale(0.4); filter: blur(12px); }
    50% { opacity: 1; transform: scale(1.08); filter: blur(0); }
    100% { opacity: 1; transform: scale(1); filter: blur(0); }
}

.splash-text-appear {
    animation: textAppear 1s ease-out 0.8s forwards;
    opacity: 0;
    transform: translateY(15px);
}

@keyframes textAppear {
    0% { opacity: 0; transform: translateY(15px); }
    100% { opacity: 1; transform: translateY(0); }
}

.splash-load-bar {
    background: linear-gradient(90deg, #ffffff, #f0fdfa, #ffffff);
    animation: loadBar 2.2s ease-in-out 0.6s forwards;
    width: 0%;
}

@keyframes loadBar {
    0% { width: 0%; }
    100% { width: 100%; }
}

.animate-pulse-slow { animation: pulseSlow 4s ease-in-out infinite; }

@keyframes pulseSlow {
    0%, 100% { opacity: 0.3; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.15); }
}
</style>