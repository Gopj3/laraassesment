<template>
    <div class="container">
        <button v-if="$route.name !== 'users' && $route.name !== 'users-trashed'" class="btn btn-outline-info" @click="handleBack">Back</button>
        <button v-if="$route.name !== 'users-trashed'" class="btn btn-outline-info" @click="handleTrashed">Trashed</button>
        <button v-if="$route.name === 'users-trashed'" class="btn btn-outline-info" @click="handleGoToIndex">Index</button>
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
            this.$router.replace('/users/trashed');
        },
        handleGoToIndex() {
            this.$router.replace('/users');
        }
    }
}
</script>

<style scoped>

</style>
