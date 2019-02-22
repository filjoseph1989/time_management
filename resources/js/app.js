/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import {
    Form,
    HasError,
    AlertError
} from 'vform'
import VueRouter from 'vue-router'
import moment from 'moment'
import toastr from 'toastr'

Vue.use(VueRouter)
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

window.Form = Form;

toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-bottom-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

window.toastr = toastr;

/**
 * Here we define the vue routes
 * @type {Array}
 */
const routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/users', component: require('./components/Users.vue').default},
    { path: '/projects-list', component: require('./components/Projects.vue').default},
    { path: '/projects-page/:id', component: require('./components/ProjectsPage.vue').default},
    { path: '/timer', component: require('./components/Timer.vue').default},
    { path: '/client', component: require('./components/Client.vue').default},
];

const router = new VueRouter({
  routes,
  mode: 'history',
});

/**
 * Here we register a filter function
 * for changing first letter to upper case
 * @type {Vue}
 */
Vue.filter('changeToCapitalize', (string) => {
    if (string != undefined) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    return string;
});

Vue.filter('formateDate', (created) => {
    return moment(created).format('MMMM Do YYYY');
});

Vue.filter('formatTime', (date) => {
    return moment(date).format('hh:mm:ss');
});

Vue.filter('checkDescription', (description) => {
    if (description == null) {
        return "No Description";
    }

    return description;
});

Vue.filter('splitDate', (date) => {
    if (date != null) {
        let d = date.split(' ');
        return d[1];
    }

    return '';
});

/**
 * Here we instantiate vue
 * @type {Vue}
 */
const app = new Vue({
    el: '#app',
    router
});
