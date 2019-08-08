import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import Multiselect from "vue-multiselect";

Vue.use(BootstrapVue)
Vue.component('multiselect', Multiselect)

new Vue({
    delimiters: ['$[', ']'],
    el: '#newanalitic',
    created: function () {
        this.renameFields();
    },
    data: {
        upcategories: [
            {'id': 1, 'name': 'Кухня'},
            {'id': 2, 'name': 'Бар'}
        ],
        app_state: window.app_state,
        analitic_setting:{
            name:null,
            date_start:null,
            date_finish:null,
            selected_departments:app_state.project_departments,
        },
        link:null,
        show_link:false

    },
    methods: {
        renameFields() {
            this.categories = makeForRadio(this.categories);
            this.degustation.products = makeForRadio(this.degustation.products);
        },
        requestIiko(){
            this.uploading = true
            let analitic_setting = JSON.stringify(this.analitic_setting);
            axios.post('/ajax/project/'+ this.app_state.project_id +'/analitics/download',
                "analitic_setting=" + analitic_setting
            ).then(response => {
                this.link = response.data.analitic_id;
                this.show_link = true;

            }).catch(function (error) {
                console.log('FAILURE!!');

            });
        }
    },
    watch: {},
    computed: {

    }
})