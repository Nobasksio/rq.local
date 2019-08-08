import Vue from 'vue'
import Multiselect from 'vue-multiselect'
import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)
Vue.component('multiselect', Multiselect)

const axios = require('axios');

new Vue({
    el: '#company',
    delimiters: ['$[', ']'],
    data() {
        return {
            app_state: window.app_state,
            all_projects: app_state.all_projects,
            company: app_state.company,
            loading:false
        }
    },
    methods:{
        saveProduct: function () {
            let data_request = JSON.stringify(this.company);

            this.loading = true;
            axios.post('/ajax/company/update',
                "company=" + data_request
            ).then(response => {
                this.company = response.data.company;

                this.$bvToast.toast('Настройки компании успешно сохранены', {
                    title: 'Компания сохранена',
                    autoHideDelay: 5000,
                    appendToast: true
                })
                this.loading = false;

            }).catch(function (error) {
                this.looading_here = false;
            });
        }
    }
});