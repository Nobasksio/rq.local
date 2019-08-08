<template >
    <div class="pb-4">

                <b-input-group size="lg" class='mf-10' :prepend="count_product_yet" >
                    <b-form-select v-model="selected_product"
                                   class="selector"
                                   :options="products" >
                        <template slot="first" >
                            <option :value="null" disabled >-- Пожалуйста выбери блюдо --</option >
                        </template >
                    </b-form-select >
                </b-input-group >

                <div v-show="!selected_product" >
                    <b-container >
                        <h2 class="py-4" >Добро пожаловать на дегустацию!</h2 >
                        Для начала выбери блюдо в верху экрана.<br >
                        Сегодня тебя ждет {{ count_product_all }} {{word_dishs}}

                    </b-container >
                </div >

                <div v-show="selected_product" >
                    <div class="text-center" >
                        <label class="pt-2 mb-0" >Название</label >
                    </div >
                    <b-container class="text-center">
                        {{product.name}}
                    </b-container >
                    <div class="text-center" >
                        <label class="pt-2 mb-0" >Состав</label >
                    </div >
                    <b-container >
                        {{product.consist}}
                    </b-container >
                    <div class="text-center" >
                        <label class="pt-2 mb-0" >Вид</label >
                    </div >
                    <b-form-radio-group
                            class="container-fluid px-0"
                            id="btn-radios-1"
                            v-model="product.scores.view"
                            :options="rait"
                            buttons
                            block="true"
                            button-variant="outline-primary"
                            size="lg"
                            name="radio-btn-outline"
                    ></b-form-radio-group >
                    <div class="text-center" >
                        <label class="pt-2 mb-0" >Вкус</label >
                    </div >
                    <b-form-radio-group
                            class="container-fluid px-0"
                            id="btn-radios-2"
                            v-model="product.scores.taste"
                            :options="rait"
                            buttons
                            button-variant="outline-primary"
                            size="lg"
                            name="radio-btn-outline"
                    ></b-form-radio-group >
                    <div class="text-center" >
                        <label class="pt-2 mb-0" >Концепция</label >
                    </div >
                    <b-form-radio-group
                            class="container-fluid px-0"
                            id="btn-radios-3"
                            v-model="product.scores.concept"
                            :options="rait"
                            buttons
                            button-variant="outline-primary"
                            size="lg"
                            name="radio-btn-outline"
                    ></b-form-radio-group >
                    <b-container >
                        <label class="pt-2 mb-0" >Справедливая цена: {{ product.scores.price }}</label >
                        <b-form-input v-model="product.scores.price" type="range" min="0" :max="average_price"
                                      step="10" ></b-form-input >

                    </b-container >
                    <b-container >
                        <label class="pt-2 mb-0" >Комментарий</label >
                        <b-form-textarea
                                id="textarea"
                                v-model="product.scores.comment"
                                placeholder="Кисло, солёно, восхитительно"
                                rows="3"
                                max-rows="6"
                        ></b-form-textarea >
                    </b-container >
                    <b-alert :show="dismissCountDown"
                             @dismissed="dismissCountDown=0"
                             @dismiss-count-down="countDownChanged"
                             variant="success" dismissible >
                        Что ж ты за прекрасный человек! Оценки сохранены. Спасибо за труды!
                    </b-alert >
                    <b-alert :show="teribleAlert"
                             @dismissed="dismissCountDown=0"
                             @dismiss-count-down="countDownChanged"
                             variant="danger" dismissible >
                        Увас ущербный интернет. Пока все сохранилось в вашем телефоне. Попробуйте ещё раз
                    </b-alert >

                    <b-button block variant="primary" class="mt-4" @click="saveScores(product.id)" >
                        <b-spinner type="grow" small v-show="loading" label="Loading..." ></b-spinner >
                        Сохранить
                        <b-spinner type="grow" small v-show="loading" label="Loading..." ></b-spinner >
                    </b-button >
                    <b-button block v-show="Boolean(!file)" variant="warning" class="my-4" @click="upload(product.id)" >
                        <b-spinner type="grow" small v-show="uploading" label="Loading..." ></b-spinner >
                        Сфотать
                        <b-spinner type="grow" small v-show="uploading" label="Loading..." ></b-spinner >
                    </b-button >
                    <b-button block v-show="Boolean(file)" variant="success" class="my-4" @click="upload(product.id)" >
                        <b-spinner type="grow" small v-show="uploading" label="Loading..." ></b-spinner >
                        Перефотать
                        <b-spinner type="grow" small v-show="uploading" label="Loading..." ></b-spinner >
                    </b-button >
                    <div class="text-center" >
                        <b-card
                                title=""
                                v-show="Boolean(image.preview)"
                                :img-src="'/uploads/file/preview/'+image.preview"
                                img-alt="Image"
                                img-top
                                tag="article"
                                style="max-width: 15rem;"
                                class="my-2 mx-auto"
                                :key="image.id"
                                v-for="image in product.photos"
                        >
                            <b-card-text >
                                <b-badge variant="success" class="clearfix" >дегустация</b-badge >
                            </b-card-text >
                        </b-card >
                    </div >
                    <input style="display: none;" type="file" ref="fileInput" v-on:change="handleFileUpload()" />
                    <!--<input type="file" ref="uploadform" class="form-control-file" id="exampleFormControlFile1">-->
                    <!--<b-form-file-->
                    <!--class="mt-4 d-none"-->
                    <!--ref="uploadform"-->
                    <!--v-model="file"-->
                    <!--:state="Boolean(file)"-->
                    <!--placeholder="Загрузить файл"-->
                    <!--drop-placeholder="Загрузить файл"-->
                    <!--&gt;</b-form-file>-->
                </div >
            </div >
</template >

<script >
    export default {
        name: "vote-page",
        props: {
            products: Array,
            user_hash: String,
            average_price: Number,
            degustation_hash: String
        },
        data: function () {
            return {
                rait: [1, 2, 3, 4, 5],
                file: null,
                selected_product: null,
                product: {scores: {}},
                dismissSecs: 5,
                dismissCountDown: 0,
                loading: false,
                uploading: false,
                showPreview: false,
                imagePreview: '',
                selected_index: null,
                teribleAlert: false
            }
        },
        methods: {
            handleFileUpload() {
                /*
                  Set the local file variable to what the user has selected.
                */
                this.file = this.$refs.fileInput.files[0];

                /*
                  Initialize a File Reader object
                */
                let reader = new FileReader();

                /*
                  Add an event listener to the reader that when the file
                  has been loaded, we flag the show preview as true and set the
                  image to be what was read from the reader.
                */
                reader.addEventListener("load", function () {
                    this.showPreview = true;
                    this.imagePreview = reader.result;
                }.bind(this), false);

                /*
                  Check to see if the file is not empty.
                */
                if (this.file) {
                    /*
                      Ensure the file is an image file.
                    */
                    if (/\.(jpe?g|png|gif)$/i.test(this.file.name)) {
                        /*
                          Fire the readAsDataURL method which will read the file in and
                          upon completion fire a 'load' event which we will listen to and
                          display the image in the preview.
                        */
                        reader.readAsDataURL(this.file);
                        this.submitFile()
                    }
                }
            },
            submitFile() {
                /*
                  Initialize the form data
                */
                let formData = new FormData();
                let self = this;

                /*
                  Add the form data we need to submit
                */
                formData.append('file', this.file);

                /*
                  Make the request to the POST /single-file URL
                */
                this.uploading = true
                axios.post('/ajax/product/' + this.product.id + '/addfooto/4/2',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(response => {
                    console.log('SUCCESS!!');
                    self.product.photos.push(response.data);
                    self.uploading = false
                }).catch(function (error) {
                    console.log('FAILURE!!');
                    alert('хуйня')
                    self.uploading = false
                });
            },
            upload: function (product_id) {

                this.$refs.fileInput.click()
            },
            saveScores: function (id) {
                let self = this;

                let products_request = JSON.stringify(this.products);
                this.loading = true;
                axios.post('/ajax/degustation/vote/' + this.degustation_hash,
                    "products=" + products_request + "&hash=" + this.user_hash
                ).then(response => {

                    this.products = response.data.products
                    let product_item = this.products.filter(function (product_item) {
                        return product_item.id == id;
                    });
                    if (this.product.text.indexOf("✔") == -1) {
                        this.products[this.selected_index].text = "✔" + this.product.text + " — готово";
                    }
                    // this.product[this.selected_index] = "✔" + this.product.text + " — готово";
                    this.products = this.products.sort(function (a, b) {
                        if (a.saved == false && b.saved == true) {
                            return -1;
                        } else if (a.saved == true && b.saved == false) {
                            return 1;
                        } else {
                            return 0;
                        }
                    })
                    this.loading = false;
                    this.showAlert();
                }).catch(error => {
                    this.teribleAlert = true;
                    this.loading = false;
                    console.log(error);
                });
                //
            },
            countDownChanged(dismissCountDown) {
                this.dismissCountDown = dismissCountDown
            },
            showAlert() {
                this.dismissCountDown = this.dismissSecs
            }
        },
        watch: {
            selected_product: function (selected_product) {
                let self = this;
                let product_return = this.products.filter(function (product_item) {

                    return product_item.id == selected_product;
                });
                this.products.forEach(function (item, i) {
                    if (item.id == selected_product) {
                        self.selected_index = i;
                    }
                });

                if (product_return[0]) {

                    return this.product = product_return[0]

                } else {
                    return []
                }
            }
        },
        computed: {
            count_product_yet: function () {
                let products_return = this.products.filter(function (product_item) {
                    return product_item.saved == false;

                });
                return String(products_return.length)
            },
            count_product_all: function () {
                return this.products.length
            },
            word_dishs: function () {
                if (this.products.length % 10 == 1) {
                    return 'блюдо'
                } else if (this.products.length % 10 > 1 & this.products.length % 10 < 5) {
                    return 'блюда'
                } else if (this.products.length % 10 > 4 || this.products.length % 10 == 0) {
                    return 'блюд'
                }
            }
        },

    }
</script >

<style scoped >
    .selector {
        height: 70px;
    }
    .mf-10{
      font-size: 10px;
    }
</style >