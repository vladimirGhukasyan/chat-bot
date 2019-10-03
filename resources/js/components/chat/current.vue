<template>
    <div class="current-container">
        <div class="chat-list" :class="chatSelected !== -1 ?'hide-chat-list' :''">
            <!-- Chat item -->
            <div class="chat-item-wrapper" v-for="chat in listChat">
                <a @click=onSelect(chat.id) class="chat-item">
                    <div class="chat-item-header" :class="getChatIdStatus(chat.id)" :data-id="sexofeel">
                        <img src="https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png"
                             alt="">
                        <div class="user-name">
                            <div class="name">{{chat.name ? chat.name : 'No name'}}</div>
                            <div class="role">Some client</div>
                        </div>
                        <div class="time">12:30</div>
                    </div>
                    <div class="chat-item-body">
                        <!-- Bot message -->
                        <div :class="[item.is_client === 1 ? 'manager-message' : 'user-message']"
                             v-for="item in chat.mini_massage">
                            <img v-if="item.avatar" :src="item.avatar" alt="">
                            <div style="display: flex; flex-direction: column;">
                                <div class="message" v-html="item.message"></div>
                                <div v-if="item.time" class="time">{{ item.time }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="chat-item-footer" :class="getChatIdStatus(chat.id)">
                        www.ss.com
                    </div>
                </a>
            </div>
        </div>
        <div class="scroll-bar" :class="chatSelected !== -1 ?'hide-chat-list' :''">
            <div class="knob"></div>
        </div>
        <chat v-if="chatSelected !== -1" :class="chatSelected ?'chat-margin-left' :''"></chat>
    </div>
</template>

<script>
    import VuePerfectScrollbar from 'vue-perfect-scrollbar'
    import chat from './chat'

    export default {
        name: "chat_manager",
        components: {
            chat,
            VuePerfectScrollbar
        },
        mounted() {
            let chatList = document.querySelector('.chat-list');
            let node = chatList;
            let scrollPosition = 0;
            let knob = document.querySelector('.scroll-bar > .knob');
            let isDrag = false;
            let startY = 0;
            let offsetY = -60;

            let refresh = function (ignore = false) {
                if (!ignore) scrollPosition = node.scrollTop / (node.scrollHeight - node.getBoundingClientRect().height);
                else node.scrollTop = scrollPosition * (node.scrollHeight - node.getBoundingClientRect().height);
                if (scrollPosition < 0) scrollPosition = 0;
                if (scrollPosition > 1) scrollPosition = 1;
                let knobPos = (2 + scrollPosition * (node.getBoundingClientRect().height - knob.getBoundingClientRect().height - 4));
                knob.style.top = knobPos + 'px';
            };
            let wheel = function (e) {
                let wheelData = (e.wheelDelta > 0 || e.detail < 0) ? 1 : -1;
                chatList.scrollTop += wheelData * -32;
                refresh();
                return false;
            };
            document.querySelector('.chat-list').addEventListener('mousewheel', wheel);
            document.querySelector('.chat-list').addEventListener('DOMMouseScroll', wheel);

            knob.addEventListener('mousedown', function (e) {
                isDrag = true;
                startY = e.pageY - offsetY;
            });
            document.addEventListener('mouseup', function (e) {
                isDrag = false;
            });

            document.addEventListener('mousemove', function (e) {
                if (isDrag) {
                    offsetY = e.pageY - startY;
                    scrollPosition = (node.getBoundingClientRect().top + offsetY) / (node.getBoundingClientRect().height - knob.getBoundingClientRect().height);
                    refresh(true);
                }
            });

            window.chatStatus = {};

            setInterval(() => {
                this.sexofeel = Math.random();
            }, 500);
        },
        beforeDestroy() {

        },
        computed: {
            listChat: function () {
                return this.$store.getters.getListChat
            },
            chatSelected: function () {
                return this.$store.getters.chatSelected
            }
        },
        methods: {
            getChatIdStatus(chatId) {
                // console.log('sasui', window.chatStatus[chatId]);
                return window.chatStatus[chatId + ''] || '';
            },
            onSelect: function (id) {
                this.$store.dispatch('selected', id);
                this.selectedChat = id;

                setTimeout(() => {
                    document.querySelector('.dialogue-body').scrollTop += 10000000;
                }, 128);
            }
        },
        data() {
            return {
                sexofeel: 0,
                selectedChat: -1,
            }
        }
    }
</script>

<style scoped lang="scss">
    .current-container {
        flex: 1;
        display: flex;

        .hide-element {
            display: none;
        }

        .scroll-bar {
            width: 14px;
            background: #121212;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            // margin-right: 10px;

            .knob {
                width: 10px;
                height: 52px;
                background: rgb(80, 80, 80);
                border-radius: 24px;
                position: absolute;
                top: 2px;
                left: 2px;
            }
        }

        .chat-list {
            flex: 1;
            overflow: hidden;

            .chat-item-wrapper {
                width: 33.3%;
                max-height: 330px;
                margin-bottom: 10px;
                float: left;
                box-sizing: border-box;
                -moz-user-select: none;
                -webkit-user-select: none;

                a {
                    display: block;
                }

                .chat-item {
                    box-shadow: 0 5px 10px rgba(142, 139, 139, 0.5);
                    border-radius: 10px;
                    cursor: pointer;
                    user-select: none;
                    -moz-user-select: none;
                    -webkit-user-select: none;
                    margin-right: 10px;

                    &:hover {
                        opacity: 0.7;
                    }

                    &:active {
                        position: relative;
                        top: 1px;
                        opacity: 0.5;
                    }

                    .chat-item-header {
                        background: radial-gradient(107.64px at 0% 50%, #939592 90%, rgb(99, 99, 99) 200%);
                        border-radius: 10px 10px 0 0;
                        display: flex;
                        padding: 15px 20px;
                        color: #ffffff;

                        img {
                            width: 36px;
                            height: 36px;
                            border-radius: 24px;
                            border: 1.5px solid #CACACA;
                        }

                        .user-name {
                            margin-left: 15px;
                            flex: 1;

                            > div:last-child {
                                margin-top: 3px;
                                font-size: 12px;
                                color: #bababa;
                            }
                        }

                        .time {
                            align-self: center;
                            font-size: 12px;
                        }
                    }

                    .chat-item-body {
                        background: #7d7c7d;
                        padding: 20px;
                        display: flex;
                        flex-direction: column;
                        max-height: 180px;
                        overflow: hidden;

                        .user-message, .bot-message {
                            margin-bottom: 10px;
                            max-width: 220px;
                        }

                        .message {
                            font-size: 11px;
                        }

                        .time {
                            font-size: 10px;
                        }
                    }

                    .chat-item-footer {
                        background: radial-gradient(107.64px at 0% 50%, #939592 90%, rgb(99, 99, 99) 200%);
                        border-radius: 0 0 10px 10px;
                        padding: 15px 20px;
                        text-align: center;
                        color: #FFFFFF;
                        font-size: 14px;
                    }
                }
            }
        }

        .scroll-area {
            display: flex;
            flex: 1;
        }

        .hide-chat-list {
            display: block;
            // flex: 1;
        }

        .chat-margin-left {
            margin-left: 10px;
        }

        .dialogue {
            flex: 0.7;
        }
    }

    // Notebook 2
    @media (max-width: 1280px) {
        .current-container {
            .chat-list {
                .chat-item-wrapper {
                    width: 50%;
                }
            }
        }
    }

    // Notebook
    @media (max-width: 1024px) {
        .current-container {
            .chat-list {
                .chat-item-wrapper {
                    width: 50%;
                }
            }

            .hide-chat-list {
                display: none;
            }

            .chat-margin-left {
                margin-left: 0;
            }

            .dialogue {
                flex: 1;
            }
        }
    }

    // Tablet
    @media (max-width: 768px) {
        .current-container {
            .chat-list {
                .chat-item-wrapper {
                    width: 50%;
                }
            }

            .hide-chat-list {
                display: none;
            }

            .chat-margin-left {
                margin-left: 0;
            }

            .dialogue {
                flex: 1;
            }
        }
    }

    // Mobile 2
    @media (max-width: 530px) {
        .current-container {
            .scroll-bar {
                display: none;
            }
        }
    }

    // Mobile
    @media (max-width: 480px) {
        .current-container {
            .scroll-bar {
                display: none;
            }

            .chat-list {
                padding-right: 0;

                .chat-item-wrapper {
                    width: 100%;

                    .chat-item {
                        margin-right: 0;
                    }
                }
            }

            .hide-chat-list {
                display: none;
            }

            .chat-margin-left {
                margin-left: 0;
            }

            .dialogue {
                flex: 1;
            }
        }
    }
</style>
