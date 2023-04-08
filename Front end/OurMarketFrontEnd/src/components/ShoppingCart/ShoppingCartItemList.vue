<template>
    <div class="card" v-if="UseShoppingCartStore.getItemsCount !==0">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h5 class="mb-3">
                        <RouterLink to="/" class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>Continue
                            shopping
                        </RouterLink>
                    </h5>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="mb-1">Shopping cart</h3>
                            <p class="mb-0">You have {{ UseShoppingCartStore.getItemsCount }} items in
                                your cart</p>
                        </div>
                    </div>
                    <ShoppingCartItem v-for="ad in shoppingCartItems" :key="ad.id" :ad="ad"
                                      @removeItemSuccessfully="removeItemSuccessfully"
                    ></ShoppingCartItem>
                    <div class="card-footer">
                        <div class="d-flex d-md-flex justify-content-between">
                            <p class="mb-2"><strong>VAT Amount (21%)</strong></p>
                            <p class="mb-2">
                                € {{ totalVat.toFixed(2) }}</p>
                        </div>
                        <div class="d-flex d-md-flex justify-content-between">
                            <p class="mb-2"><strong>Delivery Fee</strong></p>
                            <p class="mb-2">€0.00</p>
                        </div>

                        <div class="d-flex d-md-flex justify-content-between mb-4">
                            <p class="mb-2"><strong>Total (Incl. taxes)</strong></p>
                            <p class="mb-2">
                                €{{ total.toFixed(2) }}</p>
                        </div>
                        <button name="buttonCheckOut" type="button"
                                class="btn  btn-block btn-lg d-sm-block float-right" @click="checkout"
                                style="float: right !important; background-color:#00ff00;">
                            <div class="d-flex">
                                <span>Checkout €{{ total.toFixed(2) }} <i
                                        class="fas fa-long-arrow-alt-right ms-2"></i></span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else>
        <div class="container text-center my-5">
            <h1 class="display1">
                <span style=" color:#00ff00">"You're not only saving money, you're saving the planet too!"</span>
            </h1>
            <h5 class="text-muted">
                Every time you buy something used product, you're helping to reduce the demand for new products and the
                resources needed to make them. So, <span class="text-primary">"Shop Used Product, Save the world"</span>!
            </h5>
            <RouterLink class="btn btn-primary" to="/">Shop Now !</RouterLink>
        </div>
    </div>
</template>

<script>
import ShoppingCartItem from "./ShoppingCartItem.vue";
import {UseShoppingCartStore} from "@/stores/ShoppingCart.js";
import axios from '@/axios-auth.js';
import Swal from "sweetalert2";

export default {
    name: "ShoppingCartItemList",
    setup() {
        return {
            UseShoppingCartStore: UseShoppingCartStore()
        };
    },
    data() {
        return {
            shoppingCartItems: []
        }
    },
    components: {
        ShoppingCartItem
    },
    computed: {
        total() {
            return this.UseShoppingCartStore.getTotalPrice;
        },
        totalVat() {
            return this.UseShoppingCartStore.getTotalPrice * 0.21;
        }
    },
    mounted() {
        this.loadShoppingCartItems();
    },
    methods: {
        loadShoppingCartItems() {
            this.shoppingCartItems = this.UseShoppingCartStore.getAds;
        },
        removeItemSuccessfully() {
            this.loadShoppingCartItems();
        },
        checkout() {
            this.sendPostRequest();
        },
        sendPostRequest() {
            axios.post('/ads/checkout', this.UseShoppingCartStore.getAdsIds)
                .then(response => {
                    this.UseShoppingCartStore.clearCart();
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your order has been placed successfully!',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }) .then((result) => {
                        if (result.isConfirmed) {
                            this.$router.push('/');
                        }
                    })
                })
                .catch(error => {
                    if(error.response.status === 404) {
                      Swal.fire({
                        title: 'Error!',
                        text: error.response.data.errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Ok'
                      }) .then((result) => {
                        if (result.isConfirmed) {
                          this.UseShoppingCartStore.clearCart();
                          this.$router.push('/');
                        }
                      })
                    }
                    else{
                        console.log(error.response.data.errorMessage);
                    }
                });
        }
    }

}
</script>

<style scoped>

</style>