import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import abcPage from './abc-page'


Vue.use(BootstrapVue)
Vue.component('abc-page', abcPage)

const axios = require('axios');

function makeForRadio(data) {
    var data = $.map(data, function (obj) {
        obj.value = obj.id;
        obj.name = obj.name_work || obj.name;

        if (obj.alias != null) {
            obj.name = obj.alias;
        }
        return obj;
    });
    return data;
}

new Vue({
    delimiters: ['$[', ']'],
    el: '#abc',
    data:{
       upcategories: [
           {'id': 1, 'name': 'Кухня'},
           {'id': 2, 'name': 'Бар'}
       ],
       app_state:window.app_state,
       categories: app_state.categories,
        products: app_state.products,
        product_category_type:1,
        analitic_id:app_state.analitic_id,
        selected_category:0,
        components: app_state.components,
        loading:false
   },
    mounted: function () {
        this.selected_category = this.categoryType[0].id,
            this.categories = makeForRadio(this.categories)
    },
    method:{

    },
    computed:{
        mean_price:function () {
            let summ = 0;
            this.products.forEach(function (item,index) {
                summ += item.price
            });
            return Math.round(summ/this.products.length)
        },
        val_vir:function () {
            let summ = 0;
            this.products.forEach(function (item,index) {
                summ += item.price*item.sale
            });
            return Math.round(summ)
        },
        val_ss:function () {
            let summ = 0;
            this.products.forEach(function (item,index) {
                summ += item.cost_price * item.sale
            });
            return Math.round(summ/this.val_vir*100)/100
        },
        categoryType: function (){
            let self = this


            return this.categories.filter(function (category) {
                return category.type == self.product_category_type
            })
        }
    },
    watch:{
        product_category_type:function () {
            this.selected_category = this.categoryType[0].id
        },
        selected_category:function (value) {
            if (value!=0) {
                this.loading = true;
                axios.get('/ajax/project/' + this.app_state.project_id + '/analitics/' + value,
                ).then(response => {
                    let products = response.data.products;
                    this.products = products;
                    this.loading = false;
                })
            }
        }
    }
})