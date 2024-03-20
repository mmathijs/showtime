<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {onMounted, ref} from "vue";
import '../echo.js';
import AdvancementDropdown from "@/Components/AdvancementDropdown.vue";

const props = defineProps({
    acts: {
        type: Array,
        required: true
    },
    currentAct: {
        type: Object,
        required: true
    }
});

const currentAct = ref(props.currentAct);

const actsByDay = ref({});

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


onMounted(() => {
    window.Echo.private('act-update')
        .listen('UpdateAct', (e) => {
            console.log("UpdateAct", e);
            currentAct.value = props.acts.find((act) => {
                return act.id === e.act.id;
            });
        })
        .listen('Countdown', (e) => {
            console.log(e);
        });
});

function launchAct(act) {
    if (act.id === currentAct.value.id) {
        axios.post('/act/start', {
            act_id: act.id
        })
            .then((response) => {
                console.log(response);
            })
            .catch((error) => {
                console.log(error);
            });
        return;
    }

    axios.post('/act/launch', {
        act_id: act.id
    })
        .then((response) => {
            console.log(response);
        })
        .catch((error) => {
            console.log(error);
        });
}
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class=" sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-x-auto shadow-sm sm:rounded-lg">
                    <!--                    <div class="p-6 font-semibold text-2xl text-gray-900 dark:text-gray-100">Acts:</div>-->

                    <!-- Table with act.type, act.name, act.display_type, act.people, start_time    -->
                    <div class="p-6 pb-20">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Type
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Name
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Display Type
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    People
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Start Time
                                </th>

                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Privacy
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    Edit
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="act in actsByDay['1']" :key="act.id"
                                class="border-b-2 border-gray-600 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700"
                                :style="{'background-color': act.id === currentAct.id ? 'rgba(180,205,255,0.12)' : ''}">
                                <!-- Highlight the current act -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ act.type }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap w-32">
                                    <div class="text-sm text-gray-900 dark:text-gray-100 w-60">
                                        <p class="truncate">{{ act.name }}</p></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ act.display_type }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900 dark:text-gray-100 overflow-auto"
                                       style="max-height: 40px">{{ act.people }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ act.start_time }}</div>
                                </td>
                                <!-- Show if privacy mode is enabled                                -->
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div v-if="act.hidden" class="text-red-600 dark:text-red-400">Hidden</div>
                                    <div v-else class="text-green-600 dark:text-green-400">Shown</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <button
                                        @click="launchAct(act)"
                                        class="text-white hover:bg-indigo-700 dark:hover:bg-indigo-600 px-4 py-2 rounded-md w-full"
                                        :class="act.id === currentAct.id ? 'bg-green-200  dark:bg-green-500' : 'bg-indigo-600  dark:bg-indigo-500'"
                                    >
                                        {{ act.id === currentAct.id ? 'Start' : 'Launch' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button
                                        @click="editAct(act)"
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-600"
                                    >
                                        Edit
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 100%;
}
</style>
