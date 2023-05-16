<script setup lang="ts">
import { RouterLink } from 'vue-router'
import { AUTH_CSRF_COOKIE_URL, BACKEND_URL } from '../env'
import { formValidate } from '@/helpers/FormHandler'
import { onMounted, reactive, ref } from 'vue'

/**
 * *******************************************************
 *    SEPERATOR  'cuz why not'
 *    CleanCode 101 :)
 * *******************************************************
 */

onMounted(async () => {
  await fetch(AUTH_CSRF_COOKIE_URL, {
    method: 'GET',
    credentials: 'include'
  })
  /* .then(console.log(document.cookie)) */
})

/**
 * making the Validation process
 */

let errorForm = ref()

async function handleSubmition(ev: Event) {
  let registerForm = ev.currentTarget as HTMLFormElement
  let formData = Object.fromEntries(new FormData(registerForm))
  const validateForm = formValidate(formData, {
    required: {
      applyTo: ['UserName', 'Email', 'Password', 'Confirmation']
    },
    email: {
      applyTo: ['Email']
    },
    minlength: {
      value: 8,
      applyTo: ['UserName', 'Password', 'Confirmation']
    }
  })

  if (!validateForm.length) {
    // let response = await fetch(BACKEND_URL + '/register', {
    //   method: 'POST',
    //   headers: {
    //     'Content-Type': 'application/json',
    //     'Access-Control-Allow-Origin': '*'
    //   },
    //   body: JSON.stringify(Object.fromEntries(formData))
    // })
    // let result = await response.json()
    // console.log(result)
  } else {
    console.log(validateForm)
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
          <input class="w-full" type="text" name="UserName" />
          <div v-for="error in errorForm" :key="error">
            <span class="text-sm text-red-600">{{ error?.UserName?.message }}</span>
          </div>
        </label>
        <label class="mb-4"
          >Email:
          <input class="w-full" type="text" name="Email" />
          <div v-for="error in errorForm" :key="error">
            <span class="text-sm text-red-600">{{ error?.Email?.message }}</span>
          </div>
        </label>
        <label class="mb-4"
          >Password:
          <input class="w-full" type="text" name="Password" />
          <div v-for="error in errorForm" :key="error">
            <span class="text-sm text-red-600">{{ error?.Password?.message }}</span>
          </div>
        </label>
        <label class="mb-4"
          >Confirmation:
          <input class="w-full" type="text" name="Confirmation" />
          <div v-for="error in errorForm" :key="error">
            <span class="text-sm text-red-600">{{ error?.Confirmation?.message }}</span>
          </div>
        </label>
        <button class="block w-1/2 bg-slate-300 rounded mx-auto p-1" type="submit">register</button>
        <RouterLink to="/login" class="contents">already registered</RouterLink>
      </form>
    </div>
  </div>
</template>
