import { createRouter, createWebHistory } from 'vue-router'
import HomeComp from '../components/HomeComp.vue'
import LoginComp from '../components/LoginComp.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeComp
    },
    {
      path: '/login',
      name: 'login',
      component: LoginComp
    }
  ]
})

export default router
