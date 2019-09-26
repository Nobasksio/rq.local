import Vue from 'vue'
import Multiselect from 'vue-multiselect'
import BootstrapVue from 'bootstrap-vue'
import select2 from './select2.vue'
import ttk from './ttk.vue'
import components from './components'
import savebutton from './savebutton'
import multiselect2 from './multiselect2'



Vue.use(BootstrapVue)
Vue.use(select2)
Vue.use(ttk)
Vue.use(components)
Vue.use(savebutton)
Vue.use(multiselect2)
Vue.component('multiselect', Multiselect)
Vue.component('ttk', ttk)
Vue.component('select2', select2)
Vue.component('components', components)
Vue.component('savebutton', savebutton)
Vue.component('multiselect2', multiselect2)

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

function checktype(data) {
    if (data.type == '') {
        data.type = "1";
    }
    return data;
}

function makeValue(data) {
    var data = $.map(data, function (obj) {
        obj.value = obj.id;
        obj.text = obj.name; // replace name with the property used for the text

        return obj;products
    });
    return data;
}

function makeForRadio(data) {
    var data = $.map(data, function (obj) {
        obj.value = obj.id;
        obj.text = obj.name_work || obj.name;// replace name with the property used for the text
        return obj;
    });
    return data;
}

function clearName(iiko_product) {
    var data = $.map(iiko_product, function (obj) {
        if (obj.name[0] == '/' || obj.name[0] == '*') {
            obj.name = obj.name.slice(1);
        }
        return obj;
    });
    return data;
}


new Vue({
    delimiters: ['$[', ']'],
    el: '#ttk',
    created: function () {
        this.renameFields();
    },
    data: {
        app_state:window.app_state,
        visible: false,
        visibleC: false,
        isActive: false,
        project_id: app_state.project_id,
        doResponseProduct: true,
        types_product: app_state.types_product,
        categories: app_state.categories,
        products: app_state.products,
        subcategories: app_state.subcategories,
        measures: app_state.measures,
        all_components: app_state.components,
        selected_component: {
            id:null,
            name:''
        },
        selected_measure: 3,
        iiko_products:app_state.iiko_products,
        selected_subcategory:0,
        mainAlert:false,
        save: 0,
        product: {
            id: app_state.first_product.id,
            type: app_state.first_product.type,
            selected_category: app_state.first_product.selected_category,
            selected_subcategory: app_state.first_product.selected_subcategory,
            name: app_state.first_product.ttk_name,
            ttk_num: app_state.first_product.ttk_num,
            comment: app_state.first_product.comment,
            technology: app_state.first_product.technology,
            components: app_state.first_product.components,
            iiko_ttk:app_state.first_product.iiko_ttk,
            photo: app_state.first_product.photo
        }
    },
    methods: {
        renameFields() {

            this.types_product = makeText(this.types_product);
            this.iiko_products = makeText(this.iiko_products);
            this.product = checktype(this.product);
            this.measures = makeText(this.measures);
            this.all_components = makeText(this.all_components);
            this.categories = makeText(this.categories);
            this.subcategories = makeText(this.subcategories);
            this.categories = makeForRadio(this.categories);
            this.products = makeForRadio(this.products);
        },
        addComponent() {
            this.mainAlert = true;
            this.product.components.push({name: 'new', measure: 2, count: 11})
        },
        changeCategory(category_id) {
            this.isActive = true;
            this.visibleC = true;
            this.selected_category = category_id;
            axios.get('/ajax/'+this.app_state.project_id+'/manual/category/' + this.selected_category).then(response => {

                let res_data = response.data;

                if (res_data.id != 0) {
                    this.product =
                        {
                            id: res_data.product.id,
                            type: res_data.product.type,
                            selected_category: res_data.product.selected_category,
                            selected_subcategory: res_data.product.selected_subcategory,
                            name: res_data.product.name,
                            ttk_num: res_data.product.ttk_num,
                            comment: res_data.product.comment,
                            technology: res_data.product.technology,
                            components: res_data.product.components,
                            iiko_ttk:res_data.product.iiko_ttk,
                            photo: res_data.product.photo
                        };
                } else {
                    this.product =
                        {
                            id: res_data.id,
                            type: '1',
                            selected_category: this.selected_category,
                            selected_subcategory: '0',
                            name: '',
                            ttk_num: '',
                            comment: '',
                            technology: '',
                            components: [],
                            iiko_ttk:[],
                            photo: null
                        };
                }


                this.products = res_data.product.products;
                this.products = makeValue(this.products);
                this.all_components = res_data.components;
                this.all_components =  makeText(this.all_components);
                this.doResponseProduct = false;
                this.product = checktype(this.product);
                this.selected_product = res_data.id;
                this.isActive = false;
                this.visibleC = false;
                this.mainAlert = false;
            })
        },
        changeProduct(product_id) {

            if (this.doResponseProduct) {
                this.isActive = true;
                this.visible = true;
                this.selected_product = product_id;
                axios.get('/ajax/'+this.app_state.project_id+'/manual/product/' + this.selected_product)
                    .then(response => {
                        let res_data = response.data;


                        this.product =
                            {
                                id: res_data.product.id,
                                type: res_data.product.type,
                                selected_category: res_data.product.selected_category,
                                selected_subcategory: res_data.product.selected_subcategory,
                                name: res_data.product.name,
                                ttk_num: res_data.product.ttk_num,
                                comment: res_data.product.comment,
                                technology: res_data.product.technology,
                                components: res_data.product.components,
                                iiko_ttk:res_data.product.iiko_ttk,
                                photo: res_data.product.photo,
                            };
                        this.all_components =  makeText(res_data.components);

                        this.isActive = false;
                        this.visible = false;
                    })
            } else {
                this.doResponseProduct = true;
            }
            this.mainAlert = false;
        },
        createNewProduct() {
            axios.get('/ajax/'+this.app_state.project_id+'/product/add', {
                    params: {
                        status: 3,
                        category: this.selected_category
                    }
                }
            ).then(response => {
                let res_data = response.data;
                this.product =
                    {
                        id: res_data.id,
                        type: "1",
                        selected_category: this.selected_category,
                        selected_subcategory: '0',
                        name: 'Новое блюдо',
                        ttk_num: '',
                        comment: '',
                        technology: '',
                        components: [],
                        iiko_ttk:[],
                        photo: null,
                    };
                this.doResponseProduct = false;
                this.product = checktype(this.product);
                this.selected_product = res_data.id;
                this.products.push({text: 'Новое блюдо', value: res_data.id, id: res_data.id})
            })
        }
    }
})