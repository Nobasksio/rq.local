import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import degustationsList from './degustations-list'


Vue.use(BootstrapVue)
Vue.component('degustations-list',degustationsList)


new Vue({
    delimiters: ['$[', ']'],
    el: '#degustations',
    data: {
        app_state: window.app_state,
        project_id: app_state.project_id,
        degustations: app_state.degustations,
        fields:{
            name: {
                label: 'Название'
            },
            date_str: {
                label: 'Дата и время'
            },
            place: {
                label: 'Место'
            },
            count_products: {
                label: 'Количество блюд'
            },
            status_name: {
                label: 'Статус'
            },
            link: {
                label: 'Ссылка на оценки'
            },
            delet:{
                label: ''
            },
            redact:{
                label: ''
            },
        }
    },
    methods: {

    },
    watch: {},

    computed: {}
})