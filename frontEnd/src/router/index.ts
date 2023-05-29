import { createRouter, createWebHistory } from 'vue-router'
import LoginComp from '../pages/LoginComp.vue'
import RegisterComp from '../pages/RegisterComp.vue'
import DashboardComp from '../pages/DashboardComp.vue'

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
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: DashboardComp,
      children: []
    }
  ]
})

export default router
