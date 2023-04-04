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
                <button id="AddToCart" class="btn btn-primary position-relative" type="submit"><i
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
        </div>
    </div>
</template>

<script>
import {IMG_BASE_URL} from '../../constants.js';
export default {
    name: "AvailableAdItem",
    props: {
        ad: {
            type: Object
        }
    },
    data() {
        return {
            fullImageUrl:IMG_BASE_URL,
        }
    }
}
</script>

<style scoped></style>