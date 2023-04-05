<template>
    <section class="h-100 h-custom " style="background-color: #eee;">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100 mt-n5">
                <div class="col mt-n5">
                    <ShoppingCartItemList></ShoppingCartItemList>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import ShoppingCartItemList from "@/components/ShoppingCart/ShoppingCartItemList.vue";

export default {
    name: "ShoppingCartView",
    components: {
        ShoppingCartItemList
    }
}
</script>

<style scoped>

</style>