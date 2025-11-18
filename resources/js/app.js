import { createApp } from 'vue'
import App from './App.vue'
import { createRouter, createWebHistory } from 'vue-router'
import { createVuetify } from 'vuetify'
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'

import LandingPage from './components/LandingPage.vue'
import ProductApp from './components/ProductApp.vue'
import ProductDetail from './components/ProductDetail.vue'

const routes = [
  { path: '/', component: LandingPage },
  { path: '/products', component: ProductApp },
  { path: '/products/:id', component: ProductDetail },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

const vuetify = createVuetify()

createApp(App).use(router).use(vuetify).mount('#app')
