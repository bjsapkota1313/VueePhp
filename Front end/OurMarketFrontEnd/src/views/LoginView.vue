<template>
    <div id="full">
        <Login ></Login>
    </div>
    
</template>

<script>
import Login from '@/components/Login.vue'
export default {
    name: 'LoginView',
    components: {
        Login,
    },
}
</script>
<style scoped>
#full {
    height: 100vh;
}
</style>