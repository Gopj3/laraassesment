import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});

import DefaultLayout from "./layout/DefaultLayout.vue";
import router from "./router";

app.component('default-layout', DefaultLayout);

app.use(router);
app.mount('#app');
