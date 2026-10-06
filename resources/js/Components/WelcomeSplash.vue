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
            <!-- Halo lumineux central subtil, sans cercle dur -->
            <div
                class="absolute w-[1400px] h-[1400px] rounded-full blur-[140px] animate-pulse-slow"
                style="background: radial-gradient(circle, rgba(255,255,255,0.35) 0%, rgba(255,255,255,0.15) 30%, transparent 65%);"
            ></div>

            <!-- Halo secondaire décalé pour donner de la profondeur -->
            <div
                class="absolute w-[1000px] h-[1000px] rounded-full blur-[120px] opacity-60 animate-pulse-slow"
                style="background: radial-gradient(circle, rgba(34,211,238,0.6) 0%, transparent 60%); animation-delay: 0.8s;"
            ></div>

            <div
                class="absolute w-[900px] h-[900px] rounded-full blur-[110px] opacity-50 animate-pulse-slow"
                style="background: radial-gradient(circle, rgba(16,185,129,0.5) 0%, transparent 60%); animation-delay: 1.6s;"
            ></div>

            <!-- Logo central sans cercle, juste avec une ombre douce -->
            <div class="relative z-10">
                <div v-if="showLogo" class="relative">
                    <img
                        src="/images/logo-splash.png"
                        alt="BISALELI TECH"
                        class="relative h-[520px] w-auto splash-logo-zoom"
                        style="filter: drop-shadow(0 20px 60px rgba(0,0,0,0.25));"
                    />
                </div>
            </div>

            <!-- Barre de chargement -->
            <div
                v-if="showLogo"
                class="absolute bottom-16 flex flex-col items-center gap-3 splash-text-appear"
            >
                <div class="w-64 h-0.5 bg-white/40 rounded-full overflow-hidden">
                    <div class="splash-load-bar h-full rounded-full"></div>
                </div>
                <p class="text-[11px] text-white/85 tracking-[0.35em] uppercase font-medium">
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
    animation: logoZoom 1.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    opacity: 0;
    transform: scale(0.35);
}

@keyframes logoZoom {
    0% { opacity: 0; transform: scale(0.35); filter: blur(15px) drop-shadow(0 20px 60px rgba(0,0,0,0.25)); }
    50% { opacity: 1; transform: scale(1.06); filter: blur(0) drop-shadow(0 20px 60px rgba(0,0,0,0.25)); }
    100% { opacity: 1; transform: scale(1); filter: blur(0) drop-shadow(0 20px 60px rgba(0,0,0,0.25)); }
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
    background: linear-gradient(90deg, transparent, #ffffff, transparent);
    animation: loadBar 2.2s ease-in-out 0.6s forwards;
    width: 0%;
}

@keyframes loadBar {
    0% { width: 0%; }
    100% { width: 100%; }
}

.animate-pulse-slow { animation: pulseSlow 4s ease-in-out infinite; }

@keyframes pulseSlow {
    0%, 100% { opacity: 0.4; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.12); }
}
</style>