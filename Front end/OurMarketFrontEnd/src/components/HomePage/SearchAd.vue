<template>
    <div>
        <div class="container-fluid" id="searchBar" style="background-color: aliceblue">
            <div class="container py-3 mx-auto">
                <input class="form-control" type="search" placeholder="Search in Our Market" aria-label="Search"
                    style="border-color: black"  v-model="searchingText" @input="onSearch">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "SearchAd",
    data() {
        return {
            searchingText: ''
        }
    },
    methods: {
        onSearch() {
            this.$emit('search', this.searchingText)
        }
    }
}
</script>