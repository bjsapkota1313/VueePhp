<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-info p-3">
            <div class="container-fluid">
                <a class="navbar-brand"><img src="@/assets/Logo.svg" alt="BusinessLogo" width="200" height="40">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNavDropdown"
                        aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class=" collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ms-auto ">
                        <li class="nav-item">
                            <router-link class="nav-link mx-2" to="/" active-class="active">Home</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link mx-2" to="/myAds" active-class="active">My Ads</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link mx-2" to="/UserManagement" active-class="active">User Management</router-link>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto  d-lg-inline-flex">
                        <li class="nav-item">
                            <RouterLink class="nav-link mx-2" to="/shoppingCart">
                                <i class="fa-sharp fa-solid fa-cart-shopping fa-xl">  {{
                                        UseShoppingCartStore.getItemsCount
                                    }}</i>
                            </RouterLink>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link mx-2 dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="White"
                                     class="bi bi-person" viewBox="0 0 16 16">
                                    <path
                                            d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                                </svg>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li>
                                    <router-link to="/login" id="linkLogin" class="dropdown-item"
                                                 v-if="!userSessionStore.isLoggedIn">
                                        Login
                                    </router-link>
                                    <button class="dropdown-item" v-else disabled> Login</button>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <button class="dropdown-item" name="btnSignOut" id="signOut" type="submit"
                                            value="Sign Out" v-bind:disabled="!userSessionStore.isLoggedIn"
                                            @click="logout">Log Out
                                    </button>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</template>

<script>
import {useUserSessionStore} from '@/stores/userSession.js'
import {UseShoppingCartStore} from "@/stores/shoppingCart.js";

export default {
    name: "Navigation",
    setup() {
        return {
            userSessionStore: useUserSessionStore(),
            UseShoppingCartStore: UseShoppingCartStore()
        };
    },
    methods: {
        logout() {
            this.userSessionStore.logout();
        }
    }

};
</script>

<style scoped>
#linkLogin {
    text-decoration: none;
}
.badge:after {
    content: attr(value);
    font-size: 14px;
    color: #fff;
    background: black;
    border-radius: 50%;
    padding: 0 5px;
    position: relative;
    left: -8px;
    top: -10px;
    opacity: 0.9;
    font-weight: bold;
}
</style>
  