<template>
    <div class="modal fade" tabindex="-1" role="dialog"></div>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{title}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" @click="closeModal"></button>
            </div>
            <div class="modal-body">
                {{ message }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="closeModal">OK</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ErrorMessageBox",
    props: {
        title: {
            type: String,
            required: true
        },
        message: {
            type: String,
            required: true
        }
    },methods: {
        closeModal() {
            this.$props.message = "";
            this.$props.title = "";
            this.$emit('closeModal');
        }
    }
}
</script>

<style scoped>

</style>