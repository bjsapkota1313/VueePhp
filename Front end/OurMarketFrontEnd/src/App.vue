
<template>
  <header>
    <Navigation v-if="$route.path !== '/login' && $route.path !== '/login/signup'"  />
  </header>
  <RouterView />
  <Footer v-if="$route.path !== '/login' && $route.path !== '/login/signup'" ></Footer>
</template>
<script >
import { useUserSessionStore } from '@/stores/userSession.js';
import { RouterLink, RouterView } from 'vue-router';
import Navigation from '@/components/Navigation.vue';
import {UseShoppingCartStore} from "@/stores/ShoppingCart";
import Footer from "@/components/Footer.vue";


export default {
  setup() {
    return {
      userSessionStore: useUserSessionStore(),
      UseShoppingCartStore: UseShoppingCartStore()
    };
  },
  name: "App",
  components: {
    Navigation,
      Footer
  }
  , mounted() {
    this.userSessionStore.localLogin();
    this.UseShoppingCartStore.loadFromLocalStorage();
  },
};
</script>

<style scoped></style>
