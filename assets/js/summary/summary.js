import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import summarytable from './summarytable'
import commentblock from './comment-block'

import photoblock from '../degustation/photo-block'


Vue.use(BootstrapVue)
Vue.component('photo-block',photoblock)
Vue.component('comment-block',commentblock)
Vue.use('summarytable')
Vue.use('commentblock')
Vue.component('summary-table', summarytable)
const axios = require('axios');

new Vue({
    delimiters: ['$[', ']'],
    el: '#summary',
    created: function () {
        //this.renameFields();
    },
    data: {
        upcategories: [
            {'id':1, 'name': 'Кухня'},
            {'id':2, 'name': 'Бар'}
        ],
        app_state: window.app_state,
        //upcategories: app_state.upcategories,
        selected_category:app_state.categories[0].id,
        categories: app_state.categories,
        product: app_state.product,
        project_id: app_state.project_id,
        products: app_state.products,
        subcategories: app_state.subcategories,
        product_category_type:1,
        loading: false
    },
    methods: {
        changeUpCategory:  function (id) {
            this.product_category_type = id;
            this.selected_category = this.categoryType[0].id;
        },
        changeCategory:  function (id) {
            this.selected_category = id;
        }
    },
    watch:{
        selected_category:function (value) {
            this.loading = true;
            axios.get('/ajax/'+this.app_state.project_id+'/category/'+value+'/all_product'

            ).then(response => {
                let products = response.data.products;
                    this.products = products;
                this.loading = false;
            })
        }
    },

    computed:{
        // product_category_type: function () {
        //     let self = this
        //     this.categories.forEach(function(category,index){
        //         console.log(category.id);
        //         if (category.id == self.selected_category){
        //             console.log('сработало');
        //             console.log(category.id);
        //             console.log(category.type);
        //             return category.type
        //         }
        //     })
        //
        // },
        categoryType: function (){
            let self = this
            return this.categories.filter(function (category) {
                return category.type == self.product_category_type
            })
        }

    }
})