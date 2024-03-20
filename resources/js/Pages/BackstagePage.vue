<script setup>

import {onMounted, ref, watch} from "vue";

const props = defineProps({
    currentAct: {
        type: Object,
        required: true
    },
    acts: {
        type: Array,
        required: true
    },
    dayId: {
        type: Number,
        required: true
    },
    day: {
        type: Object,
        required: true
    }
});

const currentAct = ref(props.currentAct);

const dayIdRef = ref(props.dayId);

const actsByDay = ref({});
const actsToShow = ref([]);

for (let i = 0; i < props.acts.length; i++) {
    const act = props.acts[i];
    const day = act.day;

    if (actsByDay.value[day] === undefined) {
        actsByDay.value[day] = [];
    }

    actsByDay.value[day].push(act);
}

const days = Object.keys(actsByDay.value);

for (let i = 0; i < days.length; i++) {
    const day = days[i];
    actsByDay.value[day].sort((a, b) => {
        return a.start_time.localeCompare(b.start_time);
    });
}

const countdown = ref(-1);
const showCountdown = ref(false);
const actsView = ref(null);
const vfortje = ref(null);
const nextAct = ref(null);

actsToShow.value = actsByDay.value[dayIdRef.value];

let index = actsToShow.value.findIndex((act) => {
    return act.id === currentAct.value.id;
});

if (index !== -1) {
    nextAct.value = actsToShow.value[index + 1];
}


onMounted(() => {
    window.Echo.private('act-update')
        .listen('UpdateAct', (e) => {
            console.log("UpdateAct", e);
            currentAct.value = props.acts.find((act) => {
                return act.id === e.act.id;
            });

            let index = actsToShow.value.findIndex((act) => {
                return act.id === e.act.id;
            });

            if (index !== -1) {
                nextAct.value = actsToShow.value[index + 1];
            }

            scroll()
        })
        .listen('Countdown', (e) => {
            console.log(e);
            countdown.value = e.status === 'active' ? e.time : -1;
            showCountdown.value = e.status === 'active';
        }).listen('UpdateAllActs', (e) => {
        currentAct.value = e.currentAct;

        dayIdRef.value = e.currentDay.id;

        actsByDay.value = {};

        for (let i = 0; i < e.allActs.length; i++) {
            const act = e.allActs[i];
            const day = act.day;

            if (actsByDay.value[day] === undefined) {
                actsByDay.value[day] = [];
            }

            actsByDay.value[day].push(act);
        }

        days.value = Object.keys(actsByDay.value);

        for (let i = 0; i < days.value.length; i++) {
            const day = days.value[i];
            actsByDay.value[day].sort((a, b) => {
                return a.start_time.localeCompare(b.start_time);
            });
        }

        actsToShow.value = actsByDay.value[dayIdRef.value];

        let index = actsToShow.value.findIndex((act) => {
            return act.id === currentAct.value.id;
        });

        if (index !== -1) {
            nextAct.value = actsToShow.value[index + 1];
        }



        scroll();
    });

    scroll()
});


function scroll() {
    let scrollTo = vfortje.value.filter((act) => {
        return act.id == currentAct.value.id;
    })

    // scroll to current act
    actsView.value.scrollTo({
        top: scrollTo[0].offsetTop - 300,
        behavior: 'smooth'
    });
}

</script>

<template>
    <div style="padding: 2rem;"
         class="h-screen w-screen flex flex-col backstageScreen bg-gray-100 dark:bg-gray-800 dark:text-white">
        <h1 class="text-5xl font-bold mb-4 flex-none">Backstage</h1>
        <div class=" rounded-xl h-full grid w-full gap-4"
             style="grid-template-columns: repeat(9, minmax(0, 1fr));">
            <div style="grid-column: 1/ span 2; padding: 1rem; "
                 class="flex flex-col overflow-y-auto w-full overflow-x-hidden bg-white dark:bg-gray-900 rounded-xl">
                <h2 class="text-3xl font-bold mb-4">Acts</h2>
                <div class="bg-white dark:bg-gray-900 rounded-xl pr-4 flex flex-col gap-4 overflow-y-auto"
                     style="height: 100vh"
                     ref="actsView">
                    <div v-for="act in actsByDay[dayIdRef]" ref="vfortje" :id.attr="act.id" :key="act.id"
                         class="mb-2 bg-gray-100 dark:bg-gray-800 p-4 rounded-lg"
                         :style="{'background-color': act.id === currentAct.id ? 'rgba(60,255,234,0.53)' : ''}">
                        <p class="text-lg font-bold text-gray-700 dark:text-gray-300 -mb-2">{{ act.type }}</p>
                        <div class="flex justify-between gap-4">
                            <h3 class="text-2xl font-bold truncate">{{ act.name }}</h3>
                            <h3 class="text-2xl font-bold">{{ act.start_time.substring(0, 5) }}</h3>
                        </div>
                        <p class="text-xl truncate text-gray-700 dark:text-gray-300">{{ act.people }}</p>
                    </div>
                </div>
            </div>
            <div style="grid-column: 3 / span 7; padding: 0;">
                <!-- A grid with 2 columns in one the current act and in the other one the next act                    -->
                <div class="grid gap-4 h-full overflow-hidden" :class="nextAct?'grid-rows-2':''">
                    <div class="rounded-xl flex flex-col bg-white dark:bg-gray-900 p-4">
                        <div class="flex justify-between w-full overflow-hidden ">
                            <div class="text-3xl font-bold mb-4 mr-4 flex truncate">Current Act: <h2
                                class="ml-4 truncate">
                                {{ currentAct.name }}</h2></div>
                            <h3 class="text-2xl font-bold flex-none">{{ currentAct.type }}</h3>
                        </div>
                        <div class="w-full">
                            <p class="text-xl text-gray-700 dark:text-gray-300">{{ currentAct.people }}</p>

                            <!-- Material Required area                            -->
                            <div class="pt-4 mt-3 border-t-2">
                                <h3 class="text-3xl font-bold">Material Required</h3>
                                <div v-for="material in currentAct.material_required.split(',')" :key="material">
                                    <h3 class="text-2xl ml-2">- {{ material }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="nextAct" class="bg-white dark:bg-gray-900 rounded-xl p-4 flex flex-col">
                        <div class="flex justify-between w-full overflow-hidden ">
                            <div class="text-3xl font-bold mb-4 mr-4 flex truncate">Next Act: <h2 class="ml-4 truncate">
                                {{ nextAct.name }}</h2></div>
                            <h3 class="text-2xl font-bold flex-none">{{ nextAct.type }}</h3>
                        </div>
                        <div class="">
                            <p class="text-xl truncate text-gray-700 dark:text-gray-300">{{ nextAct.people }}</p>

                            <!-- Material Required area                            -->
                            <div class="pt-4 mt-3 border-t-2">
                                <h3 class="text-3xl font-bold">Material Required</h3>
                                <div v-for="material in nextAct.material_required.split(',')" :key="material">
                                    <h3 class="text-2xl ml-2">- {{ material }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--                <div class="bg-white rounded-xl p-4 flex flex-col gap-4 mt-4">
                                    <h2 class="text-3xl font-bold mb-4">Countdown</h2>
                                    <h3 class="text-2xl font-bold truncate">{{ countdown }}</h3>
                                </div>-->
            </div>
        </div>

    </div>
    <div v-if="showCountdown" class="absolute bottom-0 right-0 w-screen h-screen">
        <div class="absolute flex rounded-2xl text-white"
             style="top: 2rem; right: 2rem; left: 2rem; bottom: 2rem; background-color: #164600">
            <p v-if="countdown !== 0" class="mx-auto my-auto"
               style="font-family: 'Seven Segment', sans-serif; font-size: 2000%">
                00:{{ countdown.toString().padStart(2, '0') }}</p>
            <p v-else class="mx-auto my-auto" style="font-family: 'Seven Segment2', sans-serif; font-size: 2500%">
                SHOWTIME</p>
        </div>

    </div>
    <p style="font-family: 'Seven Segment'; height: 0; width: 0; overflow: hidden">I Hate you SHWOTIME</p>
    <p style="font-family: 'Seven Segment2'; height: 0; width: 0; overflow: hidden">I Hate you SHWOTIME</p>
</template>

<style scoped>

.container {
    padding: 0 20px;
}

.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 100%;
}

.-mb-2 {
    margin-bottom: -0.25rem;
}

.backstageScreen {
    height: 100vh;
}

.backstageScreen > div {
    height: calc(100% - 4rem);
}

.backstageScreen > div > div {
    height: 100%;
}

@font-face {
    font-family: 'Seven Segment';
    src: url('/assets/fonts/SevenSegment.ttf');
}

@font-face {
    font-family: 'Seven Segment2';
    src: url('/assets/fonts/Seven-Segment.ttf');
}
</style>
