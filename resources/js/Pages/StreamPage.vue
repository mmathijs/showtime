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
        }).listen('UpdateAllActs', (e) => {
            act.value = e.currentAct;
        }
    );
});

</script>

<template>
    <div class="w-screen h-screen relative" style="background-color: #6f0599">
        <!--        <div class="absolute top-0 left-0 z-20 bg-white p-4 m-4 rounded-lg overflow-hidden">-->
        <!--            <img src="/assets/images/logo.svg" width="250">-->
        <!--&lt;!&ndash;            <img src="/assets/images/download.png" width="150">&ndash;&gt;-->
        <!--        </div>-->
        <div v-if="show" class="w-screen overflow-hidden z-10 h-screen absolute top-0 left-0 animation">
            <div class="left-half absolute h-full" style="background-color: blue"></div>
            <div class="middle-logo z-40 rounded-full bg-white absolute overflow-hidden">
                <img class="w-full p-6 top-50 absolute" src="/assets/images/img.png" alt="logo">
            </div>
            <div class="right-half absolute right-0 h-full" style="background-color: blue"></div>
        </div>

        <div v-if="act.display_type=== 'ActSingle' || act.display_type=== 'ActBig'"
             class="absolute flex flex-col bottom-0 left-0 rounded-lg overflow-hidden w-screen h-screen">
            <div v-if="!act.hidden" class="m-4 absolute bottom-0">
                <div class="bg-white p-4 w-60 rounded-t-lg">
                    <img src="/assets/images/logo.svg">
                </div>
                <div
                    class="bg-blue-800 h-24 p-4  text-white text-5xl flex place-items-center rounded-b-xl rounded-r-xl"
                    style="width: 600px; text-align: right">
                    {{ act.type }}
                    <p></p>
                </div>
            </div>
            <div v-else
                 class="w-screen h-screen absolute bg-blue-900 flex flex-col flex-wrap items-center justify-center">
                >
                <div class="absolute top-0 left-0 bg-white p-4 m-4 rounded-lg overflow-hidden">
                    <img src="/assets/images/logo.svg" width="250">
                    <!--            <img src="/assets/images/download.png" width="150">-->
                </div>
                <div style="max-width: 1000px" class="text-center my-auto mx-auto gap-1 flex flex-col text-white">
                    <h2 class="text-4xl text-gray-300 font-bold">{{ act.type }}</h2>
                    <h1 class="text-6xl font-semibold" v-html="act.name"></h1>
                    <p class="text-4xl my-4 font-semibold">Vanwege privacy redenen is deze act niet live te volgen</p>
                </div>

            </div>
        </div>
        <div v-else-if="act.display_type==='Pauze'|| act.display_type==='Inloop'"
             class="w-screen h-screen absolute bg-blue-900 flex flex-col flex-wrap items-center justify-center">
            <div class="absolute top-0 left-0 bg-white p-4 m-4 rounded-lg overflow-hidden">
                <img src="/assets/images/logo.svg" width="250">
                <!--            <img src="/assets/images/download.png" width="150">-->
            </div>
            <div style="max-width: 1000px">
                <h1 class="text-7xl font-semibold text-white text-center" v-if="act.display_type!=='Inloop'">{{ act.name }}</h1>
                <h2 class="text-6xl font-semibold text-white text-center">{{ act.description }}</h2>
            </div>
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
