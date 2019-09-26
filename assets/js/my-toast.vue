<template >
    <div class="my-toast" v-if="show" >
        <div class="toast-content" >
            <div class="close-my" @click="close_toast" >
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.642 15.642"
                     xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 15.642 15.642" >
                    <path fill-rule="evenodd"
                          d="M8.882,7.821l6.541-6.541c0.293-0.293,0.293-0.768,0-1.061  c-0.293-0.293-0.768-0.293-1.061,0L7.821,6.76L1.28,0.22c-0.293-0.293-0.768-0.293-1.061,0c-0.293,0.293-0.293,0.768,0,1.061  l6.541,6.541L0.22,14.362c-0.293,0.293-0.293,0.768,0,1.061c0.147,0.146,0.338,0.22,0.53,0.22s0.384-0.073,0.53-0.22l6.541-6.541  l6.541,6.541c0.147,0.146,0.338,0.22,0.53,0.22c0.192,0,0.384-0.073,0.53-0.22c0.293-0.293,0.293-0.768,0-1.061L8.882,7.821z" />
                </svg >
            </div >
            <div class="toast-content-question" >
                <div >
                    Вы делали заказ 21.08.2019г.на 1240 руб<br >
                    Как бы вы его оценили?
                </div >
            </div >
            <div class="toast-content-starts" v-show="rait == null" >
                <div class="star"
                     ref="star1"
                     v-on:mouseover="mouseon(1)"
                     v-on:mouseout="mouseout(1)"
                     @click="make_choise(1)"
                >
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 487.222 487.222" style="enable-background:new 0 0 487.222 487.222;" xml:space="preserve">
                    <g>
                        <path d="M486.554,186.811c-1.6-4.9-5.8-8.4-10.9-9.2l-152-21.6l-68.4-137.5c-2.3-4.6-7-7.5-12.1-7.5l0,0c-5.1,0-9.8,2.9-12.1,7.6
                            l-67.5,137.9l-152,22.6c-5.1,0.8-9.3,4.3-10.9,9.2s-0.2,10.3,3.5,13.8l110.3,106.9l-25.5,151.4c-0.9,5.1,1.2,10.2,5.4,13.2
                            c2.3,1.7,5.1,2.6,7.9,2.6c2.2,0,4.3-0.5,6.3-1.6l135.7-71.9l136.1,71.1c2,1,4.1,1.5,6.2,1.5l0,0c7.4,0,13.5-6.1,13.5-13.5
                            c0-1.1-0.1-2.1-0.4-3.1l-26.3-150.5l109.6-107.5C486.854,197.111,488.154,191.711,486.554,186.811z M349.554,293.911
                            c-3.2,3.1-4.6,7.6-3.8,12l22.9,131.3l-118.2-61.7c-3.9-2.1-8.6-2-12.6,0l-117.8,62.4l22.1-131.5c0.7-4.4-0.7-8.8-3.9-11.9
                            l-95.6-92.8l131.9-19.6c4.4-0.7,8.2-3.4,10.1-7.4l58.6-119.7l59.4,119.4c2,4,5.8,6.7,10.2,7.4l132,18.8L349.554,293.911z"/>
                    </g>
                    </svg>
                </div >
                <div class="star"
                     ref="star2"
                     v-on:mouseover="mouseon(2)"
                     v-on:mouseout="mouseout(2)"
                     @click="make_choise(2)"
                >
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 487.222 487.222" style="enable-background:new 0 0 487.222 487.222;" xml:space="preserve">
                    <g>
                        <path d="M486.554,186.811c-1.6-4.9-5.8-8.4-10.9-9.2l-152-21.6l-68.4-137.5c-2.3-4.6-7-7.5-12.1-7.5l0,0c-5.1,0-9.8,2.9-12.1,7.6
                            l-67.5,137.9l-152,22.6c-5.1,0.8-9.3,4.3-10.9,9.2s-0.2,10.3,3.5,13.8l110.3,106.9l-25.5,151.4c-0.9,5.1,1.2,10.2,5.4,13.2
                            c2.3,1.7,5.1,2.6,7.9,2.6c2.2,0,4.3-0.5,6.3-1.6l135.7-71.9l136.1,71.1c2,1,4.1,1.5,6.2,1.5l0,0c7.4,0,13.5-6.1,13.5-13.5
                            c0-1.1-0.1-2.1-0.4-3.1l-26.3-150.5l109.6-107.5C486.854,197.111,488.154,191.711,486.554,186.811z M349.554,293.911
                            c-3.2,3.1-4.6,7.6-3.8,12l22.9,131.3l-118.2-61.7c-3.9-2.1-8.6-2-12.6,0l-117.8,62.4l22.1-131.5c0.7-4.4-0.7-8.8-3.9-11.9
                            l-95.6-92.8l131.9-19.6c4.4-0.7,8.2-3.4,10.1-7.4l58.6-119.7l59.4,119.4c2,4,5.8,6.7,10.2,7.4l132,18.8L349.554,293.911z"/>
                    </g>
                    </svg>
                </div >
                <div class="star"
                     ref="star3"
                     v-on:mouseover="mouseon(3)"
                     v-on:mouseout="mouseout(3)"
                     @click="make_choise(3)"
                >
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 487.222 487.222" style="enable-background:new 0 0 487.222 487.222;" xml:space="preserve">
                    <g>
                        <path d="M486.554,186.811c-1.6-4.9-5.8-8.4-10.9-9.2l-152-21.6l-68.4-137.5c-2.3-4.6-7-7.5-12.1-7.5l0,0c-5.1,0-9.8,2.9-12.1,7.6
                            l-67.5,137.9l-152,22.6c-5.1,0.8-9.3,4.3-10.9,9.2s-0.2,10.3,3.5,13.8l110.3,106.9l-25.5,151.4c-0.9,5.1,1.2,10.2,5.4,13.2
                            c2.3,1.7,5.1,2.6,7.9,2.6c2.2,0,4.3-0.5,6.3-1.6l135.7-71.9l136.1,71.1c2,1,4.1,1.5,6.2,1.5l0,0c7.4,0,13.5-6.1,13.5-13.5
                            c0-1.1-0.1-2.1-0.4-3.1l-26.3-150.5l109.6-107.5C486.854,197.111,488.154,191.711,486.554,186.811z M349.554,293.911
                            c-3.2,3.1-4.6,7.6-3.8,12l22.9,131.3l-118.2-61.7c-3.9-2.1-8.6-2-12.6,0l-117.8,62.4l22.1-131.5c0.7-4.4-0.7-8.8-3.9-11.9
                            l-95.6-92.8l131.9-19.6c4.4-0.7,8.2-3.4,10.1-7.4l58.6-119.7l59.4,119.4c2,4,5.8,6.7,10.2,7.4l132,18.8L349.554,293.911z"/>
                    </g>
                    </svg>
                </div >
                <div class="star"
                     ref="star4"
                     v-on:mouseover="mouseon(4)"
                     v-on:mouseout="mouseout(4)"
                     @click="make_choise(4)"
                >
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 487.222 487.222" style="enable-background:new 0 0 487.222 487.222;" xml:space="preserve">
                    <g>
                        <path d="M486.554,186.811c-1.6-4.9-5.8-8.4-10.9-9.2l-152-21.6l-68.4-137.5c-2.3-4.6-7-7.5-12.1-7.5l0,0c-5.1,0-9.8,2.9-12.1,7.6
                            l-67.5,137.9l-152,22.6c-5.1,0.8-9.3,4.3-10.9,9.2s-0.2,10.3,3.5,13.8l110.3,106.9l-25.5,151.4c-0.9,5.1,1.2,10.2,5.4,13.2
                            c2.3,1.7,5.1,2.6,7.9,2.6c2.2,0,4.3-0.5,6.3-1.6l135.7-71.9l136.1,71.1c2,1,4.1,1.5,6.2,1.5l0,0c7.4,0,13.5-6.1,13.5-13.5
                            c0-1.1-0.1-2.1-0.4-3.1l-26.3-150.5l109.6-107.5C486.854,197.111,488.154,191.711,486.554,186.811z M349.554,293.911
                            c-3.2,3.1-4.6,7.6-3.8,12l22.9,131.3l-118.2-61.7c-3.9-2.1-8.6-2-12.6,0l-117.8,62.4l22.1-131.5c0.7-4.4-0.7-8.8-3.9-11.9
                            l-95.6-92.8l131.9-19.6c4.4-0.7,8.2-3.4,10.1-7.4l58.6-119.7l59.4,119.4c2,4,5.8,6.7,10.2,7.4l132,18.8L349.554,293.911z"/>
                    </g>
                    </svg>
                </div >
                <div class="star"
                     ref="star5"
                     v-on:mouseover="mouseon(5)"
                     v-on:mouseout="mouseout(5)"
                     @click="make_choise(5)"
                >
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 487.222 487.222" style="enable-background:new 0 0 487.222 487.222;" xml:space="preserve">
                    <g>
                        <path d="M486.554,186.811c-1.6-4.9-5.8-8.4-10.9-9.2l-152-21.6l-68.4-137.5c-2.3-4.6-7-7.5-12.1-7.5l0,0c-5.1,0-9.8,2.9-12.1,7.6
                            l-67.5,137.9l-152,22.6c-5.1,0.8-9.3,4.3-10.9,9.2s-0.2,10.3,3.5,13.8l110.3,106.9l-25.5,151.4c-0.9,5.1,1.2,10.2,5.4,13.2
                            c2.3,1.7,5.1,2.6,7.9,2.6c2.2,0,4.3-0.5,6.3-1.6l135.7-71.9l136.1,71.1c2,1,4.1,1.5,6.2,1.5l0,0c7.4,0,13.5-6.1,13.5-13.5
                            c0-1.1-0.1-2.1-0.4-3.1l-26.3-150.5l109.6-107.5C486.854,197.111,488.154,191.711,486.554,186.811z M349.554,293.911
                            c-3.2,3.1-4.6,7.6-3.8,12l22.9,131.3l-118.2-61.7c-3.9-2.1-8.6-2-12.6,0l-117.8,62.4l22.1-131.5c0.7-4.4-0.7-8.8-3.9-11.9
                            l-95.6-92.8l131.9-19.6c4.4-0.7,8.2-3.4,10.1-7.4l58.6-119.7l59.4,119.4c2,4,5.8,6.7,10.2,7.4l132,18.8L349.554,293.911z"/>
                    </g>
                    </svg>
                </div >
            </div >
            <div class="thank_you" v-show="rait != null" >
                <div class="thank_you-text">
                    Спасибо за оценку
                </div >
                <div class="thank_you-helper">
                    Сообщение закроется через {{ currentTime }} сек.
                </div>
            </div >
        </div >
    </div >
</template >


<script >

    export default {
        name: "my-toast",
        props: ['order_id'],
        data: function () {
            return {
                rait: null,
                show: true,
                mytt:null,
                currentTime:5,
            }
        },
        methods: {
            make_choise(rait) {
                let start = 1;
                for (let i = start; i < 6; i++) {
                    let name = 'star' + i;
                    if (i < rait) {
                        this.$refs[name].classList.add('star-active')
                        this.$refs[name].classList.remove('tada')
                    } else if (i == rait) {
                        this.$refs[name].classList.add('star-active')
                        this.$refs[name].classList.add('tada')
                        this.rait = rait;
                    } else {
                        this.$refs[name].classList.remove('star-active')
                        this.$refs[name].classList.remove('tada')
                    }
                }
                this.startTimer();

            },
            mouseon(rait) {
                let start = 1;
                for (let i = start; i < 6; i++) {
                    let name = 'star' + i;
                    if (i <= rait) {
                        this.$refs[name].classList.add('star-active')
                    } else {
                        this.$refs[name].classList.remove('star-active')
                    }
                }
                console.log('myau')
            },
            mouseout(rait) {
                let start = 1;
                for (let i = start; i < 6; i++) {
                    let name = 'star' + i;
                    if (this.rait < i) {
                        this.$refs[name].classList.remove('star-active')
                    }
                }
            },
            close_toast() {
                this.show = false
            },
            startTimer() {
                this.mytt = setInterval(() => {
                    this.currentTime--
                }, 1000)
            },
            stopTimer() {
                clearTimeout(this.mytt)
            },
        },
        watch: {
            currentTime(time) {
                if (time === 0) {
                    this.stopTimer()
                    this.show = false
                }
            }
        }
    }
</script >

<style scoped >
    .my-toast {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 350px;
        margin: 30px;
        box-shadow: 0 0 35px rgba(0, 0, 0, 0.5);
    }

    .toast-content {
        padding: 10px 20px;
    }

    .toast-content-question {
        text-align: center;
        color: #000000;
        font-size: 13px;
    }

    .toast-content-starts {
        padding: 10px 0 10px;
        display: flex;
        justify-content: space-around;
    }

    .star {
        width: 45px;
    }

    .star-active svg {
        fill: #ce8714;
    }

    .star:hover svg {
        fill: #ce8714;
        cursor: pointer;
    }

    .close-my {
        width: 15px;
        float: right;
        margin-right: -10px;
        fill: #999999;
    }

    .close-my:hover svg {
        fill: #000000;
        cursor: pointer;
    }

    .tada {
        animation: tada 1s;
    }

    @-webkit-keyframes tada {
        from {
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
        }

        10%,
        20% {
            -webkit-transform: scale3d(0.9, 0.9, 0.9) rotate3d(0, 0, 1, -3deg);
            transform: scale3d(0.9, 0.9, 0.9) rotate3d(0, 0, 1, -3deg);
        }

        30%,
        50%,
        70%,
        90% {
            -webkit-transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, 3deg);
            transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, 3deg);
        }

        40%,
        60%,
        80% {
            -webkit-transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, -3deg);
            transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, -3deg);
        }

        to {
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
        }
    }

    @keyframes tada {
        from {
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
        }

        10%,
        20% {
            -webkit-transform: scale3d(0.9, 0.9, 0.9) rotate3d(0, 0, 1, -3deg);
            transform: scale3d(0.9, 0.9, 0.9) rotate3d(0, 0, 1, -3deg);
        }

        30%,
        50%,
        70%,
        90% {
            -webkit-transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, 3deg);
            transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, 3deg);
        }

        40%,
        60%,
        80% {
            -webkit-transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, -3deg);
            transform: scale3d(1.1, 1.1, 1.1) rotate3d(0, 0, 1, -3deg);
        }

        to {
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
        }
    }

    .tada {
        -webkit-animation-name: tada;
        animation-name: tada;
    }

    .thank_you {

    }
    .thank_you-text{
        padding: 10px 0 10px;
        font-weight: 800;
        color: #ce8714;
        text-align: center;
        font-size: 24px;
    }
    .thank_you-helper{
        color:#b3b3b3;
        font-size: 12px;
        text-align: center;
    }
</style >