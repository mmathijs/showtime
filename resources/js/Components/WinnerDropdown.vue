<script setup>

import {ref} from "vue";

const props = defineProps({
    acts: {
        type: Array,
        required: true
    },
    winners: {
        type: Array,
        required: true
    },
    currentAct: {
        type: Object,
        required: true
    }
});

const modelValue = defineModel()

const expanded = ref(false);

function getB(act) {
    if (props.currentAct.name === "Dans en Beweging") {
        return act.type === "Dans Solo" || act.type === "Dans Ensemble"
    }


    return act.type.toLowerCase().startsWith(props.currentAct.name.toLowerCase());
}

const possibleWinners = ref(props.acts.filter(act => getB(act)).map(act => act.type + '+=' + act.name + act.name === act.people ? '' : (': ' + act.people)));

const custom = ref('');

function updateWinner() {
    console.log(model.value);
}

</script>

<template>
    <div class="w-full flex">
        <!-- A custom dropdown selector, choose out of the possibleWinners array, without vuetify -->
        <div class="w-full">
            <div ref="dropdown" class="flex items-center w-full justify-between border border-gray-300 rounded-md p-2"
                 @click="expanded = !expanded">
                <span class="w-full" v-if="modelValue">{{ modelValue }}</span>
                <span v-else class="w-full text-gray-400">Select a winner</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     :style="`transform: rotate(${expanded ? '180deg' : '0deg'})`"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
            <div v-if="expanded"
                 class="absolute z-10 mt-2 overflow-hidden bg-gray-800 border border-gray-300 rounded-md"
                 :style="`width:${$refs.dropdown.clientWidth}px`">
                <div v-for="winner in possibleWinners" :key="winner"
                     class="p-3 hover:bg-gray-900 cursor-pointer px-4 truncate"
                     @click="modelValue = winner; expanded = false">
                    {{ winner }}
                </div>
                <div class="p-1 hover:bg-gray-900 cursor-pointer">
                    <input type="text" v-model="custom" @input="modelValue = custom" placeholder="Custom"
                           class="w-full bg-transparent border-none focus:ring-0">
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
