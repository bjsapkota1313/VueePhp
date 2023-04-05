<template>
    <div class="card mb-3 ">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div class="d-flex flex-row align-items-center">
                    <div>
                        <img :src="fullImageUrl" class="img-fluid rounded-3" alt="Shopping item" style="width: 65px;">
                    </div>
                    <div class="ms-3">
                        <h5> {{ ad.productName }}</h5>
                        <p class="card-text small mb-0"><small
                                class="text-muted">{{ new Date(ad.postedDate.date).toLocaleDateString() }}
                            posted by
                        </small>
                            <strong>{{ ad.user.firstName }}</strong>
                        </p>
                    </div>
                </div>
                <div class="d-flex flex-row align-items-center">
                    <div style="width: 80px;">
                        <p class="mb-0">
                            <strong>€
                                {{ ad.price.toFixed(2) }}</strong>
                        </p>
                    </div>
                    <input type="hidden" name="hiddenSHoppingCartItemID" value="<?= $ad->getId() ?>>">
                    <button name="removeCartItem" style=" border:none; background-color: transparent; color: #cecece;" @click="removeItem(ad.id)">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {IMG_BASE_URL} from '../../constants.js';
import {UseShoppingCartStore} from "@/stores/shoppingCart.js";

export default {
    name: "ShoppingCartItem",
    setup() {
        return {
            UseShoppingCartStore: UseShoppingCartStore()
        }
    },
    props: {
        ad: {
            type: Object
        }
    },
    computed: {
        fullImageUrl() {
            return IMG_BASE_URL + this.ad.imageUri;
        }
    },methods: {
        removeItem(id) {
            this.UseShoppingCartStore.removeAd(id);
            this.$emit('removeItemSuccessfully');
        }
    }

}
</script>

<style scoped>

</style>