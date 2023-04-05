<template>
    <div class="card mb-3" id="card">
        <div class="row g-0">
            <div class="col-md-4 col-xl-4">
                <img :src="fullImageUrl + ad.imageUri" class="img-fluid rounded-start">
            </div>
            <div class="col-md-8 col-xl-8 d-flex flex-column justify-content-around">
                <div class="card-body">
                    <h5 class="card-title">{{ ad.productName }}</h5>
                    <p class="card-text"> {{ ad.description }}</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Price:</strong>
                            €{{ ad.price.toFixed(2) }}
                        </li>
                        <li class="list-group-item"><strong>Status:</strong> {{ ad.status }}</li>
                        <li class="list-group-item"><strong>Posted at: </strong> {{
                            new
                            Date(ad.postedDate.date).toLocaleDateString()
                            }}
                        </li>

                    </ul>
                </div>
                <div class="d-flex justify-content-end mb-2">
                    <button class="btn btn-primary mx-2" @click="markAsSold(ad.id)">
                        Mark As Sold
                    </button>
                    <button class="btn btn-secondary mx-2">
                        <i class="fa-solid fa-file-pen"></i> Edit
                    </button>
                    <button class="btn btn-danger mx-2" @click="deleteAd(ad.id)">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {IMG_BASE_URL} from '../../constants.js';
import axios from '@/axios-auth.js';
import Swal from "sweetalert2";


export default {
    name: "MyAdAvailableItem",
    props: {
        ad: {
            type: Object
        }
    },
    methods: {
        // showAddAdModal() {
        //     this.showModal = true;
        // },
        // closeAddAdModal() {
        //     this.showModal = false;
        // }
        deleteAd(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'If you delete Ad You cannot retrieve it back!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.sendDeleteRequest(id)
                        .then(response => {
                            this.$emit('AdDeletedSuccessFully');
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Something went wrong!',
                                text: error,
                                icon: 'error"'
                            });
                        });
                }
            });

        },
        sendDeleteRequest(id) {
            return new Promise((resolve, reject) => {
                axios.delete('/ads/' + id)
                    .then((response) => {
                        resolve();
                    })
                    .catch((error) => {
                        reject(error);
                    });
            });
        },
        markAsSold(id) {
            let adStatus =
                {
                    status: "Sold"
                };
            this.sendPutRequest(id, adStatus)
                .then(response => {
                    console.log(response);
                  //  this.$emit('AdMarkedAsSoldSuccessFully');
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Something went wrong!',
                        text: error,
                        icon: 'error"'
                    });
                });

        },
        sendPutRequest(id, adStatus) {
            return new Promise((resolve, reject) => {
                axios.put('/ads/' + id, adStatus)
                    .then((response) => {
                        resolve();
                    })
                    .catch((error) => {
                        reject(error);
                    });
            });
        }
    },
    data() {
        return {
            fullImageUrl: IMG_BASE_URL,
            showModal: false
        }
    }
}
</script>

<style scoped>
#card {
    max-width: 900px;
}
</style> 