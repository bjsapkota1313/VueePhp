import {defineStore} from "pinia";
import axios from "@/axios-auth";

export const UseShoppingCartStore = defineStore('ShoppingCart', {
    state: () => ({
        adIds: [],
        ads: []

    }),
    getters: {
        getAds: (state) => state.ads,
        getItemsCount: (state) => state.ads.length,
    },
    actions: {
        async addAd(adId) {
            try {
                const response = await this.getAd(adId);
                this.ads.push(response);
                this.adIds.push(adId);
                this.saveInLocalStorage();
                return true;
            } catch (error) {
                return error;
            }
        },
        removeAd(adId) {
            this.adIds = this.adIds.filter((id) => id !== adId)
            this.saveInLocalStorage();
        }
        , clearCart() {
            this.adIds = [];
            this.saveInLocalStorage();
        },
        getAd(adId) {
            return new Promise((resolve, reject) => {
                axios.get("/ads/" + adId)
                    .then((response) => {
                            if (response.status === 200) {
                                resolve(response.data);
                            }
                            if (response.status === 404) {
                                reject("This ad is not available anymore. ");
                            }
                        }
                    ).catch((error) => {
                    reject(error.response.data.errorMessage);
                });
            });
        },
        loadsAds() {
            this.adIds.forEach((adId) => {
                this.getAd(adId).then((response) => {
                    this.ads.push(response);
                }).catch((error) => {
                    this.removeAd(adId); // remove adId from adIds array if it is not available from backend anymore
                });
            });
        },
        saveInLocalStorage() {
            localStorage.setItem("adIds", JSON.stringify(this.adIds));
        },
        loadFromLocalStorage() {
            if (localStorage.getItem("adIds")) {
                this.adIds = JSON.parse(localStorage.getItem("adIds"));
                this.loadsAds();
            } else {
                this.adIds = [];
            }
        }
    }
});