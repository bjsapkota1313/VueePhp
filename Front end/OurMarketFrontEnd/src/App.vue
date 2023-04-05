
<template>
  <header>
    <Navigation v-if="$route.path !== '/login' && $route.path !== '/login/signup'"  />
  </header>
  <RouterView />
</template>
<script >
import { useUserSessionStore } from './stores/userSession.js'
import { RouterLink, RouterView } from 'vue-router';
import Navigation from './components/Navigation.vue'
import {UseShoppingCartStore} from "@/stores/ShoppingCart";


export default {
  setup() {
    return {
      userSessionStore: useUserSessionStore(),
      UseShoppingCartStore: UseShoppingCartStore()
    };
  },
  name: "App",
  components: {
    Navigation
  }
  , mounted() {
    this.userSessionStore.localLogin();
    this.UseShoppingCartStore.loadFromLocalStorage();
  },
};
</script>

<style scoped></style>
