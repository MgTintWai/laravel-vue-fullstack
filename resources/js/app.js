require('./bootstrap');

import Vue from 'vue'

import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';

Vue.use(ViewUI);

// importing vue-router
import router from './routers'
import store from './store'
import common from './common'
import jsonToHtml from './jsonToHtml'

Vue.mixin(common)
Vue.mixin(jsonToHtml)

import Editor from 'vue-editor-js/src/index'
Vue.use(Editor)



// importing compoment
import MainApp from './components/mainapp.vue'



const app = new Vue({
    el: '#app',
    components: {
        "main-app": MainApp
    },
    router,
    store
})
