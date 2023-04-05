<template>
    <div class="container ml-2 " v-if="!noAds">
        <MyAdAvailableItem v-for="(ad, index) in availableAds" :key="index" :ad="ad" @AdDeletedSuccessFully="adDeletedSuccessFully"/>
        <MyAdOtherStatusItem v-for="(ad, index) in otherStatusAds" :key="index" :ad="ad"/>
    </div>
    <div class="container ml-2 " v-else>
        <div class="container-fluid pt-5 mx-auto d-flex justify-content-center w-100" id="myAdsContainer">
            <div class="row">
                <div class="col text-center">
                    <div class="card">
                        <div class="card-header display-5">
                            "Sell Your Used Products, Give them a Second Life"
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Post an Ad inorder to sell your product</h5>
                            <p class="card-text">You can help in reducing waste, pollution and greenhouse gas emissions by selling your
                                used products to others who needs them.</p>
                            <button id="buttonPostNewAd" type="button" class="btn btn-success btn-lg px-4 gap-3" data-bs-toggle="modal"
                                    data-bs-target="#myModal">
                                <i class="fa-solid fa-pen-to-square"></i> Post New Ad
                            </button>
<!--                            //TODo: Change this to a router link-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import MyAdOtherStatusItem from './MyAdOtherStatusItem.vue';
import MyAdAvailableItem from './MyAdAvailableItem.vue';
import axios from '@/axios-auth.js';

export default {
    name: "MyAdsList",
    data() {
        return {
            ads: [],
            noAds: false,
        }

    },
    components: {
        MyAdOtherStatusItem,
        MyAdAvailableItem
    },
    mounted() {
        this.loadAds();
    },
    computed: {
        availableAds() {
            return this.ads.filter(ad => ad.status === 'Available');
        },
        otherStatusAds() {
            return this.ads.filter(ad => ad.status !== 'Available');
        }
    },
    methods: {
        loadAds() {
            this.getAds()
                .then((ads) => {
                    this.ads = ads;
                });
        },
        getAds() {
            return axios
                .get('/ads/user')
                .then((response) => {
                    if (response.status === 200) {
                        return response.data; // return the data
                    }
                    this.noAds = response.status === 204;
                })
                .catch((error) => {
                    console.log(error) ;
                });

        },
        adDeletedSuccessFully() {
            this.loadAds();
        }
    }
}
</script>

<style scoped></style>