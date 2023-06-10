<script setup lang="ts">
import CardMonitorsComp from '@/components/CardMonitorsComp.vue'
import DashboardLayout from '../layouts/DashboardLayout.vue'

import { onBeforeMount, ref } from 'vue'
import { fetchFn } from '@/helpers/FetchFn'
import { MonitorData } from '@/types'
import { AUTH_BACKEND_URL } from '@/env'
import router from '@/router'

// have a beforeMounted hook to the page for fetching data & also to check whether the session cookie expired


let fetchedData = ref<{ body: { User: {}[], Monitors: object[] } }>();
let loading = ref(false);
let monitors = ref<Array<object>>([]);
let monitorsMetadata = ref<MonitorData>({ totalMonitors: null, up: null, down: null, paused: null, sslCertificateCount: null });
onBeforeMount(async () => {
    loading.value = true
    let response = await fetchFn(AUTH_BACKEND_URL + '/dashboard', 'GET', false)
    if (response.status != (401 || 419)) {
        let result = await response.json()
        fetchedData.value = result;
        monitors.value = fetchedData.value?.body.Monitors
        console.log(fetchedData.value?.body)
        if (monitors.value.length > 0) {
            console.log(monitorsMetadata.value)
            return monitorsMetadata.value = monitorsMetadataObjMaker(monitors.value)
        }
        loading.value = false
        console.log(monitorsMetadata.value)
        return monitors.value
    }
    return router.replace('login')
})

const monitorsMetadataObjMaker = function (monitorsMetadata: MonitorData[]): MonitorData {
    let monitorsMetadataObj: MonitorData = { totalMonitors: 0, up: 0, down: 0, paused: 0, sslCertificateCount: 0 }
    monitorsMetadataObj.totalMonitors = monitorsMetadata.length
    monitorsMetadata.forEach(monitor => {
        monitorsMetadataObj.up = monitor.up ? monitorsMetadataObj.up + 1 : monitorsMetadataObj.up
        monitorsMetadataObj.down = monitor.down ? monitorsMetadataObj.down + 1 : monitorsMetadataObj.down
        monitorsMetadataObj.paused = monitor.paused ? monitorsMetadataObj.paused + 1 : monitorsMetadataObj.paused
        monitorsMetadataObj.sslCertificateCount = monitor.sslCertificateCount ? monitorsMetadataObj.sslCertificateCount + 1 : monitorsMetadataObj.sslCertificateCount
    })
    console.log(monitorsMetadataObj)
    return monitorsMetadataObj
}

/**
 * the logic that needs to be written:
 *    * when fetching all the resources, fill the monitors-data widgets; (monitors.length, activeCount, inactiveCount, tags.has(paused), SSL_CertCount)
 *      if they're all null/0 display 0's
 *    * pass in the monitors array to the CardMonitorsComp
 */
</script>

<template>
    <DashboardLayout>
        <!-- here the layout will be shared across the children which will be dashboardNav & profileNav -->

        <!-- the layout will get the props from the fetch reactive object and feed the appropriate data to the DashboardNav  -->
        <div class="w-full overflow-x-hidden">
            <div class="dashboard-container flex flex-col h-full w-10/12 mx-auto pl-5">
                <div class="header-greet flex justify-between my-28">
                    <h2 class="text-4xl">Greetings {{ fetchedData?.body.User[0].username }}</h2>
                    <button class="w-64 h-10 bg-red-300 rounded-md flex items-center justify-between px-4">
                        New Monitor <svg xmlns="http://www.w3.org/2000/svg" width="27" height="26" viewBox="0 0 27 26"
                            fill="none">
                            <circle cx="13.0916" cy="13" r="12.9524" fill="#E3A4A4" />
                            <path d="M13.0916 6.52382V13V19.4762" stroke="black" stroke-width="2" />
                            <path d="M19.5678 13L13.0916 13L6.61538 13" stroke="black" stroke-width="2" />
                        </svg>
                    </button>
                </div>
                <div v-if="monitors.length">
                    <div class="monitors-data flex gap-4 mb-24">
                        <div class="w-80 h-44 bg-slate-300 rounded-3xl px-4 py-10 flex flex-col justify-between">
                            <h2 class="text-3xl">total monitors</h2>
                            <p class="text-2xl  w-6 h-8 rounded-full" :class="{ 'animated-monitor-data': loading }">
                                {{ monitorsMetadata.totalMonitors }}
                            </p>
                        </div>
                        <div class="w-80 h-44 bg-slate-300 rounded-3xl px-4 py-10 flex flex-col justify-between">
                            <h2 class="text-3xl">Up</h2>
                            <p class="text-2xl  w-6 h-8 rounded-full" :class="{ 'animated-monitor-data': loading }">

                                {{ monitorsMetadata.up }}
                            </p>
                        </div>
                        <div class="w-80 h-44 bg-slate-300 rounded-3xl px-4 py-10 flex flex-col justify-between">
                            <h2 class="text-3xl">Down</h2>
                            <p class="text-2xl  w-6 h-8 rounded-full" :class="{ 'animated-monitor-data': loading }">

                                {{ monitorsMetadata.down }}
                            </p>
                        </div>
                        <div class="w-80 h-44 bg-slate-300 rounded-3xl px-4 py-10 flex flex-col justify-between">
                            <h2 class="text-3xl">Paused</h2>
                            <p class="text-2xl  w-6 h-8 rounded-full" :class="{ 'animated-monitor-data': loading }">

                                {{ monitorsMetadata.paused }}
                            </p>
                        </div>
                        <div class="w-80 h-44 bg-slate-300 rounded-3xl px-4 py-10 flex flex-col justify-between">
                            <h2 class="text-3xl">SSL Cert</h2>
                            <p class="text-2xl  w-6 h-8 rounded-full" :class="{ 'animated-monitor-data': loading }">
                                {{ monitorsMetadata.sslCertificateCount }}
                            </p>
                        </div>
                    </div>
                    <CardMonitorsComp />
                </div>
                <div v-else class="text-center text-3xl">
                    <p>Wanna add a monitor !?</p>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<style>
.animated-monitor-data {
    animation: 1s linear alternate load-content infinite;
}

@keyframes load-content {
    0% {
        background-color: hsl(255, 10%, 75%);
    }

    50% {
        background-color: hsl(255, 10%, 90%);
    }

    100% {
        background-color: hsl(255, 10%, 75%);
    }
}
</style>
