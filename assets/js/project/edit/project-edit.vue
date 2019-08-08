<template >
    <div >
        <div class="" >
            <div class="row" >
                <div class="col-6" >
                    <label class="typo__label" >Название проекта</label >
                    <b-form-input v-model="project.name" placeholder="" ></b-form-input >
                    <label class="typo__label" >Тип кухни</label >
                    <b-form-input v-model="project.type" placeholder="" ></b-form-input >
                    <div v-show="false" >
                        <label class="typo__label mt-3" >Тип заведения</label >
                        <multiselect v-model="project.type"
                                     :multiple="true"
                                     :options="all_user"
                                     id="type"
                                     label="mail"
                                     placeholder='кафе, ресторан, доставка и тд'
                                     tag-placeholder="добавить тип"
                                     selectLabel='в'
                                     :taggable="true"
                                     @tag="addtype"
                                     track-by="name" >
                        </multiselect >
                    </div >
                    <div v-show="project.project_id != 0" >
                        <label class="typo__label" >Дата создания</label >
                        <b-form-input v-model="project.date" type='date' disabled placeholder="" ></b-form-input >
                    </div >
                    <label class="typo__label mt-3" >Выбери подразделения проекта</label >
                    <multiselect v-model="choosed_departments"
                                 :multiple="true"
                                 :options="app_state.departments"
                                 label="name"
                                 placeholder='выбери подразделение'
                                 selectLabel='в'

                                 track-by="name" >
                    </multiselect >
                    <label class="typo__label mt-3" >Выбери категории меню категории iiko котроые нужны обязательно</label >
                    <multiselect v-model="chosen_category"
                                 :multiple="true"
                                 :options="app_state.categories"
                                 label="name"
                                 placeholder='выбери категорию'
                                 selectLabel='в'

                                 track-by="name" >
                    </multiselect >
                    <label class="typo__label mt-3" >Маркетологи</label >
                    <multiselect v-model="project.marketolog"
                                 :multiple="true"
                                 :options="all_user"
                                 id="m4"
                                 label="mail"
                                 placeholder='введи почту марекетолога'
                                 tag-placeholder="добавить пользователя"
                                 selectLabel='в'
                                 :taggable="true"
                                 @tag="addTag"
                                 track-by="name" >
                    </multiselect >
                    <label class="typo__label mt-3" >Повара</label >
                    <multiselect v-model="project.cooker"
                                 :multiple="true"
                                 :options="all_user"
                                 label="mail"
                                 id="c2"
                                 placeholder='введи почту повара'
                                 tag-placeholder="добавить пользователя"
                                 selectLabel='в'
                                 :taggable="true"
                                 @tag="addTag"
                                 track-by="name" >
                    </multiselect >
                    <label class="typo__label mt-3" >Бухгалтера</label >
                    <multiselect v-model="project.accountant"
                                 :multiple="true"
                                 id="a3"
                                 :options="all_user"
                                 label="mail"
                                 placeholder='введи почту бухгалтера'
                                 tag-placeholder="добавить пользователя"
                                 selectLabel='в'
                                 :taggable="true"
                                 @tag="addTag"
                                 track-by="name" >
                    </multiselect >
                </div >
            </div >
        </div >
        <div class="row" >
            <div class="col-2" >
                <b-button class="btn btn-primary my-3" @click="saveProject" >
                    Сохранить
                    <b-spinner type="grow" small v-show="loading" label="Loading..." ></b-spinner >
                </b-button >
            </div >
            <div col="8" >
                <b-alert class='my-3' variant="success" :show="showgo" >
                    Проект создан. <a :href="'/project/'+project.project_id+'/summary'" >Перейти к работе над
                    проектом</a >
                </b-alert >
            </div >
        </div >

    </div >
</template >

<script >
    export default {
        name: "project-edit",
        props: {
            choosed_departments: Array,
            app_state: Array,
            categories: Array,
            project: Array,
            all_user: Array,
            chosen_category:Array
        },
        data: function () {
            return {
                loading: false,
                showgo: false
            }
        },
        methods: {
            saveProject: function () {
                this.loading = true
                let past_id = this.project.project_id
                let data_request = JSON.stringify({
                    departments: this.choosed_departments,
                    chosen_category: this.chosen_category,
                    project: this.project
                });

                let text = ''
                axios.post('/project/ajax/' + this.app_state.project_id + '/update/',
                    "data=" + data_request
                ).then(response => {

                    this.project.project_id = response.data.project_id;

                    if (past_id == 0) {
                        text = 'Проект успешно создан'
                        this.showgo = true
                    } else {
                        text = 'Настройки проекта успешно сохранены'
                    }
                    this.$bvToast.toast(text, {
                        title: 'Проект сохранен',
                        autoHideDelay: 5000,
                        variant: 'success',
                        appendToast: true

                    })
                    this.loading = false

                }).catch(error => {
                    this.$bvToast.toast(`При сохранении проекта возникли проблемы`, {
                        title: 'Ошибка сохранения',
                        autoHideDelay: 5000,
                        variant: 'danger',
                        appendToast: true
                    })
                });
            },
            addTag(newTag, id) {
                const tag = {
                    mail: newTag,
                    id: 0,
                    name: null
                }
                this.all_user.push(tag)
                let type = 0;

                if (id == 'c2') {
                    this.project.cooker.push(tag)

                    let type = 2;

                } else if (id == 'a3') {
                    this.project.accountant.push(tag)
                    let type = 3;
                } else if (id == 'm4') {с
                    this.project.marketolog.push(tag)
                    let type = 4;
                }
                let user = {};
                user = this.saveUser(tag, type);

            },
            saveUser: function (user, type) {
                let data_request = JSON.stringify({
                    user: user,
                    type: type,
                    project: this.app_state.project_id
                });
                this.loading = true
                axios.post('/project/ajax/' + this.app_state.project_id + '/add_user/',
                    "data=" + data_request
                ).then(response => {
                    let added_user = {}
                    added_user = this.searchAddUser(user.mail)

                    added_user.id = response.data.user.id
                    added_user.name = response.data.user.name
                    this.$bvToast.toast('Новый пользователь успешно добавлен. Не забудьте сохранить проект.', {
                        title: 'Пользователь сохранен',
                        autoHideDelay: 5000,
                        variant: 'success',
                        appendToast: true

                    })
                    this.loading = false
                    return response.data.user
                }).catch(error => {

                    this.$bvToast.toast(`При сохранении пользователя возникли проблемы`, {
                        title: 'Ошибка сохранения',
                        autoHideDelay: 5000,
                        variant: 'danger',
                        appendToast: true
                    })
                    this.loading = false
                });
            },
            searchAddUser: function (mail) {
                let user_searched = this.all_user.filter(function (user) {

                    return user.id == 0 && user.mail == mail
                });
                return user_searched[0]
            }
        }
    }
</script >

<style scoped >

</style >