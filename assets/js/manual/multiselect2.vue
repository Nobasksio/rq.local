<template >
    <div >
        <multiselect v-model="component"
                     :multiple="false"
                     :options="options"
                     label="name"
                     placeholder='выбери блюдо из айки для подгрузки ттк'
                     selectLabel='выбери'
                     :taggable="true"
                     tag-placeholder="нажмите enter чтобы создать"
                     @tag="addTag"
                     @input="change"
                     track-by="name" >
        </multiselect >
    </div >
</template >

<script >
    export default {
        props: ['options','component', 'type', 'key_m', 'product', 'project_id'],
        data() {
            return {
            }
        },
        methods: {
            change(value, id){

                this.$parent.product.components[this.key_m].name = value.name;
                this.$parent.product.components[this.key_m].text = value.text;
                this.$parent.product.components[this.key_m].id = value.id;
            },
            addTag(newTag) {
                let name_entity = 'component';

                this.$parent.visible3 = true;

                axios.get('/ajax/' + this.project_id + '/' + name_entity + '/create', {
                    params: {
                        name: newTag,
                        product_id: this.product.id
                    }
                }).then(response => {
                    this.options.push({
                        name: newTag,
                        text: newTag,
                        id: response.data
                    });
                    if (this.type == 3) {
                        this.product.components[this.key_m].id = response.data;
                        this.product.components[this.key_m].name = newTag;

                        this.component.name = newTag;
                        this.component.name = newTag;

                        this.$parent.visible3 = false;
                    } else {
                        this.component.name = newTag;
                        this.component.id = response.data;
                        this.$parent.visible3 = false;
                    }


                });
            }
        }
    }

</script >

<style scoped >

</style >