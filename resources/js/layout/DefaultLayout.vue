<template>
    <div class="container">
        <button v-if="$route.name !== 'users'" class="btn btn-outline-info" @click="handleBack">Back</button>
        <button v-if="$route.name !== 'users-trashed'" class="btn btn-outline-info" @click="handleTrashed">Trashed</button>

        <router-view></router-view>
    </div>
</template>

<script>
export default {
    name: "DefaultLayout",
    methods: {
        handleBack() {
            this.$router.go(-1);
        },
        handleTrashed() {
            this.$router.push('users/trashed');
        }
    }
}
</script>

<style scoped>

</style>
