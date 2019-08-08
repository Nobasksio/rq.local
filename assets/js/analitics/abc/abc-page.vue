<template >
    <div >
        <b-button
                v-for="(category_item, idx) in upcategories"
                class="ml-1 mt-1"
                size="sm"
                :key="category_item.id"
                :pressed.sync="category_item.id == product_category_type"
                active-class="primary"
                variant="outline-secondary"
                @click="changeUpCategory(category_item.id)"
        >
            {{ category_item.name }}
        </b-button >
        <b-button
                class="ml-1 mt-1"
                @click="svodka(analitic_id)"
                size="sm"
                variant="outline-secondary"
                :pressed="svodka_show"
        >
            Сводка
        </b-button >
        <b-button
                class="ml-1 mt-1"
                @click="matrix"
                size="sm"
                variant="outline-secondary"
                :pressed="matrix_show"
        >
            Продуктовая матрица
        </b-button >
        <div > Категории</div >
        <b-button
                v-for="(category, idx) in categories_type"
                class="ml-1 mt-1"
                size="sm"
                :key="category.id"
                :pressed.sync="category.id == selected_category"
                active-class="primary"
                variant="outline-secondary"
                @click="changeCategory(category.id)" >
            {{ category.name }}
        </b-button >
        <b-spinner small v-show="loading" type="grow" ></b-spinner >
        <div class='line_cat_all' v-show="!svodka_show" >
            <div class='' >
                <span class='name_index' >выручка</span >
                <span class='index' >{{ val_vir }}</span >
                <span >%сс</span >
                <span class='index' >{{ val_ss }}</span >
                <span >Средняя цена</span >
                <span class='index' >{{ mean_price }}</span >
            </div >
        </div >

        <table class='table_order table' v-show="report_show" :class="{load_blk: loading}" border='0' cellpadding='0'
               cellspacing='0' >
            <thead >
            <tr class='th_order_table' >
                <th class='' ></th >
                <th class='tdz_ord_table' >Рабочее<br > название</th >
                <th class='tdz_ord_table' >Группа</th >
                <th class='tdz_ord_table' >Цена</th >
                <th class='tdz_ord_table' >Категория цены</th >
                <th class='tdz_ord_table' >Количество продаж</th >
                <th class='tdz_ord_table' >Отклонение от <br >ср. цены</th >
                <th class='tdz_ord_table' >СС за ед</th >
                <th class='tdz_ord_table' >% cc</th >
                <th class='tdz_ord_table' >Выручка</th >
                <th class='tdz_ord_table' >Валовая прибыль</th >
                <th class='tdz_ord_table' >Выручка</th >
                <th class='tdz_ord_table' >Продажи</th >
                <th class='tdz_ord_table' >Маржа</th >
                <th class='tdz_ord_table' >Категории</th >
                <th class='tdz_ord_table' >Комментарии</th >
                <th class='tdz_ord_table' >Взять в меню <span class='redact_grey_smal' id='all_check_span'
                                                              onclick='all_check()' >Отметить<br >все</span ></th >
                <th class='tdz_ord_table' >Переделать</th >
                <th class='tdz_ord_table' >Не учитывать<br > в расчетах</th >
            </tr >
            </thead >
            <tr class='tr_order2 ' v-for="(product_item, index) in products" >
                <td class='td_ord_table left' >{{ index+1 }}</td >
                <td class='td_ord_table left' >
                    {{ product_item.name }}
                </td >
                <td class='td_ord_table left' @click="editProductCategory(product_item.id)" >
                    <span v-show='!product_item.category_edit' >
                        {{ product_item.category.name }}
                    </span >
                    <div v-show='product_item.category_edit' >
                        <b-form-select
                                       v-model="product_item.category.id"
                                       :options="all_categories"
                                       size="sm"
                                       class="mt-3" >
                        </b-form-select >
                        <b-button variant="outline-primary"
                                  @click="saveProductCategory(product_item.id)" >
                            Сохранить
                            <b-spinner small v-show="looading_here" type="grow" ></b-spinner >
                        </b-button >
                    </div >
                </td >
                <td class='td_ord_table left' >{{ product_item.price }}</td >
                <td class='td_ord_table' >{{ product_item.product_cat }}</td >
                <td class='td_ord_discr' >{{ product_item.sale }}</td >
                <td class='td_ord_discr' >{{ Math.round((mean_price - product_item.price)* 100)/100 }}</td >
                <td class='td_ord_table' >{{ product_item.cost_price }}</td >
                <td class='td_ord_table' >{{ Math.round((product_item.cost_price/product_item.price)*100)/100 }}</td >
                <td class='td_ord_table' >{{ Math.round(product_item.price * product_item.sale) }}</td >
                <td class='td_ord_table' >{{ Math.round(product_item.price * product_item.sale - product_item.cost_price
                    * product_item.sale) }}
                </td >
                <td class='td_ord_table' >{{ product_item.abc_vir }}</td >
                <td class='td_ord_table' >{{ product_item.abc_sale }}</td >
                <td class='td_ord_table' >{{ product_item.abc_marj }}</td >
                <td class='td_min200 left' >
                    <span class='bold' >{{ product_item.first_otch_name }}</span >
                    {{ product_item.first_otch_comment }} <br ><br >
                    <span class='bold' >{{ product_item.second_otch_name }} </span > {{ product_item.second_otch_comment
                    }}
                </td >
                <td class='td_ord_table_medium center' >
                    <br >
                </td >
                <td class='td_ord_table' ><input type='checkbox' class='check' ></td >
                <td class='td_ord_table' ><input type='checkbox' class='remake' ></td >
                <td class='td_ord_table' ><input type='checkbox' class='remake' ></td >
            </tr >
        </table >
        <div v-show="matrix_show">
            <b-table striped hover :items="components" :fields="fields_matrix" >
                <template slot="index" slot-scope="data" >
                    {{ data.index + 1 }}
                </template >
                <template slot="per_vir" slot-scope="data" >
                    {{ Math.round((data.item.val_vir / (all_vir/100))*100)/100 }}
                </template >
            </b-table >
        </div>
        <div v-show="svodka_show" >
            <div class="row" >
                <div class="col-6" >
                    <table class='table_order table' >
                        <thead >
                        <tr class='th_order_table' >
                            <th class='' ></th >
                            <th class='tdz_ord_table' >Категория</th >
                            <th class='tdz_ord_table' >Выручка</th >
                            <th class='tdz_ord_table' >% от общей выручки</th >
                            <th class='tdz_ord_table' >Прибыль</th >
                            <th class='tdz_ord_table' >%</th >
                        </tr >
                        </thead >
                        <tr v-for="(category_item, index) in all_categories" >
                            <td class='td_ord_table left' >{{ index+1 }}</td >
                            <td class='td_ord_table left' >{{ category_item.name }}</td >
                            <td class='td_ord_table left' >{{ Math.round(category_item.val_vir) }}</td >
                            <td class='td_ord_table left' >{{ Math.round(category_item.val_vir/ (all_vir / 100)
                                *100)/100
                                }}
                            </td >
                            <td class='td_ord_table left' >{{ Math.round(category_item.val_marj) }}</td >
                            <td class='td_ord_table left' >{{ Math.round(category_item.val_marj / (all_marj /
                                100)*100)/100
                                }}
                            </td >
                        </tr >
                    </table >
                </div >
            </div >
            <div class="col-6" >
                <div id="chart" ></div >
            </div >
            <div class="col-6" >
                <div id="chartBarK" ></div >
            </div >
            <div >
                <b-table striped hover :items="top" :fields="fields" >
                    <template slot="index" slot-scope="data" >
                        {{ data.index + 1 }}
                    </template >
                    <template slot="per_vir" slot-scope="data" >
                        {{ Math.round((data.item.val_vir / (all_vir/100))*100)/100 }}
                    </template >
                </b-table >
            </div >
        </div >
    </div >
</template >

<script >
    import ApexCharts from 'apexcharts'

    export default {
        name: "abc-page",
        props: {
            upcategories: Array,
            categories_type: Array,
            all_categories: Array,
            products: Array,
            product_category_type: Number,
            selected_category: Number,
            mean_price: Number,
            val_vir: Number,
            val_ss: Number,
            loading: Boolean,
            analitic_id: Number,
            project_id:Number,
            components: Array
        },
        methods: {
            matrix:function(){
                this.matrix_show = true;
                this.report_show = false;
                this.$parent.product_category_type = 0;
                this.$parent.selected_category = 0;
            },
            changeUpCategory: function (id) {
                this.svodka_show = false;
                this.matrix_show = false;
                this.$parent.product_category_type = id;
            },
            changeCategory: function (id) {
                this.svodka_show = false;
                this.matrix_show = false;
                this.$parent.selected_category = id;
            },
            editProductCategory: function (product_id) {
                console.log('uraaa');
                let searched_product = this.getProductforId(product_id);

                searched_product.category_edit = true;
            },
            saveProductCategory:function(product_id){
                let searched_product = this.getProductforId(product_id);


                let data_request = JSON.stringify([{id:product_id, new_cat:searched_product.category.id}]);
                console.log( this.looading_here);
                this.looading_here = true;
                console.log( this.looading_here);
                axios.post('/ajax/'+ this.project_id +'/analitics/old_product/update_category',
                    "data=" + data_request
                ).then(response => {
                    searched_product.category.name = this.getCategoryforId(searched_product.category.id).name
                    searched_product.category_edit = false;

                    this.$bvToast.toast('Блюдо ' + searched_product.name + ' теперь в категории  ' + searched_product.category.name+'.', {
                        title: 'Категория успешно изменена',
                        autoHideDelay: 5000,
                        appendToast: true
                    })
                     this.looading_here = false;

                }).catch(function (error) {
                    this.looading_here = false;
                });

            },
            getProductforId(product_id){
                let product_return = this.products.filter(function (product_item) {

                    return product_item.id == product_id;
                });
                return product_return[0];
            },
            getCategoryforId(category_id){
                let category_return = this.all_categories.filter(function (category_item) {

                    return category_item.id == category_id;
                });
                return category_return[0];
            },
            svodka: function (analitic) {
                this.svodka_show = true;
                this.report_show = false;
                this.$parent.product_category_type = 0;
                this.$parent.selected_category = 0;
                this.loading = true;
                let self = this;
                var labels = [], series = [],
                    up_categories_labels = [], up_series_b = [], up_series_k = [];

                this.all_categories.forEach(function (item, index) {
                    labels.push(item.name)
                    series.push(item.val_vir)
                });

                this.upcategories.forEach(function (item, index_up) {

                    up_categories_labels.push = item.id;
                    self.all_categories.forEach(function (item_c, index) {
                        if (item_c.type == 1) {
                            up_series_k.push(item_c.val_vir)
                        } else {
                            up_series_b.push(item_c.val_vir)
                        }
                    });

                });

                var summ_b = 0, summ_k = 0;

                console.log(up_series_b);
                console.log(up_series_k);
                up_series_b.forEach(function (item, index) {
                    summ_b += item;
                });
                up_series_k.forEach(function (item, index) {
                    summ_k += item;
                });

                console.log(summ_b);
                console.log(summ_k);
                let options = {
                    chart: {
                        type: 'pie',
                    },
                    labels: labels,
                    series: series,
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }]
                };
                let options2 = {
                    chart: {
                        type: 'pie',
                    },
                    labels: ['Кухня', 'Бар'],
                    series: [summ_k, summ_b],
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }]
                };
                var chart = new ApexCharts(document.querySelector("#chart"), options);


                chart.render();
                var chart2 = new ApexCharts(document.querySelector("#chartBarK"), options2);
                chart2.render();
                axios.get('/ajax/svodka/' + this.analitic_id,
                ).then(response => {
                    let products_top = response.data.products_top;
                    this.top = products_top;
                    this.loading = false;
                })
            }

        },
        data: function () {
            return {
                svodka_show: false,
                report_show:true,
                top: [],
                looading_here: false,
                fields: [
                    {
                        key: 'index',
                        label: '#',
                        sortable: true
                    },
                    {
                        key: 'name',
                        label: 'Название',
                        sortable: true
                    },
                    {
                        key: 'val_vir',
                        label: 'Выручка',
                        sortable: true
                    },
                    {
                        key: 'per_vir',
                        label: '% Выручки',
                        sortable: true
                    },
                    {
                        key: 'sale',
                        label: ' Продано шт',
                        sortable: true,

                    }
                ],
                fields_matrix:[
                    {
                        key: 'name',
                        label: 'Название',
                        sortable: true
                    },
                    {
                        key: 'weight',
                        label: 'вес',
                        sortable: true
                    },
                    {
                        key: 'unit',
                        label: 'ед изм',
                        sortable: true
                    },
                    {
                        key: 'count',
                        label: 'количество блюд которых используется',
                        sortable: true
                    },
                ],
                matrix_show: false
            }
        },
        computed: {
            all_vir: function () {
                let summ = 0;
                this.all_categories.forEach(function (item, index) {
                    summ += item.val_vir
                });
                return Math.round(summ)
            },
            all_marj: function () {
                let summ = 0;
                this.all_categories.forEach(function (item, index) {
                    summ += item.val_marj
                });
                return Math.round(summ)


            }

        }
    }
</script >

<style scoped >

</style >