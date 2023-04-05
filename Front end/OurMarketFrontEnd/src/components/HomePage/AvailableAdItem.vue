<template>
    <div class="col-md-4 col-sm-12 col-xl-3 my-3">
        <div class="card h-100 shadow">
            <img :src="fullImageUrl+ad.imageUri" class="img-fluid card-img-top" :alt="ad.productName" style="width:300px; height:300px">
            <div class="card-body">
                <h3 class="card-title">
                    {{ ad.productName }}
                </h3>
                <p class="card-text">
                    {{ ad.description }}
                </p>
                <button class="btn btn-primary position-relative" type="button" @click="addToCart"><i
                        class="fa-solid fa-cart-plus"></i>
                    €
                    {{ ad.price.toFixed(2) }}
                </button>
            </div>
            <div class="card-footer ">
                <p class="card-text"><small class="text-muted">
                        {{ new Date(ad.postedDate.date).toLocaleDateString() }}
                    </small> posted by  <strong>
                        {{ ad.user.firstName }}
                    </strong>
                </p>
            </div>
            <ErrorMessageBox
                v-show="displayErrorMessageBox"
                title="Sorry"
                :message="errorMessage"
                @closeModal="closeErrorMessageBox"> </ErrorMessageBox>
        </div>
    </div>
</template>

<script>
import {IMG_BASE_URL} from '../../constants.js';
import  {UseShoppingCartStore} from "@/stores/shoppingCart.js";
import ErrorMessageBox from "@/components/ErrorMessageBox.vue";
export default {
    name: "AvailableAdItem",
    components: {ErrorMessageBox},
    setup() {
        return {
            UseShoppingCartStore:UseShoppingCartStore
        }
    },
    props: {
        ad: {
            type: Object
        }
    },
    data() {
        return {
            fullImageUrl:IMG_BASE_URL,
            errorMessage: '',
            displayErrorMessageBox: true
        }
    } ,
    methods: {
        async addToCart() {
            try {
                const result =  await this.UseShoppingCartStore.addAd(this.ad.id);
                if (result === true) {

                }
            } catch (error) {
                this.displayErrorMessageBox = true;
                this.errorMessage = error;
            }
        },
        closeErrorMessageBox() {
            this.displayErrorMessageBox = false;
        }
    }
}
</script>

<style scoped></style>