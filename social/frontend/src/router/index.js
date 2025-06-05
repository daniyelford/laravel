import { createRouter, createWebHistory } from 'vue-router'
import Home from '@/Pages/Home.vue'
import Telagram from '@/Pages/Telagram.vue'
import Instagram from '@/Pages/Instagram.vue'


const routes = [
  { path: '/', name: 'Home', component: Home },
  {path: '/telegram',name: 'telegram',component: Telagram},
  {path: '/instagram',name: 'instagram',component: Instagram},
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router