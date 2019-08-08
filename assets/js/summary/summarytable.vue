<template >
    <div id='type_blc' :class="{load_blk: loading}" >
        <div class='new_str ' >
            <div class='new_str' id='table' >
                <table class='table_order' border="0" cellpadding="0" cellspacing="0" >
                    <tr class='th_order_table' >
                        <td class='' ></td >
                        <td class='tdz_ord_table' >Рабочее<br > название</td >
                        <td class='tdz_ord_table' >Название<br > для меню</td >
                        <td class='tdz_ord_table' >Фото</td >
                        <td class='tdz_ord_table' >Состав</td >
                        <td class='tdz_ord_table' >Описание<br > для меню</td >
                        <td class='tdz_ord_table' >Выход</td >
                        <td class='tdz_ord_table' >Себестоимость</td >
                        <td class='tdz_ord_table' >Старая цена</td >
                        <td class='tdz_ord_table' >Новая цена</td >
                        <td class='tdz_ord_table' >Комментарии</td >
                        <td class='tdz_ord_table' ></td >
                    </tr >
                    <tr class='tr_order2' v-for="(product_item, index) in products" >
                        <td class='td_ord_table left' >{{ index+1 }}</td >
                        <td class='td_ord_table left ' >{{ product_item.name_work }}</td >
                        <td class='td_ord_table left ' >{{ product_item.name_menu }}</td >
                        <td class='td_ord_table left ' ><photo-block
                                :product="product_item"
                        ></photo-block>
                        </td >
                        <td class='td_ord_table left ' >{{ product_item.consist }}</td >
                        <td class='td_ord_table left ' >{{ product_item.discriprion_menu }}</td >
                        <td class='td_ord_table left ' >{{ product_item.ves }}</td >
                        <td class='td_ord_table left ' >{{ product_item.cost_price }}</td >
                        <td class='td_ord_table left ' >{{ product_item.old_price }}</td >
                        <td class='td_ord_table left ' >{{ product_item.new_price }}</td >
                        <td class='td_ord_table left w120' >
                            <comment-block
                                    :product="product_item"
                                    place="9"
                            ></comment-block>

                        </td >
                        <td class='td_ord_table left ' ><a
                                :href="'/project/'+project_id+'/product/'+product_item.id+'/edit'"
                                class="redact" >ред.</a >

                        </td >
                    </tr >
                    <!--<tr class='tr_order2' id='tr$id_tov'>-->
                    <!--<td class='td_ord_table left' id='num$id_tov'>$i</td>-->
                    <!--<td class='td_ord_table left ' id='name_tr$id_tov'>$name_work</td>-->
                    <!--<td class='td_ord_table left ' id='name_menu_tr$id_tov'>$name_menu </td>-->
                    <!--<td class='td_ord_table_medium'  id='img_tr$id_tov'>$prewfoto <div class='add_foto' onclick='foto_on($id_tov)'>+ фото</div></td>-->
                    <!--<td class='td_ord_table_medium left' id='sostav_tr$id_tov'>$consist</td>-->
                    <!--<td class='td_ord_table_medium left' id='discription_menu_tr$id_tov'>$discription_menu</td>-->
                    <!--<td class='td_ord_table $style_text' id='ves_tr$id_tov'>$ves</td>-->
                    <!--<td class='td_ord_table $style_text' id='ss_tr$id_tov'>$new_ss</td>-->
                    <!--<td class='td_ord_table $style_text' id='ss_tr$id_tov'>$old_price</td>-->
                    <!--<td class='td_ord_table $style_text' id='price_tr$id_tov'>$new_price</td>-->
                    <!--<td class='td_ord_table_medium' id='comment_td$id_tov' $style_text'>$coment_all</td>-->
                    <!--</tr>-->
                    <tr class='tr_order2' id='end_tr' >
                        <td class='td_ord_table' id='num0' ></td >
                        <td class='td_ord_table left' >
                            <b-form-input v-model="new_product.name"
                                          placeholder=""
                                          id="name_new"
                                          :state="validationName"
                            ></b-form-input>
                            <b-form-invalid-feedback id="name_new-feedback">
                               Не может быть блюда без имени
                            </b-form-invalid-feedback>
                        </td >
                        <td class='td_ord_discr left' ></td >
                        <td class='td_ord_table' ></td >
                        <td class='td_ord_table' >
                            <b-form-textarea
                                    id="textarea"
                                    v-model="new_product.consist"
                                    placeholder=""
                                    rows="2"
                                    max-rows="6"
                            ></b-form-textarea>
                        </td >
                        <td class='td_ord_table' ></td >
                        <td class='td_ord_table' >
                            <b-form-input v-model="new_product.weight"
                                          placeholder=""></b-form-input>
                        </td >
                        <td class='td_ord_table' >
                            <b-form-input v-model="new_product.cost_price" placeholder=""></b-form-input>
                        </td >
                        <td class='td_ord_table' >
                            <b-form-input v-model="new_product.old_price" placeholder=""></b-form-input>
                        </td >
                        <td class='td_ord_table' ></td >
                        <td class='td_ord_table' ></td >

                    </tr >

                    <tr class='tr_order3' >
                        <td class='td_ord_table' ></td >
                        <td class='td_ord_table' colspan="10" >
                            <b-button variant="primary float-left" @click="addNewProduct()" >
                                Добавить блюдо
                                <b-spinner small v-show="loading_add" type="grow" ></b-spinner >
                            </b-button >

                        </td >

                    </tr >
                </table >

            </div >
        </div >
    </div >
</template >

<script >
    export default {
        name: "summary-table",
        props: {
            products: Array,
            project_id: Number,
            selected_category: Number,
            loading: Boolean,
            product_category_type: Number
        },
        data: function () {
            return {
                new_product: {
                    name: null,
                    consist: null,
                    weight: null,
                    cost_price: null,
                    old_price: null,
                    status: 2,
                    category_id: this.selected_category,
                    type: this.product_category_type
                },
                loading_add:false,
                validationName: null,
            };
        },
        methods: {
            checkValid: function () {
                if (this.name != null) {
                    this.validationName = true;
                } else {
                    this.validationName = false;
                }
            },
            resetValid: function () {
                this.validationName = null;
            },
            resetProduct: function(){
                this.new_product = {
                    name: null,
                        consist: null,
                        weight: null,
                        cost_price: null,
                        old_price: null,
                        status: 2,
                        category_id: this.selected_category,
                        type: this.product_category_type
                }
            },
            addNewProduct() {
                if (this.isValid) {
                    this.resetValid();
                    let data_request = JSON.stringify({new_product: this.new_product});
                    this.loading_add = true;
                    axios.post('/ajax/' + this.project_id + '/product/add2',
                        "data=" + data_request
                    ).then(response => {

                        this.products.push({
                            comment: null,
                            consist: this.new_product.consist,
                            cost_price: this.new_product.cost_price,
                            id: response.data.id,
                            name: this.new_product.name,
                            selected_category: this.new_product.category_id,
                            ves: this.new_product.weight,
                            old_price: this.new_product.old_price
                        });

                        this.$bvToast.toast('Блюдо ' + this.new_product.name + ' успешно добавлено! ', {
                            title: 'Блюдо добавлено',
                            autoHideDelay: 5000,
                            appendToast: true,
                            variant:"success"
                        });
                        this.loading_add = false;
                        this.resetProduct();

                    }).catch(error => {
                        this.loading_add = false;
                        this.$bvToast.toast('Блюдо ' + this.new_product.name + ' не добавлено! Что-то пошло не так! ', {
                            title: 'Блюдо не добавлено',
                            autoHideDelay: 5000,
                            appendToast: true,
                            variant:"warning"
                        });

                    });
                } else {
                    this.checkValid();
                }
            }
        },
        computed:{
            isValid: function () {
                if (this.new_product.name != null & this.new_product.name != '') {
                    return true;
                } else {
                    return false;
                }
            }
        }

    }
</script >

<style scoped >
    .preview {
        max-width: 150px;
        max-height: 150px;
    }
    .w120{
        min-width: 220px;
    }
</style >