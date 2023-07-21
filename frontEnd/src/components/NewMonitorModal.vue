<script setup lang="ts">
import { AUTH_BACKEND_URL } from '@/env';
import { fetchFn } from '@/helpers/FetchFn';
import { formValidate } from '@/helpers/FormHandler';
import { ignoredKeys } from '@/helpers/IgnoredKeys';
import router from '@/router';
import { ref } from 'vue';


let tagList = ref<string[]>([])
let errorForm = ref()

const handleSubmition = async function (event: Event) {
    let newMonitorForm = event.currentTarget as HTMLFormElement
    let formData = Object.fromEntries(new FormData(newMonitorForm))
    Object.assign(formData, { tags: JSON.stringify(tagList.value) })
    const validateForm = formValidate(formData, {
        required: {
            applyTo: ['friendlyName', 'url', 'interval']
        }
    })
    // send the req to backend and return that tags array in response 
    if (!validateForm.length) {
        let response = await fetchFn(AUTH_BACKEND_URL + '/newMonitor', 'POST', true, JSON.stringify(formData))
        let result = await response.json();
        console.log(result)
        return window.location.reload();
    } else {
        errorForm.value = validateForm
    }
}

const tagSnippet = function (keyboradEv: KeyboardEvent) {
    if (ignoredKeys.includes(keyboradEv.key)) return;
    if ([" ", ","].includes(keyboradEv.key) && keyboradEv.currentTarget.value.length >= 3) {
        //clearn the tag array & push the word to taglist

        tagList.value.push((keyboradEv.currentTarget.value).slice(0, -1))
        console.log(tagList.value)
        return keyboradEv.currentTarget.value = null;
    }
}
/*
what's this component's purpose, what should it do?
this is a form that's gonna be filled with new monitor info (friendlyName,url,intervalOfRequests,retriesBeforeFailure,tagsList) 
*/
</script>

<template>
    <div>
        <form class="flex w-40 flex-wrap" @submit.prevent="handleSubmition">
            <label for="friendlyName">
                Friendly Name:
                <br>
                <input type="text" placeholder="e.g: google" name="friendlyName">
                <div v-for="error in errorForm" :key="error">
                    <span class="text-sm text-red-600">{{ error?.friendlyName?.message }}</span>
                </div>
            </label>
            <label for="url">
                Url:
                <br>
                <input type="text" placeholder="e.g: https://google.com" name="url">
                <div v-for="error in errorForm" :key="error">
                    <span class="text-sm text-red-600">{{ error?.url?.message }}</span>
                </div>
            </label>
            <label for="interval">
                Interval: <i>(in minutes)</i> <br>
                <input type="number" name="interval" min="1" placeholder="10">
                <div v-for="error in errorForm" :key="error">
                    <span class="text-sm text-red-600">{{error?.interval?.message}}</span>
                </div>
            </label>
            <label for="tags">
                Tags: <br>
                <input type="text" class="block" @keyup="tagSnippet">
                <div id="collected-tags" v-for="tag in tagList" :key="tag" class="flex bg-gray-200">
                    <!-- foreach loop over a ref tagList and display it here -->
                    <ul>{{ tag }}</ul>
                </div>
            </label>
            <button type="submit">New Monitor</button>
        </form>
    </div>
</template>
