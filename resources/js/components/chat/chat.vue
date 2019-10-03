<template>
    <div class="dialogue" style="position: relative;">
        <!-- Dialogue header -->
        <div class="header">
            <div class="profile-image">
                <div class="online online-big">
                    <img src="https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png"
                         alt="">
                </div>
            </div>
            <div class="profile-name">
                <div class="name">{{chat.name}}</div>
                <div class="role">Some client</div>
            </div>

            <div class="time">4:20</div>
            <a @click="onClose" class="close">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M13.8338 13.0311L7.80265 6.99997L13.8337 0.968887C14.0554 0.747265 14.0554 0.387886 13.8337 0.166237C13.6121 -0.0554122 13.2527 -0.0554122 13.0311 0.166237L7 6.19732L0.968883 0.166237C0.747262 -0.0554122 0.387884 -0.0554122 0.166236 0.166237C-0.055412 0.387858 -0.055412 0.747238 0.166236 0.968887L6.19733 6.99997L0.166236 13.0311C-0.055412 13.2528 -0.055412 13.6121 0.166236 13.8338C0.387857 14.0554 0.747235 14.0554 0.968883 13.8338L7 7.80262L13.0311 13.8338C13.2527 14.0554 13.6121 14.0554 13.8338 13.8338C14.0554 13.6121 14.0554 13.2528 13.8338 13.0311Z"
                        fill="white"/>
                </svg>
            </a>
        </div>

        <!-- Dialogue body, ie chat -->
        <div class="dialogue-body">
            <div :class="[item.is_client === 1 ? 'manager-message' : 'user-message']" v-for="item in chat.messages">
                <img v-if="item.avatar" :src="item.avatar" alt="">
                <div style="display: flex; flex-direction: column;">
                    <div class="message" v-html="item.message"></div>
                    <div v-if="item.time" class="time">{{ item.time }}</div>
                </div>
            </div>
        </div>

        <!-- Dialogue footer -->
        <!--<div class="dialogue-footer">
            <div class="contact-list">Contacts</div>
            <div class="percent">
                <img src="assets/images/percent.png" alt="">
            </div>
            <div class="attach">
                <img src="assets/images/attach.png" alt="">
            </div>
            <div class="message-input">
                <input type="text" style="" placeholder="Type a message" v-model="message">
                <div>
                    <img src="assets/images/smile.png" alt="">
                </div>
                <div @click.keyup.enter="onSend">
                    <img src="assets/images/send.png" alt="">
                </div>
            </div>
        </div>-->

        <div v-if="isSelectAttachMode" class="body-overflow" style="display: flex; flex-direction: column;">
            <button @click="sendAttachment('bg')">Send as background</button>
            <button @click="sendAttachment('story')">Send as story</button>
            <button @click="sendAttachment()">Send as photo</button>
            <button @click="isSelectAttachMode = false">Cancel</button>
        </div>

        <div class="dialogue-footer">
            <input ref="uploadFile" type="file" style="position: absolute; z-index: -10; top: -100px; left: -100px;">
            <div class="contact-list" @click="isSelectRequestInformation = !isSelectRequestInformation">Contacts</div>
            <div class="percent-button"></div>
            <div @click="uploadPhoto()" class="attach-button"></div>
            <div class="message-input">
                <input @keyup.enter="onSend()" v-model="message" type="text" placeholder="Type a message">
                <div class="buttons">
                    <div class="smile-button"></div>
                    <div @click="onSend()" class="send-button"></div>
                </div>
            </div>
        </div>

        <div class="request-info-block" v-if="isSelectRequestInformation">
            <a @click="requestInfo(t)" v-for="t in ['name', 'email', 'phone', 'address']">{{ t }}</a>
        </div>
    </div>
</template>

<script>
    export default {
        name: "chat",
        data() {
            return {
                message: '',
                isSelectRequestInformation: false,
                isSelectAttachMode: false,
                tempAttachmentUrl: ''
            }
        },
        computed: {
            chat: function () {
                return this.$store.getters.getActiveChat
            }
        },
        mounted() {
            window.onChatMessage = () => {
                setTimeout(() => {
                    document.querySelector('.dialogue-body').scrollTop += 10000000;
                }, 128);
            };
        },
        methods: {
            onClose: function () {
                this.$store.dispatch('selected', -1);
            },
            onSend: function () {
                this.$socket.sendObj({
                    type: 2,
                    client_id: `${this.$store.getters.chatSelected}`,
                    room_id: `${window.room_id}`,
                    message: this.message,
                    name: window.name,
                    avatar: window.avatar
                });

                this.message = '';

                setTimeout(() => {
                    document.querySelector('.dialogue-body').scrollTop += 10000000;
                }, 128);
            },
            uploadPhoto() {
                let parent = this;
                this.$refs.uploadFile.click();
                this.$refs.uploadFile.addEventListener('change', function () {
                    if (!this.files && !this.files[0]) return;

                    let formData = new FormData();
                    formData.append("image", parent.$refs.uploadFile.files[0]);
                    axios.post('/image-upload', formData, {
                        headers: {'Content-Type': 'multipart/form-data'}
                    }).then((response) => {
                        parent.isSelectAttachMode = true;
                        parent.tempAttachmentUrl = response.data.path;
                        // console.log();
                        // response.data.path
                    }).catch((error) => {
                        console.log(error);
                    });
                });
            },
            sendMessage(text) {
                console.log(text);
                this.$socket.sendObj({
                    type: 2,
                    client_id: `${this.$store.getters.chatSelected}`,
                    room_id: `${window.room_id}`,
                    message: text,
                    name: window.name,
                    avatar: window.avatar
                });
            },
            requestInfo(type) {
                this.isSelectRequestInformation = false;
                this.sendMessage(`[request=${type}]`);
            },
            sendAttachment(type) {
                this.isSelectAttachMode = false;
                if (!type) {
                    this.sendMessage(`[img=http://${window.location.host}/${this.tempAttachmentUrl}]`);
                }
                if (type === 'bg') {
                    this.sendMessage(`[bg=http://${window.location.host}/${this.tempAttachmentUrl}]`);
                }
                if (type === 'story') {
                    this.sendMessage(`[story=http://${window.location.host}/${this.tempAttachmentUrl}]`);
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .request-info-block {
        position: absolute;
        bottom: 80px;
        left: 10px;
        background: #f6f6f6;
        padding: 10px;
        border-radius: 4px;
        display: flex;
        flex-direction: column;

        a {
            cursor: pointer;
            text-decoration: none;
            color: #353535;
            padding: 5px;

            &:hover {
                color: #7c7c7c;
            }
        }
    }

    .body-overflow {
        position: absolute;
        left: 0;
        top: 0;
        background: rgba(0, 0, 0, 0.7);
        color: #fefefe;
        width: 100%;
        height: 100%;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;

        button {
            background: #fefefe;
            border: 0;
            padding: 10px 15px;
            border-radius: 9px;
            font-size: 12px;
            color: #3b3b3b;
            margin-bottom: 5px;
            width: 40%;
            cursor: pointer;

            &:hover {
                opacity: 0.8;
            }

            &:active {
                opacity: 0.5;
                position: relative;
                top: 1px;
            }
        }
    }
</style>
