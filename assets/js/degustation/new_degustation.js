import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import createform from './create-form.vue'
import oldCreateTable from './old-create-table'
import Multiselect from "vue-multiselect";
import helper from "./../helper.js"

Vue.use(BootstrapVue)
Vue.use(createform)
Vue.component('create-form', createform)
Vue.component('old-create-table',oldCreateTable)
Vue.component('multiselect', Multiselect)

const axios = require('axios');

function makeForRadio(data) {
    var data = $.map(data, function (obj) {
        obj.value = obj.id;
        obj.name = obj.name_work || obj.name;
        obj.text = obj.name_work || obj.name;// replace name with the property used for the text
        return obj;
    });
    return data;
}

new Vue({
    delimiters: ['$[', ']'],
    el: '#new_degustation',
    created: function () {
        this.renameFields();
    },
    data: {
        upcategories: [
            {'id': 1, 'name': 'Кухня'},
            {'id': 2, 'name': 'Бар'}
        ],
        app_state: window.app_state,
        project_id: app_state.project_id,
        categories: app_state.categories,
        degustation: app_state.degustation,
        fields:{
            index: {
                label: '№'
            },
            name: {
                label: 'Рабочее название'
            },
            consist: {
                label: 'Состав'
            },
            category: {
                label: 'Категория'
            },
            wieght: {
                label: 'Выход'
            },
            cost_price: {
                label: 'Себестоимость'
            },
            povar: {
                label: 'Повар'
            },
            delete: {
                label: ''
            },
            redact: {
                label: ''
            }
        }
    },
    methods: {
        renameFields() {
            this.categories = makeForRadio(this.categories);
            this.degustation.products = makeForRadio(this.degustation.products);
        }
    },
    watch: {},

    computed: {}
})