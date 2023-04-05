<template>
    <tr>
        <td>{{ user.id }}</td>
        <td>{{ user.firstName }}</td>
        <td>{{ user.lastName }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.role }}</td>
        <td>
            <button class="btn btn-danger" @click="deleteUser(user.id)">Delete</button>
        </td>
    </tr>

</template>

<script>
import Swal from "sweetalert2";
import axios from '@/axios-auth.js';

export default {
    name: "UserManagementTableRow",
    props: {
        user: Object
    },
    methods: {
        deleteUser(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'If you delete this user, you will also delete all the ads that this user has posted!',
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
                            this.$emit('userDeletedSuccessFully');
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
        }
        , sendDeleteRequest(id) {
            return new Promise((resolve, reject) => {
                axios.delete("/users/" + id)
                    .then(response => {
                        resolve();
                    })
                    .catch(error => {
                        reject(error.response.data.errorMessage);
                    });
            });
        }
    }
}
</script>

<style scoped>

</style>