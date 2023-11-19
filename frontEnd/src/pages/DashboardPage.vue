<script setup lang="ts">
import CardMonitorsComp from '@/components/CardMonitorsComp.vue'
import DashboardLayout from '../layouts/DashboardLayout.vue'
import NewMonitorModal from '@/components/NewMonitorModal.vue'

import { onBeforeMount, provide, ref } from 'vue'
import { fetchFn } from '@/helpers/FetchFn'
import type { TMonitor, TMonitorMetadata } from '@/types'
import { AUTH_BACKEND_URL } from '@/env'
import router from '@/router'



let fetchedData = ref<{ body: { User: { email: string, username: string }; Monitors: TMonitor[] } }>()
let loading = ref(false)
let monitors = ref<TMonitor[]>([])
let monitoMetadata = ref<TMonitorMetadata>()

let newMonitorDisplayed = ref<boolean>(false)


onBeforeMount(async () => {
    loading.value = true
    let response = await fetchFn(AUTH_BACKEND_URL + '/dashboard', 'GET', false)
    if (response.status != (401 || 419)) {
        let result = await response.json()
        fetchedData.value = result
        monitors.value = fetchedData.value?.body.Monitors
        console.log(fetchedData.value?.body)
        if (monitors.value.length > 0) {
            loading.value = false
            return monitoMetadata.value = monitorsMetadataObjMaker(monitors.value)
        }
        loading.value = false
        return monitors.value
    }
    return router.replace('login')
})


const monitorsMetadataObjMaker = function (fetchedMonitorData: TMonitor[]): TMonitorMetadata {

    // what should this function do, 
    // this should read the Monitors [] & format a MonitorMetaData object and return it 
    let monitorsMetadataObj: TMonitorMetadata = { totalMonitors: 0, up: 0, down: 0, paused: 0, sslCertCount: 0 }
    monitorsMetadataObj.totalMonitors = fetchedMonitorData.length
    fetchedMonitorData.forEach((monitor) => {
        monitorsMetadataObj.up = monitor['active'] && !monitor['is_paused'] ? monitorsMetadataObj.up + 1 : monitorsMetadataObj.up
        monitorsMetadataObj.down = !monitor['active'] && !monitor['is_paused'] ? monitorsMetadataObj.down +1 : monitorsMetadataObj.down
        monitorsMetadataObj.paused = monitor['is_paused'] ? monitorsMetadataObj.paused + 1 : monitorsMetadataObj.paused
        monitorsMetadataObj.sslCertCount = monitor['ssl_certificate'] ? monitorsMetadataObj.sslCertCount + 1 : monitorsMetadataObj.sslCertCount
    })
    console.log(monitorsMetadataObj)
    return monitorsMetadataObj
}

provide('userdata', fetchedData)


</script>

<template>
    <DashboardLayout>
        <!-- here the layout will be shared across the children which will be dashboardNav & profileNav -->

        <!-- the layout will get the props from the fetch reactive object and feed the appropriate data to the DashboardNav  -->
        <div class="w-full overflow-x-hidden">
            <div class="dashboard-container flex flex-col h-full w-10/12 mx-auto pl-5"
                :class="{ 'blur-[6px]': newMonitorDisplayed }">
                <div class="header-greet flex justify-between my-28">
                    <h2 class="text-4xl">Greetings {{ fetchedData?.body.User.username }}</h2>
                    <button class="w-64 h-10 bg-emerald-500 rounded-md flex items-center justify-between px-4"
                        @click="newMonitorDisplayed = !newMonitorDisplayed" :disabled="newMonitorDisplayed">
                        New Monitor
                        <svg xmlns="http://www.w3.org/2000/svg" width="27" height="26" viewBox="0 0 27 26" fill="none">
                            <circle cx="13.0916" cy="13" r="12.9524" fill="#24FF8A" />
                            <path d="M13.0916 6.52382V13V19.4762" stroke="black" stroke-width="2" />
                            <path d="M19.5678 13L13.0916 13L6.61538 13" stroke="black" stroke-width="2" />
                        </svg>
                    </button>
                </div>
                <div v-if="loading || monitors.length">
                    <div class="monitors-data flex gap-4 mb-24">
                        <div class="w-80 h-44 bg-slate-300 rounded-3xl px-4 py-10 flex flex-col justify-between">
                            <h2 class="text-3xl">total monitors</h2>
                            <p class="text-2xl w-6 h-8 rounded-full" :class="{ 'animated-monitor-data': loading }">
                                {{ monitoMetadata?.totalMonitors }}
                            </p>
                        </div>
                        <div class="w-80 h-44 bg-slate-300 rounded-3xl px-4 py-10 flex flex-col justify-between">
                            <h2 class="text-3xl">Up</h2>
                            <p class="text-2xl w-6 h-8 rounded-full" :class="{ 'animated-monitor-data': loading }">
                                {{ monitoMetadata?.up }}
                            </p>
                        </div>
                        <div class="w-80 h-44 bg-slate-300 rounded-3xl px-4 py-10 flex flex-col justify-between">
                            <h2 class="text-3xl">Down</h2>
                            <p class="text-2xl w-6 h-8 rounded-full" :class="{ 'animated-monitor-data': loading }">
                                {{ monitoMetadata?.down }}
                            </p>
                        </div>
                        <div class="w-80 h-44 bg-slate-300 rounded-3xl px-4 py-10 flex flex-col justify-between">
                            <h2 class="text-3xl">Paused</h2>
                            <p class="text-2xl w-6 h-8 rounded-full" :class="{ 'animated-monitor-data': loading }">
                                {{ monitoMetadata?.paused }}
                            </p>
                        </div>
                        <div class="w-80 h-44 bg-slate-300 rounded-3xl px-4 py-10 flex flex-col justify-between">
                            <h2 class="text-3xl">SSL Cert</h2>
                            <p class="text-2xl w-6 h-8 rounded-full" :class="{ 'animated-monitor-data': loading }">
                                {{ monitoMetadata?.sslCertCount }}
                            </p>
                        </div>
                    </div>
                    <CardMonitorsComp :monitors=monitors />
                </div>
                <div v-else class="text-center text-3xl">
                    <p>Wanna add a monitor !?</p>
                </div>
            </div>
            <div id="new-monitor-modal" class="bg-red-200 w-3/4 h-1/3 absolute top-[20%] left-[20%]"
                v-if="newMonitorDisplayed">
                <NewMonitorModal />
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

