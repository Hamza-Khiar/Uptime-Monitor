<script setup lang="ts">
import { RouterLink } from 'vue-router'
import { AUTH_CSRF_COOKIE_URL, BACKEND_URL } from '../env'
import { onMounted } from 'vue'
/**
 * some form handling and after finished; sending your first request to laravel
 *
 *
 */

// i'll have to firstly capture the data & then send

onMounted(async () => {
  let xsrfHeaderReq = await fetch(AUTH_CSRF_COOKIE_URL, {
    method: 'GET',
    credentials: 'include'
  })
})

async function loggerVal(ev: Event) {
  ev.preventDefault()
  /**
   *  i'll need some utility function who'll call that will deal with;
   *    * Form-Validation
   *    * Fetching FormData (get,post,delete,patch)
   */
  let registerForm = ev.currentTarget as HTMLFormElement
  let formData = new FormData(registerForm)
  let response = await fetch(BACKEND_URL + '/register', {
    method: 'POST',
    // mode: 'cors',
    headers: {
      'Content-Type': 'application/json',
      'Access-Control-Allow-Origin': '*'
    },
    body: JSON.stringify(Object.fromEntries(formData))
  })
  let result = await response.json()
  console.log(result)
}
</script>

<template>
  <div class="auth-container h-screen flex align-middle">
    <div class="w-1/4 m-auto border-2 h-1/2 bg-gray-200">
      <h3 class="text-center font-sans text-3xl">Signup Page</h3>
      <form
        class="flex flex-col w-4/5 my-0 mx-auto"
        data-formType="registerForm"
        name="registerForm"
        @submit="loggerVal"
      >
        <label for="FullName">FullName</label>
        <input type="text" name="FullName" />
        <label for="Email">Email</label>
        <input type="text" name="Email" />
        <label for="Password">Password</label>
        <input type="text" name="Password" />
        <label for="Confirmation">Confirmation</label>
        <input type="text" name="Confirmation" />
        <button type="submit">register</button>
        <RouterLink to="/login" class="contents">already registered</RouterLink>
      </form>
    </div>
  </div>
</template>
