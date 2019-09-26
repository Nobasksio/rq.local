import Vue from 'vue'

import BootstrapVue from 'bootstrap-vue'
import printMnual from './print-manual'
Vue.component('print-manual', printMnual)

Vue.use(BootstrapVue)


new Vue({
    delimiters: ['$[', ']'],
    el: '#print_app',

    data: {
        app_state:window.app_state,
    }
})