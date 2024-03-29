import Vue from 'vue'
import Multiselect from 'vue-multiselect'
import BootstrapVue from 'bootstrap-vue'
import ProjectEdit from './project-edit'

Vue.use(BootstrapVue)
Vue.component('multiselect', Multiselect)
Vue.component('project-edit', ProjectEdit)

const axios = require('axios');


new Vue({
    el: '#departments',
    delimiters: ['$[', ']'],
    data() {
        return {
            choosed_departments: window.app_state.project_departments,
            app_state: window.app_state,
            categories: window.app_state.categories,
            project:app_state.project,
            chosen_category: app_state.chosen_category,
            all_user:app_state.all_users

        }
    },
    methods:{

    }
});