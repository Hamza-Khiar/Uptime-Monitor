import { createApp } from 'vue'

import App from './App.vue'
import router from './router'

import './assets/main.css'

const app = createApp(App)

app.use(router)

app.mount('#app')

/**
 *
 * You can add here other config like app.config.globalProperties and stuff like that
 */
