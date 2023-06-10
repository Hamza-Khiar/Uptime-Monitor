<script setup lang="ts">
/**
 * the logic that needs to be written:
 *    * when fetching for all monitors for a user fill the cards
 */
</script>
<template>
    <div id="monitors-card-container" class="h-96 flex">
        <!-- foreach loop over the JSON object and fill the divs -->
        <div id="monitors-card-containers" class="flex gap-56 overflow-x-scroll overflow-y-hidden">
            <div class="monitor-card w-64 h-80 flex-none border-solid border-4 rounded-3xl p-4">
                <h3 class="text-2xl my-3">FriendlyName</h3>
                <p class="text-sm pb-6">http://url.com</p>
                <hr />
                <div id="monitor-status" class="flex justify-between items-center mt-3">
                    <h3 class="text-2xl">Status:</h3>
                    <div class="h-6 w-6 bg-green-500 rounded-full"></div>
                </div>
            </div>
            <div class="monitor-card w-64 h-80 flex-none border-solid border-4 rounded-3xl p-4">
                <h3 class="text-2xl my-3">FriendlyName</h3>
                <p class="text-sm pb-6">http://url.com</p>
                <hr />
                <div id="monitor-status" class="flex justify-between items-center mt-3">
                    <h3 class="text-2xl">Status:</h3>
                    <div class="h-6 w-6 bg-green-500 rounded-full"></div>
                </div>
            </div>
            <div class="monitor-card w-64 h-80 flex-none border-solid border-4 rounded-3xl p-4">
                <h3 class="text-2xl my-3">FriendlyName</h3>
                <p class="text-sm pb-6">http://url.com</p>
                <hr />
                <div id="monitor-status" class="flex justify-between items-center mt-3">
                    <h3 class="text-2xl">Status:</h3>
                    <div class="h-6 w-6 bg-green-500 rounded-full"></div>
                </div>
            </div>
            <div class="monitor-card w-64 h-80 flex-none border-solid border-4 rounded-3xl p-4">
                <h3 class="text-2xl my-3">FriendlyName</h3>
                <p class="text-sm pb-6">http://url.com</p>
                <hr />
                <div id="monitor-status" class="flex justify-between items-center mt-3">
                    <h3 class="text-2xl">Status:</h3>
                    <div class="h-6 w-6 bg-green-500 rounded-full"></div>
                </div>
            </div>
            <div class="monitor-card w-64 h-80 flex-none border-solid border-4 rounded-3xl p-4">
                <h3 class="text-2xl my-3">FriendlyName</h3>
                <p class="text-sm pb-6">http://url.com</p>
                <hr />
                <div id="monitor-status" class="flex justify-between items-center mt-3">
                    <h3 class="text-2xl">Status:</h3>
                    <div class="h-6 w-6 bg-green-500 rounded-full"></div>
                </div>
            </div>

        </div>
    </div>
</template>

<style>
#monitors-card-container {
    width: 80vw;
}

#monitors-card-containers::-webkit-scrollbar {
    display: none;
}
</style>
