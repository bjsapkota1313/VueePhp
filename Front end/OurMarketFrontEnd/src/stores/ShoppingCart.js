import {defineStore} from "pinia";
import axios from "@/axios-auth";
import Swal from "sweetalert2";

export const UseShoppingCartStore = defineStore('ShoppingCart', {
    state: () => ({
        adIds: [],
        ads: [],
        totalPrice: 0,

    }),
    getters: {
        getAds: (state) => state.ads,
        getItemsCount: (state) => state.adIds.length,
        getAdsIds: (state) => state.adIds,
        getTotalPrice: (state) => {
            let totalPrice = 0;
            state.ads.forEach((ad) => {
                totalPrice += ad.price;
            });
            return totalPrice;
        }
    },
    actions: {
        addAd(adId) {
            if (this.checkExistenceOfAdInCart(adId)) {
                Swal.fire({
                    position: 'top-end',
                    title: 'oops!',
                    text: 'This ad is already in your cart',
                    showConfirmButton: true,
                    timer: 1500
                })
                return;
            }
            this.getAd(adId)
                .then((response) => {
                        this.ads.push(response);
                        this.adIds.push(adId);
                        this.saveInLocalStorage();
                    }
                ).catch((error) => {
                console.log(error);
            });
        },
        removeAd(adId) {
            this.adIds = this.adIds.filter(
                (id) => id !== adId);
            this.loadsAds();
            this.saveInLocalStorage();
        },
        checkExistenceOfAdInCart(adId) {
            return this.adIds.includes(adId);
        }
        , clearCart() {
            this.adIds = [];
            this.saveInLocalStorage();
            this.ads = [];
        },
        getAd(adId) {
            return new Promise((resolve, reject) => {
                axios.get("/ads/" + adId)
                    .then((response) => {
                            if (response.status === 200) {
                                resolve(response.data);
                            }
                        }
                    ).catch((error) => {
                    if (error.response.status === 404) {
                        let title = "This ad is not available anymore. ";
                        let text = "It might have been deleted by the owner or it might have been sold.";
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: title,
                            text: text,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                    else {
                        reject(error.response.data.errorMessage);
                    }
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
    },
});
// Clear the store and local storage after 20 minutes
setTimeout(() => {
    UseShoppingCartStore().clearCart();
    UseShoppingCartStore().$reset();

}, 20 * 60 * 1000) // 20 minutes in milliseconds