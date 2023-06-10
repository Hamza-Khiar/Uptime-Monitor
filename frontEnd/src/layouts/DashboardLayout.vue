<script setup lang="ts">
import DashboardNavbarComp from '../components/DashboardNavbarComp.vue'

</script>

<template>
    <div class="flex">
        <DashboardNavbarComp>
            <!-- this will take the username as prop -->
        </DashboardNavbarComp>
        <slot />
    </div>
</template>
