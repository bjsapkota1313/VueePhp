
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

export default {
  setup() {
    return {
      userSessionStore: useUserSessionStore(),
    };
  },
  name: "App",
  components: {
    Navigation
  }
  , mounted() {
    this.userSessionStore.localLogin();
  },
};
</script>

<style scoped></style>
