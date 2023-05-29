<script setup lang="ts">
import { AUTH_CSRF_COOKIE_URL, BACKEND_URL } from '@/env'
import { formValidate } from '@/helpers/FormHandler'
import { onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'

onMounted(async () => {
  if (!document.cookie) {
    await fetch(AUTH_CSRF_COOKIE_URL, {
      method: 'GET',
      credentials: 'include'
    })
  }
})

// let errorForm = ref()

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
}
</script>
<template>
  <div class="auth-container h-screen flex align-middle">
    <div class="w-1/4 m-auto border-2 h-fit p-4 bg-gray-200">
      <h3 class="text-3xl font-sans text-center">Login</h3>
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
        </label>
        <label class="mb-4"
          >Password:

          <input class="w-full" type="text" name="password" id="" />
        </label>
        <button class="block w-1/2 bg-slate-300 rounded mx-auto p-1" type="submit">login</button>
        <RouterLink to="/register">don't have an account</RouterLink>
      </form>
    </div>
  </div>
</template>
