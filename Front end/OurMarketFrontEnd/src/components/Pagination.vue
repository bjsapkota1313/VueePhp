<template>
    <ul class="pagination justify-content-center">
        <li class="page-item">
            <button :class="['page-link', activePage === 1 ? 'disabled' : '']" @click="previous">Previous</button>
        </li>
        <li v-for="(pageNumber, index) in pageNumbers" :key="index" :class="['page-item', isActive === pageNumber ? 'active' : '']">
            <button class="page-link" disabled @click="changeActivePage(pageNumber)">{{pageNumber}}</button>
        </li>
        <li class="page-item">
            <button class="page-link" @click="next">Next</button>
        </li>
    </ul>
</template>

<script>
export default {
    name: "Pagination",
    data() {
        return {
            activePage: 1,
            pageNumbers: [1, 2, 3],
        }
    },
    methods: {
        previous() {
            if (this.activePage > 1) {
                this.activePage--;
            }
            this.$emit('previous');
        },
        next() {
            if (this.activePage < this.pageNumbers.length) {
                this.activePage++;
            } else {
                if(this.pageNumbers.length < 5){
                    this.pageNumbers.push(this.pageNumbers.length + 1);
                    this.activePage = this.pageNumbers.length;
                }
            }
            this.$emit('next');
        },
        changeActivePage(pageNumber) {
            this.activePage = pageNumber;
        }
    },
    computed: {
        isActive() {
            return this.activePage;
        }
    }
}
</script>

<style scoped>

</style>

