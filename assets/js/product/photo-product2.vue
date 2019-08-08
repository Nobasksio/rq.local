<template >
    <div>
    <b-card-group deck >
        <b-card
                title=""
                :img-src="'/uploads/file/preview/'+photo.preview"
                img-alt="Image"
                img-top
                tag="article"
                style="max-width: 15rem;"
                @click="showPhoto(photo.id)"
                class="mb-2"
                v-for="(photo, index) in all_photo_this_type"
                :key="photo.id"
        >
            <b-card-text >
                <b-badge variant="success" class="clearfix" v-if="photo.isMain == true">главная</b-badge >
                <b-badge variant="primary" class="clearfix" @click='makeMain(photo.id,index)' v-if="photo.isMain != true">сделать главной</b-badge >
                <b-badge variant="danger" href="#" @click="wantDelete(product.id,photo.id,index)"
                         class="clearfix mt-2" >Удалить
                </b-badge >
            </b-card-text >
        </b-card >

    </b-card-group >
        <b-button variant="light" class='' size="sm" @click="upload(product.id)" >загрузить фото</b-button >
        <input style="display: none;" type="file" :ref="'fileInput'+product.id"
               v-on:change="handleFileUpload(product.id)" />
        <b-modal :id="'modal_delete'+product.id" hide-footer >
            <template slot="modal-title" >
                Удаление фото
            </template >
            <p class="my-4" >Вы на самом деле хотите удалить эту фотографию?</p >
            <div class="d-block text-center" >
                <b-img fluid
                       :src="'/uploads/file/'+delete_photo.img"
                       alt="Image 1"
                       class="my-2 mx-auto" >
                </b-img >
            </div >

            <b-button class="mt-3" @click="$bvModal.hide('modal_delete'+product.id)" >Отмена</b-button >
            <b-button class="mt-3 float-right" variant="danger" @click="deletePhoto()" >Удалить</b-button >
        </b-modal >
        <b-modal :id="'photoshow'+photo_type" hide-footer >
            <template slot="modal-title" >
                Фотография
            </template >
            <div class="d-block text-center" >
                <b-img fluid
                       :src="'/uploads/file/'+seleted_photo.img"
                       alt="Image 1"
                       class="my-2 mx-auto" >
                </b-img >
            </div >
            <b-button class="mt-3" @click="$bvModal.hide('photoshow'+photo_type)" >закрыть</b-button >
        </b-modal >
    </div>
</template >

<script >
    export default {
        name: "photo-product2",
        props: {
            product: Object,
            photo_type: Number
        },
        data: function () {
            return {
                more: false,
                types: {
                    1: "проработка",
                    2: "дегустация",
                    3: "фотосет",
                    4: "ттк",
                    5: "меню",
                },
                delete_photo: {
                    img: null
                },
                delete_index: null,
                seleted_photo:{
                    img:null
                }
            }
        },
        methods: {
            showMore: function () {
                if (this.more == true) {
                    this.more = false
                } else {
                    this.more = true
                }
            },
            showPhoto: function(photo_id){
                this.$bvModal.show('photoshow'+this.photo_type);
                let index_del = 0;
                let return_arr = this.product.photos.filter(function (photo) {
                    return photo.id == photo_id;
                });

                this.seleted_photo = return_arr[0]
            },
            upload: function (product_id) {
                let name = 'fileInput' + product_id

                this.$refs[name].click()
            },
            handleFileUpload(product_id) {
                /*
                  Set the local file variable to what the user has selected.
                */
                let name = 'fileInput' + product_id
                this.file = this.$refs[name].files[0];

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
                        this.submitFile(product_id)
                    }
                }
            },
            submitFile(product_id) {
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
                axios.post('/ajax/product/' + product_id + '/addfooto/4/2',
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
            makeMain: function (photo_id, index) {
                this.product.photos.forEach(function (photo) {
                    photo.isMain = false
                });
                let return_arr = this.product.photos.filter(function (photo) {
                    return photo.id == photo_id;
                });
                return_arr[0].isMain = true;
                axios.post('/ajax/photo/make_main/' + photo_id, {}).then(response => {

                });
            },
            wantDelete: function (product_id, photo_id) {
                this.$bvModal.show('modal_delete' + product_id)
                let index_del = 0;
                let return_arr = this.product.photos.filter(function (photo) {
                    return photo.id == photo_id;
                });
                this.product.photos.forEach(function (item, i, arr) {
                    if (item.id == photo_id) {
                        index_del = i;
                    }
                });

                this.delete_photo = return_arr[0]
                this.delete_index = index_del

            },
            deletePhoto: function () {


                axios.delete('/ajax/product/' + this.product.id + '/photo/' + this.product.photos[this.delete_index].id, {}).then(response => {
                    this.product.photos.splice(this.delete_index, 1);

                });

                this.$bvModal.hide('modal_delete' + this.product.id)
            }
        },
        computed: {
            count_photos: function () {
                return this.product.photos.length
            },
            all_photo_this_type: function () {
                let self = this;
                let positiveArr = this.product.photos.filter(function (photo) {
                    return photo.type == self.photo_type;
                })
                return positiveArr;
            },
        },

    }
</script >

<style scoped >

</style >