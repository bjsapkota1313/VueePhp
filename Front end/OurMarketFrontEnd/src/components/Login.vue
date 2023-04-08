<template>
    <div class="pt-5 bg-dark ">
        <div class="text-center bg-dark body pt-5">
            <div class="form-signin w-100 m-auto pt-5" id="loginForm">
                <div class="form pt-5">
                    <router-link to="/"><img class="mb-4" src="@/assets/Logo.svg" alt="BusinessLogo" width="250"
                            height="80"></router-link>
                    <h1 class="h3 mb-3 fw-bold">Log In</h1>
                    <div class="form-floating">
                        <input type="email" class="form-control" v-model="email" placeholder="name@example.com" name="email"
                            autocomplete="email" required>
                        <label for="floatingInput">Email address</label>
                    </div>
                    <div class="form-floating" id="passwordDiv">
                        <input name="password" type="password" class="form-control" v-model="password"
                            placeholder="Password" autocomplete="current-password" required>
                        <i class="fa fa-eye" data-bs-toggle="password-toggle" data-bs-target="#password"></i>
                        <label for="floatingPassword">Password</label>
                    </div>
                    <div class="alert alert-danger" role="alert" v-if="error">
                        {{ error }}
                    </div>
                    <div class="p"><button class="w-100 btn btn-lg btn-success" type="submit" name="btnLogin"
                            @click="login">Log
                            in</button>
                    </div>
                    <div class="text-center pt-3">
                        <router-link to="/login/signup">
                            <p class="text-white">Not a member?</p>
                        </router-link>
                    </div>
                    <p class="mt-5 mb-3 text-muted"> &#169;OurMarket.com</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useUserSessionStore } from '@/stores/UserSession.js'

export default {
    name: 'Login',
    setup() {
        return {
            userSessionStore: useUserSessionStore(),
        };
    },
    data() {
        return {
            email: '',
            password: '',
            error: '',
        }
    },
    methods: {
        login() {
            this.userSessionStore.login(this.email, this.password).
                then(response => {
                    this.$router.push('/myads');
                }).catch(error => {
                    this.error = error;
                });
        }
    }

}
</script>

<style scoped>
.body {
    height: 100vh !important
}

body {
    display: flex;
    align-items: center;
    padding-top: 40px;
    padding-bottom: 40px;
    background-color: #f5f5f5;
}

.form-signin {
    max-width: 330px;
    padding: 15px;
}

.form-signin .form-floating:focus-within {
    z-index: 2;
}

.form-signin input[type="email"] {
    margin-bottom: -1px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
}

.form-signin input[type="password"] {
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}

.bd-placeholder-img {
    font-size: 1.125rem;
    text-anchor: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
}

@media (min-width: 768px) {
    .bd-placeholder-img-lg {
        font-size: 3.5rem;
    }
}

.b-example-divider {
    height: 3rem;
    background-color: rgba(0, 0, 0, .1);
    border: solid rgba(0, 0, 0, .15);
    border-width: 1px 0;
    box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
}

.b-example-vr {
    flex-shrink: 0;
    width: 1.5rem;
    height: 100vh;
}

.bi {
    vertical-align: -.125em;
    fill: currentColor;
}

.nav-scroller {
    position: relative;
    z-index: 2;
    height: 2.75rem;
    overflow-y: hidden;
}

.nav-scroller .nav {
    display: flex;
    flex-wrap: nowrap;
    padding-bottom: 1rem;
    margin-top: -1px;
    overflow-x: auto;
    text-align: center;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
}

h1 {
    color: white;
}

#labelRememberMe {
    color: white;
}
</style>