<template>
    <div class="bg-dark full">
        <div class="mask d-flex align-items-center h-100 gradient-custom-3">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-5">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body p-5">
                                <h2 class="text-uppercase text-center mb-5">Create an account</h2>
                                <div class="form-group col-12 ">
                                    <label class="form-label" for="FirstName">Your First Name</label>
                                    <input type="text" v-model="firstName" class="form-control form-control-lg"
                                           autocomplete="first-name" required/>
                                </div>
                                <div class="form-group col-12 ">
                                    <label class="form-label is-invalid" for="LastName">Your Last Name</label>
                                    <input type="text" v-model="lastName" class="form-control form-control-lg"
                                           autocomplete="last-name" required/>
                                </div>
                                <div class="form-group col-12">
                                    <label class="form-label" for="Email">Your Email</label>
                                    <input type="email" v-model="email" class="form-control form-control-lg"
                                           autocomplete="email" required/>
                                </div>
                                <div class="form-group col-12">
                                    <label class="form-label" for="password">Password</label>
                                    <input type="password" v-model="password" class="form-control form-control-lg"
                                           autocomplete="new-password" required/>
                                    <div id="feedback-invalid-pass"></div>
                                </div>
                                <div class="form-group col-12">
                                    <label class="form-label" for="RepeatPassword"> Repeat your password</label>
                                    <input type="password" v-model="repeatPassword" class="form-control form-control-lg"
                                           autocomplete="new-password" required/>
                                </div>
                                <div class="alert alert-danger mt-2" role="alert" v-if="error">
                                    {{ error }}
                                </div>
                                <div class="d-flex justify-content-center pt-2">
                                    <button type="button" @click="signup" class="w-100 btn btn-lg btn-success">
                                        Register
                                    </button>
                                </div>
                                <div class="d-flex justify-content-center pt-3">
                                    <router-link to="/" class="w-100 btn btn-lg btn-secondary"> Cancel</router-link>
                                </div>
                                <p class="text-center text-muted mt-5 mb-0">Have already an account?
                                    <RouterLink to="/login"
                                                class="fw-link"><u>Login Here</u></RouterLink>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from '@/axios-auth.js'
import {useUserSessionStore} from "@/stores/UserSession";
import Swal from "sweetalert2";

export default {
    name: 'Signup',
    setup() {
        return {
            userSessionStore: useUserSessionStore(),
        }
    },
    data() {
        return {
            firstName: '',
            lastName: '',
            email: '',
            password: '',
            repeatPassword: '',
            error: '',
        }
    },
    methods: {
        signup() {
            if (!this.isFilled) {
                this.error = 'Please fill in all fields.';
                return;
            }
            if (!this.isPasswordValid) {
                this.error = 'Password should be at least 8 characters long.';
                return;
            }
            if (!this.isPasswordMatch) {
                this.error = 'Passwords do not match.';
                return;
            }
            if (!this.isValidEmail) {
                this.error = 'Please enter a valid email address.';
                return;
            }
            this.sendPostRequest()
                .then((createdUser) => {
                    Swal.fire({
                        title: 'Success!',
                        text: 'You have successfully registered!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        this.$router.push('/');
                    });
                }).catch((error) => {
                this.error = error;
            });
        },
        sendPostRequest() {
            return new Promise((resolve, reject) => {
                axios.post('/users/register', {
                    firstName: this.firstName,
                    lastName: this.lastName,
                    emailAddress: this.email,
                    password: this.password
                }).then(function (response) {
                    resolve(response.data);
                }).catch(function (error) {
                    reject(error.response.data.errorMessage);
                });
            });
        }
    },
    computed: {
        isPasswordValid() {
            return this.password.length >= 8;
        },
        isPasswordMatch() {
            return this.password === this.repeatPassword;
        },
        isFilled() {
            return this.firstName && this.lastName && this.email;
        },
        isValidEmail() {
            return this.email.includes('@');
        }
    }

}
</script>

<style scoped>
.full {
    height: 100vh;
}
</style>