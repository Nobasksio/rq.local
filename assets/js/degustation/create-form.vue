<template >
    <div >
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
                        type="text"
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
        <old-create-table
                :fields="fields"
                foot-clone="true"
                :categories="categories"
                :degustation="degustation"
                :project_id="project_id" >

        </old-create-table >
    </div >
</template >

<script >

    export default {
        name: "create-form",
        props: {
            categories: Array,
            fields: Object,
            degustation: Object,
            project_id: Number
        },
        created: function () {


        },
        data: function data() {
            return {
                name: '',
                text: ''
            }
        },
        methods: {
        }
    }
</script >

<style scoped >

</style >