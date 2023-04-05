<template>
    <div class="card">
        <div class="card-body p-4">
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
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ShoppingCartItem from "./ShoppingCartItem.vue";
import {UseShoppingCartStore} from "@/stores/ShoppingCart";

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
    mounted() {
        this.loadShoppingCartItems();
    },
    methods: {
        loadShoppingCartItems() {
            this.shoppingCartItems = this.UseShoppingCartStore.getAds;
        },
        removeItemSuccessfully() {
            this.loadShoppingCartItems();
            console.log('removeItemSuccessfully');
        },
        getAllItems(){


        }
    }

}
</script>

<style scoped>

</style>