require('./bootstrap');

window.Vue = require('vue');
import App from './App.vue';
import VueRouter from 'vue-router';
import VueAxios from 'vue-axios';
import axios from 'axios';
Vue.use(VueAxios, axios);

import {routes} from './routes';
Vue.use(VueRouter);

import moment from 'moment'
Vue.prototype.moment = moment

// import Vue           from 'vue'
import Notifications from 'vue-notification'
Vue.use(Notifications)

// import VueQuill from 'vue-quill'
// Vue.use(VueQuill)

import VueQuillEditor from 'vue-quill-editor'
import 'quill/dist/quill.core.css' // import styles
import 'quill/dist/quill.snow.css' // for snow theme
import 'quill/dist/quill.bubble.css' // for bubble theme
Vue.use(VueQuillEditor, /* { default global options } */)

const router = new VueRouter({
    mode: 'history',
    routes: routes
});

const app = new Vue({
    el: '#app',
    router: router,
    render: h => h(App),
});
