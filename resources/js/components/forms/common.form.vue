<template>
    <div class="d-flex w-100 justify-content-center">
        <div class="card" style="width: 24rem;">
            <div class="card-body">
                <form enctype="multipart/form-data" @submit="submitAction">
                    <fieldset>
                        <div class="mb-3">
                            <img class="w-100 c-pointer" @click="chooseFile" v-if="avatarPreview" :src="avatarPreview"
                                 alt="Avatar">

                            <div v-show="!avatarPreview">
                                <input
                                    class="form-control "
                                    ref="fileInput"
                                    type="file"
                                    id="formFileLg"
                                    @change="onFileChange">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" id="email" class="form-control" v-model="userData.email" required
                                   placeholder="Email">
                        </div>
                        <div class="mb-3">
                            <label for="firstname" class="form-label">First Name</label>
                            <input type="text" id="firstname" class="form-control" v-model="userData.firstname" required
                                   placeholder="First Name">
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Last Name</label>
                            <input type="text" id="lastname" class="form-control" v-model="userData.lastname" required
                                   placeholder="Last Name">
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="lastname" class="form-control" v-model="userData.username" required
                                   placeholder="Username">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" v-model="userData.password" class="form-control"
                                   placeholder="Password">
                        </div>
                        <div class="mb-3">
                            <label for="prefixname" class="form-label">Prefix Name (optional)</label>
                            <select id="prefixname" v-model="userData.prefixname" class="form-select">
                                <option>Mrs.</option>
                                <option>Mr.</option>
                                <option>Ms.</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="middlename" class="form-label">Middle Name (optional)</label>
                            <input type="text" id="middlename" v-model="userData.middlename" class="form-control"
                                   placeholder="Middle Name">
                        </div>
                        <div class="mb-3">
                            <label for="suffixname" class="form-label">Suffix Name (optional)</label>
                            <input type="text" id="suffixname" v-model="userData.suffixname" class="form-control"
                                   placeholder="Suffix Name">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</template>

<script>

import {createUserAsync, updateSingleUserAsync} from "../../services/users.service";

export default {
    name: "common-form",
    props: {
        user: {
            required: false,
            type: Object
        },
        actionType: {
            required: true,
            type: String
        }
    },
    data() {
        return {
            userData: {},
            formData: new FormData(),
            avatarPreview: null,
            file: null,
        };
    },
    created() {
        if (this.$props.user && Object.keys(this.$props.user).length > 0) {
            this.userData = {...this.$props.user, password: '*****'};
            this.avatarPreview = this.userData?.photo;

            return
        }

        this.userData = {
            'firstname': "",
            'lastname': "",
            'username': "",
            'email': "",
            'photo': "",
            'middlename': "",
            'suffixname': "",
            'prefixname': "",
            'fullname': "",
            'type': "",
            'deleted_at': "",
            'password': '',
        }
    },
    methods: {
        async submitAction(e) {
            e.preventDefault();
            e.stopPropagation();

            if (this.file) {
                this.formData.append("file", this.file);
            }

            for (let val of Object.keys(this.userData).filter(
                el => el !== 'photo' && el !== 'id' && el !== 'deleted_at')) {
                this.formData.append(val, this.userData[val]);
            }

            if (this.actionType === 'edit') {
                await updateSingleUserAsync(this.userData.id, this.formData);

                return;
            }

            await createUserAsync(this.formData);
        },
        onFileChange(e) {
            const file = e.target.files[0];
            this.avatarPreview = URL.createObjectURL(file);
            this.file = file;
        },
        chooseFile() {
            this.$refs.fileInput.click();
        }
    },
}
</script>

<style scoped>

</style>
