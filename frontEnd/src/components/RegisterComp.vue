<script setup lang="ts">
import { RouterLink } from 'vue-router'
import { AUTH_CSRF_COOKIE_URL, BACKEND_URL } from '../env'
import { formValidate } from '@/helpers/FormHandler'
import { onMounted, ref } from 'vue'
import { fetchFn } from '@/helpers/FetchFn'

/**
 * *******************************************************
 *    SEPERATOR  'cuz why not'
 *    CleanCode 101 :)
 * *******************************************************
 */

onMounted(async () => {
  if (!document.cookie) {
    await fetch(AUTH_CSRF_COOKIE_URL, {
      method: 'GET',
      credentials: 'include'
    })
  }
})

let errorForm = ref()

async function handleSubmition(ev: Event) {
  let registerForm = ev.currentTarget as HTMLFormElement
  let formData = Object.fromEntries(new FormData(registerForm))
  const validateForm = formValidate(formData, {
    required: {
      applyTo: ['username', 'email', 'password', 'password_confirmation']
    },
    email: {
      applyTo: ['email']
    },
    minlength: {
      value: 8,
      applyTo: ['username', 'password', 'password_confirmation']
    },
    maxlength: {
      value: 50,
      applyTo: ['username', 'password']
    },
    equals: {
      applyTo: ['password', 'password_confirmation']
    }
  })
  if (!validateForm.length) {
    errorForm.value = null
    let response = await fetchFn(BACKEND_URL + '/register', 'POST', JSON.stringify(formData))
    let result = await response.json()
    console.log(result)
  } else {
    errorForm.value = validateForm
  }
}
</script>

<template>
  <div class="auth-container h-screen flex align-middle">
    <div class="w-1/4 m-auto border-2 h-1/2 bg-gray-200">
      <h3 class="text-center font-sans text-3xl">Signup Page</h3>
      <form
        class="mx-auto w-5/6 py-5 flex flex-col"
        data-formType="registerForm"
        name="registerForm"
        @submit.prevent="handleSubmition"
      >
        <label class="mb-4"
          >UserName:
          <input class="w-full" type="text" name="username" />
          <div v-for="error in errorForm" :key="error">
            <span class="text-sm text-red-600">{{ error?.username?.message }}</span>
          </div>
        </label>
        <label class="mb-4"
          >Email:
          <input class="w-full" type="text" name="email" />
          <div v-for="error in errorForm" :key="error">
            <span class="text-sm text-red-600">{{ error?.email?.message }}</span>
          </div>
        </label>
        <label class="mb-4"
          >Password:
          <input class="w-full" type="text" name="password" />
          <div v-for="error in errorForm" :key="error">
            <span class="text-sm text-red-600">{{ error?.password?.message }}</span>
          </div>
        </label>
        <label class="mb-4"
          >Confirmation:
          <input class="w-full" type="text" name="password_confirmation" />
          <div v-for="error in errorForm" :key="error">
            <span class="text-sm text-red-600">{{ error?.password_confirmation?.message }}</span>
          </div>
        </label>
        <button class="block w-1/2 bg-slate-300 rounded mx-auto p-1" type="submit">register</button>
        <RouterLink to="/login" class="contents">already registered</RouterLink>
      </form>
    </div>
  </div>
</template>
