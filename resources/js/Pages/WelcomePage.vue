<script setup>

import {onMounted, ref} from "vue";

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
    },
    title: {
        type: String,
        required: true
    },
    permissionTo: {
        type: Boolean,
    }
});

const currentAct = ref(props.currentAct);

const dayIdRef = ref(props.dayId);
const dayRef = ref(props.day);

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
        dayRef.value = e.currentDay;

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
    <div
        class="h-screen w-screen flex flex-col backstageScreen bg-gray-100 dark:bg-gray-800 dark:text-white lg:p-4 p-2">
        <div class="flex w-full pb-4 px-2 h-6 overflow-hidden">
            <h1 class="text-4xl font-bold my-auto truncate">{{ title }}</h1>
            <h2 class="text-3xl ml-auto my-auto flex-none">{{ dayRef.name }}</h2>
        </div>
        <div class="rounded-xl h-full w-full"
             style="">
            <div style="padding: 1rem; "
                 class="flex flex-col  overflow-y-auto w-full overflow-x-hidden bg-white dark:bg-gray-900 rounded-xl">
                <h2 class="text-3xl font-bold mb-4">Acts</h2>
                <div class="bg-white dark:bg-gray-900 rounded-xl lg:pr-4 flex flex-col gap-4 overflow-y-auto"
                     style="height: 100vh"
                     ref="actsView">
                    <div v-for="act in actsByDay[dayIdRef]" ref="vfortje" :id.attr="act.id" :key="act.id"
                         class="mb-2 bg-gray-100 dark:bg-gray-800 p-4 rounded-lg w-full"
                         :style="{'background-color': act.id === currentAct.id ? 'rgba(60,255,234,0.53)' : ''}">
                        <p v-if="!act.hidden || permissionTo"
                           class="text-lg font-bold text-gray-700 dark:text-gray-300 -mb-2">
                            {{ act.type }}</p>
                        <div class="flex justify-between gap-4" v-if="!act.hidden  || permissionTo">
                            <h3 class="text-2xl font-bold truncate">{{ act.name.replace('<br>', ' & ') }}</h3>
                            <h3 class="text-2xl font-bold">{{ act.start_time.substring(0, 5) }}</h3>
                        </div>
                        <p class="text-xl truncate text-gray-700 dark:text-gray-300"
                           v-if="act.display_type !== 'Pauze' && (!act.hidden || permissionTo)">{{ act.people }}</p>
                        <div v-if="act.hidden && !permissionTo" class="flex w-full justify-between gap-4">
                            <div>
                                <p class="text-lg font-bold text-gray-700 dark:text-gray-300 -mb-2">{{ act.type }}</p>
                                <h3 class="text-2xl font-bold text-red-400">
                                    De naam en deelnemer(s) van deze act zijn verborgen
                                </h3>
                            </div>
                            <h3 class="text-2xl font-bold my-auto">{{ act.start_time.substring(0, 5) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
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

