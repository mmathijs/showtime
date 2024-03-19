<script setup>

import {onMounted, ref} from "vue";

const props = defineProps({
    currentAct: {
        type: Object,
        required: true
    }
});

const currentAct = ref(props.currentAct);
const show = ref(false);

onMounted(() => {
    window.Echo.private('act-update')
        .listen('UpdateAct', (e) => {
            show.value = true;
            setTimeout(() => {
                currentAct.value = e.act;
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
                <h1 class="text-6xl font-semibold" v-html="currentAct.name"></h1>
                <p class="text-4xl mt-4 font-semibold">{{ currentAct.people }}</p>
            </div>

            <div class="text-center my-auto mx-auto gap-6 flex flex-col flex-wrap" style="max-width: 1000px"
                 v-else-if="currentAct.display_type==='ActBig'">
                <h1 class="text-7xl font-semibold">{{ currentAct.name }}</h1>
                <h2 class="text-6xl font-semibold">{{ currentAct.description }}</h2>
            </div>
            <div class="text-center my-auto mx-auto gap-6 flex flex-col flex-wrap" style="max-width: 1000px" v-else>
                <h1 class="text-7xl font-semibold">{{ currentAct.name }}</h1>
                <div class="flex flex-wrap">
                    <!-- Show all people, but split the all names on the , to make sure every name is on a compelte line                    -->
                    <div class="text-3xl flex gap-2 flex-wrap h-32  justify-center" style="width: 1000px">
                        <p class="whitespace-nowrap text-center" v-for="person in currentAct.people.split(',')"
                           :key="person">{{
                                person
                            }}{{ currentAct.people.split(',')[currentAct.people.split(',').length - 1] === person ? '' : ',' }} </p>
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

</style>
