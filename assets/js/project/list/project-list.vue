<template >
    <b-row >
        <my-toast order_id="123">
        </my-toast>
        <b-modal id="modal-1" v-model="modalShow" title="Удаление проекта" @ok="deletproject">
            <p class="my-4">Вы уверены что хотите удалить этот проект? </p>
        </b-modal>
        <b-col cols="6" >
            <ul class="pl-0" >
                <li :key="company.id"
                    v-for="company in companies" >Проекты компании «{{company.name}}»

                    <ul >

                        <li :key="project.id" class="py-2 name_project"
                            v-for="project in projects_in_company(company.id)" >
                            <div class="row" >
                                <div class="col-10" ><a :href="+project.id+'/summary'" >{{
                                    project.name }}</a >
                                </div >
                                <div class="col-1 icon_wrapper" >
                                    <a :href="+project.id+'/edit/'" >
                                        <svg id="i-edit" class='icon' xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 32 32"
                                             width="20"
                                             height="20" fill="none" stroke="currentcolor" stroke-linecap="round"
                                             stroke-linejoin="round" stroke-width="2" >
                                            <path d="M30 7 L25 2 5 22 3 29 10 27 Z M21 6 L26 11 Z M5 22 L10 27 Z" />
                                        </svg >
                                    </a >
                                </div >
                                <div class="col-1 icon_wrapper" @click="wantDelete(project.id,true)" >
                                    <svg id="i-trash" class='icon' xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 32 32" width="20"
                                         height="20" fill="none" stroke="currentcolor" stroke-linecap="round"
                                         stroke-linejoin="round"
                                         stroke-width="2" >
                                        <path d="M28 6 L6 6 8 30 24 30 26 6 4 6 M16 12 L16 24 M21 12 L20 24 M11 12 L12 24 M12 6 L13 2 19 2 20 6" />
                                    </svg >
                                </div >
                            </div >
                        </li >
                    </ul >
                </li >
            </ul >
            <ul class="pl-0" >
                <li class="mt-3" v-show="projects_out_company.length>0" >Индивидуальные проекты
                    <ul >
                        <li class="py-2 name_project" v-for="project in projects_out_company" :key="project.id" >
                            <div class="row" >
                                <div class="col-10" >
                                    <a :href="+project.id+'/summary'" >{{
                                        project.name }}
                                    </a >
                                </div >
                                <div class="col-1 icon_wrapper" >
                                    <a :href="+project.id+'/edit/'" >
                                        <svg id="i-edit" class='icon' xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 32 32"
                                             width="20"
                                             height="20" fill="none" stroke="currentcolor" stroke-linecap="round"
                                             stroke-linejoin="round" stroke-width="2" >
                                            <path d="M30 7 L25 2 5 22 3 29 10 27 Z M21 6 L26 11 Z M5 22 L10 27 Z" />
                                        </svg >
                                    </a >
                                </div >
                                <div class="col-1 icon_wrapper" @click="wantDelete(project.id,false)" >
                                    <svg id="i-trash" class='icon' xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 32 32" width="20"
                                         height="20" fill="none" stroke="currentcolor" stroke-linecap="round"
                                         stroke-linejoin="round"
                                         stroke-width="2" >
                                        <path d="M28 6 L6 6 8 30 24 30 26 6 4 6 M16 12 L16 24 M21 12 L20 24 M11 12 L12 24 M12 6 L13 2 19 2 20 6" />
                                    </svg >
                                </div >
                            </div >
                        </li >
                    </ul >
                </li >
            </ul >
        </b-col >
    </b-row >

</template >

<script >
    export default {
        name: "project-list",
        props: {
            projects: Array,
            companies: Array,
        },
        data:function(){
            return{
                modalShow:false,
                delet_project:null,
                loading:false
            }
        },
        methods: {
            getCompanyName: function (company_id) {
                let company_searched = this.companies.filter(function (company) {

                    return company.id == company_id;
                });
                return company_searched[0].name

            },
            projects_in_company: function (company_id) {
                let projects_searched = this.projects.filter(function (project) {

                    return project.company_id == company_id;
                });
                if (projects_searched.length == 0) {
                    return [];
                }


                return projects_searched;
            },
            wantDelete: function (project_id,isIn) {
                    this.delet_project = project_id,
                    this.modalShow = true
            },
            deletproject: function(){

                let data_request = JSON.stringify({
                    project_id:this.delet_project,
                });
                let self = this;
                this.loading = true
                axios.delete('/project/ajax/'+this.delet_project).then(response => {
                    let index = 0,
                        delet_project = {};

                    this.projects.forEach(function(item, i, arr) {
                        if (item.id == self.delet_project){
                            index = i;
                            delet_project = item;
                        }
                    });
                    console.log(index)
                    console.log(delet_project)


                    this.$bvToast.toast('Проект '+delet_project.name+' успешно удален.', {
                        title: ' Проект удален',
                        autoHideDelay: 5000,
                        variant: 'success',
                        appendToast: true

                    })

                    this.projects = this.projects.splice(index,1);
                    this.loading = false

                }).catch(error => {
                    console.log(error)
                    this.$bvToast.toast(`При удалении возникли проблемы`, {
                        title: 'Ошибка удаления',
                        autoHideDelay: 5000,
                        variant: 'danger',
                        appendToast: true
                    })
                    this.loading = false
                });
            }


        },
        computed: {
            projects_out_company: function () {
                let projects_searched = this.projects.filter(function (project) {

                    return project.company_id == 0;
                });

                if (projects_searched.length == 0) {
                    return [];
                }

                return projects_searched;
            }
        }
    }
</script >

<style scoped >
    li {
        list-style-type: none; /* Убираем маркеры */
    }
</style >