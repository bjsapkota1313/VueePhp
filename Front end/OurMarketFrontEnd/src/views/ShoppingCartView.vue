<template>
    <div class="container">
        <div class="row py-3 text-center" id="containerRowContainerAvailableAds">
            <ShoppingCartItemList></ShoppingCartItemList>
        </div>
    </div>
</template>

<script>
import ShoppingCartItemList from "@/components/ShoppingCart/ShoppingCartItemList.vue";
export default {
    name: "ShoppingCartView",
    components: {
        ShoppingCartItemList}
}
</script>

<style scoped>

</style>