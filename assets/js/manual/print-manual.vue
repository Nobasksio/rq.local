<template >

    <div >
        <div > Категории</div >
        <b-button
                v-for="(category, idx) in app_state.categories"
                class="ml-1 mt-1"
                size="sm"
                :key="idx"
                :pressed.sync="category.id == selected_category"
                active-class="primary"
                variant="outline-secondary"
                @click="choose_category(category.id)"
        >
            {{ category.text }}
        </b-button >

        <div id="element-to-print" >

            <template v-for="(product,index) in product_choosed_category">
                <div class="print-container " >
                    <page >
                        <div class="table-manual-container" >
                            <table class="table-manual border component_ttk" cellpadding="0" >
                                <tr >
                                    <td class="text-center font-18 p-1 border header_table" >{{product.name_work}}</td >
                                    <td class="text-center h3 p-2 border text-dark w-50" rowspan="2" >
                                        <img :src="product.photo" alt=""
                                             class="img_meal" >
                                    </td >
                                </tr >
                                <tr >
                                    <td style="vertical-align:top; height:100%;" class="w-50" >
                                        <table class="in_size_table font-8 component_ttk" >
                                            <tr >
                                                <td class="p-2 border mebold" >№</td >
                                                <td class="p-2 border mebold" >Наименование продукта</td >
                                                <td class="p-2 border mebold" >Ед. изм.</td >
                                                <td class="p-2 border mebold" >кол-во</td >
                                            </tr >
                                            <tr v-for="(component, index2) in product.components">
                                                <td class="p-2 border " >{{index2 +1 }}</td >
                                                <td class="p-2 border " >{{component.name}}</td >
                                                <td class="p-2 border " >{{component.measure_name}}</td >
                                                <td class="p-2 border " >{{component.count}}</td >
                                            </tr >
                                            <tr>
                                                <td class="p-2 border text-right mebold" colspan="3"  > Выход по меню</td >
                                                <td class="p-2 border mebold " >{{product.ves}}</td >
                                            </tr >
                                        </table >
                                    </td >
                                </tr >
                                <tr >
                                    <td colspan="2" class="text-center border p-2 font-11 mebold" >Технология
                                        Приготовления
                                    </td >
                                </tr >
                                <tr >
                                    <td colspan="2" class="border p-3 font-8" >{{product.technology}}

                                    </td >
                                </tr >
                                <tr >
                                    <td colspan="2" class="text-center border p-2 font-11 mebold" >Cпособ оформления
                                    </td >
                                </tr >
                                <tr >
                                    <td colspan="2" class="border p-3 font-8" >
                                        {{product.comment}}
                                    </td >
                                </tr >

                            </table >
                        </div >
                    </page >

                </div >
                <div class="html2pdf__page-break" ></div >
            </template >
        </div >
    </div >
</template >

<script >
    export default {
        name: "print-manual",
        props: ['app_state'],
        data:function () {
            return{
                selected_category: app_state.selected_category
            }
        },
        methods:{
            choose_category(category_id){
                this.selected_category = category_id
            }
        },
        computed:{
           product_choosed_category(){
               let our_arr = this.app_state.products.filter((product)=>{
                   return product.selected_category == this.selected_category
               })
               return our_arr;
           }
        }
    }
</script >

<style scoped >

</style >