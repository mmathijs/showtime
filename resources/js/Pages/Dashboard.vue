<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {onMounted} from "vue";
import '../echo.js';

onMounted(() => {
    window.Echo.private('act-update')
        .listen('UpdateAct', (e) => {
            console.log(e);
        })
        .listen('Countdown', (e) => {
            console.log(e);
        });
});
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
                    <div class="p-6 text-gray-900 dark:text-gray-100">You're logged in!</div>

                    <div class="p-6 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Your
                            Roles:</h2>
                        <ul class="pl-2">
                            <li v-for="role in $page.props.roles" :key="role.id">
                                - {{ role.name }}
                            </li>
                        </ul>
                    </div>

                    <div class="p-6 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Your
                            Update act-update channel:</h2>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
