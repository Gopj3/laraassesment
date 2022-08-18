<template>
    <div>
        <div v-if="loading" class="d-flex justify-content-center w-100 align-items-center">
            <div class="spinner-border" role="status" >
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div v-else class="d-flex w-100 justify-content-center align-items-center">
            <div class="card" style="width: 24rem;">
                <div class="w-100 d-flex justify-content-center">
                    <img :src="user.photo" class="card-img-top" alt="...">
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex gap-3" v-for="property in Object.keys(userFiltered)" :key="property">
                            <span class="link-primary">{{property.toUpperCase()}}:</span>
                            <span class="link-info">{{user[property]}}</span>
                        </li>
                    </ul>

                    <div class="d-flex mt-2 gap-1 w-100 justify-content-around">
                        <button class="btn btn-outline-success" @click="handleEditNavigation">Edit</button>
                        <button class="btn btn-outline-danger" @click="handleSoftDelete">Temporary Delete</button>
                        <button class="btn btn-outline-danger">Completely delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {getSingleUserAsync, softDeleteAsync} from "../../services/users.service";

export default {
    name: "users.show",
    data() {
        return {
            loading: false,
            user: {},
            userFiltered: {}
        }
    },
    created() {
        this.showUserInfo();
    },
    methods: {
        async showUserInfo() {
            this.loading = true;
            const id = this.$route.params.id;
            this.user = await getSingleUserAsync(id);
            const filteredKeys = Object.keys(this.user).filter(el => el !== 'photo' && el !== 'deleted_at');

            for (let val of filteredKeys) {
                this.userFiltered[val] = this.user[val];
            }
            this.loading = false;
        },
        async handleSoftDelete() {
            this.loading = true;
            const id = this.$route.params.id;
            await softDeleteAsync(id);
            this.loading = false;

            this.$router.push('/users');
        },
        handleEditNavigation() {
            const id = this.$route.params.id;
            this.$router.push(`/users/edit/${id}`);
        },
    },
}
</script>

<style scoped>

</style>
