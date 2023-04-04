import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/HomeView.vue';
import MyAds from '../views/MyAdsView.vue';
import LoginView from '../views/LoginView.vue';
import SignupView from '../views/SignupView.vue';



const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', component: Home },
    { path: '/myads', component: MyAds },
    { path: '/login', component: LoginView },
    { path: '/login/signup', component: SignupView },
  ]
})

export default router
