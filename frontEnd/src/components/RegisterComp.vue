<script setup lang="ts">
import { RouterLink } from 'vue-router'
import { AUTH_CSRF_COOKIE_URL, BACKEND_URL } from '../env'
import { FormValidate } from '@/helpers/FormHandler'
import { onMounted } from 'vue'

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
  }) /* .then(console.log(document.cookie)) */
})

/**
 * making the Validation process
 */

async function handleSubmition(ev: Event) {
  /**
   *  i'll need some utility function who'll call that will deal with;
   *    * Form-Validation
   *    * Fetching FormData (get,post,delete,patch)
   */
  let registerForm = ev.currentTarget as HTMLFormElement
  let formData = Object.fromEntries(new FormData(registerForm))
  FormValidate(formData, {
    required: {
      applyTo: ['UserName', 'Email', 'Password', 'Confirmation']
    },
    email: {
      applyTo: ['Email']
    }
  })
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
}
</script>

<template>
  <div class="auth-container h-screen flex align-middle">
    <div class="w-1/4 m-auto border-2 h-1/2 bg-gray-200">
      <h3 class="text-center font-sans text-3xl">Signup Page</h3>
      <form
        class="mx-auto w-5/6 py-3"
        data-formType="registerForm"
        name="registerForm"
        @submit.prevent="handleSubmition"
      >
        <label
          >UserName:
          <input class="w-full my-2" type="text" name="UserName" />
        </label>
        <label
          >Email:
          <input class="w-full my-2" type="text" name="Email" />
        </label>
        <label
          >Password:
          <input class="w-full my-2" type="text" name="Password" />
        </label>
        <label
          >Confirmation:
          <input class="w-full my-2" type="text" name="Confirmation" />
        </label>
        <button class="block w-1/2 bg-slate-300 rounded mx-auto p-1" type="submit">register</button>
        <RouterLink to="/login" class="contents">already registered</RouterLink>
      </form>
    </div>
  </div>
</template>
