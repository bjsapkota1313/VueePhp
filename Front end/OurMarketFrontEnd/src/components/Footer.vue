<template>
    <footer class="footer mt-auto py-3 bg-dark d-flex justify-content-between align-items-center text-white">
        <div class="pl-5"><img src="@/assets/Logo.svg" alt="Company Logo" class="img-fluid" style="max-width:150px;"></div>
        <div class="text-center text-muted">
            Copyright &copy; 2023 OurMarket
        </div>
        <div class="d-flex" style="float: right">
            <a href="#">
                <i class="fab fa-facebook fa-2x mx-3"></i>
            </a>
            <a href="#">
                <i class="fab fa-instagram fa-2x mx-3"></i>
            </a>
            <a href="#">
                <i class="fab fa-twitter fa-2x mx-3"></i>
            </a>
        </div>
    </footer>
</template>

<script>
export default {
    name: "Footer"
}
</script>
<style scoped>
.footer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
}
</style>
