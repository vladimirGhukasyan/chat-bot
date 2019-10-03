<div style="display: flex; width: 100%; flex-direction: column;justify-content: flex-end;">
    <div class="dialogue dialogue-design" v-show="showChat">
        <div class="header" :style="widget_header">
            <div class="profile-image">
                <div class="online online-big">
                    <div class="user-photo" :style="[widget_header_manager_photo, botAvatar ?{backgroundImage: 'url(' + botAvatar + ')', border: 0} :{}]"></div>
                </div>
            </div>
            <div class="profile-name">
                <div class="name">{{ name }}</div>
                <div class="role">Bot manager</div>
            </div>

            <div v-if="!isTest" class="time">4:20</div>
            <a v-if="!isTest" class="close" @click="closeChat()">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.8338 13.0311L7.80265 6.99997L13.8337 0.968887C14.0554 0.747265 14.0554 0.387886 13.8337 0.166237C13.6121 -0.0554122 13.2527 -0.0554122 13.0311 0.166237L7 6.19732L0.968883 0.166237C0.747262 -0.0554122 0.387884 -0.0554122 0.166236 0.166237C-0.055412 0.387858 -0.055412 0.747238 0.166236 0.968887L6.19733 6.99997L0.166236 13.0311C-0.055412 13.2528 -0.055412 13.6121 0.166236 13.8338C0.387857 14.0554 0.747235 14.0554 0.968883 13.8338L7 7.80262L13.0311 13.8338C13.2527 14.0554 13.6121 14.0554 13.8338 13.8338C14.0554 13.6121 14.0554 13.2528 13.8338 13.0311Z" fill="white"></path>
                </svg>
            </a>
        </div>
        <div v-show="storyTimer > 0" ref="storyBody" style="position: absolute; background: #3b3b3b; z-index: 10;">
            <div style="height: 4px; background: #fefefe;" :style="[{ width: (1 - (storyTimer / 5)) * 100 + '%' }]"></div>
        </div>
        <div @click="isBackgroundImageMode && (totalChatOpacity = totalChatOpacity <= 0.2 ?1 :0.2)" ref="dialogueBody" class="dialogue-body" :style="[widget_body, widget_dialogue_body]">
            <!-- MESSAGE -->
            <div @click.stop="" :class="[widget_bubble.animation, message.type + '-message']" :style="[widget_bubble_main, { opacity: totalChatOpacity }]" v-for="(message, messageIndex) in messages">
                <img v-if="message.photo" :src="botAvatar ?botAvatar :message.photo" :style="[botAvatar ?{border: 0} :{}]" alt="photo">
                <div v-if="message.type === 'user' || message.type === 'bot'">
                    <!-- TEXT -->
                    <div @click="message.isStory ?(storyTimer = 5) :null" class="message" :class="[widget_bubble.shape, (message.hasTags || message.borderLess) ?'message-border-less' :'']" :style="[widget_bubble, message.type === 'user' ?widget_bubble_client :widget_bubble_admin]">
                        <span v-html="message.data" :style="[message.isStory? {cursor: 'pointer'}: {}]"></span>
                        <div :style="[ message.type === 'user' ?{ color: widget_bubble_client.backgroundColor } :{ color: widget_bubble_admin.backgroundColor } ]"></div>
                    </div>

                    <!-- TIME -->
                    <div v-if="message.time" class="time" :style="[{ color: invertColor(widget_body.backgroundColor) }]">
                        {{ message.time }}
                    </div>
                </div>

                <!-- MULTIPLE -->
                <div v-if="message.type === 'user-select'" style="display: flex; flex-direction: column;">
                    <div style="font-size: 12px;margin-bottom: 8px;text-align: right;word-break: break-all;" :style="[{ color: invertColor(widget_body.backgroundColor) }]">Select the answer</div>
                    <div class="multiple-choice">
                        <!-- TEXT PINS -->
                        <div v-if="!message.pinsAsImage" @click="selectAnswer(messageIndex, index)" v-for="(pin, index) in message.data[1]" class="message" :class="widget_bubble.shape" :style="[widget_bubble, widget_bubble_client]">
                            {{ pin }}
                        </div>
                        <!-- IMAGE PINS -->
                        <div v-if="message.pinsAsImage" style="width: 33%;" @click="selectAnswer(messageIndex, index)" v-for="(pin, index) in message.data[1]" class="message message-border-less" :class="widget_bubble.shape" :style="[widget_bubble, widget_bubble_client]">
                            <span v-html="pin"></span>
                        </div>
                    </div>
                </div>

                <!-- RATING -->
                <div v-if="message.type === 'user-rating'" style="display: flex; flex-direction: column;">
                    <div style="font-size: 12px;margin-bottom: 8px;text-align: right;word-break: break-all;" :style="[{ color: invertColor(widget_body.backgroundColor) }]">Rate this</div>
                    <div class="multiple-choice" @mouseout="hoverRatingIndex = -1">
                        <div class="message" :class="[widget_bubble.shape]" :style="[widget_bubble, widget_bubble_client]">
                            <svg @click="selectAnswer(messageIndex, index)" @mouseover="hoverRatingIndex = index" style="margin-right: 5px;cursor: pointer;" width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg" v-for="(pin, index) in message.data[1]">
                                <path d="M6.80749 0.198113L8.46065 3.98556L12.6939 4.33517C12.9908 4.36237 13.1071 4.71586 12.8824 4.89844L9.67243 7.59432L10.6354 11.6032C10.6996 11.879 10.3907 12.1004 10.1379 11.9528L6.49852 9.82795L2.85917 11.9528C2.61039 12.0965 2.29742 11.879 2.36162 11.6032L3.32462 7.59432L0.114607 4.89844C-0.106082 4.71586 0.0102814 4.35848 0.307208 4.33517L4.54041 3.98168L6.19357 0.198113C6.30592 -0.0660376 6.69112 -0.0660376 6.80749 0.198113Z" :fill="[index > hoverRatingIndex ?'#383A3C' :'#529E37']"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- SYSTEM -->
                <div v-if="message.type === 'system' || message.type === 'system-manager'" style="display: flex;">
                    <div class="message">
                        {{ message.data }}
                    </div>
                </div>

                <!-- USER INPUT -->
                <div v-if="message.type === 'user-input'" style="display: flex;justify-content: flex-end;">
                    <input @keyup.enter="selectAnswer(messageIndex, -1, $event)" v-model="tempInput" type="text" :placeholder="message.data">
                    <svg @click="selectAnswer(messageIndex, -1)" width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor: pointer;position: absolute;top: 30%;right: 9px;">
                        <path d="M19.8349 0.277837C19.639 0.0279518 19.3141 -0.0647167 19.0253 0.0464855L0.679363 6.98847C0.312847 7.12795 0.0555848 7.46619 0.0078249 7.87122C-0.0394773 8.27433 0.1302 8.66545 0.448498 8.89057L3.07087 10.765C3.32493 10.9467 3.65162 10.9662 3.92368 10.8158L9.61215 7.67486L5.17871 11.1411C4.97058 11.3039 4.84623 11.5636 4.84623 11.8355V15.0463C4.84623 15.3775 5.01972 15.6809 5.29895 15.8385C5.57865 15.996 5.91648 15.9803 6.18213 15.7969L8.17447 14.4131L10.1337 15.8119C10.3054 15.9351 10.5098 16 10.7161 16C10.7859 16 10.8559 15.9925 10.9249 15.9775C11.2 15.9174 11.4434 15.7394 11.5927 15.4892L19.893 1.15787C20.0546 0.88194 20.0314 0.528841 19.8349 0.277837Z" fill="#383A3C"/>
                    </svg>
                </div>

                <!-- USER SCALE -->
                <div v-if="message.type === 'user-scale'" style="display: flex;">
                    <div class="message">
                        {{ message.data[0] }}
                    </div>
                    <input @change="selectAnswer(messageIndex, -1)" type="range" :min="message.data[1]" :max="message.data[2]" v-model="tempInput" class="slider" id="myRange">
                </div>
            </div>
            <div style="height: 32px;user-select: none;-webkit-user-select: none;">&nbsp;</div>
        </div>

        <div v-show="showInput" class="dialogue-footer" :style="widget_footer">
            <!--<div class="contact-list">Contacts</div>
            <div class="percent-button" :style="{ backgroundColor: widget_footer.color }"></div>
            <div class="attach-button" :style="{ backgroundColor: widget_footer.color }"></div> -->
            <div class="message-input">
                <input @keyup.enter="sendMessage()" v-model="tempInputMessage" type="text" placeholder="Type a message">
                <div class="buttons">
                    <div class="smile-button" :style="{ backgroundColor: widget_footer.color }"></div>
                    <div @click="sendMessage()" class="send-button" :class="widget_footer.send"
                         :style="{ backgroundColor: widget_footer.color }"></div>
                </div>
            </div>
        </div>
    </div>

    <div v-show="!showChat" ref="chatButton" class="open-chat-button" :class="widget_button.shape" @click="openChat()" :style="widget_button"
         style="align-self: flex-end; cursor: pointer;">
        {{ widget_button.text }}
    </div>
</div>
