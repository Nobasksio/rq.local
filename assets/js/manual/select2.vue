<template >
    <select>
        <slot></slot>
    </select>
</template >

<script >
    function prepareComponents(data) {
        var data = $.map(data, function (obj) {
            obj.id_t = obj.component_id || obj.id;
            obj.text = obj.component_name || obj.name;// replace name with the property used for the text
            return obj;
        });
        return data;
    }

    export default {
        name: "select2",
        props: ['measures', 'value', 'type','key_m', 'product','project_id'],
        data: function data(){
            return {
                nonchange:false,
                count:0
            }
        },
        mounted: function mounted() {
            this.measure = prepareComponents(this.measure);

            var vm = this;
            $(this.$el) // init select2
                .select2({
                    data: this.measures,
                    tags: true
                })
                .val(this.value)
                .trigger('change') // emit event on change.
                .on('change', function () {
                    vm.$emit('input', this.value)
                });
            this.nonchange = true;
        },

        watch: {
        measures: function(measures) {
            // update options
            // $(this.$el).empty().select2({
            //     data: measures,
            //     tags: true
            // });
        },
            value: function(value) {
                let _this = this;

                this.count++;
                if (value != '' & typeof value != "undefined" & value != 0) {


                    let isComponent = false,
                        i = 0;

                    for (i = 0; i < this.measures.length; i++) {

                        if (this.measures[i].id == value & value != '') {


                            isComponent = true;
                            break;
                        }

                        if (this.measures[i].text == value & value != '') {


                            isComponent = true;
                            break;
                        }
                    }
                    this.$parent.$parent.mainAlert = true;

                    if (!isComponent) {


                        let name_entity;

                        if (this.type == 1) {
                            name_entity = 'category';
                            this.$parent.visible1 = true;

                        } else if (this.type == 2) {
                            name_entity = 'subcategory';
                            this.$parent.visible2 = true;

                        } else if (this.type == 3 || this.type == 5) {
                            name_entity = 'component';
                            this.$parent.visible3 = true;
                        } else if (this.type == 4 || this.type == 6) {
                            name_entity = 'measure';
                            this.$parent.visible4 = true;
                        }

                        axios.get('/ajax/'+this.project_id+'/' + name_entity + '/create', {
                            params: {
                                name: value,
                                product_id: this.product.id
                            }
                        }).then(response => {
                            this.measures.push({
                                name: value,
                                text: value,
                                id: response.data
                            });

                            if (this.type == 1) {
                                this.$parent.$parent.product.selected_category = response.data;
                                this.$parent.visible1 = false;
                            } else if (this.type == 2) {

                                this.$parent.$parent.product.selected_subcategory = response.data;
                                this.$parent.visible2 = false;
                            } else if (this.type == 3) {

                                this.$parent.$parent.$parent.product.components[this.key_m-1].component_id = response.data;
                                this.$parent.$parent.$parent.product.components[this.key_m-1].component_name = this.value;

                                this.$parent.visible3 = false;
                            } else if (this.type == 4) {
                                this.$parent.$parent.$parent.product.components[this.key_m-1].measure = response.data;
                                this.$parent.$parent.$parent.product.components[this.key_m-1].measure_name = this.value;
                                this.$parent.$parent.$parent.product.components[this.key_m-1].measure_name = null;
                                this.$parent.visible4 = false;


                            } else if (this.type == 5) {

                                this.$parent.$parent.$parent.selected_component = response.data;
                                this.$parent.visible3 = false;


                            }
                            else if (this.type == 6) {
                                this.$parent.$parent.$parent.selected_measure = response.data;
                                this.$parent.visible4 = false;


                            }

                            $(this.$el).empty().select2({
                                data: this.measures,
                                tags: true
                            });


                        });
                    } else {


                        if (this.type == 1) {
                            this.$parent.$parent.mainAlert = true;

                        } else if (this.type == 2) {
                            this.$parent.$parent.mainAlert = true;

                        } else if (this.type == 3) {

                        } else if (this.type == 4) {

                        }
                    } // update value

                }


                $(this.$el).val(value).trigger('change');


            }
        },
        destroyed: function destroyed() {
            $(this.$el).off().select2('destroy');
        }
    }


</script >

<style scoped >

</style >