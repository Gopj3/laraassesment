<template>
    <div v-if="loading" class="d-flex justify-content-center w-100 align-items-center">
        <div class="spinner-border" role="status" >
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div v-else>
        <common-form actionType="edit" :user="user"></common-form>
    </div>
</template>

<script>
import CommonForm from "../../components/forms/common.form.vue";
import {getSingleUserAsync} from "../../services/users.service";

export default {
    name: "users.edit",
    data() {
        return {
            user: {},
            loading: false,
        }
    },
    created() {
        this.getUserInfo();
    },
    methods: {
        async getUserInfo() {
            this.loading = true;
            const id = this.$route.params.id;
            this.user = await getSingleUserAsync(id);
            this.loading = false;
        }
    },
    components: {
        CommonForm
    },
}
</script>

<style scoped>

</style>

