import {defineStore} from 'pinia'
import axios from '../axios-auth'

export const useUserSessionStore = defineStore('userSession', {
    state: () => ({
        jwt: '',
        firstName: '',
        emailAddress: '',
    }),
    getters: {
        isLoggedIn: (state) => state.jwt !== '',
        getFirstName: (state) => state.firstName,
        getEmailAddress: (state) => state.emailAddress,
    },
    actions: {
        localLogin() {
            if (localStorage['jwt']) {
                this.jwt = localStorage['jwt'];
                this.firstName = localStorage['firstName'];
                this.emailAddress = localStorage['emailAddress'];
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage['jwt'];
            }
        },
        login(emailAddress, password) {
            return new Promise((resolve, reject) => {
                axios.post("/users/login", {
                    emailAddress: emailAddress,
                    password: password,

                }).then((response) => {
                    this.jwt = response.data.jwt;
                    localStorage['jwt'] = response.data.jwt;
                    localStorage['firstName'] = response.data.firstName;
                    localStorage['emailAddress'] = response.data.emailAddress;
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage['jwt'];
                    resolve();
                }).catch((error) => {
                    reject(error.response.data.errorMessage);
                })
            });
        },
        logout() {
            this.jwt = '';
            this.firstName = '';
            localStorage.removeItem('jwt');
            localStorage.removeItem('firstName');
            localStorage.removeItem('emailAddress');
            delete axios.defaults.headers.common['Authorization'];
        }
    },
})