<script setup lang="ts">
import { RouterLink } from 'vue-router'
/**
 * some form handling and after finished; sending your first request to laravel
 */
</script>
<template>
  <h1>Login Page</h1>
  <form action="post">
    <label for="email">Your Email</label>
    <input type="text" name="email" id="" />
    <label for="password">Your Password</label>
    <input type="text" name="password" id="" />
  </form>
  <RouterLink to="/register">don't have an account</RouterLink>
</template>
