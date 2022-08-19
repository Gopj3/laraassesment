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
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li
                    v-for="i in (new Array(lastPage).fill('').keys())"
                    :key="i"
                    class="page-item">
                        <a class="page-link" @click="handleReload(i)" href="#">{{i + 1}}</a>
                </li>
            </ul>
        </nav>
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
            currentPage: 1,
            lastPage: 0,
        }
    },
    created() {
        this.listUsers();
    },
    methods: {
        async handleReload(i) {
            this.currentPage = i + 1;
            await this.listUsers();
        },
        async listUsers() {
            const {users, currentPage, lastPage} = await getUsersAsync(this.currentPage);

            this.users = users
            this.currentPage = currentPage;
            this.lastPage = lastPage;
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
