import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import Multiselect from 'vue-multiselect'
import photoproduct2 from './photo-product2'

Vue.component('multiselect', Multiselect)
Vue.component('photo-product2',photoproduct2)
Vue.use(BootstrapVue)
const axios = require('axios');


function makeText(data) {
    var data = $.map(data, function (obj) {
        obj.text = obj.text || obj.name; // replace name with the property used for the text
        obj.title = '';
        if (obj.name[0] == '/' || obj.name[0] == '*') {
            obj.name = obj.name.slice(1);
        }
        return obj;
    });
    return data;
}

new Vue({
    delimiters: ['$[', ']'],
    el: '#products',
    created: function () {
        //this.renameFields();
    },
    data() {
        return {
            fields: {
                name: {
                    label: 'Продукт',
                    sortable: true
                },
                measure_name: {
                    label: 'ед. изм',
                    sortable: true
                },
                count: {
                    label: 'кол-во',
                    sortable: true
                },
            },
        app_state: window.app_state,
        product: app_state.product,
        categories:  app_state.categories,
            loading: false

    }}
    ,
    methods: {
        saveProduct:function () {
            this.loading = true
            let data_request = JSON.stringify({
                product: this.product,
                category:this.selected_category
            });

            let text = ''
            axios.post('/ajax/' + this.product.id + '/product/update',
                "data=" + data_request
            ).then(response => {



                this.$bvToast.toast('Продукт успешно сохранен', {
                    title: 'Продукт успешно создан',
                    autoHideDelay: 5000,
                    variant: 'success',
                    appendToast: true

                })
                this.loading = false

            }).catch(error => {
                this.$bvToast.toast(`При сохранении проекта возникли проблемы`, {
                    title: 'Ошибка сохранения',
                    autoHideDelay: 5000,
                    variant: 'danger',
                    appendToast: true
                })
                console.log(error);
            });
        },
        delProduct(){
            axios.delete('/ajax/'+this.app_state.project.id+'/product/delete/', {
                params: {
                    product: JSON.stringify(this.product)
                }
            }).then(response => {
                let res_data = response.data;
                this.product.status = res_data.status;

            })
        },
    },
    computed: {
        selected_category:function () {
            let self = this;
            let return_arr = this.categories.filter(function (category) {
                return category.id == self.product.selected_category;
            });
            return return_arr[0]
        }
    }
})

