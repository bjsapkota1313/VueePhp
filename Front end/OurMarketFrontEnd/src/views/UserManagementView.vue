<template>
    <div class="container-fluid pt-5">
        <div v-if="userSessionStore.isLoggedIn ">
            <div v-if="isAdmin ">
                <div v-if="!noUsers">
                    <UserManagementTable :users="usersWithoutLoggedUser"
                                         @userDeletedSuccessFully="userDeletedSuccessFully"
                    >
                    </UserManagementTable>
                </div>
                <div v-else>
                    <h1 class="text-center">No Users to show</h1>
                </div>
            </div>
            <div class="container" v-if="!isAdmin">
                <h1 class="text-center">You are not authorized to view this page only Admin are Allowed</h1>
            </div>
        </div>
        <div v-else>
            <div class="container-fluid ps-3 text-center">
                <h1> You are not logged in </h1>
            </div>
        </div>
    </div>
    <pagination class="pt-5" @previous="previousPageClicked"
                @next="nextBtnPageClicked"
                :needMorePages="!noUsers"
                v-if="isAdmin && userSessionStore.isLoggedIn"
    ></pagination>
</template>

<script>
import UserManagementTable from "@/components/UserManageMent/UserManagementTable.vue";
import axios from '@/axios-auth.js';
import {useUserSessionStore} from '@/stores/UserSession.js'
import Pagination from "@/components/Pagination.vue";

export default {
    name: "UserManagementView",
    components: {Pagination, UserManagementTable},
    setup() {
        return {
            userSessionStore: useUserSessionStore(),
        }
    },
    data() {
        return {
            users: [],
            isAdmin: false,
            limit: 4,
            offset: 0,
            noUsers: false,
        }
    },
    mounted() {
        if (this.userSessionStore.isLoggedIn) {
            this.loadsUsers();
        }
    },
    methods: {
        userDeletedSuccessFully() {
            this.loadsUsers();
        },
        getUsers() {
            const params = {
                limit: this.limit,
                offset: this.offset,
            }
            return axios.get('/users', {params})
                .then(response => {
                    if (response.status === 204) {
                        this.noUsers = true;
                        return [];
                    }
                    if (response.status === 200) {
                        this.isAdmin = true;
                        this.noUsers = false;
                        return response.data;
                    }
                })
                .catch(error => {
                    if (error.response.status === 403) {
                        this.isAdmin = false;
                    } else {
                        console.log(error);
                    }
                });
        },
        loadsUsers() {
            try {
                this.getUsers().then(
                    (users) => {
                        this.users = users;
                    });
            } catch (error) {
                console.log(error);
            }
        },
        previousPageClicked() {
            if (this.offset > 0) {
                this.offset = this.offset - this.limit;
            }
            this.loadsUsers();
        },
        nextBtnPageClicked() {
            if (!this.noUsers) {
                this.offset = this.offset + this.limit;
                this.loadsUsers()
            }
        }
    },
    computed: {
        usersWithoutLoggedUser() {
            return this.users.filter(user => user.email !== this.userSessionStore.getEmailAddress);
            // since every user has a unique email address
        }
    }
}
</script>

<style scoped>

</style>