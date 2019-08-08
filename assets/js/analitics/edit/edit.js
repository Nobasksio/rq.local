import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import Multiselect from "vue-multiselect";

Vue.use(BootstrapVue)
Vue.component('multiselect', Multiselect)

function makeForRadio(data) {
    var data = $.map(data, function (obj) {
        obj.value = obj.id;
        obj.name = obj.name_work || obj.name;
        obj.text = obj.name_work || obj.name;
        if (obj.alias != null) {
            obj.text = obj.alias;
        }
        return obj;
    });
    return data;
}

new Vue({
    delimiters: ['$[', ']'],
    el: '#edit_analitic',
    created: function () {

    },
    mounted: function () {

            this.categories = makeForRadio(this.categories)
    },
    data: {
        upcategories: [
            {'id': 1,'value': 1, 'name': 'Кухня', 'text': 'Кухня'},
            {'id': 2,'value': 2, 'name': 'Бар', 'text': 'Бар'}
        ],
        app_state: window.app_state,
        analitic_setting:{
            name: app_state.name,
            date_start:app_state.date_start,
            date_finish:app_state.date_finish,
            selected_departments:app_state.project_departments,
        },
        uploading: false,
        categories:app_state.categories,
        statushead:"Категория скрыта"
    },
    methods: {
        changeType(category_id) {
            let category = this.categories.filter(function(category) {
                return category.id == category_id ;
            });
            console.log(category[0].type);
            let data_request = JSON.stringify({type:category[0].typek});
            console.log(data_request);
            axios.post('/ajax/'+ this.app_state.project_id +'/analitics/category/'+category_id+'/update_field',
                "data=" + data_request
            ).then(response => {
                this.makeToast(category[0].name,3);

            }).catch(function (error) {

            });
        },
        changestatus(category_id) {
            console.log(11);
            this.uploading = true
            let analitic_setting = JSON.stringify(this.analitic_setting);
            axios.post('/ajax/'+ this.app_state.project_id +'/analitics/category/'+category_id+'/update_status'
            ).then(response => {
                this.show_link = true;
                var category = this.categories.filter(function(category) {
                    return category.id == category_id ;
                });
                console.log(category[0].name, category[0].status);
                this.makeToast(category[0].name,category[0].status,1);

            }).catch(function (error) {

            });
        },
        saveName(category_id){
            let category = this.categories.filter(function(category) {
                return category.id == category_id ;
            });
            let data_request = JSON.stringify({alias:category[0].alias,
            combine:category[0].combine});
            axios.post('/ajax/'+ this.app_state.project_id +'/analitics/category/'+category_id+'/update_field',
            "data=" + data_request
            ).then(response => {
                this.show_link = true;
                var category = this.categories.filter(function(category) {
                    return category.id == category_id ;
                });
                console.log(category[0].name, category[0].status);
                this.makeToast(category[0].name,category[0].status,2);

            }).catch(function (error) {

            });
        },
        makeToast(category_name,status,type,append = false) {

            if (type == 1) {
                if (status) {
                    this.statushead = 'Категория возвращена'
                    this.$bvToast.toast(`Категория ` + category_name + ` возвращена в отчет`, {
                        title: this.statushead,
                        autoHideDelay: 5000,
                        appendToast: append
                    })
                } else {
                    this.statushead = 'Категория скрыта'
                    this.$bvToast.toast(`Категория ` + category_name + ` больше  не будет отображаться в отчете`, {
                        title: this.statushead,
                        autoHideDelay: 5000,
                        appendToast: append
                    })
                }
            } else if (type == 2){
                this.statushead = 'Категория сохранены'
                this.$bvToast.toast(`Категория ` + category_name + ` успешно сохранена`, {
                    title: this.statushead,
                    autoHideDelay: 5000,
                    appendToast: append
                })

            } else if (type == 3){
                this.statushead = 'Тип категории сохранне'
                this.$bvToast.toast(`Тип категории ` + category_name + ` успешно сохранен`, {
                    title: this.statushead,
                    autoHideDelay: 5000,
                    appendToast: append
                })
            }
        }
    },
    watch: {},
    computed: {

    }
})