<template>
    <div class="premium-container">
        <!-- Left menu -->
        <div class="left-menu">
            <a @click.stop="openMenu(index)" class="btn" v-for="(item, index) in menuItems">
                <img :src="item.icon" :alt="item.name">
                <div class="title" :class="[selectedMenuId === index ?'' :'opacity-hidden']">{{ item.name }}</div>
            </a>
        </div>

        <!-- EMOTIONS -->
        <div v-if="selectedMenuId === 0" class="emotions-container">
            <VuePerfectScrollbar class="scroll-area">
                <div>
                    <div class="emoji-item" v-for="(sticker, index) in stickers"
                         :style="[index % 2 === 0 ?{paddingRight: '25px'} :{paddingLeft: '25px'}]">
                        <div class="icon">
                            <img :src="sticker.icon" :alt="sticker.title">
                        </div>
                        <div class="desc">
                            <div>{{ sticker.title }}</div>
                            <div>{{ sticker.amount }} stickers</div>
                        </div>
                        <div class="buttons">
                            <button class="blue">Buy</button>
                            <button>View</button>
                        </div>
                    </div>
                </div>
            </VuePerfectScrollbar>
        </div>

        <!-- SMS -->
        <div v-if="selectedMenuId === 1" class="sms-container">
            <VuePerfectScrollbar class="scroll-area" style="height: 100%;">
                <div class="sms-info">
                    <div class="radial" v-for="smsItem in smsInfo">
                        <svg width="200" height="200" viewPort="0 0 100 100" version="1.1"
                             xmlns="http://www.w3.org/2000/svg">
                            <circle r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48"
                                    stroke-dashoffset="0"></circle>
                            <circle id="bar" class="bar" r="90" cx="100" cy="100" fill="transparent"
                                    stroke-dasharray="565.48" stroke-dashoffset="45" stroke-width="10px"
                                    :stroke="smsItem.color"></circle>
                        </svg>
                        <div class="title">{{smsItem.amount}}</div>
                        <div class="desc">{{smsItem.title}}</div>
                    </div>
                </div>
                <div class="card-list">
                    <div v-for="itemSms in sms" class="card">
                        <div class="title">{{ itemSms.amount }}</div>
                        <div class="desc">SMS PACKAGE</div>
                        <div class="price" style="">${{itemSms.price}}</div>
                        <button>BUY A PACKAGE</button>
                    </div>
                </div>
            </VuePerfectScrollbar>
        </div>

        <!-- PLAN -->
        <div v-if="selectedMenuId === 2" class="plan-container">
            <VuePerfectScrollbar class="scroll-area">
                <div class="plan-info">
                    <div class="plan-card" v-for="plan in planCards" :style="[{backgroundColor: plan.color}]">
                        <div class="title">{{ plan.title }}</div>
                        <div class="gap"></div>
                        <div class="radial-list">
                            <div class="radial" v-for="planData in plan.data">
                                <svg width="80" height="80" viewPort="0 0 80 80" version="1.1"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <circle r="32" cx="40" cy="40" fill="transparent" stroke-dasharray="360"
                                            stroke-dashoffset="0" stroke-width="2px" stroke="#ffffff"></circle>
                                    <circle class="bar" r="32" cx="40" cy="40" fill="transparent" stroke-dasharray="360"
                                            stroke-dashoffset="290" stroke-width="7px" stroke="#ffffff"></circle>
                                </svg>
                                <div class="title">{{ planData }}</div>
                                <div class="desc">in stock</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-list">
                    <div class="card" v-for="plan in planList">
                        <div class="title">{{ plan.title }}</div>
                        <div class="price">${{ plan.price }}</div>
                        <div class="field">
                            <div>Red cards</div>
                            <div>{{ plan.red }}</div>
                        </div>
                        <div class="field">
                            <div>Burgundy cards</div>
                            <div>{{ plan.burgundy }}</div>
                        </div>
                        <button>BUY A PACKAGE</button>
                    </div>
                </div>
            </VuePerfectScrollbar>
        </div>
    </div>
</template>

<script>
    import VuePerfectScrollbar from 'vue-perfect-scrollbar'

    export default {
        components: {
            VuePerfectScrollbar
        },
        computed: {},
        mounted() {

        },
        methods: {
            openMenu(index) {
                this.selectedMenuId = index;
            }
        },
        data() {
            return {
                selectedMenuId: 0,
                menuItems: [
                    {
                        name: 'Emotions',
                        icon: '/assets/images/emotions_icon.png'
                    },
                    {
                        name: 'SMS',
                        icon: '/assets/images/sms_icon.png'
                    },
                    {
                        name: 'Notification card',
                        icon: '/assets/images/plan_icon.png'
                    },
                    {
                        name: 'Voice',
                        icon: '/assets/images/voice_icon.png'
                    }
                ],
                stickers: [
                    {id: 1, amount: 27, title: 'Happy hippo', icon: '/assets/images/emoji/sticker_hippo.png'},
                    {id: 1, amount: 27, title: 'Happy bunny', icon: '/assets/images/emoji/sticker_bunny.png'},
                    {id: 1, amount: 15, title: 'Red fox', icon: '/assets/images/emoji/sticker_redfox.png'},
                    {id: 1, amount: 15, title: 'Raccoon', icon: '/assets/images/emoji/sticker_raccoon.png'},
                    {id: 1, amount: 15, title: 'Surprised dog', icon: '/assets/images/emoji/sticker_surpdog.png'},
                    {id: 1, amount: 15, title: 'Happy dog', icon: '/assets/images/emoji/sticker_happydog.png'},
                    {
                        id: 1,
                        amount: 15,
                        title: 'Crazy pineapple',
                        icon: '/assets/images/emoji/sticker_crazypineapple.png'
                    },
                    {id: 1, amount: 15, title: 'Crazy ferret', icon: '/assets/images/emoji/sticker_crazyferret.png'},
                    {id: 1, amount: 15, title: 'Watermelon', icon: '/assets/images/emoji/sticker_watermelon.png'},
                    {id: 1, amount: 15, title: 'Fur seal', icon: '/assets/images/emoji/sticker_furseal.png'},
                    {id: 1, amount: 15, title: 'Happy beaver', icon: '/assets/images/emoji/sticker_happybeaver.png'},
                    {id: 1, amount: 15, title: 'Happy deer', icon: '/assets/images/emoji/sticker_happydeer.png'},
                    {id: 1, amount: 15, title: 'Happy hippo', icon: '/assets/images/emoji/sticker_tiger.png'},
                    {id: 1, amount: 15, title: 'Cherry bomb', icon: '/assets/images/emoji/sticker_cherry.png'},
                ],
                sms: [
                    {id: 1, amount: 100, price: 30},
                    {id: 2, amount: 1000, price: 50},
                    {id: 3, amount: 500, price: 60},
                ],
                smsInfo: [
                    {amount: 200, title: 'sms bought', color: '#1b9529'},
                    {amount: 55, title: 'sms send', color: '#af2e17'},
                    {amount: 145, title: 'sms left', color: '#1e73a5'}
                ],
                planList: [
                    {id: 1, title: 'Classic', price: 30, red: 10, burgundy: 16},
                    {id: 2, title: 'Premium', price: 50, red: 10, burgundy: 16},
                    {id: 3, title: 'Gold', price: 60, red: 10, burgundy: 16},
                    {id: 4, title: 'Platinum', price: 70, red: 10, burgundy: 16},
                ],
                planCards: [
                    {id: 1, title: 'BURGUNDY CARDS', data: [115, 80, 25], color: '#921515'},
                    {id: 2, title: 'RED CARDS', data: [115, 80, 25], color: '#D34143'},
                ]
            }
        }
    }
</script>

<style lang="scss" scoped>
    .premium-container {
        background: url('/assets/images/dark-bg.png');
        flex: 1;
        background-size: cover;
        border-radius: 14px;
        padding: 20px;
        display: flex;
        overflow: hidden;

        .left-menu {
            background: #23282B;
            width: 56px;
            height: 256px;
            align-items: center;
            justify-content: space-between;
            display: flex;
            flex-direction: column;
            border-radius: 12px;
            padding: 20px 2px;
            margin-right: 10px;

            .btn {
                cursor: pointer;
                display: flex;
                flex-direction: column;
                width: 100%;

                img {
                    width: 20px;
                    align-self: center;
                }

                .title {
                    background: rgba(196, 196, 196, 0.4);
                    text-align: center;
                    color: #fff;
                    font-size: 10px;
                    margin-top: 10px;
                    user-select: none;
                    -moz-user-select: none;
                    padding: 6px 0;

                    &::after {
                        background: rgba(196, 196, 196, 0.4);
                        width: 24px;
                        height: 24px;
                        position: relative;
                    }
                }
            }
        }

        .emotions-container {
            background: #23282B;
            border-radius: 10px;
            max-width: 590px;
            max-height: 580px;
            padding: 40px;
            color: #ffffff;
            overflow: hidden;

            .scroll-area {
                height: 100%;
            }

            .emoji-item {
                display: flex;
                float: left;
                width: 50%;
                margin-bottom: 35px;
                box-sizing: border-box;

                .icon {
                    width: 32px;

                    img {
                        width: 100%;
                    }
                }

                .desc {
                    padding: 0 25px;
                    flex: 1;

                    > div:first-child {
                        font-size: 14px;
                        margin-bottom: 5px;
                    }

                    > div:last-child {
                        font-size: 12px;
                        color: #c2c2c2;
                    }
                }

                .buttons {
                    display: flex;
                    flex-direction: column;
                    width: 70px;
                    padding-right: 15px;

                    button {
                        cursor: pointer;
                        background: #fff;
                        border: 0;
                        border-radius: 4px;
                        padding: 2px 5px;
                        font-size: 12px;
                        color: #2b2b2b;

                        &:hover {
                            opacity: 0.8;
                        }

                        &:active {
                            position: relative;
                            top: 1px;
                        }
                    }

                    .blue {
                        background: #2B70A3;
                        color: #fefefe;
                        margin-bottom: 5px;
                    }
                }
            }
        }

        .sms-container {
            flex: 1;
            margin-top: auto;
            margin-bottom: auto;
            height: 100%;

            .sms-info {
                display: flex;
                justify-content: space-around;
                height: 270px;

                .radial {
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    justify-items: center;
                    align-items: center;
                    height: 200px;
                    width: 200px;

                    svg {
                        position: absolute;
                        stroke-dashoffset: 0;
                        transition: stroke-dashoffset 1s linear;
                        stroke: #2E3233;
                        stroke-width: 2px;
                    }

                    .title {
                        font-size: 48px;
                        color: #FFFFFF;
                        font-weight: bold;
                        margin-bottom: 10px;
                    }

                    .desc {
                        font-size: 14px;
                        color: #FFFFFF;
                        text-transform: uppercase;
                    }
                }
            }

            .card-list {
                display: flex;
                justify-content: space-around;

                .card {
                    display: flex;
                    justify-content: space-around;
                    flex-direction: column;
                    width: 185px;
                    height: 205px;
                    border-radius: 6px;
                    background: rgb(255, 255, 255) none repeat scroll 0% 0%;
                    text-align: center;
                    padding: 20px;

                    .title {
                        font-size: 48px;
                        color: #1D2122;
                        font-weight: bold;
                    }

                    .desc {
                        color: #1D2122;
                        font-size: 14px;
                    }

                    .price {
                        color: #2B70A3;
                        font-size: 36px;
                        font-weight: 500;
                    }

                    button {
                        color: #fff;
                        background: #529E37;
                        font-size: 12px;
                        padding: 13px 25px;
                        border: 0;
                        border-radius: 6px;
                        cursor: pointer;

                        &:hover {
                            opacity: 0.8;
                        }

                        &:active {
                            position: relative;
                            top: 1px;
                        }
                    }
                }
            }
        }

        .plan-container {
            flex: 1;
            display: flex;
            flex-direction: column;

            .plan-info {
                margin-bottom: 35px;
                display: flex;
                justify-content: space-around;
                flex: 1;
                margin-top: 35px;

                .plan-card {
                    background: #921515;
                    display: flex;
                    flex-direction: column;
                    border-radius: 10px;
                    padding: 40px 10px;
                    width: 280px;
                    height: 140px;

                    .title {
                        font-size: 24px;
                        font-weight: bold;
                        color: #ffffff;
                        text-align: center;
                    }

                    .gap {
                        flex: 1;
                    }

                    .radial-list {
                        display: flex;
                        justify-content: space-around;
                    }
                }

                .radial {
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    justify-items: center;
                    align-items: center;
                    height: 80px;
                    width: 80px;

                    svg {
                        position: absolute;
                        stroke-dashoffset: 0;
                        transition: stroke-dashoffset 1s linear;
                        stroke: #2E3233;
                        stroke-width: 2px;
                        transform: rotate(-90deg);
                    }

                    .title {
                        font-size: 14px;
                        color: #FFFFFF;
                        font-weight: bold;
                        margin-bottom: 4px;
                    }

                    .desc {
                        font-size: 12px;
                        color: #FFFFFF;
                    }
                }
            }

            .card-list {
                display: flex;
                justify-content: space-around;
                flex: 1;
                margin-bottom: 35px;

                .card {
                    display: flex;
                    justify-content: space-around;
                    flex-direction: column;
                    width: 185px;
                    height: 245px;
                    border-radius: 6px;
                    background: rgb(255, 255, 255) none repeat scroll 0% 0%;
                    text-align: center;
                    padding: 20px;

                    .title {
                        font-size: 36px;
                        color: #1D2122;
                        font-weight: bold;
                    }

                    .price {
                        color: #2B70A3;
                        font-size: 36px;
                        font-weight: 500;
                    }

                    .field {
                        display: flex;

                        > div:first-child {
                            flex: 1;
                            text-align: left;
                        }

                        > div:last-child {
                            color: #2B70A3;
                            font-size: 12px;
                            font-weight: 500;
                        }
                    }

                    button {
                        color: #fff;
                        background: #529E37;
                        font-size: 12px;
                        padding: 15px 25px;
                        border: 0;
                        border-radius: 8px;
                        cursor: pointer;

                        &:hover {
                            opacity: 0.8;
                        }

                        &:active {
                            position: relative;
                            top: 1px;
                        }
                    }
                }
            }
        }
    }

    @media (max-width: 1200px) {
        .premium-container {
            .plan-container {
                .card-list {
                    .card {
                        width: 140px;
                    }
                }
            }
        }
    }

    @media (max-width: 904px) {
        .premium-container {
            .emotions-container {
                .emoji-item {
                    width: 100%;
                    padding-right: 0 !important;
                    padding-left: 0 !important;

                    .buttons {
                        padding-right: 0;
                    }
                }
            }

            .sms-container {
                .card-list {
                    .card {
                        width: 120px;
                    }
                }
            }

            .plan-container {
                .plan-info {
                    .plan-card {
                        width: 250px;
                    }
                }
                .card-list {
                    .card {
                        width: 110px;
                        padding: 10px;

                        .title {
                            font-size: 28px;
                        }
                    }
                }
            }
        }
    }

    @media (max-width: 530px) {
        .premium-container {
            flex-direction: column;
            background: none;
            padding: 0;

            .left-menu {
                width: 100%;
                height: 56px;
                margin-bottom: 10px;
                flex-direction: row;
                margin-right: 0;
            }

            .emotions-container {
                width: 100%;
                padding: 10px;
                box-sizing: border-box;
                max-height: 100%;

                //height: 100%;

                .emoji-item {
                    width: 100%;
                    padding-right: 0 !important;
                    padding-left: 0 !important;

                    .buttons {
                        padding-right: 0;
                    }
                }
            }

            .sms-container {
                height: 100%;
                overflow: hidden;

                .scroll-area {
                    height: 100%;
                }

                .sms-info {
                    height: auto;

                    .radial {
                        svg {
                            display: none;
                        }
                    }
                }

                .card-list {
                    flex-direction: column;

                    .card {
                        width: 100%;
                        box-sizing: border-box;
                        margin-bottom: 10px;
                    }
                }
            }

            .plan-container {
                height: 100%;
                overflow: hidden;

                .plan-info {
                    display: flex;
                    flex-direction: column;
                    margin-top: 0;
                    margin-bottom: 0;

                    .plan-card {
                        margin-bottom: 10px;
                        width: 100%;
                        box-sizing: border-box;
                        height: 200px;
                    }
                }

                .card-list {
                    flex-direction: column;
                    margin-bottom: 0;

                    .card {
                        width: 100%;
                        box-sizing: border-box;
                        margin-bottom: 10px;
                    }
                }
            }
        }
    }
</style>
