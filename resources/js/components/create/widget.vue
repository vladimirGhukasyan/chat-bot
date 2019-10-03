<template>
    <div class="design-container">
        <!-- Widget menu -->
        <div class="widget-list" v-show="!selectedWidgetId">
            <div class="widget-window">
                <div class="title">NEW WIDGET</div>
                <input id="new-widget-name" type="text" placeholder="Enter name of your new widget">
                <button @click="createNewWidget()">CREATE</button>
            </div>

            <div class="widget-window widget-created-window" v-for="widget in widgets" :style="[
                    widget.widget_body ?{background: widget.widget_body.backgroundImage === 'none' ?widget.widget_body.backgroundColor :widget.widget_body.backgroundImage} :{}
                ]">
                <div class="title">{{ widget.name }}</div>
                <div class="buttons">
                    <button @click="" :style="[widget.widget_bubble_admin ?{background: widget.widget_bubble_admin.backgroundColor, color: widget.widget_bubble_admin.color} :{}]">CLONE</button>
                    <div>&nbsp;</div>
                    <button @click="editWidget(widget.id)" :style="[widget.widget_bubble_admin ?{background: widget.widget_bubble_admin.backgroundColor, color: widget.widget_bubble_admin.color} :{}]">EDIT</button>
                    <div>&nbsp;</div>
                    <button @click="removeWidget(widget.id)" :style="[widget.widget_bubble_admin ?{background: widget.widget_bubble_admin.backgroundColor, color: widget.widget_bubble_admin.color} :{}]">DELETE</button>
                </div>
            </div>
        </div>

        <!-- Left menu -->
        <div v-show="selectedWidgetId" class="design-menu" style="position: absolute;z-index: 10;">
            <a @click.stop="openMenu(index)" class="btn" v-for="(item, index) in menuItems">
                <img :src="item.icon" :alt="item.name">
                <div class="title" :class="[selectedMenuId === index ?'' :'opacity-hidden']">{{ item.name }}</div>
            </a>
        </div>

        <!-- Long toolbar -->
        <div @click.stop="" v-if="selectedToolBarId" class="chat-setting-toolbar"
             :style="{ top: toolBarInfo[selectedToolBarId].top + 'px',
             left: toolBarInfo[selectedToolBarId].left + 'px', padding: toolBarInfo[selectedToolBarId].padding }">
            <!-- Items -->
            <div
                @click.stop="openComponent($event, item.id, item.component, item.data, item.field, item)"
                ref="item"
                class="item"
                :class="[selectedComponentId === item.id ?'selected' : '']"
                v-for="item in toolBarInfo[selectedToolBarId].tools">

                <!-- Button -->
                <div class="button" v-if="item.type === 'button'">
                    {{ item.title }}
                </div>

                <!-- Dropdown mini -->
                <div class="dropdown-mini" v-if="item.type === 'dropdown-mini'">
                    {{ getChatField(item.field) }}
                    <svg width="12" height="6" viewBox="0 0 12 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect y="0.972656" width="1.2" height="7.33" rx="0.6" transform="rotate(-53.1073 0 0.972656)" fill="black"/>
                        <rect x="5.83298" y="5.34082" width="1.2" height="6.91658" rx="0.6" transform="rotate(-129.689 5.83298 5.34082)" fill="black"/>
                    </svg>
                </div>

                <!-- Dropdown -->
                <div class="dropdown-big" v-if="item.type === 'dropdown'">
                    {{ getChatField(item.field) }}
                    <svg width="12" height="6" viewBox="0 0 12 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect y="0.972656" width="1.2" height="7.33" rx="0.6" transform="rotate(-53.1073 0 0.972656)" fill="black"/>
                        <rect x="5.83298" y="5.34082" width="1.2" height="6.91658" rx="0.6" transform="rotate(-129.689 5.83298 5.34082)" fill="black"/>
                    </svg>
                </div>

                <!-- Input -->
                <input v-if="item.type === 'input'" type="text" :value="getChatField(item.field)" @change="inputUpdate($event, item.field)">

                <!-- On off -->
                <onoff v-if="item.type === 'onoff'"></onoff>

                <!-- Just icon -->
                <a v-if="item.type === 'icon'" class="icon"><img :src="item.icon" alt=""></a>
            </div>
        </div>

        <!-- Theme list -->
        <div v-if="selectedMenuId === 0" style="position: absolute;z-index: 10;left: 180px;">
            <div class="chat-themes chat-themes-list-container">
                <div @click.stop="" class="chat-theme" :class="[`theme-` + chatTheme]" v-for="chatTheme in chatThemes">
                    <div class="title">{{ chatTheme }}</div>
                    <button>Cancel</button>
                    <button @click.stop="applyTheme(chatTheme)">Apply</button>
                </div>
            </div>
        </div>

        <!-- Chat settings sub menu -->
        <div v-if="selectedMenuId === 1" style="position: absolute;left: 180px;z-index: 10;">
            <!-- Item -->
            <div class="chat-setting">
                <a @click.stop="openToolBar(chatItem.toolBarId)"
                   :class="[selectedToolBarId === chatItem.toolBarId ?'selected' :'']"
                   v-for="chatItem in chatSettingsMenuItems">
                    <img :src="[`/assets/images/design/${chatItem.icon}.png`]" alt="">
                    <div class="title">{{ chatItem.title }}</div>
                </a>
            </div>
        </div>

        <!-- Chat at the right side -->
        <div v-if="!isButtonMode && selectedWidgetId" style="margin-left: auto;display: flex;flex: 1;" :style="{ justifyContent: chatModel.sizeAndLocation.align }">
            <div class="dialogue dialogue-design" style="flex: 0 1 auto;align-self: flex-end;" :class="chatModel.sizeAndLocation.embedType" :style="chatModel.sizeAndLocation">
                <!-- Dialogue header -->
                <div class="header" :style="chatModel.header">
                    <div class="profile-image">
                        <div class="online online-big">
                            <div class="user-photo" :style="chatModel.headerManagerPhoto"></div>
                        </div>
                    </div>
                    <div class="profile-name">
                        <div class="name">Chat</div>
                        <div class="role">Some client</div>
                    </div>

                    <div class="time">4:20</div>
                    <a class="close">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13.8338 13.0311L7.80265 6.99997L13.8337 0.968887C14.0554 0.747265 14.0554 0.387886 13.8337 0.166237C13.6121 -0.0554122 13.2527 -0.0554122 13.0311 0.166237L7 6.19732L0.968883 0.166237C0.747262 -0.0554122 0.387884 -0.0554122 0.166236 0.166237C-0.055412 0.387858 -0.055412 0.747238 0.166236 0.968887L6.19733 6.99997L0.166236 13.0311C-0.055412 13.2528 -0.055412 13.6121 0.166236 13.8338C0.387857 14.0554 0.747235 14.0554 0.968883 13.8338L7 7.80262L13.0311 13.8338C13.2527 14.0554 13.6121 14.0554 13.8338 13.8338C14.0554 13.6121 14.0554 13.2528 13.8338 13.0311Z"
                                fill="white"></path>
                        </svg>
                    </a>
                </div>

                <!-- Dialogue body, ie chat -->
                <div style="overflow: hidden; position: absolute; z-index: 0;pointer-events: none;display: flex;justify-content: center;" id="backgroundVideoContainer">
                    <video v-show="chatModel.backgroundVideo.url" autoplay muted loop id="backgroundVideo" style="align-self: center;z-index: -3;height: 100%;">
                        <source :src="chatModel.backgroundVideo.url" type="video/mp4">
                    </video>
                </div>

                <div class="dialogue-body" :style="[chatModel.body, chatModel.dialogueBody]">
                    <div
                        :class="[chatModel.bubble.animation, message.type === 'user' ?'user-message' :'bot-message']"
                        :style="chatModel.bubbleMain"
                        v-for="message in tempChatMessages">
                        <!-- PHOTO -->
                        <img v-if="message.photo" :src="message.photo" alt="photo">
                        <div>
                            <!-- MESSAGE -->
                            <div class="message"
                                 :class="chatModel.bubble.shape"
                                 :style="[chatModel.bubble,
                                 message.type === 'user' ?chatModel.bubbleClient :chatModel.bubbleAdmin]">
                                {{ message.data }}
                                <div :style="[
                                    message.type === 'user' ?{ color: chatModel.bubbleClient.backgroundColor }
                                    :{ color: chatModel.bubbleAdmin.backgroundColor }
                                    ]"></div>
                            </div>
                            <!-- TIME -->
                            <div class="time" :style="[
                                    message.type === 'user' ?{ color: chatModel.bubbleClient.color }
                                    :{ color: chatModel.bubbleAdmin.color }
                                    ]">{{ message.time }}</div>
                        </div>
                    </div>
                </div>

                <!-- Dialogue footer -->
                <div class="dialogue-footer" :style="chatModel.footer">
                    <div class="contact-list">Contacts</div>
                    <div class="percent-button" :style="{ backgroundColor: chatModel.footer.color }"></div>
                    <div class="attach-button" :style="{ backgroundColor: chatModel.footer.color }"></div>
                    <div class="message-input">
                        <input type="text" placeholder="Type a message">
                        <div class="buttons">
                            <div class="smile-button" :style="{ backgroundColor: chatModel.footer.color }"></div>
                            <div class="send-button" :class="chatModel.footer.send" :style="{ backgroundColor: chatModel.footer.color }"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button @click="saveWidgetOnServer()" v-if="!isButtonMode && selectedWidgetId" class="save-widget">
            Save widget
        </button>

        <!-- Chat tool -->
        <div id="chat-tool"><div></div></div>

        <!-- Button -->
        <div v-if="isButtonMode" class="open-chat-button" :class="chatModel.button.shape" :style="chatModel.button">
            <div class="text">{{ chatModel.button.text }}</div>
            <div class="corner" :style="[{ color: chatModel.button.backgroundColor }]"></div>
        </div>
    </div>
</template>

<script>
    import dropdown from '../tool-component/dropdown';
    import resize from '../tool-component/resize';
    import align from '../tool-component/align';
    import slider from '../tool-component/slider';
    import palette from '../tool-component/palette';
    import paletteduo from '../tool-component/paletteduo';
    import imgupload from '../tool-component/imgupload';
    import videoupload from '../tool-component/videoupload';
    import onoff from '../tool-component/onoff';
    import typespeed from '../tool-component/typespeed';
    import gradient from '../tool-component/gradient';

    let fonts = ['Helvetica', 'Verdana', 'Tahoma', 'Arial', 'Roboto'];
    let videoBackgroundIsChecked = false;
    const axios = require('axios');

    function snakeToCamel(string) {
        return string.replace(/(_\w)/g, function(m){
            return m[1].toUpperCase();
        });
    }

    function camelToSnake(string) {
        return string.replace(/[\w]([A-Z])/g, function(m) {
            return m[0] + "_" + m[1];
        }).toLowerCase();
    }

    export default {
        components: {onoff},
        computed: {},
        mounted() {
            let parent = this;
            // Close component if click at any area except component
            document.addEventListener('click', function (e) {
                if (parent.selectedMenuId === 1) {
                    parent.selectedComponentId = '';
                    parent.selectedToolBarId = null;
                    if (document.querySelector(`#chat-tool`))
                        document.querySelector(`#chat-tool`).innerHTML = '<div></div>';
                } else {
                    parent.selectedComponentId = '';
                    parent.selectedToolBarId = null;
                    parent.selectedMenuId = -1;
                    if (document.querySelector(`#chat-tool`))
                        document.querySelector(`#chat-tool`).innerHTML = '<div></div>';
                }
            });
            document.getElementById('chat-tool').addEventListener('click', function (e) {
                e.stopPropagation();
            });

            // Get unsaved theme
            if (localStorage.getItem(`tempTheme`)) {
                this.widgets.push({ name: '* unsaved', id: -2 });
            }

            let s = false;
            let lastChatH = 0;
            setInterval(function () {
                if (parent.chatModel.backgroundVideo.url) {
                    parent.chatModel.body.backgroundImage = 'none';
                    parent.chatModel.body.backgroundColor = 'transparent';
                    let video = document.querySelector(`#backgroundVideo`);
                    let chat = document.querySelector(`.dialogue-body`).getBoundingClientRect();
                    let videoRect = document.querySelector(`#backgroundVideo`).getBoundingClientRect();
                    if (!video.videoWidth) s = false;
                    lastChatH = chat.height;

                    if (video.videoWidth > video.videoHeight && !s) {
                        s = true;
                    }

                    let contRect = document.querySelector(`#backgroundVideoContainer`).getBoundingClientRect();
                    document.querySelector(`#backgroundVideoContainer`).style.left = chat.left + 'px';
                    document.querySelector(`#backgroundVideoContainer`).style.top = chat.top + 'px';
                    document.querySelector(`#backgroundVideoContainer`).style.width = chat.width + 'px';
                    document.querySelector(`#backgroundVideoContainer`).style.height = chat.height + 'px';

                    if (videoRect.width < contRect.width) {
                        document.querySelector(`#backgroundVideo`).style.height = 100 * (contRect.width / videoRect.width) + '%';
                    }

                    // let rect = document.querySelector(`#backgroundVideo`).getBoundingClientRect();
                    // document.querySelector(`#backgroundVideo`).style.marginBottom = '-' + rect.height + 'px';
                }
            }, 100);

            this.loadWidgetList();

            window.onpopstate = (event) => {
                if (window.location.hash === '#/create/widget') {
                    this.selectedWidgetId = 0;
                }
                if (window.location.hash.match(/#\/create\/widget\/(\d+)/)) {
                    let match = window.location.hash.match(/#\/create\/widget\/(\d+)/);
                    console.log(match);
                    //this.selectedWidgetId = match[1] * 1;
                    this.editWidget(match[1], false);
                }
            };
        },
        methods: {
            openMenu(index) {
                if (this.selectedMenuId === index) this.selectedMenuId = -1;
                else this.selectedMenuId = index;

                this.selectedToolBarId = null;
                this.selectedComponentId = null;
                document.querySelector(`#chat-tool`).innerHTML = '<div></div>';

                this.isButtonMode = false;

                // Button settings
                if (this.selectedMenuId === 2) {
                    this.selectedToolBarId = 'chatButtonSettings';
                    this.isButtonMode = true;
                }

                // Typing emulation
                if (this.selectedMenuId === 3)
                    this.selectedToolBarId = 'chatTypingEmulation';
            },
            openToolBar(index) {
                if (this.selectedToolBarId === index) this.selectedToolBarId = null;
                else this.selectedToolBarId = index;
                document.querySelector(`#chat-tool`).innerHTML = '<div></div>';
            },
            openComponent(event, id, component, data, field, params) {
                let parent = this;

                // Close opened component
                if (this.selectedComponentId === id) {
                    this.selectedComponentId = null;
                    document.querySelector(`#chat-tool`).innerHTML = `<div></div>`;
                    return;
                }

                // Check if component exists
                this.selectedComponentId = id;
                if (!component) {
                    this.selectedComponentId = '';
                    document.querySelector(`#chat-tool`).innerHTML = `<div></div>`;
                    return;
                }

                // Create new component and mount it
                const CmpCtor = Vue.extend(component);
                const vm = new CmpCtor({
                    data: function () {
                        return {
                            params: params.params || {},
                            selected: params.getter ?params.getter() :parent.getChatField(field),
                            selected1: params.getter1 ?params.getter1() :parent.getChatField(field, 1),
                            selected2: params.getter2 ?params.getter2() :parent.getChatField(field, 2),
                            data
                        }
                    }
                }).$mount('#chat-tool > div');

                // Parse field path
                let paramList = field.split(',');
                let paramOut = [];
                for (let i = 0; i < paramList.length; i++) {
                    paramOut.push([
                        paramList[i].split(`.`)[0],
                        paramList[i].split(`.`)[1],
                    ]);
                }

                // Listen component data event
                vm.$on('data', function (data, data1, data2) {
                    // Set data to field
                    if (data !== undefined) {
                        if (params.setter && paramOut[0]) parent.chatModel[paramOut[0][0]][paramOut[0][1]] = params.setter(data);
                        else parent.chatModel[paramOut[0][0]][paramOut[0][1]] = data;
                    }
                    if (data1 !== undefined && paramOut[1]) {
                        if (params.setter1) parent.chatModel[paramOut[1][0]][paramOut[1][1]] = params.setter1(data1);
                        else parent.chatModel[paramOut[1][0]][paramOut[1][1]] = data1;
                    }
                    if (data2 !== undefined && paramOut[2]) {
                        if (params.setter2) parent.chatModel[paramOut[2][0]][paramOut[2][1]] = params.setter2(data2);
                        else parent.chatModel[paramOut[2][0]][paramOut[2][1]] = data2;
                    }

                    // Save temp result
                    parent.saveWidgetLocally();
                });

                // Move component to needed position
                let tool = document.querySelector(`#chat-tool`);
                let icon = event.target;
                if (icon) {
                    tool.style.left = icon.getBoundingClientRect().left + 'px';
                    tool.style.top = (icon.getBoundingClientRect().top + 60) + 'px';
                }
            },
            getChatField(field, index = 0) {
                let fields = field.split(',');
                if (!fields[index]) return null;
                let fieldTuple = fields[index].split(`.`);
                let model = fieldTuple[0];
                let modelField = fieldTuple[1];
                return this.chatModel[model][modelField];
            },
            createNewWidget() {
                if (!document.querySelector('#new-widget-name').value) return;

                this.currentName = document.querySelector('#new-widget-name').value;
                this.selectedWidgetId = -1;
                this.widgets.push({
                    id: -1,
                    name: document.querySelector('#new-widget-name').value,
                    model: JSON.parse(JSON.stringify(this.chatModel))
                });
                window.location = `/#/create/widget/-1`;
                document.querySelector('#new-widget-name').value = '';
                //this.checkVideoBackground();
            },
            editWidget(id, goToLocation = true) {
                id *= 1;
                if (id === -2) {
                    let theme = localStorage.getItem(`tempTheme`);

                    try {
                        this.chatModel = JSON.parse(theme);
                        this.selectedWidgetId = id;
                        //this.checkVideoBackground();
                    }
                    catch (e) {

                    }
                } else {
                    axios.get(`/client-widget/${id}`)
                        .then((response) => {
                            let tempModel = {};
                            for (let s in response.data) {
                                if (s.match('widget_')) {
                                    tempModel[snakeToCamel(s.replace('widget_', ''))] = response.data[s];
                                }
                            }
                            this.chatModel = tempModel;
                            this.selectedWidgetId = id;
                            this.currentName = response.data.name;
                            if (goToLocation)
                                window.location = `/#/create/widget/${id}`;
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }
            },
            removeWidget(id) {
                id *= 1;
                if (id === -2) localStorage.setItem(`tempTheme`, '');
                for (let i = 0; i < this.widgets.length; i++) {
                    if (this.widgets[i].id === id) {
                        axios.delete(`/client-widget/${id}`);
                        this.widgets.splice(i, 1);
                        break;
                    }
                }
            },
            inputUpdate(event, field) {
                let fieldType = field.split('.');
                this.chatModel[fieldType[0]][fieldType[1]] = event.target.value;
            },
            applyTheme(theme) {
                if (theme === 'red') {
                    this.chatModel.header.backgroundColor = `#E70955`;
                    this.chatModel.body.backgroundImage = `none`;
                    this.chatModel.body.backgroundColor = `#f2edf1`;
                    this.chatModel.footer.backgroundColor = `#2b2b2b`;
                    this.chatModel.footer.color = `#cecece`;
                }
                if (theme === 'orange') {
                    this.chatModel.header.backgroundColor = `rgb(237, 138, 53)`;
                    this.chatModel.body.backgroundImage = `none`;
                    this.chatModel.body.backgroundColor = `#f2d0b2`;
                    this.chatModel.footer.backgroundColor = `rgb(135, 82, 3)`;
                    this.chatModel.footer.color = `rgb(227, 217, 133)`;
                }
                if (theme === 'business') {
                    this.chatModel.header.backgroundColor = `rgb(66, 133, 237)`;
                    this.chatModel.body.backgroundImage = `none`;
                    this.chatModel.body.backgroundColor = `#fdf0f1`;
                    this.chatModel.footer.backgroundColor = `rgb(44, 42, 135)`;
                    this.chatModel.footer.color = `rgb(141, 153, 227)`;
                }
                if (theme === 'sky') {
                    this.chatModel.header.backgroundColor = `rgb(118, 204, 237)`;
                    this.chatModel.body.backgroundImage = `none`;
                    this.chatModel.body.backgroundColor = `#e9e6f2`;
                    this.chatModel.footer.backgroundColor = `rgb(97, 136, 156)`;
                    this.chatModel.footer.color = `#fefefe`;
                }
                this.saveWidgetLocally();
            },
            loadWidgetList() {
                axios.get('/client-widget')
                    .then((response) => {
                        console.log(response);
                        for (let i = 0; i < response.data.length; i++) {
                            this.widgets.push({
                                ...response.data[i]
                                //name: response.data[i].name,
                                //id: response.data[i].id
                            });
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            saveWidgetLocally() {
                /*let parent = this;
                parent.isNeedToSaveLocalWidget = true;
                if (!parent.isLocalWidgetSaveTimerInit) {
                    parent.isLocalWidgetSaveTimerInit = true;

                    // Set timer to save data
                    setInterval(function () {
                        if (parent.isNeedToSaveLocalWidget) {
                            parent.isNeedToSaveLocalWidget = false;
                            localStorage.setItem(`tempTheme`, JSON.stringify(parent.chatModel));
                            console.log('Saved local copy');
                        }
                    }, 250);
                }*/
            },
            saveWidgetOnServer() {
                let model = JSON.parse(JSON.stringify(this.chatModel));
                let modelNew = {
                    name: this.currentName
                };
                if (this.selectedWidgetId > 0)
                    modelNew.id = this.selectedWidgetId;

                for (let s in model) {
                    modelNew['widget_' + camelToSnake(s)] = model[s];
                }
                console.log(modelNew);

                axios.post('/client-widget', modelNew)
                    .then((response) => {
                        this.selectedWidgetId = 0; //response.data.id;
                        this.selectedMenuId = -1; //response.data.id;
                        // console.log(response);
                        window.history.back();
                        this.widgets = [];
                        this.loadWidgetList();
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        data() {
            let parent = this;

            return {
                selectedMenuId: -1,
                selectedToolBarId: null,
                selectedComponentId: null,
                selectedWidgetId: 0,
                isButtonMode: false,

                isNeedToSaveLocalWidget: false,
                isLocalWidgetSaveTimerInit: false,
                currentName: '',

                menuItems: [
                    {
                        name: 'Themes',
                        icon: '/assets/images/design/themes.png'
                    },
                    {
                        name: 'Chat settings',
                        icon: '/assets/images/design/chat-stg.png'
                    },
                    {
                        name: 'Button settings',
                        icon: '/assets/images/design/button-stg.png'
                    },
                    /*{
                        name: 'Typing emulation',
                        icon: '/assets/images/design/typing-emulation.png'
                    }*/
                ],
                chatThemes: ['red', 'orange', 'business', 'sky', 'velours', 'neon', 'summertime', 'meetings', 'nature'],
                chatSettingsMenuItems: [
                    {
                        title: 'Size & Location',
                        icon: 'size-and-location',
                        toolBarId: 'sizeAndLocation'
                    },
                    {
                        title: 'Header',
                        icon: 'header',
                        toolBarId: 'chatHeader'
                    },
                    {
                        title: 'Body',
                        icon: 'body',
                        toolBarId: 'chatBody'
                    },
                    {
                        title: 'Footer',
                        icon: 'footer',
                        toolBarId: 'chatFooter'
                    },
                    {
                        title: 'Bubble settings',
                        icon: 'button',
                        toolBarId: 'chatBubbleSettings'
                    }
                ],
                toolBarInfo: {
                    'sizeAndLocation': {
                        left: 257,
                        top: 89,
                        tools: [
                            {
                                id: 'chatEmbedType',
                                type: 'icon',
                                icon: `/assets/images/design/embed-mode.png`,
                                component: dropdown,
                                data: [
                                    'Full page',
                                    'Embed',
                                    'Classic',
                                ],
                                field: 'sizeAndLocation.embedType',
                                setter: function (val) {
                                    if (val === 'Full page') {
                                        parent.chatModel.sizeAndLocation.width = '100%';
                                        parent.chatModel.sizeAndLocation.height = '100%';
                                    }
                                    if (val === 'Embed') {
                                        parent.chatModel.sizeAndLocation.width = '100%';
                                        parent.chatModel.sizeAndLocation.height = '450px';
                                    }
                                    if (val === 'Classic') {
                                        parent.chatModel.sizeAndLocation.width = '420px';
                                        parent.chatModel.sizeAndLocation.height = '450px';
                                    }

                                    parent.chatModel.dialogueBody.paddingTop = `10px`;
                                    parent.chatModel.dialogueBody.paddingBottom = `10px`;
                                    parent.chatModel.dialogueBody.paddingLeft = `25px`;
                                    parent.chatModel.dialogueBody.paddingRight = `25px`;
                                    if (val === 'Full page') return 'chat-full-page';
                                    if (val === 'Embed') return 'chat-embed';
                                    return 'chat-classic';
                                }
                            },
                            {
                                id: 'chatSize',
                                type: 'icon',
                                icon: `/assets/images/design/size-and-location.png`,
                                component: resize,
                                field: 'sizeAndLocation.width,sizeAndLocation.height',
                                setter: function (val) {
                                    if (parent.chatModel.sizeAndLocation.embedType === 'chat-full-page') {
                                        parent.chatModel.dialogueBody.paddingLeft = `calc(25px + ${val * 35}%)`;
                                        parent.chatModel.dialogueBody.paddingRight = `calc(25px + ${val * 35}%)`;
                                        return '100%';
                                        // return `${30 + val * 70}%`;
                                    }
                                    if (parent.chatModel.sizeAndLocation.embedType === 'chat-embed') {
                                        parent.chatModel.dialogueBody.paddingLeft = `calc(25px + ${val * 35}%)`;
                                        parent.chatModel.dialogueBody.paddingRight = `calc(25px + ${val * 35}%)`;
                                        return '100%';
                                    }
                                    if (parent.chatModel.sizeAndLocation.embedType === 'chat-classic') {
                                        return `${400 + val * 500}px`;
                                    }
                                    return `${15 + val * 70}%`;
                                },
                                setter1: function (val) {
                                    if (parent.chatModel.sizeAndLocation.embedType === 'chat-full-page') {
                                        //parent.chatModel.dialogueBody.paddingTop = `calc(10px + ${val * 35}%)`;
                                        //parent.chatModel.dialogueBody.paddingBottom = `calc(10px + ${val * 35}%)`;
                                        return '100%';
                                        // return `${30 + val * 70}%`;
                                    }
                                    if (parent.chatModel.sizeAndLocation.embedType === 'chat-embed') {
                                        return `${400 + val * 300}px`;
                                    }
                                    if (parent.chatModel.sizeAndLocation.embedType === 'chat-classic') {
                                        return `${400 + val * 300}px`;
                                    }
                                    return `${30 + val * 60}%`;
                                },
                                getter: function () {
                                    if (parent.chatModel.sizeAndLocation.embedType === 'chat-full-page') {
                                        let t = parent.chatModel.dialogueBody.paddingTop.split(' ');
                                        if (t.length < 2) return 0;
                                        return Number.parseFloat(t[2].slice(0, -2)) / 35;
                                    }
                                    if (parent.chatModel.sizeAndLocation.embedType === 'chat-embed') {
                                        let t = parent.chatModel.dialogueBody.paddingLeft.split(' ');
                                        if (t.length < 2) return 0;
                                        return Number.parseFloat(t[2].slice(0, -2)) / 35;
                                    }
                                    if (parent.chatModel.sizeAndLocation.embedType === 'chat-classic') {
                                        let sizeOut = (Number.parseFloat(parent.chatModel.sizeAndLocation.width) - 400) / 500;
                                        if (sizeOut < 0) sizeOut = 0;
                                        if (sizeOut > 1) sizeOut = 1;
                                        return sizeOut;
                                    }
                                    return (Number.parseFloat(parent.chatModel.sizeAndLocation.width) - 30) / 70;
                                },
                                getter1: function () {
                                    if (parent.chatModel.sizeAndLocation.embedType === 'chat-classic'
                                    || parent.chatModel.sizeAndLocation.embedType === 'chat-embed') {
                                        let sizeOut = (Number.parseFloat(parent.chatModel.sizeAndLocation.width) - 400) / 300;
                                        if (sizeOut < 0) sizeOut = 0;
                                        if (sizeOut > 1) sizeOut = 1;
                                        return sizeOut;
                                    }
                                    return (Number.parseFloat(parent.chatModel.sizeAndLocation.height) - 40) / 60;
                                }
                            },
                            {
                                id: 'chatAlign',
                                type: 'icon',
                                icon: `/assets/images/design/align.png`,
                                component: align,
                                field: 'sizeAndLocation.align'
                            },
                            {
                                id: 'chatShadowOpacity',
                                type: 'icon',
                                icon: `/assets/images/design/shadow-opacity.png`,
                                component: slider,
                                field: 'sizeAndLocation.boxShadow',
                                setter: function (val) {
                                    return `0 5px 10px rgba(142, 139, 139, ${val})`;
                                },
                                getter: function () {
                                    return parent.chatModel.sizeAndLocation.boxShadow.split(' ')[6].slice(0, -1) * 1;
                                }
                            }
                        ]
                    },
                    'chatHeader': {
                        left: 257,
                        top: 140,
                        tools: [
                            {
                                id: 'headerFontFamily',
                                type: 'dropdown',
                                component: dropdown,
                                data: fonts,
                                field: 'header.fontFamily'
                            },
                            {
                                id: 'headerFontSize',
                                type: 'dropdown-mini',
                                component: dropdown,
                                data: (new Array(16)).fill(1).map((x, i) => (i + 8) + 'px'),
                                field: 'header.fontSize'
                            },
                            {
                                id: 'headerFontColor',
                                type: 'icon',
                                icon: `/assets/images/design/font-color.png`,
                                component: palette,
                                field: 'header.color'
                            },
                            {
                                id: 'headerBackgroundColor',
                                type: 'icon',
                                icon: `/assets/images/design/background-color.png`,
                                component: palette,
                                field: 'header.backgroundColor',
                                setter: function (val) {
                                    parent.chatModel.header.backgroundImage = 'none';
                                    return val + 'FF';
                                }
                            },
                            {
                                id: 'headerBackgroundOpacity',
                                type: 'icon',
                                icon: `/assets/images/design/background-opacity.png`,
                                component: slider,
                                field: 'header.backgroundColor',
                                setter: function (val) {
                                    return parent.chatModel.header.backgroundColor.slice(0, 7) + ('00' + (~~(val * 255)).toString(16)).slice(-2);
                                },
                                getter: function () {
                                    return Number.parseInt(parent.chatModel.header.backgroundColor.slice(-2), 16) / 255;
                                }
                            },
                            {
                                id: 'headerBackgroundImage',
                                type: 'icon',
                                icon: `/assets/images/design/background-image.png`,
                                component: imgupload,
                                field: 'header.backgroundImage,header.backgroundSize',
                                params: { resizeMode: 'width' },
                                setter: function (val) {
                                    return `url(${val})`;
                                },
                                setter1: function (val) {
                                    if (Number.parseFloat(val) <= 0.05) return 'cover';
                                    return val;
                                },
                                getter: function () {
                                    if (parent.chatModel.header.backgroundImage[0] === 'u')
                                        return parent.chatModel.header.backgroundImage.slice(4, -1);
                                    return parent.chatModel.header.backgroundImage;
                                },
                                getter1: function () {
                                    let tuple = parent.chatModel.header.backgroundSize.split(' ');
                                    if (tuple.length === 1) return 0;
                                    if (tuple[0] === 'auto') return (Number.parseInt(tuple[1]) - 100) / 400;
                                    return (Number.parseInt(tuple[0]) - 100) / 400;
                                }
                            },
                            {
                                id: 'headerManagerPhoto',
                                type: 'icon',
                                icon: `/assets/images/design/user-photo.png`,
                                component: imgupload,
                                field: 'headerManagerPhoto.backgroundImage,headerManagerPhoto.backgroundSize',
                                setter: function (val) {
                                    return `url(${val})`;
                                },
                                setter1: function (val) {
                                    if (Number.parseFloat(val) <= 0.05) return 'cover';
                                    return val;
                                },
                                getter: function () {
                                    if (parent.chatModel.headerManagerPhoto.backgroundImage[0] === 'u')
                                        return parent.chatModel.headerManagerPhoto.backgroundImage.slice(4, -1);
                                    return parent.chatModel.headerManagerPhoto.backgroundImage;
                                },
                                getter1: function () {
                                    let tuple = parent.chatModel.headerManagerPhoto.backgroundSize.split(' ');
                                    if (tuple.length === 1) return 0;
                                    if (tuple[0] === 'auto') return (Number.parseInt(tuple[1]) - 100) / 400;
                                    return (Number.parseInt(tuple[0]) - 100) / 400;
                                }
                            }
                        ]
                    },
                    'chatBody': {
                        left: 257,
                        top: 190,
                        tools: [
                            {
                                id: 'bodyBackgroundColor',
                                type: 'icon',
                                icon: `/assets/images/design/font-color.png`,
                                component: palette,
                                field: 'body.backgroundColor',
                                setter: function (val) {
                                    parent.chatModel.body.backgroundImage = 'none';
                                    parent.chatModel.backgroundVideo.url = '';
                                    return val + 'FF';
                                }
                            },
                            {
                                id: 'bodyBackgroundGradient',
                                type: 'icon',
                                icon: `/assets/images/design/gradient.png`,
                                component: gradient,
                                field: 'body.backgroundImage,body.backgroundImage',
                                setter: function (val) {
                                    let second = val;
                                    if (parent.chatModel.body.backgroundImage.slice(0, 6) === 'linear')
                                        second = parent.chatModel.body.backgroundImage.split(' ')[3];
                                    return `linear-gradient(180deg, ${val} 0%, ${second} 100%)`;
                                },
                                setter1: function (val) {
                                    let first = val;
                                    if (parent.chatModel.body.backgroundImage.slice(0, 6) === 'linear')
                                        first = parent.chatModel.body.backgroundImage.split(' ')[1];
                                    return `linear-gradient(180deg, ${first} 0%, ${val} 100%)`;
                                },
                            },
                            {
                                id: 'bodyBackgroundImage',
                                type: 'icon',
                                icon: `/assets/images/design/background-image.png`,
                                component: imgupload,
                                field: 'body.backgroundImage,body.backgroundSize',
                                setter: function (val) {
                                    return `url(${val})`;
                                },
                                setter1: function (val) {
                                    if (Number.parseFloat(val) <= 0.05) return 'cover';
                                    return val;
                                },
                                getter: function () {
                                    if (parent.chatModel.body.backgroundImage[0] === 'u')
                                        return parent.chatModel.body.backgroundImage.slice(4, -1);
                                    return parent.chatModel.body.backgroundImage;
                                },
                                getter1: function () {
                                    let tuple = parent.chatModel.body.backgroundSize.split(' ');
                                    if (tuple[0] === 'auto') return (Number.parseInt(tuple[1]) - 100) / 400;
                                    return (Number.parseInt(tuple[0]) - 100) / 400;
                                }
                            },
                            {
                                id: 'bodyBackgroundVideo',
                                type: 'icon',
                                icon: `/assets/images/design/background-video.png`,
                                component: videoupload,
                                field: 'backgroundVideo.url,body._',
                                setter: function (val) {
                                    return val;
                                },
                                setter1: function (val) {
                                    // Video loaded event
                                    document.querySelector(`#backgroundVideo`).load();
                                    document.querySelector(`#backgroundVideo`).play();
                                    return '';
                                },
                                getter: function () {
                                    if (parent.chatModel.backgroundVideo.url[0] === 'u')
                                        return parent.chatModel.backgroundVideo.url.slice(4, -1);
                                    return parent.chatModel.backgroundVideo.url;
                                }
                            },
                            {
                                id: 'bodyBackgroundOpacity',
                                type: 'icon',
                                icon: `/assets/images/design/background-opacity.png`,
                                component: slider,
                                field: 'body.backgroundColor',
                                setter: function (val) {
                                    return parent.chatModel.body.backgroundColor.slice(0, 7) + ('00' + (~~(val * 255)).toString(16)).slice(-2);
                                },
                                getter: function () {
                                    return Number.parseInt(parent.chatModel.body.backgroundColor.slice(-2), 16) / 255;
                                }
                            },
                        ]
                    },
                    'chatFooter': {
                        left: 257,
                        top: 250,
                        tools: [
                            {
                                id: 'footerFontColor',
                                type: 'icon',
                                icon: `/assets/images/design/font-color.png`,
                                component: palette,
                                field: 'footer.color'
                            },
                            {
                                id: 'footerBackgroundColor',
                                type: 'icon',
                                icon: `/assets/images/design/background-color.png`,
                                component: palette,
                                field: 'footer.backgroundColor',
                                setter: function (val) {
                                    return val + 'FF';
                                }
                            },
                            {
                                id: 'footerBackgroundOpacity',
                                type: 'icon',
                                icon: `/assets/images/design/background-opacity.png`,
                                component: slider,
                                field: 'footer.backgroundColor',
                                setter: function (val) {
                                    return parent.chatModel.footer.backgroundColor.slice(0, 7) + ('00' + (~~(val * 255)).toString(16)).slice(-2);
                                },
                                getter: function () {
                                    return Number.parseInt(parent.chatModel.footer.backgroundColor.slice(-2), 16) / 255;
                                }
                            },
                            {
                                id: 'footerButton',
                                type: 'icon',
                                icon: `/assets/images/design/send.png`,
                                component: dropdown,
                                data: [
                                    '<img src="/assets/images/btn-send/send-1.png" style="width: 32px;height: 32px;" data-data="button-send-1">',
                                    '<img src="/assets/images/btn-send/send-2.png" style="width: 32px;height: 32px;" data-data="button-send-2">',
                                    '<img src="/assets/images/btn-send/send-3.png" style="width: 32px;height: 32px;" data-data="button-send-3">',
                                    '<img src="/assets/images/btn-send/send-4.png" style="width: 32px;height: 32px;" data-data="button-send-4">',
                                    '<img src="/assets/images/btn-send/send-5.png" style="width: 64px;height: 28px;" data-data="button-send-5">',
                                ],
                                field: 'footer.send',
                            }
                        ]
                    },
                    'chatBubbleSettings': {
                        left: 257,
                        top: 306,
                        tools: [
                            {
                                id: 'bubbleShape',
                                type: 'icon',
                                icon: `/assets/images/design/shape.png`,
                                component: dropdown,
                                data: [
                                    `<div class="bot-message" data-data="bubble-shape-1" style="margin-bottom: 5px;"><div class="message bubble-shape-1" data-data="bubble-shape-1" style="background: #a8a8a8;color: #ffffff;">Bubble<div style="color: #a8a8a8;"></div></div></div>`,
                                    `<div class="bot-message" data-data="bubble-shape-2" style="margin-bottom: 5px;"><div class="message bubble-shape-2" data-data="bubble-shape-2" style="background: #a8a8a8;color: #ffffff;">Bubble<div style="color: #a8a8a8;"></div></div></div>`,
                                    `<div class="bot-message" data-data="bubble-shape-3" style="margin-bottom: 5px;"><div class="message bubble-shape-3" data-data="bubble-shape-3" style="background: #a8a8a8;color: #ffffff;">Bubble<div style="color: #a8a8a8;"></div></div></div>`,
                                    `<div class="bot-message" data-data="bubble-shape-4" style="margin-bottom: 5px;"><div class="message bubble-shape-4" data-data="bubble-shape-4" style="background: #a8a8a8;color: #ffffff;">Bubble<div style="color: #a8a8a8;"></div></div></div>`,
                                    `<div class="bot-message" data-data="bubble-shape-5" style="margin-bottom: 5px;"><div class="message bubble-shape-5" data-data="bubble-shape-5" style="background: #a8a8a8;color: #ffffff;">Bubble<div style="color: #a8a8a8;"></div></div></div>`,
                                    `<div class="bot-message" data-data="bubble-shape-6" style="margin-bottom: 5px;"><div class="message bubble-shape-6" data-data="bubble-shape-6" style="background: #a8a8a8;color: #ffffff;">Bubble<div style="color: #a8a8a8;"></div></div></div>`,
                                    `<div class="bot-message" data-data="bubble-shape-7" style="margin-bottom: 5px;"><div class="message bubble-shape-7" data-data="bubble-shape-7" style="background: #a8a8a8;color: #ffffff;">Bubble<div style="color: #a8a8a8;"></div></div></div>`,
                                    `<div class="bot-message" data-data="bubble-shape-8" style="margin-bottom: 5px;"><div class="message bubble-shape-8" data-data="bubble-shape-8" style="background: #a8a8a8;color: #ffffff;">Bubble<div style="color: #a8a8a8;"></div></div></div>`,
                                ],
                                field: 'bubble.shape'
                            },
                            {
                                id: 'bubbleAnimation',
                                type: 'icon',
                                icon: `/assets/images/design/animation.png`,
                                component: dropdown,
                                data: [
                                    'None',
                                    `<div data-data="bubble-animation-left" style="padding: 5px 0;">Slide left</div>`,
                                    `<div data-data="bubble-animation-right" style="padding: 5px 0;">Slide right</div>`,
                                    `<div data-data="bubble-animation-up" style="padding: 5px 0;">Slide up</div>`,
                                    `<div data-data="bubble-animation-down" style="padding: 5px 0;">Slide down</div>`,
                                    /*`<div class="user-message bubble-shape-1" data-data="bubble-animation-down" style="margin-bottom: 5px;"><div class="message" data-data="bubble-animation-down" style="background: #a8a8a8;color: #ffffff;">Down<div style="color: #a8a8a8;"></div></div></div>`,
                                    `<div class="user-message bubble-shape-1" data-data="bubble-animation-up" style="margin-bottom: 5px;"><div class="message" data-data="bubble-animation-up" style="background: #a8a8a8;color: #ffffff;">Up<div style="color: #a8a8a8;"></div></div></div>`,
                                    `<div class="user-message bubble-shape-1" data-data="bubble-animation-left" style="margin-bottom: 5px;"><div class="message" data-data="bubble-animation-left" style="background: #a8a8a8;color: #ffffff;">Left<div style="color: #a8a8a8;"></div></div></div>`,
                                    `<div class="user-message bubble-shape-1" data-data="bubble-animation-right" style="margin-bottom: 5px;"><div class="message" data-data="bubble-animation-right" style="background: #a8a8a8;color: #ffffff;">Right<div style="color: #a8a8a8;"></div></div></div>`,*/
                                ],
                                field: 'bubble.animation',
                                setter: function (val) {
                                    setTimeout(function () {
                                        parent.chatModel.bubble.animation = val;
                                        parent.saveWidgetLocally();
                                    }, 32);
                                    return Math.random();
                                }
                            },
                            {
                                id: 'bubbleWidth',
                                type: 'icon',
                                icon: `/assets/images/design/width.png`,
                                component: slider,
                                field: 'bubbleMain.maxWidth',
                                setter: function (val) {
                                    return (30 + val * 70) + '%';
                                },
                                getter: function () {
                                    return (Number.parseFloat(parent.chatModel.bubbleMain.maxWidth) - 30) / 70;
                                }
                            },
                            {
                                id: 'bubbleFontFamily',
                                type: 'dropdown',
                                component: dropdown,
                                data: fonts,
                                field: 'bubble.fontFamily'
                            },
                            {
                                id: 'bubbleFontSize',
                                type: 'dropdown-mini',
                                component: dropdown,
                                data: (new Array(16)).fill(1).map((x, i) => (i + 8) + 'px'),
                                field: 'bubble.fontSize'
                            },
                            {
                                id: 'bubbleAdmin',
                                type: 'icon',
                                icon: `/assets/images/design/admin.png`,
                                component: paletteduo,
                                params: {
                                    title: 'Text',
                                    title1: 'Background'
                                },
                                field: 'bubbleAdmin.color,bubbleAdmin.backgroundColor'
                            },
                            {
                                id: 'bubbleClient',
                                type: 'icon',
                                icon: `/assets/images/design/client.png`,
                                component: paletteduo,
                                params: {
                                    title: 'Text',
                                    title1: 'Background'
                                },
                                field: 'bubbleClient.color,bubbleClient.backgroundColor'
                            },
                            {
                                id: 'bubbleBackgroundOpacity',
                                type: 'icon',
                                icon: `/assets/images/design/background-opacity.png`,
                                component: slider,
                                field: 'bubbleClient.backgroundColor,bubbleAdmin.backgroundColor',
                                setter: function (val) {
                                    return parent.chatModel.bubbleClient.backgroundColor.slice(0, 7) + ('00' + (~~(val * 255)).toString(16)).slice(-2);
                                },
                                setter1: function (val) {
                                    return parent.chatModel.bubbleAdmin.backgroundColor.slice(0, 7) + ('00' + (~~(val * 255)).toString(16)).slice(-2);
                                },
                                getter: function () {
                                    return Number.parseInt(parent.chatModel.bubbleClient.backgroundColor.slice(-2), 16) / 255;
                                },
                                getter1: function () {
                                    return Number.parseInt(parent.chatModel.bubbleAdmin.backgroundColor.slice(-2), 16) / 255;
                                }
                            },
                            {
                                id: 'bubbleShadowOpacity',
                                type: 'icon',
                                icon: `/assets/images/design/shadow-opacity.png`,
                                component: slider,
                                field: 'bubble.boxShadow',
                                setter: function (val) {
                                    return `0 4px 4px rgba(0, 0, 0, ${val})`;
                                },
                                getter: function () {
                                    return parent.chatModel.bubble.boxShadow.split(' ')[6].slice(0, -1) * 1;
                                }
                            },
                        ]
                    },
                    'chatButtonSettings': {
                        left: 194,
                        top: 308,
                        tools: [
                            {
                                id: 'buttonBackground',
                                type: 'icon',
                                icon: `/assets/images/design/background-image.png`,
                                component: imgupload,
                                field: 'button.backgroundImage,button.backgroundSize,button._',
                                params: {
                                    hideSlider: true
                                },
                                setter: function (val) {
                                    parent.chatModel.button.backgroundColor = 'transparent';
                                    parent.chatModel.button.width = '150px';
                                    parent.chatModel.button.height = '150px';
                                    parent.chatModel.button.backgroundSize = 'contain';
                                    parent.chatModel.button.textAlign = 'center';
                                    parent.chatModel.button.padding = '0';
                                    return `url(${val})`;
                                },
                                setter1: function (val) {
                                    // if (Number.parseFloat(val) <= 0.05) return 'cover';
                                    return 'contain';
                                },
                                setter2: function (size) {
                                    let t = size.split(' ').map(Number);
                                    let s = t[0] / 150;
                                    if (t[0] > 150) {
                                        parent.chatModel.button.width = t[0] + 'px';
                                        parent.chatModel.button.height = (t[1] / s) + 'px';
                                    } else {
                                        parent.chatModel.button.width = t[0] + 'px';
                                        parent.chatModel.button.height = t[1] + 'px';
                                    }
                                    // if (Number.parseFloat(val) <= 0.05) return 'cover';
                                    return null;
                                },
                                getter: function () {
                                    if (parent.chatModel.button.backgroundImage[0] === 'u')
                                        return parent.chatModel.button.backgroundImage.slice(4, -1);
                                    return parent.chatModel.button.backgroundImage;
                                }
                            },
                            {
                                id: 'buttonShape',
                                type: 'icon',
                                icon: `/assets/images/design/shape.png`,
                                component: dropdown,
                                data: [
                                    `<div class="open-chat-button" data-data="open-chat-button">Button</div>`,
                                    `<div class="open-chat-button open-chat-button-1" data-data="open-chat-button-1">Button</div>`,
                                    `<div class="open-chat-button open-chat-button-2" data-data="open-chat-button-2">Button</div>`,
                                    `<div class="open-chat-button open-chat-button-3" data-data="open-chat-button-3">Button</div>`,
                                    `<div class="open-chat-button open-chat-button-4" data-data="open-chat-button-4">Button<div class="corner" style="color: #8e8e8e;"></div></div>`,
                                ],
                                componentStyle: {
                                    width: '90px'
                                },
                                field: 'button.shape'
                            },
                            {
                                id: 'buttonFontColor',
                                type: 'icon',
                                icon: `/assets/images/design/font-color.png`,
                                component: palette,
                                field: 'button.color'
                            },
                            {
                                id: 'buttonBackgroundColor',
                                type: 'icon',
                                icon: `/assets/images/design/background-color.png`,
                                component: palette,
                                field: 'button.backgroundColor',
                                setter: function (val) {
                                    parent.chatModel.button.width = 'auto';
                                    parent.chatModel.button.height = 'auto';
                                    parent.chatModel.button.backgroundImage = 'none';
                                    parent.chatModel.button.padding = '15px';
                                    return val;
                                }
                            },
                            {
                                id: 'buttonFontFamily',
                                type: 'dropdown',
                                component: dropdown,
                                data: fonts,
                                field: 'button.fontFamily'
                            },
                            {
                                id: 'buttonText',
                                type: 'input',
                                field: 'button.text'
                            },
                            {
                                id: 'buttonFontSize',
                                type: 'dropdown-mini',
                                component: dropdown,
                                data: (new Array(16)).fill(1).map((x, i) => (i + 8) + 'px'),
                                field: 'button.fontSize'
                            }
                        ]
                    },
                    'chatTypingEmulation': {
                        left: 194,
                        top: 308,
                        padding: '15px',
                        tools: [
                            {
                                id: 'typingOnOff',
                                type: 'onoff',
                                field: 'typing.status'
                            },
                            {
                                id: 'typingType',
                                type: 'button',
                                title: 'type',
                                component: dropdown,
                                data: [
                                    `<img src="/assets/images/typing/type-1.png" style="width: 50%;">`,
                                    `<img src="/assets/images/typing/type-2.png" style="width: 50%;">`,
                                    `<img src="/assets/images/typing/type-3.png" style="width: 50%;">`,
                                    `<img src="/assets/images/typing/type-4.png" style="width: 50%;">`,
                                ],
                                field: 'typing.type'
                            },
                            {
                                id: 'typingSpeed',
                                type: 'button',
                                title: 'speed',
                                component: typespeed,
                                field: 'typing.speed'
                            },
                        ]
                    }
                },
                chatModel: {
                    sizeAndLocation: {
                        embedType: 'chat-classic',
                        width: '420px',
                        height: '450px',
                        boxShadow: '0 5px 10px rgba(142, 139, 139, 1)',
                        align: 'flex-end'
                    },
                    header: {
                        fontFamily: 'Roboto',
                        fontSize: '12px',
                        color: '#fefefe',
                        backgroundSize: 'cover',
                        backgroundImage: 'none',
                        backgroundColor: '#3a3a3aff',
                        // backgroundBlendMode: 'overlay'
                    },
                    headerManagerPhoto: {
                        backgroundImage: 'url(https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png)',
                        backgroundSize: 'cover',
                    },
                    body: {
                        backgroundImage: 'none',
                        backgroundColor: '#a0a0a0ff',
                        backgroundSize: 'cover',
                    },
                    dialogueBody: {
                        paddingLeft: '25px',
                        paddingRight: '25px',
                        paddingTop: '10px',
                        paddingBottom: '10px',
                    },
                    footer: {
                        color: '#cecece',
                        backgroundColor: '#2b2b2b',
                        send: ''
                    },
                    button: {
                        backgroundImage: 'none',
                        backgroundSize: 'cover',
                        backgroundColor: '#636363ff',
                        color: '#ffffff',
                        fontFamily: 'Tahoma',
                        fontSize: '12px',
                        text: 'Type your text...',
                        width: 'auto',
                        height: 'auto',
                        textAlign: 'left',
                        shape: 'chat-button-shape-1',
                        padding: '15px'
                    },
                    bubbleMain: {
                        maxWidth: '50%'
                    },
                    bubble: {
                        fontFamily: 'Roboto',
                        fontSize: '12px',
                        boxShadow: `0 4px 4px rgba(0, 0, 0, 0.25)`,
                        backgroundImage: 'none',
                        backgroundColor: '#dededeff',
                        shape: '',
                        animation: ''
                    },
                    bubbleAdmin: {
                        color: '#2b2b2b',
                        backgroundColor: '#dededeff',
                    },
                    bubbleClient: {
                        color: '#2b2b2b',
                        backgroundColor: '#dededeff',
                    },
                    typing: {

                    },
                    backgroundVideo: {
                        url: ''
                    }
                },
                tempChatMessages: [
                    { type: 'user', data: 'Hello!', time: '4:20' },
                    { type: 'bot', data: 'Some item I wish to return because it doesn\'t have an exchange option or you received the item as a gift, you\'ll need to return the original item which i ordered day ago... ', time: '4:21', photo: 'https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png' },
                    { type: 'user', data: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', time: '4:30' },
                    { type: 'bot', data: 'Good', time: '4:55', photo: 'https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png' },
                ],
                widgets: []
            }
        }
    }
</script>

<style>

</style>
