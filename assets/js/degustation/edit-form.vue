<template >
    <div >
        <b-form-row class="mb-3" >
            <b-col sm="6" >
                <label >Ссылка:</label >
                <b-input-group class="mb-3" >

                    <b-form-input id='copy' v-model="degustation.link"></b-form-input >
                    <b-input-group-append >
                        <b-button variant="outline-success" v-show="!copied" @click="copyLink">Скопировать</b-button >
                        <b-button variant="success" v-show="copied" @click="copyLink">Ссылка скопирована!</b-button >
                    </b-input-group-append >
                </b-input-group >
            </b-col >
        </b-form-row >
        <b-form-row class="mb-3" >
            <b-col sm="6" >
                <label >Название:</label >
                <b-form-input
                        id="input-live"
                        v-model="degustation.name"
                        placeholder="Название"
                        trim
                >
                </b-form-input >


                <b-form-text id="input-live-help" >Что бы не перепутать.</b-form-text >
            </b-col >
        </b-form-row >

        <b-form-row class="mb-3" >
            <b-col sm="2" >
                <label >Дата:</label >
                <b-form-input
                        type="date"
                        id="input-live"
                        v-model="degustation.date"
                        placeholder="Название"
                        trim
                ></b-form-input >
            </b-col >
            <b-col sm="2" >
                <label >Время:</label >
                <b-form-input
                        type="time"
                        id="input-live"
                        v-model="degustation.time"
                        placeholder="Название"
                        trim
                ></b-form-input >
            </b-col >
            <b-col sm="2" >
                <label >Место:</label >
                <b-form-input
                        type="texts"
                        id="input-live"
                        v-model="degustation.place"
                        placeholder="Название"
                        trim
                ></b-form-input >
            </b-col >
        </b-form-row >
        <b-form-row class="mb-3" >
            <b-col sm="6" >
                <label >Комментарии:</label >
                <b-form-textarea
                        id="textarea"
                        v-model="degustation.description"
                        placeholder="Ну вдруг"
                        rows="3"
                        max-rows="6"
                ></b-form-textarea >
            </b-col >
        </b-form-row >

        <div class='new_str ' >
            Перечень блюд

        </div >
        <old-create-table-full
                :fields="fields"
                foot-clone="true"
                :categories="categories"
                :degustation="degustation"
                :project_id="project_id" >

        </old-create-table-full >
    </div >
</template >

<script >

    export default {
        name: "create-form",
        props: {
            categories: Array,
            products: Array,
            fields: Object,
            degustation: Object,
            project_id: Number
        },
        created: function () {


        },
        data: function data() {
            return {
                name: '',
                text: '',
                copied:false
            }
        },
        methods: {
            copyLink: function () {
                let copyText = document.getElementById("copy");

                /* Select the text field */
                copyText.select();

                /* Copy the text inside the text field */
                document.execCommand("copy");

                /* Alert the copied text */
                this.copied = true;
            }
        }
    }
</script >

<style scoped >

</style >