<template>
    <div class="modal fade show" id="editModal" style="display: block" tabindex="-1" role="dialog"
         aria-labelledby="edit-modal-label"
         aria-hidden="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Your Ad</h5>
                    <button id="buttonCloseEditModal" class="btn-close" @click="close"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="edit-form">
                        <div class="modal-body">
                            <input type="number" id="hiddenAdIdEditAdModal" hidden>
                            <div class="form-group">
                                <img class="img-fluid rounded" :src="fullImageUrl" alt=""
                                     style="width: 400px; height: 200px;">
                                <label for="image"><strong>Change Image</strong> </label><br>
                                <input type="file" class="form-control-file"
                                       accept="image/png, image/jpeg,image/jpg" v-on:change="handleFileUpload">
                            </div>
                            <div class="form-group">
                                <label for="productName"> <strong>Product Name</strong></label>
                                <input type="text" class="form-control" v-model="productName">
                            </div>
                            <div class="form-group">
                                <label for="price"><strong>Price</strong></label>
                                <input type="number" class="form-control" step="0.01" v-model="price">
                            </div>
                            <div class="form-group">
                                <label for="description"><strong>Description</strong></label>
                                <textarea class="form-control" v-model="description"
                                          rows="7"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger" role="alert" v-if="errorMessage">
                        {{ errorMessage }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="close">Cancel</button>
                    <button type="button" class="btn btn-success" @click="saveChange">Save
                        Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from '@/axios-auth.js';
import {IMG_BASE_URL} from '../../constants.js';
import Swal from "sweetalert2";

export default {
    name: "EditAdModal",
    props: {
        adId: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            productName: '',
            price: '',
            description: '',
            image: null,
            imageUri: '',
            errorMessage: '',
            IMG_BASE_URL: IMG_BASE_URL,
            imageValid: true
        }
    }, computed: {
        fullImageUrl() {
            if (this.image !== null) {
                return this.imageUri;
            }
            return this.IMG_BASE_URL + this.imageUri;
        }
    },
    methods: {

        saveChange() {
            if (!this.validateForm()) {
                return;
            }
            let formData = new  URLSearchParams();
            let adDetails = {
                productName: this.productName,
                price: this.price,
                description: this.description
            }
            formData.append('adDetails', JSON.stringify(adDetails));
            formData.append('image', this.image);
            this.sendPutRequest(formData);
        },
        sendPutRequest(formData) {
            axios.put('/ads/' + this.adId, formData.toString(),
                {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .then(response => {
                    console.log(response);
                })
                .catch(error => {
                    console.log(error);
                });


        },
        validateForm() {
            if (this.productName === '') {
                this.errorMessage = 'Product name is required!';
                return false;
            }
            if (this.price === '') {
                this.errorMessage = 'Price is required!';
                return false;
            }
            if (this.description === '') {
                this.errorMessage = 'Description is required!';
                return false;
            }
            return this.imageValid;
        },
        isUploadingImageValid(image) {
            if (image.type !== 'image/png' && image.type !== 'image/jpeg' && image.type !== 'image/jpg') {
                this.errorMessage = 'Image type must be PNG, JPG or JPEG!';
                return false;
            }
            return true;
        },
        handleFileUpload(event) {
            let image = event.target.files[0];
            if (this.isUploadingImageValid(image)) {
                this.image = image;
                let reader = new FileReader();
                reader.readAsDataURL(this.image);
                reader.onload = (e) => {
                    this.imageUri = e.target.result;
                }
                this.imageValid = true;
            } else {
                this.imageValid = false;
            }
        },
        close() {
            this.$emit('closeModal');
        },
        getAd() {
            axios.get('/ads/' + this.adId)
                .then(response => {
                    this.productName = response.data.productName;
                    this.price = response.data.price;
                    this.description = response.data.description;
                    this.imageUri = response.data.imageUri;
                })
                .catch(error => {
                    if (error.response.status === 404) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'This ad does not exist anymore!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        })
                    } else {
                        console.log(error);
                    }
                })
        },
    },
    mounted() {
        this.getAd();
    },
}
</script>

<style scoped>

</style>