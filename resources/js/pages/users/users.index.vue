<template>
    <div>
        <div class="d-flex justify-content-end w-100 mb-5">
            <button class="btn btn-outline-primary" @click="handleCreate">Create User</button>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th scope="col" v-for="(column, index) in headerColumns" :key="index">
                    <div class="d-flex justify-content-center">
                        {{ column.name }}
                    </div>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr class="c-pointer" v-for="user in users" :key="user.id" @click="showUser(user.id)">
                <td v-for="(row, index) in headerColumns" :key="index">
                    <div class="d-flex justify-content-center">
                        {{ user[row.beValue] }}
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import {getUsersAsync} from "../../services/users.service";

export default {
    name: "users.index",
    data() {
        return {
            users: [],
            headerColumns: [
                {name: 'id', beValue: 'id'},
                {name: 'First Name', beValue: 'firstname'},
                {name: 'Last Name', beValue: 'lastname'},
                {name: 'Username', beValue: 'username'},
                {name: 'Email', beValue: 'email'},
                {name: 'Type', beValue: 'type'},
            ],
        }
    },
    created() {
        this.listUsers();
    },
    methods: {
        async listUsers() {
            this.users = await getUsersAsync();
        },
        showUser(id) {
            this.$router.push(`/users/show/${id}`);
        },
        handleCreate() {
            this.$router.push('/users/create');
        }
    }
}
</script>

<style scoped>

</style>
