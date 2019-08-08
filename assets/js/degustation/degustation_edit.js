import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import editeform from './edit-form.vue'
import oldCreateTable from './old-create-table-full'
import Multiselect from "vue-multiselect";
import photoblock from "./photo-block";
import helper from "./../helper.js"

Vue.use(BootstrapVue)
Vue.use(editeform)
Vue.component('edit-form', editeform)
Vue.component('old-create-table-full',oldCreateTable)
Vue.component('multiselect', Multiselect)
Vue.component('photo-block',photoblock)
const axios = require('axios');

function makeForRadio(data) {
    var data = $.map(data, function (obj) {
        obj.value = obj.id;
        obj.name = obj.name_work || obj.name;
        obj.text = obj.name_work || obj.name;
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
            photo: {
                label: 'Фото'
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
            taste: {
                label: 'Вкус'
            },
            view: {
                label: 'Вид'
            },
            concept: {
                label: 'Концепция'
            },
            comments: {
                label: 'Комментарии'
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