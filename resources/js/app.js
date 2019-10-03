/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vuex from 'vuex';
import VueRouter from 'vue-router'
import VueNativeSock from 'vue-native-websocket'

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('header-view', require('./components/header.vue').default);
Vue.component('client', require('./components/client.vue').default);

Vue.use(Vuex);
Vue.use(VueRouter);

import store from './storage/store'
Vue.use(VueNativeSock, 'ws://92.63.98.53:8082/manager', {
    store: store,
    format: 'json',
    reconnection: true,         // (Boolean) whether to reconnect automatically (false)
    reconnectionAttempts: 5,    // (Number) number of reconnection attempts before giving up (Infinity),
    reconnectionDelay: 3000,
});

import routes from './route'
const router = new VueRouter({
    routes
});

//=========================
const app = new Vue({
    router,
    store,
    el: '#app',
});
