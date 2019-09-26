<template >
    <div class="print-container " >
        <div class=" d-flex justify-content-between align-content-center pb-4" >
            <div class="d-flex align-items-center" >
                <div class="d-flex align-items-center" >
                    <span >4</span >.

                    <div class="" >
                        <label >Категория</label >
                        <div class="d-inline" >
                            <select2 class="select2 form-control"r
                                     :measures="categories"
                                     type='1'
                                     :project_id="project_id"
                                     :product="product"
                                     v-model="product.selected_category" >
                                <option disabled value="0" >выбери</option >
                            </select2 >
                            <b-spinner small v-show="visible1" type="grow" ></b-spinner >
                        </div >
                    </div >
                    /
                    <div class="" >
                        <label >Подкатегория</label >
                        <div class="d-inline" >
                            <select2 class="select2 form-control"
                                     :measures="subcategories"
                                     type='2'
                                     :project_id="project_id"
                                     :product="product"
                                     v-model="product.selected_subcategory" >
                                <option disabled value='0' >Выберите подкатегорию</option >
                            </select2 >
                            <b-spinner small v-show="visible2" type="grow" ></b-spinner >
                        </div >
                    </div >
                    <div class="" >
                        <label >Тип</label >
                        <div class="d-inline" >
                            <select2 class="select2 form-control"
                                     :measures="types_product"
                                     type="7"
                                     :product="product"
                                     v-model="product.type" >
                                <option disabled value="0" ></option >
                            </select2 >
                            <b-spinner small v-show="visible6" type="grow" ></b-spinner >
                        </div >
                    </div >
                </div >
            </div >
            <div class="float-right container_logo" >
                <img src="/img/logomanual.jpg" alt="" class='logo_manual' >
            </div >
        </div >
        <components :product="product"
                    :measures="measures"
                    :all_components="all_components"
                    :selected_component="selected_component"
                    :selected_measure="selected_measure"
                    :project_id="project_id"
                    v-on:add-component="addComponent" >

        </components >
        <div class="pb-5" >

            <h1 >Комментарий</h1 >
            <div class="font-10" >
                <textarea class="form-control" v-model="product.comment" >$[product.comment]</textarea >
            </div >
        </div >

        <label class="typo__label" >Выбери блюдо из айки</label >
        <div class="row" >
            <div class="pb-5 col-6 d-flex" >
                <div class="col-8" >
                    <multiselect v-model="iiko_product"
                                 :multiple="false"
                                 :options="iiko_products"
                                 label="name"
                                 placeholder='выбери блюдо из айки для подгрузки ттк'
                                 selectLabel='выбери'
                                 track-by="name" >
                    </multiselect >
                </div >
                <div class="col-4" >
                    <div class="btn btn-secondary mx-2" @click="getTtk" >
                        Получить ТТК
                        <b-spinner small v-show="visibleGetTTk" type="grow" ></b-spinner >
                    </div >
                </div >
            </div >
        </div >
        <div class="fixed-top" >
            <b-alert v-model="mainAlert" variant="Warning" dismissible >
               Вы внесли изменения в ттк. Не забудьте их сохранить.
            </b-alert >
        </div >
        <b-alert variant="success"
                 :show="dismissCountDown"
                 @dismissed="dismissCountDown=0"
                 dismissible
                 @dismiss-count-down="countDownChanged" >Данные успешно сохранены
        </b-alert >
        <savebutton v-on:saveproduct="saveProduct"
                    :visibleSave="visibleSave"
                    :project_id="project_id"
        >
        </savebutton >

    </div >
</template >

<script >
    function prepareComponents(data) {
        var data = $.map(data, function (obj) {
            obj.id = obj.component_id || obj.id;
            obj.text = obj.component_name || obj.name;// replace name with the property used for the text
            return obj;
        });
        return data;
    }

    export default {
        name: "ttk",
        props: {
            project_id: Number,
            ttk_num: Number,
            categories: Array,
            subcategories: Array,
            components: Array,
            product: Object,
            measures: Array,
            types_product: Array,
            selected_component: Number,
            selected_measure: Number,
            selected_category: Number,
            selected_subcategory: Number,
            all_components: Array,
            iiko_products: Array,
            iiko_product: Object,
            mainAlert:Boolean,
        },
        delimiters: ['$[', ']'],
        data: function data() {
            return {
                visible1:false,
                visible2:false,
                visible6:false,
                visibleSave: false,
                dismissSecs: 3,
                dismissCountDown: 0,
                visibleGetTTk: false
            };
        },
        methods: {
            addComponent: function addComponent() {
                this.$emit('add-component');
            },
            saveProduct: function saveProduct() {
                var _this2 = this;

                this.visibleSave = true;

                let data = JSON.stringify({
                    product: this.product
                })
                axios.post('/ajax/' + this.project_id + '/product/update_ttk',
                    "data=" + data
                ).then(function (response) {
                    _this2.visibleSave = false;
                    _this2.dismissCountDown = _this2.dismissSecs
                    _this2.$parent.mainAlert = false;
                });
            },

            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown
            },
            getTtk() {
                let self = this;
                this.visibleGetTTk = true;


                axios.get('/ajax/' + this.project_id + '/product/getiikottk', {
                    params: {
                        iiko_product: JSON.stringify(this.iiko_product)
                    }
                }).then(response => {
                    this.visibleGetTTk = false;

                    let res_data = response.data;

                    this.$parent.product.comment = res_data.technology;
                    this.$parent.product.iiko_ttk = this.iiko_product;

                    res_data.components.forEach(function(item, i, arr) {
                        self.$parent.all_components.push({
                            name: item.name,
                            text: item.name,
                            id: item.id,
                            title: ""
                        });
                    });

                    this.$parent.product.name = this.iiko_product.name;
                    this.$parent.product.technology = res_data.comment;
                    this.$parent.product.ttk_num = res_data.ttk_num;
                    this.$parent.product.components = res_data.components;
                    this.$parent.mainAlert = true;
                }).then(
                    //this.$parent.product.components = this.components

                );
            }


        },
        watch: {
            // 'product.comment': function (value) {
            //     this.$parent.mainAlert = true;
            // },
            // 'product.technology': function (value) {
            //     this.$parent.mainAlert = true;
            // },
            // 'product.ttk_num': function (value) {
            //     this.$parent.mainAlert = true;
            // },
            // 'product.name': function (value) {
            //     this.$parent.mainAlert = true;
            // }
        }
    }
</script >

<style scoped >

</style >