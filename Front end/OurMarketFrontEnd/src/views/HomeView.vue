<template>
    <div>
        <SearchAd @search="search"></SearchAd>
        <div v-if="searchNotFound">
            <h2 class="text-center pt-3">🤷 Sorry, no search result found for {{searchQuery}} 🙁</h2>
        </div>
        <div v-else-if="noAds" class="container-fluid mt-5">
            <div class="row">
                <div class="container-fluid">
                    <div class="col offset-lg-2 text-center pr-3">
                        <h1 class="font-weight-bold">
                            No ads at the moment? That's okay, just think of it as a chance to take a break and come
                            back
                            refreshed and
                            ready to shop!
                            <span class="emoji" style="font-size: 1.0em;">👀</span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <AvailableAdList :ads="ads"></AvailableAdList>
        </div>
        <pagination class="pt-3" @previous="previousPageClicked"
                    @next="nextBtnPageClicked"
                    :needMorePages="!noAds"
        ></pagination>
    </div>
</template>

<script>
import SearchAd from '@/components/HomePage/SearchAd.vue'
import AvailableAdList from '@/components/HomePage/AvailableAdList.vue'
import Pagination from '@/components/Pagination.vue'
import axios from '@/axios-auth.js'

export default {
    name: 'Home',
    components: {
        SearchAd,
        AvailableAdList,
        Pagination
    },
    data() {
        return {
            ads: [],
            limit: 4,
            offset: 0,
            url: '/ads',
            noAds: false,
            searchNotFound: false,
            searchQuery: '',
        }
    },
    methods: {
        search(searchQuery) {
            if (searchQuery !== '') {
                this.searchQuery = searchQuery;
                this.url = '/ads?name=' + searchQuery
            } else {
                this.searchQuery = '';
                this.url = '/ads';
            }
            this.getAds();
        },
        previousPageClicked() {
            if (this.offset > 0) {
                this.offset = this.offset - this.limit;
            }
            this.getAds();
        },
        nextBtnPageClicked() {
            if(!this.noAds){
                this.offset = this.offset + this.limit;
                this.getAds();
            }
        },
        getAds() {
            const params = {
                limit: this.limit,
                offset: this.offset,
            }
            axios.get(this.url, {params})
                .then(response => {
                    if (response.status === 200) {
                        this.ads = response.data;
                        this.noAds = false;
                        this.searchNotFound = false;
                    }
                    if (response.status === 204) {
                        this.noAds = true;
                    }
                })
                .catch(error => {
                    if (error.response.status === 404) {
                        this.searchNotFound = true;
                        this.noAds = true;
                    } else {
                        console.log(error);
                    }
                });
        },
    },
    mounted() {
        this.getAds();
    },
}
</script>
