<template>
    <div class="card mb-3" style="max-width: 900px; position: relative;">
        <div class="row g-0">
            <div class="col-md-4 col-xl-4">
                <img :src="fullImageUrl + ad.imageUri" class="img-fluid rounded-start" alt="...">
            </div>
            <div class="col-md-8 col-xl-8 d-flex flex-column justify-content-around">
                <div class="card-body">
                    <h5 class="card-title">{{ ad.productName }}</h5>
                    <p class="card-text">{{ ad.description }}</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Price:</strong>
                            €{{ ad.price.toFixed(2) }}</li>
                        <li class="list-group-item"><strong>Status:</strong> {{ ad.status }}</li>
                        <li class="list-group-item"><strong>Posted at: </strong>{{ new
                            Date(ad.postedDate.date).toLocaleDateString() }}</li>
                    </ul>
                </div>
                <div class="d-flex justify-content-end mb-2">
                    <button class="btn btn-primary mx-2" disabled>Mark As Sold</button>
                    <button class="btn btn-secondary mx-2" disabled><i class="fa-solid fa-file-pen"></i> Edit
                    </button>
                    <button class="btn btn-danger mx-2" disabled><i class="fa-solid fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
        <div class="overlay"
            style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center;">
            <h2 style="color: white;">{{ ad.status }}</h2>
        </div>
    </div>
</template>

<script>
import { IMG_BASE_URL } from '@/constants.js';
export default {
    name: "MyAdOtherStatusItem",
    props: {
        ad: {
            type: Object
        }
    },
    data() {
        return {
            fullImageUrl: IMG_BASE_URL,
        }
    }
}
</script>

<style scoped></style>