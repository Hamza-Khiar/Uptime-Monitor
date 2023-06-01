<script setup lang="ts">
import CardMonitorsComp from '@/components/CardMonitorsComp.vue'
import DashboardLayout from '../layouts/DashboardLayout.vue'

import { onMounted } from 'vue'
import { fetchFn } from '@/helpers/FetchFn'
import { BACKEND_URL } from '@/env'

onMounted(async () => {
  let response = await fetchFn(BACKEND_URL + '/dashboard')
  let result = await response.json()
  console.log(result)
})

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
    <div class="w-full">
      <div class="dashboard-container flex flex-col h-full w-10/12 mx-auto pl-5">
        <div class="header-greet flex justify-between my-28">
          <h2 class="text-3xl">Greetings</h2>
          <button class="w-64 h-10 bg-red-300 rounded-md flex items-center justify-between px-4">
            New Monitor
            <!--
               add an svg comp with the prop of what to look for

               something like <Icon :icon='circlePlus' />
             -->
          </button>
        </div>
        <div class="monitors-data flex gap-4 mb-24">
          <div class="w-80 h-44 bg-slate-300 rounded-3xl px-4 py-10">
            <h2 class="text-2xl">total monitors</h2>
          </div>
          <div class="w-80 h-44 bg-slate-300 rounded-3xl px-4 py-10">
            <h2 class="text-2xl">Up</h2>
          </div>
          <div class="w-80 h-44 bg-slate-300 rounded-3xl px-4 py-10">
            <h2 class="text-2xl">Down</h2>
          </div>
          <div class="w-80 h-44 bg-slate-300 rounded-3xl px-4 py-10">
            <h2 class="text-2xl">Paused</h2>
          </div>
          <div class="w-80 h-44 bg-slate-300 rounded-3xl px-4 py-10">
            <h2 class="text-2xl">SSL Cert</h2>
          </div>
        </div>
        <CardMonitorsComp />
      </div>
    </div>
  </DashboardLayout>
</template>
