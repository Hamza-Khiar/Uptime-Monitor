<script setup lang="ts">
import { AUTH_CSRF_COOKIE_URL, BACKEND_URL } from '@/env'
import { onMounted } from 'vue'
import { RouterLink } from 'vue-router'

/**
 * some form handling and after finished; sending your first request to laravel
 */

onMounted(async () => {
  await fetch(AUTH_CSRF_COOKIE_URL, {
    method: 'GET',
    credentials: 'include'
  })
})
</script>
<template>
  <div class="auth-container h-screen flex align-middle">
    <div class="w-1/4 m-auto border-2 h-1/2 bg-gray-200">
      <h3 class="text-3xl font-sans text-center">Login</h3>
      <form
        action="post"
        class="flex flex-col w-4/5 my-0 mx-auto"
        name="loginForm"
        data-formType="loginForm"
      >
        <label class="mb-4"
          >Email:

          <input class="w-full" type="text" name="email" id="" />
        </label>
        <label class="mb-4"
          >Password:

          <input class="w-full" type="text" name="password" id="" />
        </label>
        <!-- <button type="submit">login</button> -->
        <RouterLink to="/register">don't have an account</RouterLink>
      </form>
    </div>
  </div>
</template>
