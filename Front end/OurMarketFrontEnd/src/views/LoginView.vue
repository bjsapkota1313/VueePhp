<template>
    <div v-if="userSessionStore.isLoggedIn">
        <h1 class="text-center"> You are already logged in </h1>
        <RouterLink to="/MyAds" class="btn btn-success">Go to My Ads</RouterLink>
    </div>
    <div id="full" v-else>
        <Login ></Login>
    </div>
    
</template>

<script>
import Login from '@/components/Login.vue'
import {useUserSessionStore} from '@/stores/userSession.js'
export default {
    name: 'LoginView',
    components: {
        Login,
    },
    setup() {
        return {
            userSessionStore: useUserSessionStore(),
        };
    }
}
</script>
<style scoped>
#full {
    height: 100vh;
}
</style>