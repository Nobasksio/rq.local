import Vue from 'vue'
import Multiselect from 'vue-multiselect'
import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)
Vue.component('multiselect', Multiselect)

const axios = require('axios');


new Vue({
    el: '#departments',
    delimiters: ['$[', ']'],
    data() {
        return {
            value: window.app_state.project_departments,
            app_state: window.app_state,
            categories: window.app_state.categories,
            category_chosed:[],

        }
    },
    methods:{
        saveProduct: function () {
            axios.get('/project/ajax/'+this.app_state.project_id+'/update/', {
                    params: {
                        departments: JSON.stringify(this.value),
                    }
                }
            ).then(response => {


            })
        }
    }
});