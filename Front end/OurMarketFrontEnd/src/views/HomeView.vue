<template>
    <div>
        <SearchAd @search="search"></SearchAd>
        <AvailableAdList :ads="ads"></AvailableAdList>
    </div>
</template>

<script>
import SearchAd from '@/components/HomePage/SearchAd.vue'
import AvailableAdList from '@/components/HomePage/AvailableAdList.vue'
import axios from '@/axios-auth.js'

export default {
    name: 'Home',
    components: {
        SearchAd,
        AvailableAdList,
    },
    data() {
        return {
            ads: [],
            currentPage: 1,
            limit: 5,
            offset: 0,
            rows: 0,
        }
    },
    methods: {
        search(searchQuery) {
            if (searchQuery !== '')
                this.getAds('/ads?name=' + searchQuery);
            else
                this.getAds('/ads');
        }
        , getAds($url) {
            // const params = {
            //     limit: this.limit,
            //     offset: this.offset,
            // }
            axios.get($url) //, {params}
                .then(response => {
                    this.ads = response.data;
                    this.rows = response.headers['x-total-count'];
                })
                .catch(error => {
                    console.log(error.response.data.errorMessage);
                });
        }
        , paginationChange(event) {
            this.limit = event.perPage;
            this.offset = (event.currentPage - 1) * this.limit;
            this.getAds('/ads');
        },
    }, computed: {}
    , mounted() {
        this.getAds('/ads');
    }
}
</script>
