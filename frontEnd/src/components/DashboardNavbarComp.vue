<script setup lang="ts">
import { inject } from 'vue';
import { RouterLink } from 'vue-router'
/**
 * what logic should be contained in the navbar besides the interlinks
 *
 ************************************************************
 *
 ************************************************************
 *
 * getting the data as proprs from the layout of the page
 *
 */ 

let userdata = inject('userdata')

</script>

<template>
  <div class="bg-zinc-200 w-72 h-screen flex flex-col py-2">
    <p class="text-4xl text-center">Logo</p>
    <nav class=" mx-auto mt-28 basis-4/5">
      <ul class="flex flex-col gap-y-16 content-between">
        <RouterLink to="#">Monitors</RouterLink>
        <RouterLink to="#">StatusPage</RouterLink>
        <RouterLink to="#">Settings</RouterLink>
      </ul>
    </nav>
    <div class="profile-bar w-full bg-slate-400 h-10">
        <p>{{userdata?.body?.User?.username}}</p>
    </div>
  </div>
</template>
