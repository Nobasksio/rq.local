<template >
    <div class="" >
        <div class="card" v-show="product.comments.length>0">
        <div class="card-body msg_card_body p-2" >
            <div class="d-flex justify-content-start mb-4" v-for="comment in product.comments" >
                <div class="img_cont_msg" >
                    <div class="rounded-circle round_user_letter d-flex align-items-center">
                        <div class="mx-auto">
                            {{ comment.user_name[0].toUpperCase() }}
                        </div>
                    </div>
                </div >
                <div class="msg_cotainer " >
                    {{ comment.text }}
                    <span class="msg_time" >{{ comment.date }}</span >
                </div >
            </div >
        </div >
        </div>
        <b-form-textarea
                id="textarea"
                v-model="new_comment"
                placeholder="Комментарий"
                class="font-12"
                rows="2"
                max-rows="6"
        ></b-form-textarea >
        <div class="text-center" >
            <b-button variant="light" class='my-2' size="sm" @click="saveComment()" >
                сохранить
                <b-spinner small v-show="loading" type="grow" ></b-spinner >
            </b-button >
        </div >
    </div >
</template >

<script >
    export default {
        name: "comment-block",
        props: {
            product: Array,
            place: Number
        },
        data: function () {
            return {
                new_comment: null,
                loading: false
            }
        },
        methods: {
            saveComment: function () {
                this.product.comments.push({
                    text: this.new_comment,
                    date:'только что',
                    user_name:'Ты'
                });
                this.loading = true;
                let data_request = JSON.stringify({
                    text: this.new_comment,
                    place: this.place
                });

                axios.post('/ajax/' + this.product.id + '/comment/',
                    "data=" + data_request
                ).then(response => {
                    this.loading = false;
                }).catch(error => {
                    this.loading = false;
                    this.$bvToast.toast('Ваш комментарий не сохранился! Что-то пошло не так! ', {
                        title: 'Комментарий не сохранился',
                        autoHideDelay: 5000,
                        appendToast: true,
                        variant: "warning"
                    });

                });
                this.new_comment = null;
            }
        }
    }
</script >

<style scoped >
    .msg_card_body {
        overflow-y: auto;
    }
    .round_user_letter{
        text-align: center;
        background: rgba(220, 220, 220, 0.25);
        Color:#00aaff;
        height: 40px;
        width: 40px;
    }
    .img_cont_msg {
        height: 40px;
        width: 40px;
    }

    .msg_cotainer {
        margin-top: auto;
        margin-bottom: auto;
        margin-left: 10px;
        border-radius: 25px;
        background-color: #1f82dd;
        padding: 10px;
        position: relative;
        min-width: 60px;
        color:#ffffff;
    }

    .msg_cotainer_send {
        margin-top: auto;
        margin-bottom: auto;
        margin-right: 10px;
        border-radius: 25px;
        background-color: #78e08f;
        padding: 10px;
        position: relative;
    }

    .msg_time {
        position: absolute;
        left: 0;
        bottom: -15px;
        color: rgba(49, 49, 49, 0.83);
        font-size: 10px;
    }

    .msg_time_send {
        position: absolute;
        right: 0;
        bottom: -15px;
        color: rgba(255, 255, 255, 0.5);
        font-size: 10px;
    }

    .msg_head {
        position: relative;
    }
    .card{
        max-height: 300px;

    }
    .font-12{
        font-size: 12px;
    }
</style >