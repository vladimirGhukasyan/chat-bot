<template>
    <div class="main-body">
        <!-- MENU -->
        <div v-show="this.$store.getters.isMenuVisible" class="menu">
            <!-- MENU ITEM -->
            <div v-for="item in menu" class="menu-item">
                <!-- ICON -->
                <a @click="openCategory(item.name, !!item.categories)">
                    <img :src="item.image" :alt="item.name" :id="'menu-item-icon-' + item.name.toLowerCase()">
                    <div>{{ item.name }}</div>
                </a>

                <!-- SUB MENU -->
                <div class="sub-menu" v-if="selectedCategory === item.name && item.categories">
                    <div class="list">
                        <a @click="openSubCategory(subItem.name)"
                           v-for="subItem in item.categories"
                           :class="[(selectedCategory === item.name && selectedSubCategory === subItem.name) ?'shadowed' :'']">
                            <img :src="subItem.image" :id="'sub-menu-item-icon-' + subItem.name.toLowerCase()"
                                 :alt="subItem.name">
                            <div>{{ subItem.name }}</div>
                        </a>
                    </div>
                </div>
                <div class="gap" v-if="selectedCategory !== item.name || !item.categories">&nbsp;</div>
            </div>

            <!-- LINE -->
            <svg v-show="isShowLine" class="line" xmlns="http://www.w3.org/2000/svg">
                <line :x1="linePosition.line1.x1" :x2="linePosition.line1.x2" :y1="linePosition.line1.y1"
                      :y2="linePosition.line1.y2" stroke="#787878" stroke-width="0.25"></line>
                <line :x1="linePosition.line2.x1" :x2="linePosition.line2.x2" :y1="linePosition.line2.y1"
                      :y2="linePosition.line2.y2" stroke="#787878" stroke-width="0.25"></line>
                <line :x1="linePosition.line3.x1" :x2="linePosition.line3.x2" :y1="linePosition.line3.y1"
                      :y2="linePosition.line3.y2" stroke="#787878" stroke-width="0.25"></line>
            </svg>
        </div>

        <!-- CHAT LIST -->
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        props: {
            id: String,
            room_id: String,
            avatar: String,
            name: String,
        },
        data() {
            return {
                selectedCategory: '',
                selectedSubCategory: '',
                menu: [
                    {
                        name: 'chat',
                        image: '/assets/images/chat.png',
                        categories: [
                            {
                                name: 'current',
                                image: '/assets/images/current_icon.png',
                            },
                            {
                                name: 'archive',
                                image: '/assets/images/archive_icon.png',
                            }
                        ]
                    },
                    {
                        name: 'create',
                        image: '/assets/images/create.png',
                        categories: [
                            {
                                name: 'widget',
                                image: '/assets/images/widget_icon.png',
                            },
                            /*{
                                name: 'buttons',
                                image: '/assets/images/buttons_icon.png',
                            },*/
                            {
                                name: 'bots',
                                image: '/assets/images/bots_icon.png',
                            }
                        ]
                    },
                    {
                        name: 'assign',
                        image: '/assets/images/assign.png',
                        categories: [
                            {
                                name: 'agents',
                                image: '/assets/images/agents_icon.png',
                            },
                            {
                                name: 'botsapp',
                                image: '/assets/images/botsapp_icon.png',
                            }
                        ]
                    },
                    {
                        name: 'results',
                        image: '/assets/images/statistics.png'
                    },
                    {
                        name: 'settings',
                        image: '/assets/images/settings.png',
                        categories: [
                            {
                                name: 'account',
                                image: '/assets/images/account_icon.png',
                            },
                            {
                                name: 'premium',
                                image: '/assets/images/premium_icon.png',
                            },
                            {
                                name: 'notification',
                                image: '/assets/images/notification_icon.png',
                            },
                            {
                                name: 'share',
                                image: '/assets/images/share_icon.png',
                            }
                        ]
                    },
                    {
                        name: 'faq',
                        image: '/assets/images/faq.png'
                    }
                ],
                isShowLine: false,
                linePosition: {
                    line1: {x1: 0, x2: 0, y1: 0, y2: 0},
                    line2: {x1: 0, x2: 0, y1: 0, y2: 0},
                    line3: {x1: 0, x2: 0, y1: 0, y2: 0},
                }
            }
        },
        mounted() {
            window.id = this.id;
            window.room_id = this.room_id;
            window.avatar = this.avatar;
            window.name = this.name;

            // Parse path and open specific category
            let pathTuple = this.$router.history.current.path.split('/');
            if (pathTuple[2]) {
                this.openCategory(pathTuple[1]);
                this.openSubCategory(pathTuple[2]);
            } else this.openCategory(pathTuple[1], false);

            this.$options.sockets.onmessage = this.onMessage;

            // Wait until icons is loaded
            this.resolveMenuLineSVG();
            setTimeout(() => {this.resolveMenuLineSVG();}, 2000);
        },
        methods: {
            resolveMenuLineSVG: function () {
                let element1 = document.querySelector(`#menu-item-icon-${this.selectedCategory.toLowerCase()}`);
                let element2 = document.querySelector(`#sub-menu-item-icon-${this.selectedSubCategory.toLowerCase()}`);

                if (element1 && element2) {
                    this.linePosition.line1.x1 = element1.getBoundingClientRect().left + element1.getBoundingClientRect().width + 10;
                    this.linePosition.line1.x2 = element1.getBoundingClientRect().left + element1.getBoundingClientRect().width + 40;
                    this.linePosition.line1.y1 = element1.getBoundingClientRect().top + element1.getBoundingClientRect().height / 2;
                    this.linePosition.line1.y2 = element1.getBoundingClientRect().top + element1.getBoundingClientRect().height / 2;

                    this.linePosition.line2.x1 = element1.getBoundingClientRect().left + element1.getBoundingClientRect().width + 40;
                    this.linePosition.line2.x2 = element1.getBoundingClientRect().left + element1.getBoundingClientRect().width + 40;
                    this.linePosition.line2.y1 = element1.getBoundingClientRect().top + element1.getBoundingClientRect().height / 2;
                    this.linePosition.line2.y2 = element2.getBoundingClientRect().top + element2.getBoundingClientRect().height / 2;

                    this.linePosition.line3.x1 = element2.getBoundingClientRect().left + element2.getBoundingClientRect().width + 10;
                    this.linePosition.line3.x2 = element2.getBoundingClientRect().left + element2.getBoundingClientRect().width + 24;
                    this.linePosition.line3.y1 = element2.getBoundingClientRect().top + element2.getBoundingClientRect().height / 2;
                    this.linePosition.line3.y2 = element2.getBoundingClientRect().top + element2.getBoundingClientRect().height / 2;
                }
            },
            openCategory(category, hasSubCategory) {
                this.selectedCategory = category;
                this.isShowLine = false;
                if (!hasSubCategory) {
                    this.$router.push({
                        path: '/' + category,
                        query: {}
                    });
                }
            },
            openSubCategory(category) {
                this.selectedSubCategory = category;
                this.isShowLine = true;

                // Open page
                this.$router.push({
                    path: '/' + this.selectedCategory.toLowerCase() + '/' + category.toLowerCase(),
                    query: {}
                });
                this.resolveMenuLineSVG();
            },
            onMessage(data) {
                let response = JSON.parse(data.data);

                // Подключение
                if (response.type === 0) {
                    this.$socket.sendObj({
                        type: 1,
                        id: this.id,
                        room_id: this.room_id,
                        name: this.name,
                        avatar: this.avatar,
                    })
                }
                // Сообщение от пользователя
                else if (response.type === 2) {
                    this.$store.dispatch('addMessage', response);
                }
                // Принять пользователей в системе
                else if (response.type === 4) {
                    this.$store.dispatch('updateChat', response);
                }
                // Удалить пользователя
                else if (response.type === 10) {
                    this.$store.dispatch('removeChat', response);
                }
            }
        }
    }
</script>
<style>
    .shadowed img {
        -webkit-filter: drop-shadow(0px 0px 3px #26ff2d5c);
        filter: drop-shadow(0px 0px 3px #26ff2d5c);
    }
</style>
