<template >
    <div class=' blc_table_new_deg ' >
        <b-modal id="modal-1" v-model="modalShow" title="Удаление блюда" @ok="deletProduct">
            <p class="my-4">Вы уверены что хотите удалить это блюдо? </p>
        </b-modal>
        <table class="table table-all" >
            <thead class="thead-light" >
            <tr >
                <th v-for="field in fields" scope="col" >
                    {{ field.label }}
                </th >
            </tr >
            </thead >
            <tr v-for="(product,index) in degustation.products" >
                <td class="align-middle" > {{ index+1}}</td >
                <td class="align-middle">
                    <b-form v-if="product.edit==true" >
                        <b-form-input id="input-small"
                                      size="sm"
                                      placeholder="название блюда"
                                      :state="validationName"
                                      v-model="product.name" ></b-form-input >
                        <b-form-invalid-feedback :state="validationName" >
                            Блюд без названия не бывает
                        </b-form-invalid-feedback >
                        <b-form-valid-feedback :state="validationName" >
                            Отличная работа!
                        </b-form-valid-feedback >
                    </b-form >
                    <span v-else >{{ product.name }}</span >
                </td >
                <td class="align-middle">
                    <photo-block
                    :product="product"
                    ></photo-block>
                </td>
                <td class="align-middle">
                    <b-form v-if="product.edit==true" >
                        <b-form-input id="input-small"
                                      size="sm" placeholder="состав"
                                      :state="validationConsist"
                                      v-model="product.consist" ></b-form-input >
                        <b-form-invalid-feedback :state="validationConsist" >
                            У блюда нет состава?
                        </b-form-invalid-feedback >
                        <b-form-valid-feedback :state="validationConsist" >
                            Отличная работа!
                        </b-form-valid-feedback >
                    </b-form >
                    <span v-else >{{ product.consist }}</span >
                </td >
                <td class="align-middle">
                    <b-form v-if="product.edit==true">
                        <b-form-select v-model="selected_category_edit"
                                       :options="categories"
                                       size="sm"
                                       :state="validationCategory" ></b-form-select >
                        <b-form-invalid-feedback :state="validationCategory" >
                            Нужно выбрать категорию
                        </b-form-invalid-feedback >
                        <b-form-valid-feedback :state="validationCategory" >
                            Отличная работа!
                        </b-form-valid-feedback >
                    </b-form >
                    <span v-else >
                    {{ product.category.name }}
                    </span>
                </td >
                <td class="align-middle">
                    <b-form-input v-if="product.edit==true" id="input-small" size="sm" placeholder="выход" v-model="product.weight" ></b-form-input >
                    <span v-else > {{ product.weight }}</span>
                </td >
                <td class="align-middle">
                    <b-form-input v-if="product.edit==true" id="input-small" size="sm" placeholder="себес" v-model="product.cost_price" ></b-form-input >
                    <span v-else >{{ product.cost_price }}</span>
                </td >
                <td class="align-middle">
                    <b-form-input v-if="product.edit==true" id="input-small" size="sm" placeholder="Имя повара" v-model="product.povar" ></b-form-input >
                    <span v-else >{{ product.povar }}</span>
                </td >
                <td :class="{'table-success': product.scores.taste.average>=4}" class="align-middle">
                    <b-popover
                            :target="`popover-taste-${product.id}`"
                            title="Оценки за вкус, Милорд!"
                            placement="top"
                            triggers="hover focus"
                            :content="` ${product.scores.taste.all}`"
                    ></b-popover>
                    <div :id="`popover-taste-${product.id}`" class="text-center">
                    {{ product.scores.taste.average }}
                    </div>
                </td >
                <td :class="{'table-success': product.scores.view.average>=4}" class="align-middle">
                    <div :id="`popover-view-${product.id}`" class="text-center">
                        {{ product.scores.view.average }}
                    </div>
                    <b-popover
                            :target="`popover-view-${product.id}`"
                            placement="top"
                            title="Оценки за вид, Мой повелитель!"
                            triggers="hover focus"
                            :content="` ${product.scores.view.all}`"
                    ></b-popover>
                </td >
                <td :class="{'table-success': product.scores.concept.average>=4}" class="align-middle">
                    <div :id="`popover-concept-${product.id}`" class="text-center">
                    {{ product.scores.concept.average }}
                    </div>
                    <b-popover
                            :target="`popover-concept-${product.id}`"
                            placement="top"
                            title="Оценки за концепцию, Ваше сиятельство!"
                            triggers="hover focus"
                            :content="` ${product.scores.concept.all}`"
                    ></b-popover>
                </td >
                <td class="align-middle"> {{ product.scores.comment }}</td >
                <td class='icon_wrapper align-middle' @click="edit(index)" >
                    <svg id="i-checkmark" v-if="product.edit===true" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 32 32" width="20" height="20" fill="none" stroke="currentcolor"
                         stroke-linecap="round" stroke-linejoin="round" stroke-width="2" >
                        <path d="M2 20 L12 28 30 4" />
                    </svg >
                    <svg id="i-edit" v-else class='icon' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"
                         width="20"
                         height="20" fill="none" stroke="currentcolor" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" >
                        <path d="M30 7 L25 2 5 22 3 29 10 27 Z M21 6 L26 11 Z M5 22 L10 27 Z" />
                    </svg >
                </td >

                <td class='icon_wrapper align-middle' @click="wantDeleteProduct(index)" >
                    <svg id="i-trash" class='icon' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="20"
                         height="20" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round"
                         stroke-width="2" >
                        <path d="M28 6 L6 6 8 30 24 30 26 6 4 6 M16 12 L16 24 M21 12 L20 24 M11 12 L12 24 M12 6 L13 2 19 2 20 6" />
                    </svg >
                </td >
            </tr >
            <tr >
                <td ></td >
                <td >
                    <b-form >
                        <b-form-input id="input-small"
                                      size="sm"
                                      placeholder="название блюда"
                                      :state="validationName"
                                      v-model="name" ></b-form-input >
                        <b-form-invalid-feedback :state="validationName" >
                            Блюд без названия не бывает
                        </b-form-invalid-feedback >
                        <b-form-valid-feedback :state="validationName" >
                            Отличная работа!
                        </b-form-valid-feedback >
                    </b-form >
                </td >
                <td ></td >
                <td >
                    <b-form >
                        <b-form-input id="input-small"
                                      size="sm" placeholder="состав"
                                      :state="validationConsist"
                                      v-model="consist" ></b-form-input >
                        <b-form-invalid-feedback :state="validationConsist" >
                            У блюда нет состава?
                        </b-form-invalid-feedback >
                        <b-form-valid-feedback :state="validationConsist" >
                            Отличная работа!
                        </b-form-valid-feedback >
                    </b-form >
                </td >
                <td >
                    <b-form >
                        <b-form-select v-model="selected_category"
                                       :options="categories"
                                       size="sm"
                                       :state="validationCategory" ></b-form-select >
                        <b-form-invalid-feedback :state="validationCategory" >
                            Нужно выбрать категорию
                        </b-form-invalid-feedback >
                        <b-form-valid-feedback :state="validationCategory" >
                            Отличная работа!
                        </b-form-valid-feedback >
                    </b-form >
                    <!--<b-form-input id="input-small" size="sm" placeholder="" v-model="category"></b-form-input >-->
                </td >
                <td >
                    <b-form-input id="input-small" size="sm" placeholder="выход" v-model="weight" ></b-form-input >
                </td >
                <td >
                    <b-form-input id="input-small" size="sm" placeholder="себес" v-model="cost_price" ></b-form-input >
                </td >
                <td >
                    <b-form-input id="input-small" size="sm" placeholder="Имя повара" v-model="povar" ></b-form-input >
                </td >
            </tr >
        </table >
        <b-alert v-model="teribleAlert" variant="danger" dismissible >
            Что-то пошло не так. Попробуйте ещё раз или зовите на помощь Артура.
        </b-alert >
        <b-alert v-model="amazingAlert" variant="success" dismissible >
           Все шик. дегустация успешно сохранена
        </b-alert >
        <!--<b-table striped hover-->
        <!--:items="products"-->
        <!--foot-clone="true"-->
        <!--:fields="fields" >-->
        <!--<template v-for="field in fields"-->
        <!--:slot="field.key" slot-scope="data">-->
        <!--<b-form-input class="border-0 no-shadow p-1" type="number" v-model="data.value"-->
        <!--&gt;</b-form-input>-->
        <!--</template>-->
        <!--<template slot="FOOT_name" slot-scope="data" >-->
        <!--<b-form-input id="input-small" size="sm" placeholder="название блюда" ></b-form-input >-->
        <!--</template >-->
        <!--<template slot="FOOT_consist" slot-scope="data" >-->
        <!--<b-form-input id="input-small" size="sm" placeholder="состав" ></b-form-input >-->
        <!--</template >-->
        <!--<template slot="FOOT_category" slot-scope="data" >-->
        <!--<b-form-input id="input-small" size="sm" placeholder="" ></b-form-input >-->
        <!--</template >-->
        <!--<template slot="FOOT_wieght" slot-scope="data" >-->
        <!--<b-form-input id="input-small" size="sm" placeholder="выход" ></b-form-input >-->
        <!--</template >-->
        <!--<template slot="FOOT_cost_price" slot-scope="data" >-->
        <!--<b-form-input id="input-small" size="sm" placeholder="себес" ></b-form-input >-->
        <!--</template >-->
        <!--<template slot="FOOT_povar" slot-scope="data" >-->
        <!--<b-form-input id="input-small" size="sm" placeholder="Имя повара" ></b-form-input >-->
        <!--</template >-->

        <!--</b-table >-->
        <b-button variant="outline-primary" size="sm" @click="addProduct" >Добавить блюдо
            <b-spinner type="grow" small v-show="loading" label="Loading..." ></b-spinner >
        </b-button >

        <b-row >
            <b-col >
                <b-button class='my-3' variant="primary" @click="createDegustation" >
                    Сохранить изменения
                    <b-spinner type="grow" small v-show="loading" label="Loading..." ></b-spinner >
                </b-button >
            </b-col >
        </b-row >
    </div >
</template >

<script >
    export default {
        name: "old-create-table",
        props: {
            categories: Array,
            fields: Object,
            degustation: Object,
            project_id: Number
        },
        data: function () {
            return {
                name: null,
                consist: null,
                weight: null,
                cost_price: null,
                povar: null,
                selected_category: null,
                selected_category_edit: null,
                validationCategory: null,
                validationName: null,
                validationConsist: null,
                loading: false,
                teribleAlert: false,
                modalShow:false,
                index_delet:0,
                amazingAlert: false
            }
        },
        methods: {
            edit: function (index) {
                if (this.degustation.products[index].edit == false) {
                    this.selected_category_edit = this.degustation.products[index].category.id
                    this.degustation.products[index].edit = true
                } else {
                    this.degustation.products[index].category.id = this.selected_category_edit
                    this.degustation.products[index].category.name = this.name_edit_category
                    this.degustation.products[index].edit = false
                }
                console.log(this.degustation.products[index].edit);
            },
            createDegustation: function () {
                let degust_request = JSON.stringify(this.degustation);
                this.loading = true;
                axios.post('/ajax/' + this.project_id + '/degustation/create',
                        "degustation="+degust_request

                  ).then(response => {
                      let test1 = response.data.products.length,
                          test2 = this.degustation.products.length
                         console.log(test1)
                        console.log(test2)
                      if (response.data.products.length == this.degustation.products.length) {

                      }
                    this.degustation.products = response.data.products
                    this.amazingAlert = true;
                    this.loading = false;

                });
            },
            addProduct: function () {

                if (this.isValid) {
                    this.degustation.products.push({
                        id: null,
                        name: this.name,
                        consist: this.consist,
                        category: {
                            id: this.selected_category,
                            name: this.name_selected_category
                        },
                        scores:{
                            taste:[],
                            view:[],
                            concept:[],
                            price:[],
                            comment:[]
                        },
                        weight: this.weight,
                        edit: false,
                        cost_price: this.cost_price,
                        povar: this.povar
                    });

                    this.uploadData();

                } else {
                    this.checkValid();

                }
            },
            wantDeleteProduct: function(my_index){
                this.index_delet = my_index,
                    this.modalShow = true
            },
            deletProduct: function(){
                this.degustation.products.splice(this.index_delet,1);
            },
            uploadData: function () {
                let degust_request = JSON.stringify(this.degustation);
                this.loading = true;
                axios.post('/ajax/' + this.project_id + '/degustation/update',
                    "degustation=" + degust_request
                ).then(response => {
                    this.degustation.products = response.data.products;
                    this.degustation.id = response.data.degustation.id;
                    this.loading = false;
                    this.resetValid();
                    this.resetNewProduct();
                    this.teribleAlert = false;

                }).catch(error => {
                    this.teribleAlert = true;
                    this.loading = false;
                    console.log(error);
                });
            },
            resetValid: function () {
                this.validationName = null;
                this.validationConsist = null;
                this.validationCategory = null;
            },
            resetNewProduct: function () {
                this.name = null,
                    this.consist = null,
                    this.weight = null,
                    this.cost_price = null,
                    this.povar = null;
            },
            checkValid: function () {
                if (this.name != null) {
                    this.validationName = true;
                } else {
                    this.validationName = false;
                }

                if (this.consist != null) {
                    this.validationConsist = true;
                } else {
                    this.validationConsist = false;
                }
                if (this.selected_category != null) {
                    this.validationCategory = true;
                } else {
                    this.validationCategory = false;
                }
            }
        },
        computed: {
            name_selected_category: function () {
                let self = this;
                let category = this.categories.filter(function (category_item) {
                    return category_item.id == self.selected_category;
                });
                return category[0].name
            },
            name_edit_category: function () {
                let self = this;
                let category = this.categories.filter(function (category_item) {
                    return category_item.id == self.selected_category_edit;
                });
                if (category[0]){
                    return category[0].name
                } else {
                    return ''
                }

            },
            isValid: function () {
                if (this.consist != null & this.name != null & this.selected_category != null) {
                    return true;
                } else {
                    return false;
                }
            }
        },
        watch: {
            name: function (name) {
                if (this.validationName == false) {
                    if (name != null) {
                        this.validationName = true
                    }
                }
            },
            selected_category: function (selected_category) {
                if (this.validationCategory == false) {
                    if (name != null) {
                        this.validationCategory = true
                    }
                }
            },
            consist: function (consist) {
                if (this.validationConsist == false) {
                    if (name != null) {
                        this.validationConsist = true
                    }
                }
            }
        }
    }
</script >

<style scoped >
    .icon_wrapper {
        cursor: pointer;
    }

    td.icon_wrapper:hover .icon {
        fill: #ff0c00;
    }

    .icon {
        width: 20px;
        height: 20px;
    }

    .icon-account-login {
        fill: #f00;
    }
</style >