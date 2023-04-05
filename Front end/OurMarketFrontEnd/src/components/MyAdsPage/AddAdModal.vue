<template>
    <div class="modal fade show " style="display: block" data-bs-backdrop="false" id="AddModal">
        <div class="modal-dialog modal-dialog-centered modal-xl w-200">

            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Post</h4>
                    <button type="button" class="btn-close" @click="closeModal"
                            aria-label="Close"></button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="productName"><strong>Product Name</strong></label>
                        <input type="text" class="form-control" v-model="productName"
                               placeholder="Enter product name">
                    </div>
                    <div class="form-group">
                        <label for="image"><strong>Image</strong></label><br>
                        <input type="file" class="form-control-file" v-on:change="handleFileUpload"
                               accept="image/png, image/jpeg,image/jpg"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="price"><strong>Price</strong></label>
                        <input type="number" class="form-control" v-model="price"
                               placeholder="Set Your Product Price" required>
                    </div>
                    <div class="form-group">
                        <label for="productDescription"><strong>Product Description</strong></label>
                        <textarea class="form-control" v-model="productDescription" rows="3"
                                  placeholder="Describe about your product like how long it is used for,what's the brand of the product"
                                  required></textarea>
                    </div>
                </div>
                <div class="alert alert-danger" role="alert" v-if="error">
                    {{ error }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success  btn-lg w-100" @click="postClicked">Post
                    </button>
                    <button type="button" class="btn btn-lg w-100 btn-secondary" @click="closeModal"
                    >Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from '@/axios-auth.js';

export default {
    name: "AddAdModal",
    data() {
        return {
            productName: "",
            image: null,
            price: "",
            productDescription: "",
            error: ""
        }
    }
    , methods: {
        handleFileUpload(event) {
            this.image = event.target.files[0];
        }
        , closeModal() {
            this.$emit('closeModal');
        },
        postClicked() {
            if (!this.productName || !this.image || !this.price || !this.productDescription) {
                this.error = "Please fill all the fields";
                return;
            }
            if (!this.isValidImage) {
                this.error = "Please upload a valid image having extension .png,jpg or jpeg";
                return;
            }
            this.sendAddingAdRequest(this.getFormData)
                .then(response => {
                this.$emit('adAddedSuccessFully');
            }).catch(error => {
                this.error = error;
            });
        },
        sendAddingAdRequest(formData) {
            return new Promise((resolve, reject) => {
                axios.post('/ads', formData )
                    .then(response => {
                    console.log(response);
                    // if (response.status === 200) {
                //     resolve(response.data); // object is returned after successful post //TODO: check if this is correct
                    // }
                }).catch(error => {
                    reject(error.response.data.errorMessage);
                });
            });
        }
    },
    computed: {
        isValidImage() {
            if (this.image) {
                const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
                return allowedExtensions.test(this.image.name);
            }
            return false;
        },
        getFormData() {
            const formData = new FormData();
            let adDetails = {
                productName: this.productName,
                price: this.price,
                description: this.productDescription
            }
            formData.append('adDetails', adDetails);
            formData.append('adImage', this.image);
            return formData;
        }
    }
}
</script>

<style scoped>

</style>