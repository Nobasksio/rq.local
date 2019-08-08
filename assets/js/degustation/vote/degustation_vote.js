import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import votepage from './vote-page'


Vue.use(BootstrapVue)
Vue.component('vote-page',votepage)


function makeForRadio(data) {
    var data = $.map(data, function (obj) {
        obj.value = obj.id;
        obj.name = obj.name_work || obj.name;
        obj.text = obj.name_work || obj.name;

        if (obj.saved == true) {
            obj.text =  "✔" + obj.text + " — готово";
        }
        return obj;
    });
    data = data.sort(function(a, b){
        if (a.saved == false && b.saved == true){
            return -1;
        } else if (a.saved == true && b.saved == false){
            return 1;
        } else {
            return 0;
        }
    })
    return data;
}

new Vue({
    delimiters: ['$[', ']'],
    el: '#vote',
    created: function () {
        this.renameFields();
    },


    data: {
        app_state: window.app_state,
        products: app_state.products,
        user_hash: app_state.user_hash,
        degustation_hash: app_state.degustation_hash


    },
    methods: {
        renameFields() {
            this.products = makeForRadio(this.products);
        }
    },
    watch: {},

    computed: {
        average_price: function () {
            let summ = 0;
            this.products.forEach(function(item, i, arr) {
                summ = summ + Number.parseInt(item.cost_price)
            });

            return Math.floor((summ/this.products.length)*5)
        }
    }
})