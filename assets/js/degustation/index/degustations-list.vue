<template >
    <div >
        <b-row >
            <b-col sm="8" >
                <b-table hover
                         :items="degustations"
                         head-variant="light"
                         bordered="true"
                         class="table-all"
                         :fields="fields" >
                    <template slot="delet" slot-scope="data" >
                        <b-badge href="#" class='cursor-pointer' @click="deletDegustation(data.index)"
                                 v-if='data.item.status != 0' variant="light" >Удалить
                        </b-badge >
                        <b-badge href="#" class='cursor-pointer' @click="returnDegustation(data.index)" v-else
                                 variant="light" >Вернуть
                        </b-badge >
                    </template >
                    <template slot="link" slot-scope="data" >
                        <div class="text-center" >
                            <b-button size="sm"
                                      :pressed="false"
                                      v-show="!data.item.copied"
                                      class='mx-auto'
                                      @click='copyLink(data.item.link,data.item)'
                                      variant="outline-primary" >
                                Скопировать
                            </b-button >
                            <b-button size="sm"
                                      :pressed="false"
                                      v-show="data.item.copied"
                                      class='mx-auto'
                                      @click='copyLink(data.item.link,data.item)'
                                      variant="success" >
                                Скопировано
                            </b-button >
                        </div >
                    </template >
                    <template slot="redact" slot-scope="data" >

                        <b-button :href="'/project/'+project_id+'/degustation/'+data.item.id" size="sm" :pressed="false"
                                  variant="primary" >Редакт
                        </b-button >

                    </template >
                </b-table >
            </b-col >
        </b-row >
    </div >
</template >

<script >
    export default {
        name: "degustations-list",
        props: {
            degustations: Array,
            project_id: Number,
            fields: Array,
        },
        data: function () {
            return {
                name: null,
                consist: null,
                weight: null,
                cost_price: null,
                povar: null,
                selected_category: null,
                validationCategory: null,
                validationName: null,
                validationConsist: null,
                loading: false
            }
        },
        methods: {
            copyLink(link,data){
                let tmp   = document.createElement('INPUT'), // Создаём новый текстовой input
                    focus = document.activeElement; // Получаем ссылку на элемент в фокусе (чтобы не терять фокус)

                tmp.value = link; // Временному input вставляем текст для копирования

                document.body.appendChild(tmp); // Вставляем input в DOM
                tmp.select(); // Выделяем весь текст в input
                document.execCommand('copy'); // Магия! Копирует в буфер выделенный текст (см. команду выше)
                document.body.removeChild(tmp); // Удаляем временный input
                focus.focus(); // Возвращаем фокус туда, где был
                data.copied = true;
            },
            makeToast(append = false) {
                this.$bvToast.toast(`Чтобы она пропала из списка обновите страницу`, {
                    title: 'Дегустация удалена',
                    autoHideDelay: 5000,
                    appendToast: append
                })
            },
            makeToastReturn(append = false) {
                this.$bvToast.toast(`Все как было.`, {
                    title: 'Дегустация восстановлена',
                    autoHideDelay: 5000,
                    appendToast: append
                })
            },
            deletDegustation: function (index) {
                this.makeToast()
                this.degustations[index]._rowVariant = 'danger'
                axios.get('/ajax/' + this.project_id + '/degustation/update_status', {
                    params: {
                        degustation_id: this.degustations[index].id,
                        status: 0
                    }
                }).then(response => {
                    this.degustations[index].old_status = this.degustations[index].status
                    this.degustations[index].status = 0
                });

                //this.degustations.splice(index, 1);
            },
            returnDegustation: function (index) {
                this.makeToastReturn();
                axios.get('/ajax/' + this.project_id + '/degustation/update_status', {
                    params: {
                        degustation_id: this.degustations[index].id,
                        status: this.degustations[index].old_status
                    }
                }).then(response => {
                    this.degustations[index]._rowVariant = ''
                    this.degustations[index].status = this.degustations[index].old_status
                })
            }
        }
    }
</script >

<style scoped >
    .cursor-pointer {
        cursor: pointer;
    }
    .table-all{
        font-size: 12px;
    }
</style >