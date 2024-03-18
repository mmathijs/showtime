<script setup>
import "../../css/app.css";

import {onMounted, ref} from "vue";

const props = defineProps({
    currentAct: {
        type: Object,
        required: true
    }
});

const act = ref(props.currentAct);
const show = ref(false);

onMounted(() => {
    window.Echo.private('act-update')
        .listen('UpdateAct', (e) => {
            show.value = true;
            setTimeout(() => {
                act.value = e.act;
            }, 1000);
            setTimeout(() => {
                show.value = false;
            }, 2000);
        })
        .listen('Countdown', (e) => {
            console.log(e);
        });
});

</script>

<template>
    <div class="w-screen h-screen relative" style="background-color: #6f0599">
        <div v-if="show" class="w-screen overflow-hidden z-10 h-screen absolute top-0 left-0 animation">
            <div class="left-half absolute h-full" style="background-color: blue"></div>
            <div class="middle-logo z-40 rounded-full bg-white absolute overflow-hidden">
                <img class="w-full p-6 top-50 absolute" src="/assets/images/img.png" alt="logo">
            </div>
            <div class="right-half absolute right-0 h-full" style="background-color: blue"></div>
        </div>

        <div v-if="act.display_type=== 'ActSingle'"
             class="rounded-xl bg-blue-900 h-24 text-3xl p-4  text-white text-5xl absolute bottom-0 right-0 m-4 flex place-items-center"
             style="width: 600px; text-align: right">
            {{ act.type }}
            <p></p>
        </div>
        <div v-else-if="act.display_type==='Pauze'"
             class="w-screen h-screen absolute bg-blue-900 flex flex-col items-center justify-center">
            <h1 class="text-7xl font-semibold text-white text-center">{{ act.type }}</h1>
            <h2 class="text-6xl font-semibold text-white text-center">{{ act.name }}</h2>

        </div>
    </div>
</template>

<style>

.w-screen {
    width: 100vw;
}

.h-screen {
    height: 100vh;
}

.right-half, .left-half {
    animation: slide 2s forwards;
    width: 75%;
}

.right-half {
    --multiplier: 1;
}

.left-half {
    --multiplier: -1;
}

@keyframes slide {
    0% {
        transform: translateX(calc(var(--multiplier) * 100%));
    }
    50% {
        transform: translateX(0);
    }

    100% {
        transform: translateX(calc(var(--multiplier) * 100%));
    }
}

.middle-logo {
    width: 240px;
    height: 240px;

    left: calc(50% - 120px);
    animation: scale 2s forwards;
    top: calc(50% - 120px);
}

.middle-logo img {
    top: 50%;
    transform: translateY(-50%);
}

@keyframes scale {
    0% {
        transform: scale(0);
    }
    20% {
        transform: scale(0);
    }
    50% {
        transform: scale(1);
    }
    80% {
        transform: scale(0);
    }
    100% {
        transform: scale(0);
    }
}

</style>
