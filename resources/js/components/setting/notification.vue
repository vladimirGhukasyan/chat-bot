<template>
    <div class="notification-container">
        <VuePerfectScrollbar class="scroll-area">
            <div class="notification-item" v-for="notification in notifications">
                <div class="checkbox" @click="notification.selected = !notification.selected">
                    <svg v-if="notification.selected" width="14" height="12" viewBox="0 0 14 12" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.795 1.06441C13.5216 0.791029 13.0784 0.791029 12.805 1.06441L4.4186 9.45093L1.19499 6.22732C0.921635 5.95394 0.478447 5.95397 0.205037 6.22732C-0.0683457 6.50068 -0.0683457 6.94387 0.205037 7.21725L3.92362 10.9358C4.1969 11.2091 4.64041 11.2089 4.91358 10.9358L13.795 2.05437C14.0684 1.78101 14.0683 1.33779 13.795 1.06441Z"
                            fill="#1D2122"/>
                    </svg>
                </div>
                <div class="message-container">
                    <div class="title">
                        <div>{{ notification.title }}</div>
                        <div>7 days ago</div>
                    </div>
                    <div class="desc">
                        {{ notification.description }}
                    </div>
                </div>
                <div class="buttons">
                    <button>open</button>
                    <button class="white">cancel</button>
                </div>
            </div>
        </VuePerfectScrollbar>
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
                notifications: [
                    {
                        id: 1,
                        selected: false,
                        title: 'New notification',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
                    },
                    {
                        id: 2,
                        selected: false,
                        title: 'New notification',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
                    },
                    {
                        id: 3,
                        selected: false,
                        title: 'New notification',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
                    },
                    {
                        id: 4,
                        selected: false,
                        title: 'New notification',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
                    },
                    {
                        id: 5,
                        selected: false,
                        title: 'New notification',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
                    },
                    {
                        id: 1,
                        selected: false,
                        title: 'New notification',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
                    },
                    {
                        id: 2,
                        selected: false,
                        title: 'New notification',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
                    },
                    {
                        id: 3,
                        selected: false,
                        title: 'New notification',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
                    },
                    {
                        id: 4,
                        selected: false,
                        title: 'New notification',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
                    },
                    {
                        id: 5,
                        selected: false,
                        title: 'New notification',
                        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
                    },
                ]
            }
        }
    }
</script>

<style lang="scss">
    .notification-container {
        background: url('/assets/images/dark-bg.png');
        flex: 1;
        background-size: cover;
        border-radius: 14px;
        padding: 20px;
        display: flex;
        overflow: hidden;
        flex-direction: column;

        .scroll-area {
            display: flex;
            flex: 1;
            flex-direction: column;
        }

        .notification-item {
            display: flex;
            background: #7D7D7D;
            padding: 20px;
            margin-bottom: 5px;
            border-radius: 10px;

            .checkbox {
                cursor: pointer;
                width: 32px;
                height: 32px;
                background: rgb(196, 196, 196) none repeat scroll 0% 0%;
                display: flex;
                align-content: center;
                align-items: center;
                justify-content: center;
                justify-items: center;
                border-radius: 8px;
                align-self: center;
                margin-right: 14px;
            }

            .message-container {
                flex: 1;
                margin-right: 10px;

                .title {
                    display: flex;

                    > div:first-child {
                        margin-right: 10px;
                        color: #ffffff;
                    }

                    > div:last-child {
                        color: #bebebe;
                    }
                }

                .desc {
                    margin-top: 10px;
                }
            }

            .buttons {
                width: 80px;
                display: flex;
                flex-direction: column;
                justify-content: space-around;

                button {
                    cursor: pointer;
                    background: #529E37;
                    border-radius: 6px;
                    border: 0;
                    color: #ffffff;
                    padding: 3px 8px;
                    margin-bottom: 5px;

                    &:hover {
                        opacity: 0.8;
                    }

                    &:active {
                        position: relative;
                        top: 1px;
                    }
                }

                .white {
                    background: #fefefe;
                    color: #2b2b2b;
                }
            }
        }
    }

    @media (max-width: 530px) {
        .notification-container {
            padding: 5px;

            .notification-item {
                padding: 10px;

                .buttons {
                    width: 50px;
                }
            }
        }
    }
</style>
