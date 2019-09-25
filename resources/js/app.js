/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')

window.Vue = require('vue')



import { Form, HasError, AlertError } from 'vform'

window.Form = Form;
window.Vue.component(HasError.name, HasError)
window.Vue.component(AlertError.name, AlertError)


import VueRouter from 'vue-router'
import moment from 'moment'
import VueProgressBar from 'vue-progressbar'


window.Vue.use(VueRouter)

window.Vue.filter('UpText',function (text) {
    return text.charAt(0).toUpperCase() + text.slice(1)
})

window.Vue.filter('UpDate',function (text) {
    return moment(text).format('MMMM Do YYYY') // September 17th 2019, 5:16:41 pm
})

window.Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '10px'
})


import Swal from 'sweetalert2'

window.Swal = Swal


window.Fire = new Vue();

//
// const fire = Swal.fire({
//     position: 'top-end',
//     type: 'success',
//     title: 'Your work has been saved',
//     showConfirmButton: false,
//     timer: 1500
// })
//
// window.fire = fire
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
//Vue.component('dashboard', require('./components/Dashboard.vue').default);
//Vue.component('profile', require('./components/Profile.vue').default);

//const dashboard = require('./components/Dashboard.vue');



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


import dashboardCom from './components/Dashboard.vue'
import ProfileCom from './components/Profile.vue'
import UsersCom from './components/Users.vue'

// import Clients from './components/passport/Clients.vue'
// import AuthorizedClients from './components/passport/AuthorizedClients.vue'
// import PersonalAccessTokens from './components/passport/PersonalAccessTokens.vue'

import Development from './components/Development.vue'

let routes = [
    {
        path: '/dashboard',
        component: dashboardCom
    }
    ,
    {
        path: '/profile',
        component: ProfileCom
    }
    ,
    {
        path: '/users',
        component: UsersCom
    },
    // {
    //     name: 'passport-clients',
    //     path: '/dev',
    //     component: Clients
    // },
    // {
    //     name: 'passport-authorized-clients',
    //     path: '/dev',
    //     component: AuthorizedClients
    // },
    // {
    //     name: 'passport-personal-access-tokens',
    //     path: '/dev',
    //     component: PersonalAccessTokens
    // },
    {
        path: '/dev',
        component: Development
    }
]

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);


const router = new VueRouter({
    mode : 'history',
    routes // short for `routes: routes`
})

const app = new Vue({
    router
}).$mount('#app')
