<template >
    <div class="table-manual-container" >
        <table class="table-manual border" cellpadding="0" >
            <tr >
                <td class=" font-12 p-3 border" >
                    <div class="form-group row justify-content-center" >
                        <label for="" class="text-right align-self-center mt-1 mr-1" >Технологическая карта
                            № </label >
                        <div class="d-inline" >
                            <input class='bg-light form-control' type="text"
                                   v-model="product.ttk_num" >
                        </div >
                    </div >
                </td >
                <td class=" font-12 text-center p-2 border text-dark" >
                    <div class="form-group row justify-content-center" >
                        <label for="" class="text-right align-self-center mt-1 mr-1" >
                            Название блюда</label >
                        <strong > <input class='bg-light form-control' type="text"
                                         v-model="product.name" ></strong >
                    </div >
                </td >
            </tr >
            <tr >
                <td style="vertical-align:top; height:100%;" class="w-50" >
                    <table class="in_size_table font-8" >
                        <tr >
                            <td class="p-2 border " >№</td >
                            <td class="p-2 border " >Наименование продукта</td >
                            <td class="p-2 border" >Ед. изм.</td >
                            <td class="p-2 border" >Колличество</td >
                            <td class="p-2 border" ></td >
                        </tr >

                        <tr v-for="(component,index) in product.components" >
                            <td class="p-2 border " >{{ index +1}}</td >
                            <td class="p-2 border" >
                                <multiselect2 type='3'
                                              :options="all_components"
                                              :component="product.components[index]"
                                              :product="product"
                                              :key_m="index"
                                              :project_id="project_id"
                                >
                                </multiselect2 >
                               <!-- <select2 class="select2"
                                         :measures="all_components"
                                         type='3'
                                         :key_m="index+1"
                                         :product="product"
                                         :project_id="project_id"
                                         v-model="component.id" >
                                    <option disabled value='0' >выбери</option >
                                </select2 >-->

                            </td >
                            <td class="p-2 border" >
                                <select2 class="select2 form-control"
                                         :measures="measures"
                                         type='4'
                                         :key_m="index+1"
                                         :product="product"
                                         :project_id="project_id"
                                         v-model="component.measure" >
                                    <option disabled value="0" >выбери</option >
                                </select2 >

                            </td >
                            <td class="p-2 border" >
                                <b-form-input v-model="component.count" placeholder="введите количество" ></b-form-input >
                            </td >
                            <td class="p-2 border" >
                                <button type="button" class="close" aria-label="Close" @click="removeComponent(index)" >
                                    <span aria-hidden="true" >&times;</span >
                                </button >
                            </td >

                        </tr >
                        <tr >
                            <td class="p-2 border" >

                            </td >
                            <td class="p-2 border" >
                                <!--<multiselect2 v-model="selected_component"-->
                                             <!--:product="product"-->
                                             <!--:project_id="project_id"-->
                                             <!--tag-placeholder="добавить этот компонент"-->
                                             <!--placeholder="выбери компонен"-->
                                             <!--label="name"-->
                                             <!--track-by="code"-->
                                             <!--:options="all_components"-->
                                             <!--:taggable="true"-->
                                             <!--@tag="addTag">-->
                                <!--</multiselect2>-->
                                <multiselect2 type='5'
                                              :options="all_components"
                                              :component="selected_component"
                                              :product="product"
                                              :key_m="0"
                                              :project_id="project_id"
                                >
                                </multiselect2 >
                                <!--<select2 class="select2"-->
                                         <!--:measures="all_components"-->
                                         <!--type='5'-->
                                         <!--key_m="0"-->
                                         <!--:product="product"-->
                                         <!--:project_id="project_id"-->
                                         <!--v-model="selected_component" >-->
                                    <!--<option disabled value="0" >выбери</option >-->
                                <!--</select2 >-->
                                <b-spinner small v-show="visible3" type="grow" ></b-spinner >
                            </td >
                            <td class="p-2 border" >

                                <select2 class="select2 form-control"
                                         :measures="measures"
                                         :type='6'
                                         key_m="0"
                                         :product="product"
                                         :project_id="project_id"
                                         v-model="selected_measure" >
                                    <option disabled value="0" >выбери</option >
                                </select2 >
                                <b-spinner small v-show="visible4" type="grow" ></b-spinner >
                            </td >
                            <td class="p-2 border" >
                                <input type="text" name="measure_count" v-model="measure_count" class="form-control" >
                            </td >
                        </tr >
                        <tr >
                            <td class="p-2 border" colspan="5" >
                                <div class="btn btn-primary float-left" @click="addComponent" >

                                    Добавить
                                </div >
                                <b-alert v-model="showAddProblem" class="float-left mx-2" variant="danger" dismissible>
                                    Заполните все поля текущего ингридиента перед добавлением следующего!
                                </b-alert>
                            </td >
                        </tr >


                    </table >
                </td >
                <td class="border" >

                    <div class="blk_img_meal d-flex justify-content-center">
                        <img :src="product.photo" v-show="product.photo!=null" alt="" class="img_meal2" >
                    </div>
                    <div class="d-flex justify-center m-4" >
                        <b-form-file
                                class="ml-4"
                                v-model="file"
                                :state="Boolean(product.photo)"
                                :file="product.photo"
                                placeholder="загрузить файл"
                                drop-placeholder="Drop file here..."
                                ref="upload"
                                accept="image/jpeg, image/png, image/gif"
                                :file-name-formatter="formatNames"
                        ></b-form-file >

                        <div class="btn btn-primary center mx-4" @click="upload" >
                            Сохранить
                            <b-spinner small v-show="visibleupload" type="grow" ></b-spinner >
                        </div >
                    </div >
                </td >
            </tr >
            <tr >
                <td colspan="2" class="text-center border p-2 font-11" ><strong >Технология
                    Приготовления</strong ></td >
            </tr >
            <tr >
                <td colspan="2" class="border p-3 font-8" >
                    <textarea class="form-control" v-model="product.technology" >$[product.technology]</textarea >
                </td >
            </tr >

        </table >
    </div >
</template >

<script >
    export default {
        name: "components",
        props: {
            product: Object,
            measures: Array,
            selected_measure: Number,
            selected_component: Number,
            all_components: Array,
            project_id: Number
        },
        delimiters: ['$[', ']'],
        data: function data() {
            return {
                file: null,
                visible3: false,
                visible4: false,
                measure_count: null,
                visibleupload:false,
                showAddProblem: false
            };
        },
        mounted: function mounted() {
            console.log('компонент');
            console.log(this.all_components);
        },
        methods: {
            addComponent: function addComponent() {

                if (this.measure_count != null && this.measure_count != '') {
                    this.$parent.$parent.product.components.push({
                        name: this.nameSelectedComponent.text,
                        id: this.selected_component.id,
                        measure: this.selected_measure,
                        measure_name: this.nameSelectedMeasure.text,
                        count: this.measure_count
                    });
                    this.$parent.$parent.mainAlert = true;
                    this.measure_count = null;
                } else {
                    this.showAddProblem = true
                }
            },

            removeComponent: function removeComponent(index) {
                console.log(index);
                this.$parent.$parent.mainAlert = true;
                this.$parent.$parent.product.components.splice(index, 1);
            },
            formatNames(files) {
                if (files.length === 1) {
                    return files[0].name
                } else {
                    return `${files.length} files selected`
                }
            },
            upload() {
                this.visibleupload = true;
                console.log(this.file);
                let formData = new FormData();
                formData.append('file', this.file);
                axios.post('/ajax/product/' + this.product.id + '/addfooto/4/4',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(response => {
                    this.$parent.$parent.product.photo = '/uploads/file/' + response.data.img
                    this.visibleupload = false;
                })
                    .catch(function () {
                        console.log('FAILURE!!');
                    });
            },


        },
        computed: {
            nameSelectedMeasure: function nameSelectedMeasure() {
                var _this3 = this;

                var search_measure = this.measures.filter(function (measure) {
                    return measure.id == _this3.selected_measure;
                });

                if (!search_measure) {
                    search_measure = this.measures.filter(function (measure) {
                        return measure.name == _this3.selected_measure;
                    });
                }

                return search_measure[0];
            },
            nameSelectedComponent: function nameSelectedComponent() {
                var _this4 = this;

                var search_measure = this.all_components.filter(function (component) {
                    return component.id == _this4.selected_component.id;
                });

                if (!search_measure) {
                    search_measure = this.all_components.filter(function (component) {
                        return component.name == _this4.selected_component.id;
                    });
                }

                return search_measure[0];
            }
        }
    }
</script >

<style scoped >
    .blk_img_meal{
        width: 100%;
        max-height: 500px
    }
    .img_meal2 {
        max-height: 500px;
    }
</style >