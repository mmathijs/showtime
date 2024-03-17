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
            console.log(e);
        })
        .listen('Countdown', (e) => {
            console.log(e);
        });
});

function launchAct(act) {
    console.log(act);
}
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
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
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Display Type
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    People
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Start Time
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Launch</span>
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="act in actsByDay['1']" :key="act.id"
                                class="border-b-2 border-gray-600 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700"
                                :style="{'background-color': act.id === currentAct.id ? 'rgba(180,205,255,0.37)' : ''}">
                                <!-- Highlight the current act -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ act.type }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ act.name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ act.display_type }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ act.people }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ act.start_time }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button
                                        @click="launchAct(act)"
                                        class="text-white bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 px-4 py-2 rounded-md"
                                    >
                                        {{ act.id === currentAct.id ? 'Active' : 'Launch'}}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <advancement-dropdown :act="act" :current-act="currentAct"/>
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
