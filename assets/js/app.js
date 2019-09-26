/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');



import FileUpload from 'v-file-upload';
import Vuetify from 'vuetify';
import UploadButton from 'vuetify-upload-button';

Vue.use(FileUpload);

export default {
    components: {
        'upload-btn': UploadButton
    }
}
// const axios = require('axios');


Vue.component('select2', {
    props: ['measures', 'value','type','product'],
    template: '#select2-template',
    mounted: function () {
        var vm = this
        $(this.$el)
        // init select2
            .select2({ data: this.measures })
            .val(this.value)
            .trigger('change')
            // emit event on change.
            .on('change', function () {
                vm.$emit('input', this.value);
                $('.select2-selection__rendered').removeAttr('title');
            })


    },
    watch: {
        value: function (value) {
            if (value!='' & typeof value != "undefined") {
                // alert('step1 value='+value+"*");
                var isComponent = false;
                for (i = 0; i < this.measures.length; ++i) {
                    if (this.measures[i].id == value & value!='') {
                        // alert('step1.1');
                        isComponent = true;
                        break;
                    }
                    if (this.measures[i].text == value & value!='') {
                        // alert('step1.2');
                        isComponent = true;
                        break;
                    }
                }
                if (!isComponent) {
                    // alert('step2');
                    let name_entity ;
                    if (this.type == 1) {
                        name_entity = 'category';
                        this.$parent.visible1 = true;

                    } else if (this.type == 2) {
                        name_entity = 'subcategory';
                        this.$parent.visible2 = true;

                    }else if (this.type == 3) {
                        name_entity = 'component';
                        this.$parent.visible3 = true;
                    }else if (this.type == 4) {
                        name_entity = 'measure';
                        this.$parent.visible4 = true;
                    }
                    axios.get('/ajax/{{ project.id }}/'+name_entity+'/create', {
                        params: {
                            name: value,
                            product_id:this.product.id
                        }
                    })
                        .then(response => {
                            this.measures.push({
                                name: this.value,
                                text: this.value,
                                id: response.data
                            });


                            if (this.type == 1) {
                                this.$parent.$parent.$parent.product.selected_category = response.data
                                this.$parent.visible1 = false;
                            } else if (this.type == 2) {
                                // this.$parent.components = this.measures,
                                this.$parent.$parent.$parent.product.selected_subcategory = response.data
                                this.$parent.visible2 = false;
                            }else if (this.type == 3) {
                                // this.$parent.components = this.measures,
                                this.$parent.$parent.$parent.selected_component = response.data
                                this.$parent.visible3 = false;
                            }else if (this.type == 4) {
                                this.$parent.$parent.$parent.selected_measure = response.data
                                this.$parent.visible4 = false;
                            }
                            $(this.$el).empty().select2({ data: this.measures,tags: true })

                        });
                }
                else{
                    if (this.type == 1) {
                        this.$parent.$parent.$parent.product.selected_category = value

                    } else if (this.type == 2) {
                        // this.$parent.components = this.measures,
                        this.$parent.$parent.$parent.product.selected_subcategory = value

                    }else if (this.type == 3) {
                        // this.$parent.components = this.measures,
                        this.$parent.$parent.$parent.selected_component = value
                    }else if (this.type == 4) {
                        this.$parent.$parent.$parent.selected_measure = value
                    }
                }

                // update value
                $(this.$el)
                    .val(value)
                    .trigger('change')
            }
        },
        options: function (measures) {
            // update options

            $(this.$el).empty().select2({ data: measures })
        }
    },
    destroyed: function () {
        $(this.$el).off().select2('destroy')
    }
});
Vue.component('my-ttk', {
    props: {
        ttk_num: Number,
        name: String,
        categories: Array,
        subcategories: Array,
        components: Array,
        product:Object,
        measures:Array,
        selected_component:Number,
        selected_measure:Number,
        selected_category:Number,
        selected_subcategory:Number,
        all_components:Array,
    },
    template: '#ttk-template',
    delimiters: ['$[', ']'],
    data() {
        return {
            visibleSave:false,
        }
    },
    methods:{
        addComponent() {
            this.$emit('add-component')
        },
        saveProduct() {
            this.visibleSave = true;
            axios.get('/ajax/{{ project.id }}/product/update', {
                params: {
                    product: JSON.stringify(this.product)
                }
            }).then(response => {
                this.visibleSave = false;
            });
        }
    }
});


Vue.component('my-components',{
    template:'#components-template' ,
    props: {
        product:Object,
        measures:Array,
        selected_measure:Number,
        selected_component:Number,
        all_components:Array,
    },
    delimiters: ['$[', ']'],
    data() {
        return {
            visible3:false,
            visible4:false,
            measure_count:null
        }
    },
    methods:{
        addComponent() {

            this.$parent.$parent.product.components.push({
                component_name:this.nameSelectedComponent.text,
                component_id:this.selected_component,
                measure:this.selected_measure,
                measure_name:this.nameSelectedMeasure.text,
                count:this.measure_count
            });
            this.measure_count = null;
        },
        removeComponent(index){
            console.log(index);
            this.$parent.$parent.product.components.splice(index,1);
        }
    },
    computed: {
        nameSelectedMeasure(){
            var search_measure = this.measures.filter(measure => measure.id==this.selected_measure);

            if (!search_measure){
                search_measure = this.measures.filter(measure => measure.name == this.selected_measure);

            }
            return search_measure[0];
        },
        nameSelectedComponent(){
            var search_measure = this.all_components.filter(component => component.id == this.selected_component);

            if (!search_measure){
                search_measure = this.all_components.filter(component => component.name == this.selected_component);

            }

            return search_measure[0];
        }
    }
});

Vue.component('save-button', {
    props:['visibleSave'],
    template: '#save-button',
    delimiters: ['$[', ']'],
    methods:{
        saveProduct() {
            this.$emit('saveproduct');
        }
    }

});

Vue.component('my-category', {
    template: '#category-template',
    props: {
        categories: Array,
        selected_category:Number,
        product:Object
    },
    delimiters: ['$[', ']'],
    data() {
        return {
            visible1:false,
        }
    },

});

Vue.component('my-subcategory', {
    template: '#subcategory-template',
    props: {
        subcategories: Array,
        selected_subcategory:Number,
        product:Object
    },
    delimiters: ['$[', ']'],
    data() {
        return {
            visible2:false,
        }
    },
});


$(document).ready(function () {
    $('.select2').select2({
        tags: true
    });
});
var element = document.getElementById('element-to-print');
var opt = {
    margin: 0.5,
    filename: 'myfile.pdf',
    image: {type: 'jpeg', quality: 1},
    pagebreak: {
        mode: ['avoid-all', 'css'],

    },
    html2canvas: {scale: 2},
    jsPDF: {unit: 'in', format: 'a4', putOnlyUsedFonts: 'true', orientation: 'portrait'}
};


function add_product_manual(category, project) {
    $.ajax({
        url: '/ajax/' + project + '/product/add',
        type: "GET",
        data: {
            "status": 1,
            "category": category,
            "project": project
        },
        success: function (data) {
            $("#ok_done2").show();
            $("#add_buut").attr('onclick', '');
            $("#add_buut").removeClass('buton_reg2');
            $("#add_buut").addClass('buton_grey');

        }
    })
}
function  showproduct(project_id,product_id) {
    manual.isActive = true;
    manual.visible = true;
    axios.get('/ajax/'+project_id+'/manual/product/'+product_id, {

    }).then(response=>{
        console.log(response.data);
        let res_data = response.data;

        console.log(res_data.id);
        manual.product =
            {
                id:res_data.id,
                selected_category:res_data.selected_category,
                selected_subcategory:res_data.selected_subcategory,
                name: res_data.name,
                ttk_num: res_data.ttk_num,
                comment:res_data.comment,
                technology:res_data.technology,
                components: res_data.components,
            };
        manual.isActive = false;
        manual.visible = false;
    })
}
