<template >
    <select>
        <slot></slot>
    </select>
</template >

<script >
    export default {
        name: "select2",
        props: ['measures', 'value', 'type', 'product'],
        mounted: function mounted() {
            var vm = this;
            $(this.$el) // init select2
                .select2({
                    data: this.measures
                }).val(this.value).trigger('change') // emit event on change.
                .on('change', function () {
                    vm.$emit('input', this.value);
                    $('.select2-selection__rendered').removeAttr('title');
                });
        },
        watch: {
            value: function value(value) {
                var _this = this;
                if (value != '' & typeof value != "undefined") {
                    // alert('step1 value='+value+"*");
                    var isComponent = false;

                    for (i = 0; i < this.measures.length; ++i) {
                        if (this.measures[i].id == value & value != '') {
                            // alert('step1.1');
                            isComponent = true;
                            break;
                        }

                        if (this.measures[i].text == value & value != '') {
                            // alert('step1.2');
                            isComponent = true;
                            break;
                        }
                    }
                    this.$parent.$parent.mainAlert = true;

                    if (!isComponent) {
                        // alert('step2');
                        var name_entity;

                        if (this.type == 1) {
                            name_entity = 'category';
                            this.$parent.visible1 = true;

                        } else if (this.type == 2) {
                            name_entity = 'subcategory';
                            this.$parent.visible2 = true;

                        } else if (this.type == 3) {
                            name_entity = 'component';
                            this.$parent.visible3 = true;
                        } else if (this.type == 4) {
                            name_entity = 'measure';
                            this.$parent.visible4 = true;
                        }

                        axios.get('/ajax/'+this.project_id+'/' + name_entity + '/create', {
                            params: {
                                name: value,
                                product_id: this.product.id
                            }
                        }).then(function (response) {
                            _this.measures.push({
                                name: _this.value,
                                text: _this.value,
                                id: response.data
                            });

                            if (_this.type == 1) {
                                _this.$parent.$parent.$parent.product.selected_category = response.data;
                                _this.$parent.visible1 = false;
                            } else if (_this.type == 2) {
                                // this.$parent.components = this.measures,
                                _this.$parent.$parent.$parent.product.selected_subcategory = response.data;
                                _this.$parent.visible2 = false;
                            } else if (_this.type == 3) {
                                // this.$parent.components = this.measures,
                                _this.$parent.$parent.$parent.selected_component = response.data;
                                _this.$parent.visible3 = false;
                            } else if (_this.type == 4) {
                                _this.$parent.$parent.$parent.selected_measure = response.data;
                                _this.$parent.visible4 = false;
                            }

                            $(_this.$el).empty().select2({
                                data: _this.measures,
                                tags: true
                            });
                        });
                    } else {
                        if (this.type == 1) {
                            this.$parent.$parent.$parent.mainAlert = true;
                            this.$parent.$parent.$parent.product.selected_category = value;
                        } else if (this.type == 2) {
                            // this.$parent.components = this.measures,
                            this.$parent.$parent.$parent.mainAlert = true;
                            this.$parent.$parent.$parent.product.selected_subcategory = value;
                        } else if (this.type == 3) {
                            // this.$parent.components = this.measures,
                            this.$parent.$parent.$parent.selected_component = value;
                        } else if (this.type == 4) {
                            this.$parent.$parent.$parent.selected_measure = value;
                        }
                    } // update value


                    $(this.$el).val(value).trigger('change');
                }
            },
            options: function options(measures) {
                // update options
                $(this.$el).empty().select2({
                    data: measures
                });
            }
        },
        destroyed: function destroyed() {
            $(this.$el).off().select2('destroy');
        }
    }


</script >

<style scoped >

</style >