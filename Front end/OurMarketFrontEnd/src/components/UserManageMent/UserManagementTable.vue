<template>
    <div class="container-fluid">
        <div class="table-responsive-sm">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>email</th>
                    <th>role</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <UserManagementTableRow v-for="user in users" :key="user.id" :user="user"
                                        @userDeletedSuccessFully="userDeletedSuccessFully">
                </UserManagementTableRow>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import UserManagementTableRow from "./UserManagementTableRow.vue";
export default {
    name: "UserManagementTable",
    props: {
        users: Array
    },
    components: {
        UserManagementTableRow
    }
    ,methods:{
        userDeletedSuccessFully(){
            this.$emit('userDeletedSuccessFully')
        }
    }
}
</script>

<style scoped>

</style>