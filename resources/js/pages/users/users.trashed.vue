<template>
    <div>
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
            <tr class="c-pointer" v-for="user in users" :key="user.id">
                <td v-for="(row, index) in headerColumns" :key="index">
                    <div v-if="row.beValue !== 'actions'">
                        <div class="d-flex justify-content-center align-items-center">
                            {{ user[row.beValue] }}
                        </div>
                    </div>
                    <div v-else class="d-flex justify-content-center">
                        <button class="btn btn-outline-info" @click="restore(user.id)">
                            Restore
                        </button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import {getTrashedListAsync, restoreAsync} from "../../services/users.service";

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
                {name: 'Actions', beValue: 'actions'}
            ],
        }
    },
    created() {
        this.listUsers();
    },
    methods: {
        async listUsers() {
            this.users = await getTrashedListAsync();
        },
        async restore(id) {
            await restoreAsync(id);
            await this.listUsers();
        }
    }
}
</script>

<style scoped>

</style>
