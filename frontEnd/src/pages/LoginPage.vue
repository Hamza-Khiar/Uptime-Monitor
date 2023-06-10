<script setup lang="ts">
import AuthRegistry from '../layouts/AuthRegistry.vue'
import { BACKEND_URL } from '@/env'
import { fetchFn } from '@/helpers/FetchFn'
import { formValidate } from '@/helpers/FormHandler'
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
import router from '@/router'

let errorForm = ref()
let verifyUser = ref() 

async function handleSubmition(ev: Event) {
  let loginForm = ev.currentTarget as HTMLFormElement
  let formData = Object.fromEntries(new FormData(loginForm))
  console.log(formData)
  const validateForm = formValidate(formData, {
    required: {
      applyTo: ['email', 'password']
    },
    email: {
      applyTo: ['email']
    } 
  })
  if (!validateForm.length) {
    errorForm.value = null
    let response = await fetchFn(BACKEND_URL + '/login', 'POST',  true,JSON.stringify(formData))
    let result = await response.json()
    if ('Error' in result) {
      verifyUser.value = result['Error']
    } else {
      // trigger a redirect to dashboard
      router.replace('dashboard')
    }
  } else {
    errorForm.value = validateForm
  }
}


</script>
<template>
  <AuthRegistry>
  <!-- div with if verifyUser take the h3 text and have the error message else Login --->
  <h3 class="text-xl text-red-500 font-sans text-center" v-if="verifyUser">{{verifyUser}}</h3>
  <h3 class="text-3xl font-sans text-center" v-else>Login</h3>
    <form
      action="post"
      class="flex flex-col w-4/5 my-0 mx-auto"
      name="loginForm"
      data-formType="loginForm"
      @submit.prevent="handleSubmition"
    >
      <label class="mb-4"
        >Email:

        <input class="w-full" type="text" name="email" id="" />
        <div v-for="error in errorForm" :key="error">
            <span class="text-sm text-red-600">{{error?.email?.message}}</span>
        </div>
      </label>
      <label class="mb-4"
        >Password:

        <input class="w-full" type="text" name="password" id="" />
        <div v-for="error in errorForm" :key="error">
            <span class="text-sm text-red-600">{{error?.password?.message}}</span>
        </div>
      </label>
      <button class="block w-1/2 bg-slate-300 rounded mx-auto p-1" type="submit">login</button>
      <RouterLink to="/register">don't have an account</RouterLink>
    </form>
  </AuthRegistry>
</template>
