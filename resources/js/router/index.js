import { createWebHistory, createRouter } from 'vue-router'

// TODO make dynamic imports
import usersIndex from "../pages/users/users.index.vue";
import DefaultLayout from "../layout/DefaultLayout.vue";
import usersShow from "../pages/users/users.show.vue";
import usersEdit from "../pages/users/users.edit.vue";
import usersCreate from "../pages/users/users.create.vue";
import usersTrashed from "../pages/users/users.trashed.vue";

const routes = [
    {
        name: "home",
        path: "/",
        component: DefaultLayout,
        children: [
            {

                path: '/users',
                children: [
                    {
                        name: "users",
                        path: '',
                        component: usersIndex,
                    },
                    {
                        name: "users-trashed",
                        path: 'trashed',
                        component: usersTrashed,
                    },
                    {
                        name: 'user-create',
                        path: 'create',
                        component: usersCreate
                    },
                    {
                        name: "user-info",
                        path: 'show/:id',
                        component: usersShow
                    },
                    {
                        name: 'user-edit',
                        path: 'edit/:id',
                        component: usersEdit
                    },
                ]
            }
        ]
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})
export default router
