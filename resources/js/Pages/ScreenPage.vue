<script setup>

import {onMounted, ref} from "vue";
import {Carousel, Slide} from "vue3-carousel";
import 'vue3-carousel/dist/carousel.css';

const props = defineProps({
    currentAct: {
        type: Object,
        required: true
    }
});

const currentAct = ref(props.currentAct);
const show = ref(false);

const winners = ref({});
const transitionToWinners = ref(false);
const showWinners = ref(false);

onMounted(() => {
    window.Echo.private('act-update')
        .listen('UpdateAct', (e) => {
            show.value = true;
            setTimeout(() => {
                currentAct.value = e.act;
                transitionToWinners.value = false;
            }, 1000);
            setTimeout(() => {
                show.value = false;
            }, 2000);
        })
        .listen('Countdown', (e) => {
            console.log(e);
        }).listen('UpdateAllActs', async () => {
        async function fetchUpdateAllActs() {
            const response = await fetch('/update-all');
            return await response.json();
        }

        const e = await fetchUpdateAllActs();
        currentAct.value = e.currentAct;
    }).listen('RevealWinners', (e) => {
        winners.value = e.winners;
        transitionToWinners.value = true;
    })
    ;
});

</script>

<template>
    <div class="w-screen h-screen relative bg-white dark:bg-gray-900 dark:text-white">
        <div v-if="show" class="w-screen overflow-hidden z-10 h-screen absolute top-0 left-0 animation">
            <div class="left-half absolute h-full" style="background-color: blue"></div>
            <div class="middle-logo z-40 rounded-full bg-white absolute overflow-hidden">
                <!--                <img class="w-full p-4 top-50 absolute" src="/assets/images/img.png" alt="logo">-->
                <img class="w-full p-4 top-50 absolute" src="/assets/images/ijsingwekkend.jpg" alt="logo">
            </div>
            <div class="right-half absolute right-0 h-full" style="background-color: blue"></div>
        </div>
        <div class="w-screen h-screen flex items-center">
            <div class="text-center my-auto mx-auto gap-1 flex flex-col" v-if="currentAct.display_type=== 'ActSingle'">
                <h2 class="text-4xl text-gray-300 font-bold">{{ currentAct.type }}</h2>
                <h1 class=" font-semibold leading-none" v-html="currentAct.name"
                    :class="currentAct.people == currentAct.name ? 'text-8xl mb-4' : 'text-7xl'"
                ></h1>
                <!--                {{currentAct.people + ' ' + currentAct.name}}{{currentAct.name.toString().trimStart().trimEnd() === currentAct.people.toString().trimStart().trimEnd() }}-->
                <p class="text-4xl mt-4 font-semibold" v-if="currentAct.people !== currentAct.name">Door</p>
                <p class="text-6xl mt-4 font-semibold" v-if="currentAct.people !== currentAct.name">{{
                        currentAct.people
                    }}</p>
            </div>

            <div class="text-center my-auto mx-auto gap-6 flex flex-col flex-wrap" style="max-width: 1000px"
                 v-else-if="currentAct.display_type==='Pauze'">
                <h1 class="text-7xl font-semibold">{{ currentAct.name }}</h1>
                <h2 class="text-6xl font-semibold">{{ currentAct.description }}</h2>
            </div>
            <div class="text-center my-auto mx-auto gap-6 flex flex-col flex-wrap" style="max-width: 1000px"
                 v-else-if="currentAct.display_type==='Inloop'">
                <h2 class="text-6xl font-semibold">{{ currentAct.description }}</h2>
            </div>
            <div class="text-center my-auto mx-auto gap-1 flex flex-col flex-wrap" style="max-width: 1300px"
                 v-else-if="currentAct.display_type=== 'Winnaars'">
                <div class="" :class="transitionToWinners?'titles' : ''">
                    <h2 class="text-4xl -mb-2 text-gray-300 font-bold">{{ currentAct.type }}</h2>
                    <h1 class="text-8xl font-semibold">{{ currentAct.name }}</h1>
                </div>

                <div class="w-full" v-if="transitionToWinners">
                    <carousel class="winners" autoplay="10000" :wrap-around="Object.keys(winners).length > 1"
                              transition="1000">
                        <slide v-for="(winner,winnerKey) in winners" :class="transitionToWinners?'':''"
                               class="text-3xl flex gap-4 mb-20 flex-wrap flex-col justify-center pb-20"
                               style="width: 1300px;">
                            <h1 v-if="winnerKey !== 'default'" class="text-gray-300 text-7xl mb-6 pt-4 font-semibold">
                                {{ winnerKey }}:</h1>
                            <h1 v-for="person in winner" :key="person" class="text-white mt-2 text-6xl ">{{
                                    person
                                }}{{

                                }}</h1>
                        </slide>
                    </carousel>
                </div>
            </div>
            <div class="text-center my-auto mx-auto gap-1 flex flex-col flex-wrap" style="max-width: 1000px" v-else>
                <h2 class="text-4xl -mb-2 text-gray-300 font-bold">{{ currentAct.type }}</h2>
                <h1 class="text-8xl font-semibold">{{ currentAct.name }}</h1>
                <div class="flex flex-wrap">
                    <!-- Show all people, but split the all names on the , to make sure every name is on a compelte line                    -->
                    <div class="text-3xl flex gap-2 flex-wrap justify-center" style="width: 1000px">
                        <p class="whitespace-nowrap text-center" v-for="person in currentAct.people.split(',')"
                           :key="person">{{
                                person
                            }}{{
                                currentAct.people.split(',')[currentAct.people.split(',').length - 1] === person ? '' : ','
                            }} </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<style>


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
    width: 280px;
    height: 280px;

    left: calc(50% - 140px);
    animation: scale 2s forwards;
    top: calc(50% - 140px);
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
        transform: scale(1.25);
    }
    80% {
        transform: scale(0);
    }
    100% {
        transform: scale(0);
    }
}

.flex-wrap {
    flex-wrap: wrap;
}

.titles {
    animation: moveUp 2s forwards;
}

.winners {
    animation: appear 2s forwards;
}

.titles h2 {
    animation: makeBigger 2s forwards;
}

.titles h1 {
    animation: dissapear 2s forwards;
}

/*@keyframes dissapear {
    0% {
        opacity: 1;
    }
    50% {
        opacity: 0;
        transform: translateY(0);
    }
    100% {
        opacity: 0;
        transform: translateY(100%);
    }
}*/

/*@keyframes makeBigger {
    0% {
        font-size: 2.25rem;
        line-height: 2.5rem;
        color: rgb(209 213 219);
    }
    50% {
        font-size: 4rem;
        line-height: 3.5rem;
        color: rgb(209 213 219);
    }
    100% {
        font-size: 6rem;
        line-height: 3.5rem;
        color: rgb(255, 255, 255);
    }
}*/

@keyframes moveUp {
    0% {
        transform: translateY(100%);
        opacity: 1;
    }
    50% {
        transform: translateY(0);
        opacity: 0;
    }
    75% {
        transform: translateY(0);
        opacity: 0;
    }
    100% {
        height: 0;
        overflow: hidden;
        opacity: 0;
    }
}

@keyframes appear {
    0% {
        opacity: 0;
    }
    50% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

</style>
