<template >
    <div class="pb-5 mb-5">
        <div class="btn btn-primary" @click="saveProduct">Сохранить <b-spinner small v-show="visibleSave" type="grow"></b-spinner></div >
        <div class="btn btn-danger float-right" v-b-modal.modal-1>Удалить продукт <b-spinner small v-show="visibleSave" type="grow"></b-spinner></div >
        <b-modal id="modal-1" title="BootstrapVue" @ok="delProduct">
            <template slot="modal-title"
            >
                Удалить блюдо
            </template>
            <p class="my-4">Вы уверены что хотите удалить этот продукт?</p>
        </b-modal>
    </div>
</template >

<script >
    export default {
        name: "save-button",
        props: ['visibleSave'],
        delimiters: ['$[', ']'],
        methods: {
            saveProduct: function saveProduct() {
                this.$emit('saveproduct');
            },
            delProduct(){
                axios.delete('/ajax/{{ project.id }}/product/delete/', {
                    params: {
                        product: JSON.stringify(this.$parent.$parent.product)
                    }
                }).then(response => {
                    let res_data = response.data;
                    this.$parent.$parent.product =
                        {
                            id: res_data.id,
                            selected_category: res_data.selected_category,
                            selected_subcategory: res_data.selected_subcategory,
                            name: res_data.name,
                            ttk_num: res_data.ttk_num,
                            comment: res_data.comment,
                            technology: res_data.technology,
                            components: res_data.components,
                        };
                    this.$parent.$parent.doResponseProduct = false;
                    this.$parent.$parent.selected_product = res_data.id;

                    this.$parent.$parent.products = res_data.products;
                    this.$parent.$parent.products = makeValue(this.$parent.$parent.products);
                })
            },
        }
    }


</script >

<style scoped >

</style >