<template>
    <div class="container-fluid pt-5">
        <div v-if="userSessionStore.isLoggedIn">
            <UserManagementTable :users="usersWithoutLoggedUser" @userDeletedSuccessFully="userDeletedSuccessFully"
                                 v-if="isAdmin">
            </UserManagementTable>
            <div class="container" v-else>
                <h1 class="text-center">You are not authorized to view this page</h1>
            </div>
        </div>
        <div v-else>
            <div class="container-fluid ps-3 text-center">
                <h1> You are not logged in </h1>
            </div>
        </div>
    </div>
</template>

<script>
import UserManagementTable from "@/components/UserManageMent/UserManagementTable.vue";
import axios from '@/axios-auth.js';
import {useUserSessionStore} from '@/stores/userSession.js'

export default {
    name: "UserManagementView",
    components: {UserManagementTable},
    setup() {
        return {
            userSessionStore: useUserSessionStore(),
        }
    },
    data() {
        return {
            users: [],
            isAdmin: true,
            noUsers: false
        }
    },
    mounted() {
        this.loadsUsers();
    },
    methods: {
        userDeletedSuccessFully() {
            this.loadsUsers();
        },
        getUsers() {
            return axios.get('/users')
                .then(response => {
                    if (response.status === 403) {
                        this.isAdmin = false;
                    } else if (response.status === 200) {
                        return response.data;
                    }
                })
                .catch(error => {
                    throw new Error(error.response.data.errorMessage);
                });
        },
        loadsUsers() {
            try {
                this.getUsers().then((users) => {
                    this.users = users;
                });
            } catch (error) {
                console.log(error);
            }
        },
        deleteSuccessful() {
            this.loadsUsers();
        }
    }, computed: {
        usersWithoutLoggedUser() {
            return this.users.filter(user => user.email !== this.userSessionStore.getEmailAddress);
            // since every user has a unique email address
        }
    }
}
</script>

<style scoped>

</style>