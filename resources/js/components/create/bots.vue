<template>
    <div class="bot-editor-container" :style="[selectedBotId ?{} :{background: 'none'}]">
        <!-- Bots menu -->
        <div class="bot-list" v-show="!selectedBotId" style="padding: 10px;">
            <VuePerfectScrollbar class="scroll-area" style="height: 100%;">
                <div class="bot-window">
                    <div class="title">NEW BOT</div>
                    <input id="new-bot-name" type="text" placeholder="Enter name of your new bot">
                    <button @click="createNewBot()">CREATE</button>
                </div>

                <div class="bot-window bot-created-window" v-for="bot in bots">
                    <div class="title">{{ bot.name }}</div>
                    <div class="buttons">
                        <button @click="">CLONE</button>
                        <div>&nbsp;</div>
                        <button @click="goToEditBot(bot.id)">EDIT</button>
                        <div>&nbsp;</div>
                        <button @click="removeBot(bot.id)">DELETE</button>
                    </div>
                </div>
            </VuePerfectScrollbar>
        </div>

        <div v-show="mediaTabIsOpen && selectedBotId" id="media-upload" @click.stop="mediaTabIsOpen = false">
            <uploadmedia ref="uploadmedia" :blockId="mediaBlockId"></uploadmedia>
        </div>

        <!-- Left menu -->
        <div v-show="selectedBotId" class="design-menu" style="position: absolute;z-index: 10;">
            <div v-for="(item, index) in menuItems">
                <a @click.stop="openMenu(index)" class="btn">
                    <img :src="item.icon" :alt="item.name">
                    <div class="title" :class="[selectedMenuId === index ?'' :'opacity-hidden']">{{ item.name }}</div>
                </a>

                <div class="sub-menu" v-if="selectedMenuId === index">
                    <a @click.stop="openSubMenu(index)" v-for="(item, index) in menuItems[index].items">
                        <img :src="item.icon" :alt="item.name">
                    </a>
                </div>
            </div>
        </div>

        <!-- Save bot -->
        <div v-show="selectedBotId" @click="saveScene()" class="save-chat-button">Save</div>
        <div v-show="selectedBotId" @click="openChatPreview()" class="preview-chat-button">Preview</div>

        <div v-show="selectedBotId" id="temp-block" style="z-index: 2;position: absolute; left: 195px; top: 78px;">
            <div></div>
        </div>

        <!-- TYPING EMULATION -->
        <div @mousedown.stop="" v-show="selectedBotId && showTypingEmulation" id="typing-emulation" style="z-index: 10; position: absolute; top: 420px; left: 200px;">
            <div style="user-select: none; -webkit-user-select: none; display: flex; align-items: center; background: #23282B; padding: 5px; color: #ffffff; border-radius: 4px;">
                <div v-if="isTypingEmulationEnabled" @click="isTypingEmulationEnabled = false; showTypingEmulationAnimation = false;" style="cursor: pointer; display: flex; align-items: center; background: #636668; border-radius: 28px; padding: 5px;">
                    <div style="font-size: 18px; margin-right: 15px; margin-left: 15px;"> | </div>
                    <div style="background: #529E37; border-radius: 28px; padding: 15px 20px;">ON</div>
                </div>
                <div v-if="!isTypingEmulationEnabled" @click="isTypingEmulationEnabled = true; showTypingEmulationAnimation = false;" style="cursor: pointer; display: flex; align-items: center; background: #636668; border-radius: 28px; padding: 5px;">
                    <div style="background: #9e0816; border-radius: 28px; padding: 15px 20px;">OFF</div>
                    <div style="font-size: 18px; margin-right: 15px; margin-left: 15px;"> | </div>
                </div>
                <div @click="showTypingEmulationAnimation = !showTypingEmulationAnimation; showTypingEmulationSpeed = false; showTypingEmulationSpeedType = 0;" style="cursor: pointer; font-size: 12px; margin-left: 20px; background: #ffffff; color: #2b2b2b; text-align: center; border-radius: 4px; font-weight: bold; padding: 5px 10px;">Type</div>
                <div @click="showTypingEmulationSpeed = !showTypingEmulationSpeed; showTypingEmulationAnimation = false; showTypingEmulationSpeedType = 0;" style="cursor: pointer; font-size: 12px; margin-left: 10px; margin-right: 10px; background: #ffffff; color: #2b2b2b; text-align: center; border-radius: 4px; font-weight: bold; padding: 5px 10px;">Speed</div>
            </div>
            <div v-if="showTypingEmulationAnimation" style="left: 134.5px; position: absolute; background: #ffffff; margin-top: 10px; border-radius: 4px; padding: 15px 10px; width: 40px; display: flex; flex-direction: column; align-items: center;">
                <div style="cursor: pointer; padding: 10px;">
                    <svg style="display: block;" width="23" height="5" viewBox="0 0 23 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="2.5" cy="2.5" r="2.5" fill="#C4C4C4"/>
                        <circle cx="11.5" cy="2.5" r="2.5" fill="#C4C4C4"/>
                        <circle cx="20.5" cy="2.5" r="2.5" fill="#C4C4C4"/>
                    </svg>
                </div>
                <div style="cursor: pointer; padding: 10px;">
                    <svg width="24" height="8" viewBox="0 0 24 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect y="3" width="22" height="1" fill="#C4C4C4"/>
                        <path d="M17.4957 0L23.141 3.22182C23.3808 3.3587 23.4643 3.66408 23.3274 3.90391C23.1906 4.14374 22.8852 4.22721 22.6453 4.09034L17.8685 1.36418C17.3888 1.09043 17.2219 0.479667 17.4957 0Z" fill="#C4C4C4"/>
                        <path d="M17.4915 7.31128C17.2201 6.83032 17.3899 6.22036 17.8709 5.94889L22.6606 3.24541C22.901 3.10967 23.206 3.19458 23.3417 3.43506C23.4775 3.67554 23.3926 3.98053 23.1521 4.11626L17.4915 7.31128Z" fill="#C4C4C4"/>
                    </svg>
                </div>
                <div style="cursor: pointer; padding: 10px;">
                    <svg width="21" height="5" viewBox="0 0 21 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="2.5" cy="2.5" r="2.5" fill="#393C3F"/>
                        <circle cx="11" cy="3" r="2" fill="#393C3F" fill-opacity="0.6"/>
                        <circle cx="19" cy="3" r="2" fill="#C4C4C4"/>
                    </svg>
                </div>
            </div>
            <div v-if="showTypingEmulationSpeed" style="font-size: 12px; left: 191.5px; position: absolute; background: #ffffff; margin-top: 10px; border-radius: 4px; padding: 10px 10px; width: 50px; display: flex; flex-direction: column; align-items: center;">
                <div @click="showTypingEmulationSpeedType = 1; typingEmulationSpeedType = 1;" :style="[typingEmulationSpeedType === 1 ?{color: 'green'} :{}]" style="cursor: pointer; padding: 5px 10px; margin-bottom: 5px; text-align: left;">
                    Standard
                </div>
                <div @click="showTypingEmulationSpeedType = 2; typingEmulationSpeedType = 2;" :style="[typingEmulationSpeedType === 2 ?{color: 'green'} :{}]" style="cursor: pointer; padding: 5px 10px; text-align: left;">
                    Custom
                </div>
            </div>

            <!-- Standard -->
            <div v-if="showTypingEmulationSpeedType === 1" style="display: flex; flex-direction: column; font-size: 12px; left: 270px; position: absolute; background: #ffffff; margin-top: 10px; border-radius: 4px; padding: 10px 10px; width: 180px; display: flex; flex-direction: column; align-items: center;">
                <div style="display: flex; width: 100%;">
                    <div style="flex: 1.5; align-self: center;">Delay</div>
                    <div style="flex: 1; background: #23282B; border-radius: 28px; padding: 5px; color: #ffffff;">
                        <input type="text" v-model="typingEmulationDelay" style="border: 0; border-radius: 22px; width: 30px;font-size: 12px; padding: 3px; text-align: center;" value="0.5">
                        sec
                    </div>
                </div>

                <!-- Customize -->
                <!--<div style="display: flex; width: 100%;">
                    <div style="flex: 1.5; align-self: center;">Message customizable</div>
                    <div v-if="!isTypingEmulationIsCustom" @click="isTypingEmulationIsCustom = true" style="display: flex; cursor: pointer; flex: 1; background: #23282B; border-radius: 28px; padding: 5px; color: #ffffff;">
                        <div style="background: #ffffff; color: #2b2b2b;text-align: center;border: 0; border-radius: 22px; width: 30px;font-size: 12px; padding: 3px;">Off</div>
                        <span style="margin-left: 15px;margin-right: 5px;align-self: center;">|</span>
                    </div>
                    <div v-if="isTypingEmulationIsCustom" @click="isTypingEmulationIsCustom = false" style="display: flex; cursor: pointer; flex: 1; background: #23282B; border-radius: 28px; padding: 5px; color: #ffffff;">
                        <span style="margin-left: 10px;margin-right: 19px;align-self: center;">|</span>
                        <div style="background: #198718; color: #2b2b2b;text-align: center;border: 0; border-radius: 22px; width: 30px;font-size: 12px; padding: 3px;">On</div>
                    </div>
                </div>-->
            </div>

            <!-- Custom -->
            <div v-if="showTypingEmulationSpeedType === 2" style="font-size: 12px; left: 270px; position: absolute; background: #ffffff; margin-top: 10px; border-radius: 4px; padding: 10px 10px; width: 180px; display: flex; flex-direction: column; align-items: center;">
                <div style="display: flex; width: 100%; margin-bottom: 5px;">
                    <div style="flex: 1.5; align-self: center;">Average human reading speed</div>
                    <div style="flex: 1; display: flex; background: #23282B; border-radius: 28px; padding: 5px; color: #ffffff;">
                        <input type="text" v-model="typingEmulationReadingSpeed" style="border: 0; border-radius: 22px; width: 30px;font-size: 12px; padding: 3px; text-align: center;" value="300">
                        <div style="width: 34px;word-wrap: break-word;word-break: break-all;display: block;font-size: 9px;text-align: center;">
                            word
                            <br>
                            min
                        </div>
                    </div>
                </div>
                <div style="display: flex; width: 100%;">
                    <div style="flex: 1.5; align-self: center;">Max Delay</div>
                    <div style="flex: 1; background: #23282B; border-radius: 28px; padding: 5px; color: #ffffff;">
                        <input type="text" v-model="typingEmulationMaxDelay" style="border: 0; border-radius: 22px; width: 30px;font-size: 12px; padding: 3px; text-align: center;" value="1.5">
                        sec
                    </div>
                </div>

                <!-- Customize -->
                <!--<div style="display: flex; width: 100%;">
                    <div style="flex: 1.5; align-self: center;">Message customizable</div>
                    <div v-if="!isTypingEmulationIsCustom" @click="isTypingEmulationIsCustom = true" style="display: flex; cursor: pointer; flex: 1; background: #23282B; border-radius: 28px; padding: 5px; color: #ffffff;">
                        <div style="background: #ffffff; color: #2b2b2b;text-align: center;border: 0; border-radius: 22px; width: 30px;font-size: 12px; padding: 3px;">Off</div>
                        <span style="margin-left: 15px;margin-right: 5px;align-self: center;">|</span>
                    </div>
                    <div v-if="isTypingEmulationIsCustom" @click="isTypingEmulationIsCustom = false" style="display: flex; cursor: pointer; flex: 1; background: #23282B; border-radius: 28px; padding: 5px; color: #ffffff;">
                        <span style="margin-left: 10px;margin-right: 19px;align-self: center;">|</span>
                        <div style="background: #198718; color: #2b2b2b;text-align: center;border: 0; border-radius: 22px; width: 30px;font-size: 12px; padding: 3px;">On</div>
                    </div>
                </div>-->
            </div>
        </div>

        <div v-show="selectedBotId" id="huiolo" class="main-container"
             style="position: relative;width: 100%;height: 100%;">
            <svg width="8192" height="8192"
                 style="position: absolute;left: 0;top: 0;pointer-events: visible;width: 8192px;height: 8192px;box-sizing: border-box;background: #0000000d;">
                <!--<line
                    :x1="connection[0].leftOut(connection[2])"
                    :y1="connection[0].topOut(connection[2])"
                    :x2="connection[1].leftIn()"
                    :y2="connection[1].topIn()"
                    pointer-events="visible"
                    stroke-linecap="round"
                    stroke="#23282B"
                    stroke-width="2" v-for="connection in connections"></line>-->

                <line
                    :x1="line[0]"
                    :y1="line[1]"
                    :x2="line[2]"
                    :y2="line[3]"
                    pointer-events="visible"
                    stroke-linecap="round"
                    stroke="#529E37"
                    stroke-width="4" v-for="line in lines"></line>
            </svg>
            <!--<div @click="removeConnection(...connection)" v-for="connection in connections"
                 class="remove-icon remove-button button-white-bg remove-button-black"
                 :style="connection[0].center(connection[1], connection[2])">

            </div>-->
            <div @click="removeConnection(...line[0])" v-for="line in linesCenter"
                 class="remove-icon remove-button button-white-bg remove-button-green"
                 :style="{ left: line[1] + 'px', top: line[2] + 'px',  }">

            </div>
            <div v-for="block in blocks">
                <messageblock
                    ref="block"
                    :id="'block-'+ block.id"
                    :key="block.id"
                    :isDraggable="true"
                    :isEditMode="block.isEditMode"
                    :blockId="block.id"
                    :emojiId="block.emojiId"
                    :blockType="block.blockType"
                    :contentType="block.contentType"
                    :content="block.content"
                    :parameter="block.parameter"
                    :externalLink="block.externalLink"
                    :notificationType="block.notificationType"
                    :agentExtraInfo="block.agentExtraInfo"
                    :agentId="block.agentId"
                    :agentIdList="block.agentIdList"
                    :pins="block.pins"
                    :position="block.position"
                    :ab="block.ab"
                    :range="block.range"
                    :style="block.position"
                    :data-id="block.id">

                </messageblock>
            </div>
            <!--<div v-show="showVariables">
                <variables ref="variables" :blockId="variableBlockId" style="position: absolute; z-index: 128;" :style="showVariablesPosition"></variables>
            </div>-->
        </div>
        <div v-show="selectedBotId" class="scroll-horizontal">
            <div class="knob"></div>
        </div>
        <div v-show="selectedBotId" class="scroll-vertical">
            <div class="knob"></div>
        </div>
    </div>
</template>

<script>
    import messageblock from "../bot-component/messageblock.vue"
    import uploadmedia from "../bot-component/uploadmedia.vue"

    import onoff from "../tool-component/onoff.vue"
    import dropdown from "../tool-component/dropdown.vue"
    import VuePerfectScrollbar from 'vue-perfect-scrollbar'

    let timers = [];
    let listeners = [];

    export default {
        components: {VuePerfectScrollbar, messageblock, onoff, uploadmedia, dropdown},
        computed: {},
        mounted() {
            let parent = this;

            setTimeout(() => {
                this.initBlocks();
            }, 16);

            document.addEventListener('click', function (e) {
                if (document.querySelector('#temp-block'))
                    document.querySelector('#temp-block').innerHTML = '<div></div>';
            });
            document.querySelector('#temp-block').addEventListener('click', function (e) {
                e.stopPropagation();
            });
            document.addEventListener('mousewheel', this.mouseWheelHandler);
            document.addEventListener('DOMMouseScroll', this.mouseWheelHandler);

            let startDrag = false;
            let startX = 0;
            let startY = 0;
            document.addEventListener('mousedown', function (e) {
                e.stopPropagation();

                if (e.button === 1) {
                    startDrag = true;
                    startX = e.pageX - parent.sceneX * parent.sceneScale;
                    startY = e.pageY - parent.sceneY * parent.sceneScale;
                }
                parent.closeTypingEmulation();
            });
            document.addEventListener('mouseup', function (e) {
                if (e.button === 1) {
                    startDrag = false;
                }
            });
            document.addEventListener('mousemove', function (e) {
                if (startDrag) {
                    parent.sceneX = (e.pageX - startX) / parent.sceneScale;
                    parent.sceneY = (e.pageY - startY) / parent.sceneScale;
                    parent.transformScene();
                    parent.refreshScroll();
                }
            });

            this.$refs.uploadmedia.$on('data', (blockId, data) => {
                this.mediaTabIsOpen = false;

                if (data) {
                    if (!blockId) {
                        if (this.tempBlock) {
                            this.tempBlock._data._content += data + ' ';
                        }
                    } else {
                        this.$refs.block.find(x => x.blockId === blockId)._data._content += data + ' ';
                    }
                }
            });

            window.onpopstate = (event) => {
                if (window.location.hash === '#/create/bots') {
                    this.selectedBotId = 0;
                }
                if (window.location.hash.match(/#\/create\/bots\/(\d+)/)) {
                    let match = window.location.hash.match(/#\/create\/bots\/(\d+)/);
                    this.editBot(match[1]);
                }
            };

            window.sas = this.blocks;
            window.sasx = this.connections;
            window.checkBlockCollision = this.checkBlockCollision;
            window.buildLines = this.buildLines;

            this.loadBotList();

            let t1 = setInterval(() => {
                this.checkBlockCollision();
            }, 16);
            timers.push(t1);

            this.initScroll();
        },
        beforeDestroy() {
            for (let i = 0; i < timers.length; i++)
                clearInterval(timers[i]);

            for (let i = 0; i < listeners.length; i++)
                listeners[i].element.removeEventListener(listeners[i].type, listeners[i].callback);
        },
        methods: {
            initScroll() {
                let dragX = false;
                let nodeX = document.querySelector('.scroll-horizontal');
                let knobX = document.querySelector('.scroll-horizontal > .knob');
                let startX = 0;
                let offsetX = 0;
                let scrollPositionX = 0.5;

                let dragY = false;
                let nodeY = document.querySelector('.scroll-vertical');
                let knobY = document.querySelector('.scroll-vertical > .knob');

                let startY = 0;
                let offsetY = 0;
                let scrollPositionY = 0.5;

                let refreshX = () => {
                    knobX.style.left = (2 + scrollPositionX * (nodeX.getBoundingClientRect().width - knobX.getBoundingClientRect().width - 4)) + 'px';
                    this.sceneX = -(scrollPositionX * (8192 - document.querySelector('.main-container').getBoundingClientRect().width));
                    this.transformScene();
                };

                let refreshY = () => {
                    knobY.style.top = (2 + scrollPositionY * (nodeY.getBoundingClientRect().height - knobY.getBoundingClientRect().height - 4)) + 'px';
                    this.sceneY = -(scrollPositionY * (8192 - document.querySelector('.main-container').getBoundingClientRect().height));
                    this.transformScene();
                };

                document.querySelector('.scroll-horizontal > .knob').addEventListener('mousedown', function (e) {
                    dragX = true;
                    startX = e.pageX;
                    offsetX = startX - knobX.getBoundingClientRect().left;
                });
                document.querySelector('.scroll-vertical > .knob').addEventListener('mousedown', function (e) {
                    dragY = true;
                    startY = e.pageY;
                    offsetY = startY - knobY.getBoundingClientRect().top;
                });

                let mouseUp = function () {
                    dragX = false;
                    dragY = false;
                };
                document.addEventListener('mouseup', mouseUp);

                let mouseMove = function (e) {
                    if (dragX) {
                        scrollPositionX = (e.pageX - nodeX.getBoundingClientRect().left - offsetX) / (nodeX.getBoundingClientRect().width - knobX.getBoundingClientRect().width);
                        if (scrollPositionX < 0) scrollPositionX = 0;
                        if (scrollPositionX > 1) scrollPositionX = 1;
                        refreshX();
                    }

                    if (dragY) {
                        scrollPositionY = (e.pageY - nodeY.getBoundingClientRect().top - offsetY) / (nodeY.getBoundingClientRect().height - knobY.getBoundingClientRect().height);
                        if (scrollPositionY < 0) scrollPositionY = 0;
                        if (scrollPositionY > 1) scrollPositionY = 1;
                        refreshY();
                    }
                };
                document.addEventListener('mousemove', mouseMove);

                let keyDownEvent = (e) => {
                    console.log(1);
                    let speed = 1;
                    if (e.shiftKey) speed = 4;

                    /*if (e.keyCode === 40) this.sceneY -= 48 * speed;
                    if (e.keyCode === 38) this.sceneY += 48 * speed;
                    if (e.keyCode === 37) this.sceneX += 48 * speed;
                    if (e.keyCode === 39) this.sceneX -= 48 * speed;*/

                    this.transformScene();
                    this.refreshScroll();
                };
                document.addEventListener('keydown', keyDownEvent);
                listeners.push({ element: document, type: 'keydown', callback: keyDownEvent });
                listeners.push({ element: document, type: 'mousemove', callback: mouseMove });
                listeners.push({ element: document, type: 'mouseup', callback: mouseUp });
            },
            refreshScroll() {
                let knobX = document.querySelector('.scroll-horizontal > .knob');
                let nodeX = document.querySelector('.scroll-horizontal');
                let scrollPositionX = -this.sceneX / (8192 - document.querySelector('.main-container').getBoundingClientRect().width);
                if (scrollPositionX < 0) scrollPositionX = 0;
                if (scrollPositionX > 1) scrollPositionX = 1;
                knobX.style.left = (2 + scrollPositionX * (nodeX.getBoundingClientRect().width - knobX.getBoundingClientRect().width - 4)) + 'px';

                let knobY = document.querySelector('.scroll-vertical > .knob');
                let nodeY = document.querySelector('.scroll-vertical');
                let scrollPositionY = -this.sceneY / (8192 - document.querySelector('.main-container').getBoundingClientRect().height);
                if (scrollPositionY < 0) scrollPositionY = 0;
                if (scrollPositionY > 1) scrollPositionY = 1;
                knobY.style.top = (2 + scrollPositionY * (nodeY.getBoundingClientRect().height - knobY.getBoundingClientRect().height - 4)) + 'px';
            },

            openVariables(blockId) {
                if (this.showVariables) {
                    this.showVariables = false;
                    return false;
                }
                this.showVariables = true;
                this.variableBlockId = blockId;

                if (!blockId) {
                    if (this.tempBlock) {
                        this.showVariablesPosition.top = 130 + this.tempBlock.$el.getBoundingClientRect().top + 'px';
                        this.showVariablesPosition.left = 130 + this.tempBlock.$el.getBoundingClientRect().left + 'px';
                    }
                } else {
                    let block = this.$refs.block.find(x => x.blockId === blockId);
                    this.showVariablesPosition.top = Number.parseInt(block.$el.style.top) + 220 + 'px';
                    this.showVariablesPosition.left = Number.parseInt(block.$el.style.left) + 20 + block.$el.getBoundingClientRect().width / this.sceneScale + 'px';
                }

                setTimeout(() => {
                    this.$refs.variables.$off('data');
                    this.$refs.variables.$on('data', (blockId, data) => {
                        this.showVariables = false;
                        if (data) {
                            if (!blockId) {
                                if (this.tempBlock) {
                                    if (this.variableOutField === '_content')
                                        this.tempBlock._data[this.variableOutField] += '@' + data + ' ';
                                    else this.tempBlock._data[this.variableOutField] = '@' + data;
                                }
                            } else {
                                if (this.variableOutField === '_content')
                                    this.$refs.block.find(x => x.blockId === blockId)._data[this.variableOutField] += '@' + data + ' ';
                                else this.$refs.block.find(x => x.blockId === blockId)._data[this.variableOutField] = '@' + data;
                            }
                        }
                    });
                }, 16);
            },
            openUploadMedia(blockId) {
                this.mediaTabIsOpen = true;
                this.mediaBlockId = blockId;
                document.querySelector('#media-upload').style.width = document.querySelector('.bot-editor-container').getBoundingClientRect().width + 'px';
                document.querySelector('#media-upload').style.height = document.querySelector('.bot-editor-container').getBoundingClientRect().height + 'px';
            },
            saveConnections() {
                let connections = [];
                for (let i = 0; i < this.connections.length; i++) {
                    connections.push([
                        this.connections[i][0].id,
                        this.connections[i][1].id,
                        this.connections[i][2]
                    ]);
                }
                axios.post(`/client-bot/connection/${this.selectedBotId}`, connections)
                    .then((response) => {
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            addBlock(params) {
                let parent = this;
                let tempBlock = Object.assign({
                    position: {left: params.positionX + 'px', top: params.positionY + 'px'},
                    connectionCorner: {x: 0, y: 0},
                    agentExtraInfo: { phone: '', email: '', login: '' },
                    range: [0, 100],
                    ab: [30, 70],
                    pins: [],
                    notificationType: 'red'
                }, params);

                try {
                    tempBlock.externalLink = JSON.parse(tempBlock.externalLink);
                    if (!tempBlock.externalLink.length)
                        tempBlock.externalLink = [];
                } catch (e) {
                    tempBlock.externalLink = [];
                }

                tempBlock.leftIn = function () {
                    if (!this.position) return 0;
                    return this.position.left.replace('px', '') * 1;
                };
                tempBlock.topIn = function () {
                    if (!this.position) return 0;
                    return this.position.top.replace('px', '') * 1 + this.height() * 0.5;
                };
                tempBlock.center = function (nextBlock, pin) {
                    return {
                        left: (this.leftOut(pin) + nextBlock.leftIn()) / 2 - 12 + 'px',
                        top: (this.topOut(pin) + nextBlock.topIn()) / 2 - 12 + 'px',
                    }
                };
                tempBlock.leftOut = function (pin) {
                    if (!this.position) return 0;
                    // return this.position.left.replace('px', '') * 1;
                    if (document.querySelector(`#block-${this.id} .pin[data-id="${pin}"]`)) {
                        return (document
                                .querySelector(`#block-${this.id} .pin[data-id="${pin}"]`)
                                .getBoundingClientRect().left -
                            document.querySelector('#huiolo').getBoundingClientRect().left
                            + document
                                .querySelector(`#block-${this.id} .pin[data-id="${pin}"]`)
                                .getBoundingClientRect().width * 0.5) / parent.sceneScale;
                    }
                    return (document
                            .querySelector(`#block-${this.id}`)
                            .getBoundingClientRect().right -
                        document.querySelector('#huiolo').getBoundingClientRect().left);
                };
                tempBlock.topOut = function (pin) {
                    if (!this.position) return 0;
                    // return this.position.top.replace('px', '') * 1;
                    if (document.querySelector(`#block-${this.id} .pin[data-id="${pin}"]`))
                        return (document
                                .querySelector(`#block-${this.id} .pin[data-id="${pin}"]`)
                                .getBoundingClientRect().top -
                            document.querySelector('#huiolo').getBoundingClientRect().top
                            + document
                                .querySelector(`#block-${this.id} .pin[data-id="${pin}"]`)
                                .getBoundingClientRect().height * 0.5) / parent.sceneScale;
                    return (document
                            .querySelector(`#block-${this.id}`)
                            .getBoundingClientRect().top -
                        document.querySelector('#huiolo').getBoundingClientRect().top);
                };
                tempBlock.width = function () {
                    if (document.querySelector(`#block-${this.id}`))
                        return document.querySelector(`#block-${this.id}`).clientWidth;
                    return 0;
                };
                tempBlock.height = function () {
                    if (document.querySelector(`#block-${this.id}`))
                        return document.querySelector(`#block-${this.id}`).clientHeight;
                    return 0;
                };
                tempBlock.rect = function () {
                    if (document.querySelector(`#block-${this.id}`)) {
                        let s = document.querySelector(`#block-${this.id}`).getBoundingClientRect();
                        return [s.left, s.top, s.width, s.height];
                    }
                    return [0, 0, 0, 0];
                };

                this.blocks.push(tempBlock);
                setTimeout(() => {
                    this.initBlocks();
                });
            },
            maxBlockId() {
                /*let maxId = 0;
                for (let i = 0; i < this.blocks.length; i++)
                    maxId = Math.max(maxId, this.blocks[i].id);
                return maxId + 1;*/
                return (Math.random() * 16777216) | 0;
            },
            createBlock(params) {
                let sx = document.querySelector('#huiolo > svg').getBoundingClientRect().x - 185;
                let sy = document.querySelector('#huiolo > svg').getBoundingClientRect().y - 102;

                sx -= document.querySelector('.bot-editor-container').getBoundingClientRect().width / 2;
                sy -= document.querySelector('.bot-editor-container').getBoundingClientRect().height / 2;
                sx += 165;
                sy += 85;

                let tempBlock = (Object.assign({
                    id: this.maxBlockId(), // Math.random() * 16777216 | 0,
                    blockType: 'simple-block',
                    position: {
                        left: (-sx / this.sceneScale) + 'px',
                        top: (-sy / this.sceneScale) + 'px'
                    },
                    connectionCorner: {x: 0, y: 0},
                }, params));

                // tempBlock.id = response.data.id;
                this.addBlock(tempBlock);

                /*axios.post(`/client-bot/block/${this.selectedBotId}`, serverBlockModel)
                    .then((response) => {
                        //console.log(response);

                    })
                    .catch((error) => {
                        console.log(error);
                    });*/

                //this.blocks.push(tempBlock);

                /*setTimeout(() => {
                    this.initBlocks();
                });*/
            },
            addConnection(b1, b2, p1, doNotSave) {
                if (b1 === b2) return;

                if (typeof b1 === "number")
                    b1 = this.blocks.find(x => x.id === b1);
                if (typeof b2 === "number")
                    b2 = this.blocks.find(x => x.id === b2);

                if (!b1 || !b2) return;

                for (let i = 0; i < this.connections.length; i++) {
                    if (this.connections[i][0] === b1 && this.connections[i][2] === p1) {
                        return;
                    }
                    /*if (this.connections[i][0] === b1 && this.connections[i][2] === p1) {
                        return;
                    }*/
                }

                this.connections.push([b1, b2, p1]);
                // if (!doNotSave) this.saveConnections();
                this.buildLines();
            },
            removeConnection(b1, b2, p1) {
                for (let i = 0; i < this.connections.length; i++) {
                    if (this.connections[i][0] === b1 && this.connections[i][1] === b2 && this.connections[i][2] === p1) {
                        this.connections.splice(i, 1);
                        this.buildLines();
                        return;
                    }
                }
                // this.saveConnections();
            },
            removeBlock(id) {
                let blockIndex = this.blocks.findIndex(x => x.id === id);
                if (blockIndex !== -1) {
                    this.removedBlocks.push(this.blocks[blockIndex]);
                    this.blocks.splice(blockIndex, 1);
                    // axios.delete(`/client-bot/block/${this.selectedBotId}/${id}`);

                    for (let i = 0; i < this.connections.length; i++) {
                        if (this.connections[i][0].id === id || this.connections[i][1].id === id) {
                            this.connections.splice(i, 1);
                            i = -1;
                        }
                    }

                    // this.saveConnections();
                    setTimeout(() => {
                        this.initBlocks();
                        this.buildLines();
                    }, 16);
                }
            },

            saveBlock(params) {
                let serverBlockModel = JSON.parse(JSON.stringify(params));

                console.log(JSON.stringify(params));

                serverBlockModel.positionX = Number.parseInt(serverBlockModel.position.left);
                serverBlockModel.positionY = Number.parseInt(serverBlockModel.position.top);
                if (!serverBlockModel.parameter)
                    serverBlockModel.parameter = '2';
                serverBlockModel.externalLink = JSON.stringify(serverBlockModel.externalLink);
                serverBlockModel.emojiId = 2;
                serverBlockModel.agentId = 2;
                if (!serverBlockModel.content) serverBlockModel.content = 'null';
                if (!serverBlockModel.agentIdList) serverBlockModel.agentIdList = [];

                // console.log(serverBlockModel.externalLink);

                // console.log(serverBlockModel);
                // console.log('save', serverBlockModel);
                // console.log(serverBlockModel);

                axios.post(`/client-bot/block/${this.selectedBotId}`, serverBlockModel)
                    .then((response) => {
                        console.log('saved', response.data);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            saveScene() {
                for (let i = 0; i < this.blocks.length; i++) {
                    this.saveBlock(this.blocks[i]);
                }
                for (let i = 0; i < this.removedBlocks.length; i++) {
                    axios.delete(`/client-bot/block/${this.selectedBotId}/${this.removedBlocks[i].id}`);
                }

                this.removedBlocks.length = 0;
                this.saveConnections();
                setTimeout(() => {
                    this.selectedBotId = 0;
                    this.blocks.length = 0;
                    this.connections.length = 0;
                    window.history.back();
                }, 128);

                this.saveTypingEmulation();
            },
            saveTypingEmulation() {
                axios.post(`/client-bot/typing/${this.selectedBotId}`, {
                    typing_emulation_is_active: this.isTypingEmulationEnabled,
                    typing_emulation_animation_id: this.typingEmulationAnimationId,
                    typing_emulation_delay: this.typingEmulationDelay * 1000,
                    typing_emulation_max_delay: this.typingEmulationMaxDelay * 1000,
                    typing_emulation_speed: this.typingEmulationReadingSpeed,
                    typing_emulation_is_custom: this.isTypingEmulationIsCustom,
                    typing_emulation_mode: this.typingEmulationSpeedType,
                })
                    .then((response) => {
                        console.log('saved', response.data);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },

            openChatPreview() {
                let windowObjectReference = window.open(
                    `//${window.location.host}/bot/chat.preview.php`,
                    "_blank",
                    "width=420,height=720,resizable,scrollbars=no,status=0"
                );
                let parent = this;
                windowObjectReference.onload = function () {
                    windowObjectReference.postMessage(JSON.parse(JSON.stringify({
                        blocks: parent.blocks,
                        connections: parent.connections.map(x => [x[0].id, x[1].id, x[2]]),
                        typing: {
                            typing_emulation_is_active: parent.isTypingEmulationEnabled,
                            typing_emulation_animation_id: parent.typingEmulationAnimationId,
                            typing_emulation_delay: parent.typingEmulationDelay * 1000,
                            typing_emulation_max_delay: parent.typingEmulationMaxDelay * 1000,
                            typing_emulation_speed: parent.typingEmulationReadingSpeed,
                            typing_emulation_is_custom: parent.isTypingEmulationIsCustom,
                            typing_emulation_mode: parent.typingEmulationSpeedType,
                        },
                        name: 'Test'
                    })), '*');
                };
            },
            openMenu(index) {
                if (index === 5) {
                    this.showTypingEmulation = !this.showTypingEmulation;
                    this.selectedMenuId = 5;
                    this.openTypingEmulation();
                    return;
                }
                // this.showTypingEmulation = false;
                this.closeTypingEmulation();
                this.selectedMenuId = index;
                if (!this.menuItems[index].items) {
                    this.openBlock(index, 0);
                }
            },
            openSubMenu(index) {
                this.selectedSubMenuId = index;
                this.openBlock(this.selectedMenuId, index);
            },
            openBlock(blockTypeId, variationId) {
                const CmpCtor = Vue.extend(messageblock);
                const blockTypes = [
                    ['simple-block'],
                    ['question-answer'],
                    ['question-multiple', 'question-rating', 'question-scale'],
                    ['question-request'],
                    ['block-notification', 'block-emoji', 'block-ab'],
                ];

                document.querySelector('#temp-block').innerHTML = '<div></div>';

                if (blockTypeId <= 4) {
                    // Create temp block for edit
                    let vm = new CmpCtor({
                        data: () => {
                            return {
                                _blockType: blockTypes[blockTypeId][variationId]
                            }
                        }
                    }).$mount('#temp-block > div');

                    // Saved block
                    vm.$on('save', (blockId, blockParams) => {
                        document.querySelector('#temp-block').innerHTML = '<div></div>';

                        // Push new block
                        this.createBlock({
                            blockType: blockTypes[blockTypeId][variationId],
                            ...blockParams
                        });

                        // console.log(blockParams);

                        setTimeout(() => {
                            this.initBlocks();
                            this.buildLines();
                        }, 16);

                        this.tempBlock = null;
                    });

                    vm.$on('openMediaUpload', (blockId) => {
                        this.openUploadMedia(blockId);
                    });
                    vm.$on('openVariables', (blockId, field) => {
                        this.variableOutField = field;
                        this.openVariables(blockId);
                    });

                    this.tempBlock = vm;

                    // Create after open
                    this.createBlock({
                        blockType: blockTypes[blockTypeId][variationId],
                        isEditMode: true
                    });
                    setTimeout(() => {
                        this.initBlocks();
                        this.buildLines();
                    }, 16);
                    document.querySelector('#temp-block').innerHTML = '<div></div>';
                    this.tempBlock = null;
                }
            },

            initBlocks() {
                if (!this.$refs.block) return;

                for (let i = 0; i < this.$refs.block.length; i++) {
                    // On remove event
                    this.$refs.block[i].$off('remove');
                    this.$refs.block[i].$off('bind');
                    this.$refs.block[i].$off('copy');
                    this.$refs.block[i].$off('openMediaUpload');
                    this.$refs.block[i].$off('openVariables');
                    this.$refs.block[i].$off('save');

                    this.$refs.block[i].$on('remove', (blockId) => {
                        this.removeBlock(blockId);
                    });
                    this.$refs.block[i].$on('bind', (blockId, blockPin) => {
                        this.startBindBlockId = blockId;
                        this.startBindBlockPinId = blockPin;
                    });
                    this.$refs.block[i].$on('copy', (blockParams) => {
                        console.log(blockParams);
                        if (blockParams.position) {
                            blockParams.position.left = Number.parseFloat(blockParams.position.left) + 150 + 'px';
                            blockParams.position.top = Number.parseFloat(blockParams.position.top) + 150 + 'px';
                        }
                        this.createBlock(blockParams);
                    });
                    this.$refs.block[i].$on('openMediaUpload', (blockId) => {
                        this.openUploadMedia(blockId);
                    });
                    this.$refs.block[i].$on('openVariables', (blockId) => {
                        this.openVariables(blockId);
                    });

                    this.$refs.block[i].$on('save', (blockId, params) => {
                        console.log(JSON.stringify(params));
                        let realBlockId = this.blocks.findIndex(x => x.id === blockId);
                        if (realBlockId >= 0) {
                            this.blocks[realBlockId] = Object.assign(this.blocks[realBlockId], params);
                        }

                        setTimeout(() => {
                            this.buildLines();
                        }, 32);
                        // this.saveBlock(Object.assign(realBlock, params));
                    });
                }

                setTimeout(() => {
                    this.setDraggable();
                });
            },
            setDraggable() {
                let d = document.querySelectorAll('.message-block-draggable');
                let isDrag = false;
                let startX = 0;
                let startMouseX = 0;
                let startY = 0;
                let startMouseY = 0;
                let draggedElement = null;
                let parent = this;
                let changed = false;

                for (let i = 0; i < d.length; i++) {
                    d[i].onmousedown = function (e) {
                        startX = Number.parseFloat(this.style.left) || 0;
                        startMouseX = e.pageX / parent.sceneScale;
                        startY = Number.parseFloat(this.style.top) || 0;
                        startMouseY = e.pageY / parent.sceneScale;
                        draggedElement = this;
                    };
                    d[i].children[0].children[0].onmousedown = function (e) {
                        if (e.button === 0) isDrag = true;
                    };
                    document.addEventListener('mouseup', function () {
                        isDrag = false;
                    });
                    d[i].onmouseup = function (e) {
                        // console.log(this.getAttribute('data-id'));
                        // console.log(parent.startBindBlockId);
                        parent.addConnection(parent.startBindBlockId, this.getAttribute('data-id') * 1, parent.startBindBlockPinId);
                        parent.startBindBlockId = -1;
                        parent.startBindBlockPinId = -1;

                        /*if (changed) {
                            parent.changedBlocks.push(this.getAttribute('data-id') * 1);
                            parent.saveChangedBlocks();
                            changed = false;
                        }*/
                    };
                    document.onmousemove = function (e) {
                        if (isDrag) {
                            let offsetX = e.pageX / parent.sceneScale - startMouseX;
                            let finalX = startX + offsetX;
                            if (finalX < 0) finalX = 0;

                            // if (finalX < 0) finalX = 0;
                            draggedElement.style.left = finalX + 'px';

                            let offsetY = e.pageY / parent.sceneScale - startMouseY;
                            let finalY = startY + offsetY;
                            if (finalY < 0) finalY = 0;
                            draggedElement.style.top = finalY + 'px';

                            let realBlock = parent.blocks.find(x => x.id === draggedElement.getAttribute('data-id') * 1);
                            if (realBlock) realBlock.position = {
                                left: draggedElement.style.left,
                                top: draggedElement.style.top
                            };
                            changed = true;
                            parent.buildLines();
                        }
                    };
                }
            },
            transformScene() {
                // if (this.sceneX < 0) this.sceneX = 0;
                document.querySelector(`#huiolo`).style.transform
                    = `scale(${this.sceneScale}) translate(${this.sceneX}px, ${this.sceneY}px)`;
            },
            mouseWheelHandler(e) {
                let delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail)));
                this.sceneScale += delta / 30;
                if (this.sceneScale < 0.25) this.sceneScale = 0.25;
                if (this.sceneScale > 4) this.sceneScale = 4;

                // console.log(this.sceneScale, this.sceneX, 512 / this.sceneScale);

                this.transformScene();
                return false;
            },

            loadBotList() {
                axios.get('/client-bot')
                    .then((response) => {
                        for (let i = 0; i < response.data.length; i++) {
                            this.bots.push({name: response.data[i].name, id: response.data[i].id});
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            createNewBot() {
                if (!document.querySelector('#new-bot-name').value) return;

                axios.post(`/client-bot`, {name: document.querySelector('#new-bot-name').value})
                    .then((response) => {
                        this.selectedBotId = response.data.id;
                        this.currentName = document.querySelector('#new-bot-name').value;
                        window.location = `/#/create/bots/${response.data.id}`;
                        this.bots.push({name: response.data.name, id: response.data.id});
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            goToEditBot(id) {
                window.location = `/#/create/bots/${id}`;
            },
            editBot(id) {
                axios.get(`/client-bot/${id}`)
                    .then((response) => {
                        console.log('bot', response);
                        this.blocks = [];
                        this.connections = [];
                        this.lines = [];
                        this.linesCenter = [];

                        this.selectedBotId = id;
                        this.currentName = response.data.name;
                        this.removedBlocks = [];

                        this.isTypingEmulationEnabled = response.data.typing_emulation_is_active;
                        this.isTypingEmulationIsCustom = response.data.typing_emulation_is_custom;
                        this.typingEmulationAnimationId = response.data.typing_emulation_animation_id;
                        this.typingEmulationReadingSpeed = response.data.typing_emulation_speed;
                        this.typingEmulationDelay = response.data.typing_emulation_delay / 1000;
                        this.typingEmulationMaxDelay = response.data.typing_emulation_max_delay / 1000;
                        this.typingEmulationSpeedType = response.data.typing_emulation_mode;

                        // window.location = `/#/create/bots/${id}`;
                        // console.log(response.data.blocks);

                        let hasEntryPoint = false;
                        for (let i = 0; i < response.data.blocks.length; i++) {
                            if (response.data.blocks[i].blockType === 'entry-point') hasEntryPoint = true;
                            this.addBlock(response.data.blocks[i]);
                            console.log(response.data.blocks[i]);
                        }

                        if (!hasEntryPoint) {
                            this.addBlock({
                                id: this.maxBlockId(), // Math.random() * 16777216 | 0,
                                blockType: 'entry-point',
                                content: 'Bot starts here',
                                positionX: 4096 + 300,
                                positionY: 4096 + 300
                            });
                        }

                        setTimeout(() => {
                            for (let i = 0; i < response.data.connections.length; i++) {
                                let b1 = this.blocks.find(x => x.id === response.data.connections[i][0]);
                                let b2 = this.blocks.find(x => x.id === response.data.connections[i][1]);
                                let p1 = response.data.connections[i][2];
                                this.addConnection(b1, b2, p1, true);
                            }
                            setTimeout(() => {
                                this.sceneScale += 0.001;
                                this.sceneX = -4096;
                                this.sceneY = -4096;
                                this.transformScene();

                                document.querySelector(`.scroll-horizontal`).style.width = document.querySelector(`.main-container`).getBoundingClientRect().width - 25 + 'px';
                                document.querySelector(`.scroll-vertical`).style.height = document.querySelector(`.main-container`).getBoundingClientRect().height - 135 + 'px';
                                setTimeout(() => {
                                    this.refreshScroll();
                                }, 64);
                            }, 16);
                        }, 32);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            removeBot(id) {
                id *= 1;
                for (let i = 0; i < this.bots.length; i++) {
                    if (this.bots[i].id === id) {
                        axios.delete(`/client-bot/${id}`);
                        this.bots.splice(i, 1);
                        break;
                    }
                }
            },
            checkBlockCollision() {
                let collision = function rectRect(r1, r2) {
                    return r1[0] + r1[2] >= r2[0] && r1[0] <= r2[0] + r2[2] && r1[1] + r1[3] >= r2[1] && r1[1] <= r2[1] + r2[3];
                };
                for (let i = 0; i < this.blocks.length; i++) {
                    let mainBlock = this.blocks[i];
                    for (let j = 0; j < this.blocks.length; j++) {
                        let subBlock = this.blocks[j];
                        if (mainBlock === subBlock) continue;
                        let rect1 = mainBlock.rect();
                        let rect2 = subBlock.rect();

                        if (rect1[0] === rect2[0] && rect1[1] === rect2[1]) {
                            mainBlock.position.left = Number.parseFloat(mainBlock.position.left) + Math.random() + 'px';
                            mainBlock.position.top = Number.parseFloat(mainBlock.position.top) + Math.random() + 'px';
                        }

                        if (collision(rect1, rect2)) {
                            let angle = Math.atan2(rect2[1] - rect1[1], rect2[0] - rect1[0]);
                            let roundAngle = Math.round(angle * 180) / Math.PI - 90;
                            // console.log(1);
                            // console.log(round);

                            mainBlock.position.left = Number.parseFloat(mainBlock.position.left) + Math.sin(roundAngle / 180 * Math.PI) * 12 + 'px';
                            mainBlock.position.top = Number.parseFloat(mainBlock.position.top) - Math.cos(roundAngle / 180 * Math.PI) * 12 + 'px';
                        }
                    }
                    // console.log(this.blocks[i].rect());
                }
            },
            buildLines() {
                let lines = [];
                let linesCenter = [];

                for (let i = 0; i < this.connections.length; i++) {
                    let connection = this.connections[i];
                    let _rect1 = connection[0].rect();
                    let _rect2 = connection[1].rect();
                    let pinX = connection[2] * 20;
                    let removeCenterX = (connection[0].leftOut(connection[2]) + connection[1].leftIn()) / 2 - 12;
                    let removeCenterY = (connection[0].topOut(connection[2]) + connection[1].topIn()) / 2 - 12;

                    //left: (this.leftOut(pin) + nextBlock.leftIn()) / 2 - 12 + 'px',
                    //    top: (this.topOut(pin) + nextBlock.topIn()) / 2 - 12 + 'px',

                    let rect1 = {
                        x: connection[0].leftOut(connection[2]),
                        y: connection[0].topOut(connection[2]),
                        width: _rect1[2],
                        height: _rect1[3]
                    };
                    let rect2 = {
                        x: connection[1].leftIn(),
                        y: connection[1].topIn(),
                        width: _rect2[2],
                        height: _rect2[3]
                    };
                    rect1.top = _rect1[1];
                    rect1.bottom = _rect1[1] + _rect1[3];
                    rect2.top = _rect2[1];
                    rect2.bottom = _rect2[1] + _rect2[3];

                    if (rect2.x >= rect1.x) {
                        let center = (rect2.x - rect1.x) / 2;

                        // First line
                        lines.push([rect1.x, rect1.y, rect1.x + center + pinX, rect1.y]);

                        // Second line
                        lines.push([rect2.x, rect2.y, rect2.x - center + pinX, rect2.y]);

                        // Center line
                        lines.push([rect1.x + center + pinX, rect1.y, rect2.x - center + pinX, rect2.y]);

                        removeCenterX = rect1.x + center + pinX - 12;
                    } else {
                        if (rect2.y >= rect1.y) {
                            let centerY = Math.abs(rect2.y - rect1.y) / 2;

                            // First line
                            lines.push([rect1.x, rect1.y, rect1.x + 25, rect1.y]);

                            // Second line
                            lines.push([rect2.x, rect2.y, rect2.x - 25, rect2.y]);

                            // First line
                            lines.push([rect1.x + 25, rect1.y, rect1.x + 25, rect1.y + centerY]);

                            // Second line
                            lines.push([rect2.x - 25, rect2.y, rect2.x - 25, rect2.y - centerY]);

                            // Second line
                            lines.push([rect2.x - 25, rect2.y - centerY, rect1.x + 25, rect2.y - centerY]);
                        } else {
                            // First line
                            lines.push([rect1.x, rect1.y, rect1.x + 25 + pinX, rect1.y]);

                            // Second line
                            lines.push([rect2.x, rect2.y, rect2.x - 25, rect2.y]);

                            // First line
                            lines.push([rect1.x + 25 + pinX, rect1.y, rect1.x + 25 + pinX, rect1.y + rect1.height + 20]);

                            // Second line
                            lines.push([rect2.x - 25, rect2.y, rect2.x - 25, rect1.y + rect1.height + 20]);

                            // First line
                            lines.push([rect1.x + 25 + pinX, rect1.y + rect1.height + 20, rect2.x - 25, rect1.y + rect1.height + 20]);

                            //removeCenterX = rect2.x - 25 + 24;
                            removeCenterY = rect1.y + rect1.height + 20 - 12;

                            /*let centerY = Math.abs(rect1.y - rect2.y) / 2;

                            // First line
                            lines.push([rect1.x, rect1.y, rect1.x + 25, rect1.y]);

                            // Second line
                            lines.push([rect2.x, rect2.y, rect2.x - 25, rect2.y]);

                            // First line
                            lines.push([rect1.x + 25, rect1.y, rect1.x + 25, rect1.y - centerY]);

                            // Second line
                            lines.push([rect2.x - 25, rect2.y, rect2.x - 25, rect2.y + centerY]);

                            // Second line
                            lines.push([rect2.x - 25, rect2.y + centerY, rect1.x + 25, rect2.y + centerY]);*/
                        }
                    }

                    linesCenter.push([connection, removeCenterX, removeCenterY]);
                }

                this.lines = lines;
                this.linesCenter = linesCenter;
            },

            openTypingEmulation() {
                this.showTypingEmulation = true;
                this.showTypingEmulationAnimation = false;
                this.showTypingEmulationSpeed = false;
                this.showTypingEmulationSpeedType = 0;
            },
            closeTypingEmulation() {
                this.showTypingEmulation = false;
                this.showTypingEmulationAnimation = false;
                this.showTypingEmulationSpeed = false;
                this.showTypingEmulationSpeedType = 0;
            }
        },
        data() {
            return {
                selectedBotId: 0,
                currentName: '',
                bots: [],
                // changedBlocks: [],
                removedBlocks: [],

                mediaTabIsOpen: false,
                mediaBlockId: 0,
                variableBlockId: 0,
                variableOutField: '',
                showVariables: false,
                showVariablesPosition: {
                    left: '0px',
                    top: '0px'
                },
                startBindBlockId: -1,
                startBindBlockPinId: -1,
                sceneScale: 1,
                sceneX: 0,
                sceneY: 0,
                selectedMenuId: -1,
                selectedSubMenuId: -1,
                blocks: [],
                connections: [],
                lines: [],
                linesCenter: [],
                tempBlock: null,

                showTypingEmulation: false,
                isTypingEmulationEnabled: false,
                isTypingEmulationIsCustom: false,
                showTypingEmulationAnimation: false,
                showTypingEmulationSpeed: false,
                showTypingEmulationSpeedType: 0,
                typingEmulationSpeedType: 1,
                typingEmulationAnimationId: 1,

                typingEmulationReadingSpeed: 300,
                typingEmulationDelay: 0.5,
                typingEmulationMaxDelay: 1.5,

                menuItems: [
                    {
                        name: 'Simple Block',
                        icon: '/assets/images/bots/message-icon.png',
                    },
                    {
                        name: 'Open Question',
                        icon: '/assets/images/bots/question-icon.png'
                    },
                    {
                        name: 'Question / Button',
                        icon: '/assets/images/bots/question-3-icon.png',
                        items: [
                            {
                                name: 'A',
                                icon: '/assets/images/bots/button-icon.png'
                            },
                            {
                                name: 'A',
                                icon: '/assets/images/bots/rating-icon.png'
                            },
                            {
                                name: 'A',
                                icon: '/assets/images/bots/scale-icon.png'
                            }
                        ]
                    },
                    {
                        name: 'Question @',
                        icon: '/assets/images/bots/question-a-icon.png'
                    },
                    {
                        name: 'Service blocks',
                        icon: '/assets/images/bots/service-icon.png',
                        items: [
                            {
                                name: 'A',
                                icon: '/assets/images/bots/status-icon.png'
                            },
                            {
                                name: 'A',
                                icon: '/assets/images/bots/avatar-icon.png'
                            },
                            {
                                name: 'A',
                                icon: '/assets/images/bots/ab-icon.png'
                            }
                        ]
                    },
                    {
                        name: 'Typing emulation',
                        icon: '/assets/images/bots/typing-icon.png'
                    }
                ]
            }
        }
    }
</script>

<style lang="scss">
    #media-upload {
        display: flex;
        position: absolute;
        z-index: 64;
        background: #000000a6;
        border-radius: 6px;
        justify-content: center;
    }

    .scroll-horizontal {
        height: 10px;
        background: rgb(59, 59, 59);
        position: absolute;
        left: 130px;
        bottom: 20px;
        border-radius: 32px;
        overflow: hidden;
        user-select: none;
        -webkit-user-select: none;

        .knob {
            user-select: none;
            -webkit-user-select: none;
            cursor: pointer;
            height: 8px;
            width: 32px;
            background: #858585;
            border-radius: 32px;
            top: 1px;
            position: absolute;
            left: 1px;
        }
    }

    .scroll-vertical {
        width: 10px;
        background: rgb(59, 59, 59);
        position: absolute;
        right: 25px;
        top: 130px;
        border-radius: 32px;
        overflow: hidden;
        user-select: none;
        -webkit-user-select: none;

        .knob {
            user-select: none;
            -webkit-user-select: none;

            cursor: pointer;
            width: 8px;
            height: 32px;
            background: #858585;
            border-radius: 32px;
            top: 1px;
            position: absolute;
            left: 1px;
        }
    }

    .bot-editor-container {
        display: flex;
        background: url('/assets/images/bots/bot.bg.png');
        flex: 1;
        background-size: cover;
        border-radius: 6px;
        overflow: hidden;

        .save-chat-button, .preview-chat-button {
            cursor: pointer;
            position: absolute;
            right: 25px;
            top: 85px;
            background: #2b2b2b;
            color: #ffffff;
            padding: 8px 30px;
            border-radius: 6px;
            font-size: 12px;
            z-index: 10;
            user-select: none;
            -webkit-user-select: none;

            &:hover {
                background: #3b3b3b;
            }
        }

        .save-chat-button {
            &:active {
                top: 86px;
            }
        }

        .preview-chat-button {
            top: auto;
            bottom: 40px;

            &:active {
                bottom: 39px;
            }
        }

        .remove-icon {
            position: absolute;
            width: 24px;
            height: 24px;
            background: #ffffff;
            text-align: center;
            line-height: 21px;
            border-radius: 32px;
        }

        .design-menu {
            background: #23282B;
            // padding: 10px;
            width: 46px;
            //height: 370px;
            align-items: center;
            justify-content: space-between;
            display: flex;
            flex-direction: column;
            border-radius: 12px;
            padding: 20px 5px 5px;
            left: 130px;
            top: 77px;

            .sub-menu {
                display: flex;
                flex-direction: column;
                // padding-top: 5px;

                a {
                    cursor: pointer;
                    display: flex;
                    align-self: flex-end;
                    margin-bottom: 15px;
                    margin-right: 5px;

                    img {
                        width: 18px;
                        height: 18px;
                    }
                }
            }

            .btn {
                // padding: 10px;
                cursor: pointer;
                display: flex;
                flex-direction: column;
                user-select: none;
                -webkit-user-select: none;

                svg {
                    align-self: center;
                }

                img {
                    width: 24px;
                    height: 24px;
                    align-self: center;
                }

                .title {
                    background: rgba(196, 196, 196, 0.4);
                    text-align: center;
                    color: #fff;
                    font-size: 12px;
                    margin-top: 5px;
                    margin-bottom: 5px;
                    user-select: none;
                    -moz-user-select: none;
                    padding: 2px 0;

                    &::after {
                        background: rgba(196, 196, 196, 0.4);
                        width: 24px;
                        height: 24px;
                        position: relative;
                    }
                }
            }
        }

        .main-container {
            // margin-left: 64px;
            color: #fff;
        }

        .bot-list {
            .bot-window {
                width: 370px;
                height: 226px;
                background: rgba(186, 186, 186, 0.3);
                box-shadow: 0 5px 10px rgba(142, 139, 139, 0.5);
                border-radius: 10px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                margin-right: 10px;
                float: left;
                margin-bottom: 10px;

                .title {
                    font-size: 36px;
                    font-weight: 900;
                    text-transform: uppercase;
                }

                input, button {
                    width: 80%;
                }

                input {
                    box-sizing: border-box;
                    border: 0;
                    padding: 15px;
                    background: rgba(56, 58, 60, 0.6);
                    border-radius: 8px;
                    color: rgba(255, 255, 255, 0.4);
                    margin-top: 10px;
                }

                button {
                    cursor: pointer;
                    border: 0;
                    box-sizing: border-box;
                    background: linear-gradient(0deg, rgba(155, 222, 131, 0) -30.06%, rgba(101, 219, 60, 0.9) 111.77%);
                    border-radius: 10px;
                    padding: 15px;
                    color: #FFFFFF;
                    margin-top: 10px;

                    &:hover {
                        background: linear-gradient(0deg, rgba(155, 222, 131, 0) -28.06%, rgba(95, 239, 55, 0.9) 111.77%);
                    }

                    &:active {
                        background: linear-gradient(0deg, rgba(155, 222, 131, 0) -28.06%, rgba(31, 162, 11, 0.9) 111.77%);
                        position: relative;
                        color: #b4b4b4;
                        top: 1px;
                    }
                }
            }

            .bot-created-window {
                background: linear-gradient(178.8deg, rgba(67, 67, 67, 0) -39.15%, #000000 38.74%);
                box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
                border-radius: 10px;

                .title {
                    color: #ffffff;
                }

                .buttons {
                    display: flex;
                    width: 80%;

                    button {
                        flex: 1;
                    }
                }

                button {
                    background: linear-gradient(360deg, #FFFFFF 0%, rgba(56, 58, 60, 0) 114.71%);

                    &:hover {
                        background: linear-gradient(360deg, #FFFFFF 0%, rgba(56, 58, 60, 0) 114.71%);
                    }

                    &:active {
                        background: linear-gradient(360deg, #FFFFFF 0%, rgba(56, 58, 60, 0) 114.71%);
                    }
                }
            }
        }
    }
</style>
