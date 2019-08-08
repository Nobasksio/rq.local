import Vue from 'vue'
import Multiselect from 'vue-multiselect'
import BootstrapVue from 'bootstrap-vue'
import projectlist from './project-list'


Vue.use(BootstrapVue)
Vue.component('multiselect', Multiselect)
Vue.component('project-list', projectlist)

const axios = require('axios');


new Vue({
    el: '#list',
    delimiters: ['$[', ']'],
    data() {
        return {
            app_state: window.app_state,
            projects: app_state.projects,
            companies: app_state.companies,
            loading:false
        }
    }
});