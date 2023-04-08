<template>
    <div v-if="userSessionStore.isLoggedIn" :class="{'modal-active': modalActive}">
        <div class="container-fluid px-3 py-2 my-2 text-center" style="background-color: aliceblue;">
            <h1 id="displayMessage" class="display-6 fw-semibold">{{ greeting }} {{
                userSessionStore.getFirstName
                }}</h1>
            <div class="col-lg-6 mx-auto">
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center" id="buttonHolder">
                    <!-- Button to Open the Modal -->
                    <button id="buttonPostNewAd" type="button" class="btn btn-success btn-lg px-4 gap-3"
                            @click="openAddAdModal">
                        <i class="fa-solid fa-pen-to-square"></i> Post New Ad
                    </button>
                </div>
            </div>
        </div>
        <MyAdsList ref="MyAdsList"></MyAdsList>
        <AddAdModal v-show="showModal"
                    @closeModal="closeAddAdModal"
                        @adAddedSuccessFully ="adAddedSuccessFully">
        </AddAdModal>
    </div>
    <div v-else>
        <div class="container-fluid px-3 py-2 my-2 text-center" style="background-color: aliceblue;">
            <h1 class="display-5 semi-bold">Please login to view your ads</h1>
            <div class="col-lg-6 mx-auto">
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center" id="buttonHolder">
                    <router-link to="/login" class="btn btn-success btn-lg px-4 gap-3">Login</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import MyAdsList from '@/components/MyAdsPAge/MyAdsList.vue'
import {useUserSessionStore} from '@/stores/UserSession.js'
import AddAdModal from '@/components/MyAdsPAge/AddAdModal.vue'

export default {
    name: "MyAdsView",
    setup() {
        return {
            userSessionStore: useUserSessionStore(),
        };
    },
    components: {
        MyAdsList,
        AddAdModal,
    },
    data() {
        return {
            showModal: false,
            modalActive: false,
        }
    },
    methods: {
        adAddedSuccessFully() {
            this.closeAddAdModal();
            this.$refs.MyAdsList.loadAds(); // calling the method in MyAdsList component
        },
        openAddAdModal() {
            this.showModal = true;
            this.updateModalActive();
        },
        closeAddAdModal() {
            this.showModal = false;
            this.updateModalActive()
        },
        updateModalActive() {
            this.modalActive = this.showModal;
            if (this.modalActive) { // preventing scrolling when modal is open
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }
        }
    },
    computed: {
        greeting() {
            const hour = new Date().getHours();
            if (hour < 12) {
                return "Good morning!";
            } else if (hour >= 12 && hour < 18) {
                return "Good afternoon!";
            } else {
                return "Good evening!";
            }
        },
    },
}
</script>

<style scoped>
.modal-active {
    overflow: hidden;
}
</style>