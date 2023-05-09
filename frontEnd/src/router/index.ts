import { createRouter, createWebHistory } from 'vue-router'
import LoginComp from '../components/LoginComp.vue'
import RegisterComp from '../components/RegisterComp.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      redirect: '/login'
    },
    {
      path: '/login',
      name: 'login',
      component: LoginComp
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterComp
    }
  ]
})

export default router
