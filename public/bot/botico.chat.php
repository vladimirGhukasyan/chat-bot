<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Chat at the right side -->
<div id="chat" style="display: flex; height: 100%;">
    <chat ref="chat"></chat>
</div>

<script>
    let TYPING_EMULATION = {
        mode: 1,
        delay: 1000,
        maxDelay: 1000,
        readingSpeed: 300
    };

    let GLOBAL_STATE = {
        autoScroll: false,
        server: '',
        socket: null,
        isTest: false,
        isManagerConnected: false,
        designObject: {},
        cardStatus: 'Green',
        tempStory: [],
        botIsOver: false,
        isManagerSentMessage: false,
        chatObject: {},
        userInfo: {
            id: Math.random() + '',
            name: '',
            email: '',
            address: '',
            phone: ''
        }
    };

    function findEntryPoint(bot, input) {
        console.log(bot.blocks);
        for (let i = 0; i < bot.blocks.length; i++) {
            if (bot.blocks[i].blockType === 'entry-point')
                return bot.blocks[i].id;
        }

        // Auto search
        let first = input || bot.blocks[0].id;
        for (let i = 0; i < bot.connections.length; i++) {
            if (bot.connections[i][1] === first) {
                return findEntryPoint(bot, bot.connections[i][0]);
            }
        }
        return first;
    }

    function getBlockById(bot, id) {
        for (let i = 0; i < bot.blocks.length; i++) {
            if (bot.blocks[i].id === id) {
                return bot.blocks[i];
            }
        }
        return null;
    }

    function getBlockOut(bot, blockId, pin = 0) {
        for (let i = 0; i < bot.connections.length; i++) {
            if (bot.connections[i][0] === blockId && bot.connections[i][2] === pin) {
                return getBlockById(bot, bot.connections[i][1]);
            }
        }
        return null;
    }

    function putMessageWithDelay(messages, msg, delay = 0) {
        if (delay > 0) {
            setTimeout(() => {
                putMessageWithDelay(messages, msg, 0);
            }, delay);
            return;
        }

        messages.push(msg);
    }

    function getTime() {
        return ('00' + new Date().getHours()).slice(-2) + ":" + ('00' + new Date().getMinutes()).slice(-2);
    }

    function formatContent(content) {
        content = content.replace(/\[img=(.*?)\]/g, function (r1, r2) {
            if (r2.indexOf('http') === -1) r2 = GLOBAL_STATE.server + r2;
            return `<img src="${r2}" style="width: 100%; height: 100%;">`;
        });

        // Replace id
        content = content.replace(/@id/g, (r1, r2) => GLOBAL_STATE.userInfo.id);
        content = content.replace(/@name/g, (r1, r2) => GLOBAL_STATE.userInfo.name);
        content = content.replace(/@email/g, (r1, r2) => GLOBAL_STATE.userInfo.email);
        content = content.replace(/@phone/g, (r1, r2) => GLOBAL_STATE.userInfo.phone);
        content = content.replace(/@address/g, (r1, r2) => GLOBAL_STATE.userInfo.address);

        return content.replace(/\[youtube=(.*?)]/gm, function (r1, r2) {
            r2 = r2.replace(`https://www.youtube.com/watch?v=`, ``);
            r2 = r2.replace(`https://www.youtu.be/watch?v=`, ``);
            r2 = r2.replace(`https://www.youtu.be/`, ``);
            r2 = r2.replace(`https://www.youtube.com/`, ``);
            r2 = r2.replace(`https://youtu.be/`, ``);
            return `<iframe style="display: block;" width="100%" height="auto" src="https://www.youtube.com/embed/${r2}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
        });
    }

    function removeTags(content) {
        return content.replace(/\[img=(.*?)\]/g, function (r1, r2) {
            return '';
        });
    }

    function removeExceptTags(content) {
        let match = content.match(/\[img=(.*?)\]/);
        return match ?match[0] :content;
    }

    function hasTags(content) {
        return !!content.match(/\[img=(.*?)\]/g) || !!content.match(/\[youtube=(.*?)\]/g);
    }

    function varTranslate(varName) {
        return varName.replace('@', '');
    }

    function flushLocalHistory() {
        // Send temp history
        if (GLOBAL_STATE.socket) {
            console.log(JSON.stringify(GLOBAL_STATE.tempStory));
            GLOBAL_STATE.socket.send(JSON.stringify({
                type: 2,
                room_id: '1',
                message: `@bot${GLOBAL_STATE.cardStatus}=` + JSON.stringify(GLOBAL_STATE.tempStory)
            }));
            GLOBAL_STATE.tempStory.length = 0;
        }
    }

    function sendBurgundy() {
        // Send temp history
        if (GLOBAL_STATE.socket) {
            GLOBAL_STATE.socket.send(JSON.stringify({
                type: 2,
                room_id: '1',
                message: `@botBurgundy=[]`
            }));
            console.log('sassssssssssss');
        }
    }

    function getMessageDelay(msg) {
        if (TYPING_EMULATION.mode === 1) {
            return TYPING_EMULATION.delay;
        } else {
            let ss = (msg.split(' ').length / TYPING_EMULATION.readingSpeed) * 60 * 1000;
            console.log(Math.min(TYPING_EMULATION.maxDelay, ss));
            return Math.min(TYPING_EMULATION.maxDelay, ss);
        }
    }

    function handleBlock(app, bot, currentBlockId, delay = 0) {
        if (delay > 0) {
            setTimeout(() => {
                handleBlock(app, bot, currentBlockId, 0);
            }, delay);
            return;
        }
        GLOBAL_STATE.autoScroll = true;

        let block = getBlockById(bot, currentBlockId);

        if (block.blockType !== 'block-emoji' && block.blockType !== 'block-notification' && block.blockType !== 'entry-point' && block.blockType !== 'question-scale' && block.blockType !== 'block-ab') {
            if (app.$refs.chat.messages.length && app.$refs.chat.messages[app.$refs.chat.messages.length - 1].type === 'bot') {
                app.$refs.chat.messages[app.$refs.chat.messages.length - 1].time = null;
            }
            app.$refs.chat.messages.push({
                photo: 'https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png',
                type: 'bot',
                time: getTime(),
                data: hasTags(block.content) ?formatContent(removeExceptTags(block.content)) :formatContent(block.content),
                hasTags: hasTags(block.content),
                blockId: currentBlockId,
                blockType: block.blockType
            });

            GLOBAL_STATE.tempStory.push({
                avatar: 'https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png',
                name: '',
                type: 1,
                is_client: 0,
                message: block.content,
                time: getTime()
            });
            // flushLocalHistory();
        }

        if (block.blockType === 'simple-block') {
            let nextBlock = getBlockOut(bot, currentBlockId);
            if (nextBlock)
                handleBlock(app, bot, nextBlock.id, getMessageDelay(removeTags(block.content)));
            else {
                /*putMessageWithDelay(app.$refs.chat.messages, {
                    type: 'system',
                    data: 'Chat is over',
                    blockId: currentBlockId
                }, TYPING_EMULATION.delay);*/
                app.$refs.chat.showInput = true;
                enableUserInput(app.$refs.chat);
            }
        }
        if (block.blockType === 'question-multiple') {
            putMessageWithDelay(app.$refs.chat.messages, {
                type: 'user-select',
                data: [removeTags(block.content), hasTags(block.pins[0]) ?block.pins.map(x => formatContent(x)) :block.pins],
                pinsAsImage: hasTags(block.pins[0]),
                externalLink: block.externalLink,
                blockType: 'question-multiple',
                blockId: currentBlockId
            }, getMessageDelay(removeTags(block.content)));
        }
        if (block.blockType === 'question-rating') {
            putMessageWithDelay(app.$refs.chat.messages, {
                type: 'user-rating',
                data: [removeTags(block.content), [1, 2, 3, 4, 5]],
                blockType: 'question-rating',
                blockId: currentBlockId
            }, getMessageDelay(removeTags(block.content)));
        }
        if (block.blockType === 'question-answer') {
            putMessageWithDelay(app.$refs.chat.messages, {
                type: 'user-input',
                data: 'Type answer',//block.content,
                blockType: 'question-answer',
                blockId: currentBlockId
            }, getMessageDelay(removeTags(block.content)));
        }
        if (block.blockType === 'question-scale') {
            putMessageWithDelay(app.$refs.chat.messages, {
                type: 'user-scale',
                data: [block.content, 0, 100],
                blockType: 'question-scale',
                blockId: currentBlockId
            }, 0);
        }
        if (block.blockType === 'question-request') {
            putMessageWithDelay(app.$refs.chat.messages, {
                type: 'user-input',
                data: 'Enter your ' + varTranslate(block.parameter),
                textPattern: block.parameter,
                blockType: 'question-request',
                blockId: currentBlockId
            }, getMessageDelay(removeTags(block.content)));
        }
        if (block.blockType === 'block-ab') {
            let nextBlock;

            if (block.ab[0] >= block.ab[1]) {
                if (Math.random() * 100 <= block.ab[1]) nextBlock = getBlockOut(bot, currentBlockId, 1);
                else nextBlock = getBlockOut(bot, currentBlockId, 0);
            } else {
                if (Math.random() * 100 <= block.ab[0]) nextBlock = getBlockOut(bot, currentBlockId, 0);
                else nextBlock = getBlockOut(bot, currentBlockId, 1);
            }

            if (nextBlock)
                handleBlock(app, bot, nextBlock.id, getMessageDelay(removeTags(block.content)));
            else {
                putMessageWithDelay(app.$refs.chat.messages, {
                    type: 'system',
                    data: 'Chat is over',
                    blockId: currentBlockId
                }, getMessageDelay(removeTags(block.content)));
                enableUserInput(app.$refs.chat);
            }
        }
        if (block.blockType === 'entry-point') {
            let nextBlock = getBlockOut(bot, currentBlockId, 0);
            if (nextBlock) {
                handleBlock(app, bot, nextBlock.id, 0);
            }
        }
        if (block.blockType === 'block-notification') {
            // console.log(123123);
            /*let nextBlock = getBlockOut(bot, currentBlockId, 0);
            if (nextBlock) {
                handleBlock(app, bot, nextBlock.id, 0);
            }*/
            // console.log(block.notificationType);
            // enableUserInput(app.$refs.chat);
            // block.agentExtraInfo.phone.split(",");

            sendNotification(block.agentExtraInfo.email, block.agentExtraInfo.phone, block.notificationType);

            connectManager(app.$refs.chat);
            let nextBlock = getBlockOut(bot, currentBlockId, 0);
            if (nextBlock) {
                handleBlock(app, bot, nextBlock.id, 0);
            }
        }
        if (block.blockType === 'block-emoji') {
            let _emoji = [
                {
                    id: 1,
                    url: '/assets/images/emoji/1.png'
                },
                {
                    id: 2,
                    url: '/assets/images/emoji/2.png'
                },
                {
                    id: 3,
                    url: '/assets/images/emoji/3.png'
                },
                {
                    id: 4,
                    url: '/assets/images/emoji/4.png'
                }
            ];
            GLOBAL_STATE.chatObject.botAvatar = _emoji.find(x => x.id === block.emojiId * 1).url;

            let nextBlock = getBlockOut(bot, currentBlockId, 0);
            if (nextBlock) {
                handleBlock(app, bot, nextBlock.id, getMessageDelay(removeTags(block.content)));
            }
        }
    }

    function sendNotification(email, phone, type) {
        if (!email) email = '';
        if (!phone) phone = '';
        email = email.split(',').map(x => x.trim()).join(',');
        phone = phone.split(',').map(x => x.trim()).join(',');

        let oReq = new XMLHttpRequest();
        oReq.onload = function () {};
        oReq.open("get", `http://osoyan.net:8801/?m=both&p1=${email}&p2=${phone}&type=${type}`, true);
        oReq.send();
    }

    function enableUserInput(app) {
        if (GLOBAL_STATE.isTest) return;

        GLOBAL_STATE.botIsOver = true;
        GLOBAL_STATE.isManagerConnected = true;

        setTimeout(() => {
            if (!GLOBAL_STATE.isManagerSentMessage) sendBurgundy();
        }, 8000);
        console.log('fsdfsdfsdfsdf');

        setTimeout(() => {
            app.showInput = true;

            setTimeout(() => {
                connectManager(app);
            }, TYPING_EMULATION.delay);
        }, TYPING_EMULATION.delay);
    }

    function connectManager(app) {
        let parent = this;

        if (GLOBAL_STATE.socket) {
            flushLocalHistory();
            return;
        }

        GLOBAL_STATE.socket = new WebSocket("ws://92.63.98.53:8082/client");
        GLOBAL_STATE.socket.onopen = function () {
            console.log("Соединение установлено.");
            // Add answer
            app.messages.push({
                type: 'system-manager',
                data: 'Manager connected',
                time: getTime(),
                blockId: -1
            });

            GLOBAL_STATE.socket.send(JSON.stringify({
                type: 1,
                room_id: '1',
                code: 'qwas_' + Math.random(),
            }));

            // GLOBAL_STATE.isManagerConnected = true;
            setTimeout(() => {
                flushLocalHistory();
            }, 1000);
        };

        GLOBAL_STATE.socket.onmessage = function (event) {
            let response = JSON.parse(event.data);
            // console.log(response);
            if (response.type === 2) {
                let name = response.name;
                if (!name) name = 'bot';
                let isStory = false;
                // console.log(response.message);

                if (response.message === 'Hello! how can I help?') return;

                if (response.message.match(/^\[bg=(.*)]$/)) {
                    let match = response.message.match(/^\[bg=(.*)]$/);
                    GLOBAL_STATE.designObject.widget_body.backgroundImage = `url(${match[1]})`;
                    GLOBAL_STATE.designObject.widget_body.backgroundSize = `100%`;
                    response.message = `[img=${match[1]}]`;
                    app.isBackgroundImageMode = true;
                }

                if (response.message.match(/^\[story=(.*)]$/)) {
                    let match = response.message.match(/^\[story=(.*)]$/);
                    response.message = `[img=${match[1]}]`;
                    isStory = true;
                    app.storyTimer = 5;
                    app.$refs.storyBody.style.left = app.$refs.dialogueBody.getBoundingClientRect().left + 'px';
                    app.$refs.storyBody.style.top = app.$refs.dialogueBody.getBoundingClientRect().top + 'px';
                    app.$refs.storyBody.style.width = app.$refs.dialogueBody.getBoundingClientRect().width + 'px';
                    app.$refs.storyBody.style.height = app.$refs.dialogueBody.getBoundingClientRect().height + 'px';
                    app.$refs.storyBody.style.backgroundImage = `url(${match[1]})`;
                    app.$refs.storyBody.style.backgroundSize = `cover`;
                }

                if (response.message.match(/^\[request=([a-z]+)]$/)) {
                    app.showInput = false;
                    let match = response.message.match(/^\[request=([a-z]+)]$/);
                    putMessageWithDelay(app.messages, {
                        type: 'user-input',
                        data: 'Enter your ' + match[1],
                        textPattern: '@' + match[1],
                        blockType: 'question-request',
                        blockId: -1
                    }, 16);
                } else {
                    if (app.messages[app.messages.length - 1].type === 'bot') {
                        app.messages[app.messages.length - 1].time = null;
                    }

                    // Add answer
                    app.messages.push({
                        type: 'bot',
                        data: formatContent(response.message),
                        borderLess: hasTags(response.message),
                        photo: response.avatar,
                        time: getTime(),
                        isStory: isStory,
                        blockId: -1
                    });
                }

                GLOBAL_STATE.autoScroll = true;
                GLOBAL_STATE.isManagerSentMessage = true;

                /*parent.addMessage({
                    type: 'botMessage',
                    content: response.message,
                    delay: 800
                });*/
                // chat.val(name + ' : ' + response.message + "\n" + chat.val())
            }
        };
    }

    function initChat(name, design, bot) {
        // Define chat component
        Vue.component('chat', {
            methods: {
                sendMessage(src = null) {
                    let text = this.tempInputMessage;
                    if (src) text = src;

                    // Add answer
                    if (this.messages[this.messages.length - 1].type === 'user') {
                        this.messages[this.messages.length - 1].time = null;
                    }
                    this.messages.push({
                        type: 'user',
                        data: text,
                        time: getTime(),
                        blockId: -1
                    });

                    console.log(1);
                    GLOBAL_STATE.autoScroll = true;

                    if (GLOBAL_STATE.socket) {
                        GLOBAL_STATE.socket.send(JSON.stringify({
                            type: 2,
                            room_id: '1',
                            message: text
                        }));
                    }
                    if (!src) this.tempInputMessage = '';
                },
                closeChat() {
                    this.showChat = false;

                    // console.log(this.$refs.chatButton.getBoundingClientRect());
                    setTimeout(() => {
                        mainEvent({
                            event: 'closeChat',
                            size: {
                                width: this.$refs.chatButton.getBoundingClientRect().width,
                                height: this.$refs.chatButton.getBoundingClientRect().height
                            }
                        });
                    });
                },
                openChat() {
                    this.showChat = true;
                    mainEvent({ event: 'openChat' });
                },
                selectAnswer(msgId, pin, event) {
                    let blockId = this.messages[msgId].blockId;
                    let blockType = this.messages[msgId].blockType;
                    let pinText = this.messages[msgId].data[1][pin];
                    let borderLess = this.messages[msgId].pinsAsImage;
                    let pinsAsImage = this.messages[msgId].pinsAsImage;
                    let textPattern = this.messages[msgId].textPattern;
                    let externalLink = this.messages[msgId].externalLink;
                    this.hoverRatingIndex = -1;

                    // Request phone
                    if (blockType === 'question-request' && textPattern === '@phone') {
                        if (!this.tempInput.match(/^\+?\d+$/g)) {
                            alert('Incorrect phone number. Phone must contain only digits');
                            event.target.style.border = '1px solid #ff0000';
                            GLOBAL_STATE.cardStatus = 'Red';
                            connectManager(this);
                            return false;
                        }
                    }

                    // Request email
                    if (blockType === 'question-request' && textPattern === '@email') {
                        if (!this.tempInput.match(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/g)) {
                            alert('Please enter correct email');
                            event.target.style.border = '1px solid #ff0000';
                            GLOBAL_STATE.cardStatus = 'Red';
                            connectManager(this);
                            return false;
                        }
                    }

                    // Remove message
                    this.messages.splice(msgId, 1);

                    // If in manager mode
                    if (GLOBAL_STATE.isManagerConnected) {
                        this.showInput = true;
                        this.sendMessage(this.tempInput);
                        this.tempInput = '';
                        return true;
                    }

                    // Text input
                    if (blockType === 'question-request' || blockType === 'question-answer') {
                        pinText = this.tempInput;
                        this.tempInput = '';
                        pin = 0;
                    }
                    if (blockType === 'question-scale') {
                        pinText = this.tempInput;
                        this.tempInput = '';
                        pin = 0;
                    }

                    if (blockType === 'question-rating') {
                        let number = pinText * 1;
                        pinText = ``;
                        for (let i = 0; i < number; i++) {
                            pinText += `<svg style="margin-right: ${i < number - 1 ?'5px' :'0'};cursor: pointer;" width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.80749 0.198113L8.46065 3.98556L12.6939 4.33517C12.9908 4.36237 13.1071 4.71586 12.8824 4.89844L9.67243 7.59432L10.6354 11.6032C10.6996 11.879 10.3907 12.1004 10.1379 11.9528L6.49852 9.82795L2.85917 11.9528C2.61039 12.0965 2.29742 11.879 2.36162 11.6032L3.32462 7.59432L0.114607 4.89844C-0.106082 4.71586 0.0102814 4.35848 0.307208 4.33517L4.54041 3.98168L6.19357 0.198113C6.30592 -0.0660376 6.69112 -0.0660376 6.80749 0.198113Z" fill="#529E37"/>
                            </svg>`;
                        }
                    }

                    // Add answer
                    this.messages.push({
                        type: 'user',
                        data: pinText,
                        borderLess: borderLess,
                        blockId: blockId
                    });

                    if (textPattern && textPattern[0] === '@') {
                        GLOBAL_STATE.userInfo[textPattern.replace('@', '')] = pinText;
                    }

                    GLOBAL_STATE.tempStory.push({
                        name: '',
                        type: 2,
                        is_client: 1,
                        message: pinText,
                        time: getTime()
                    });

                    // If rating
                    if (blockType === 'question-rating') pin += 1;

                    // Search block
                    let outBlock = getBlockOut(bot, blockId, pin);
                    if (!outBlock && blockType === 'question-rating')
                        outBlock = getBlockOut(bot, blockId, 0);

                    if (!outBlock) {
                        /*console.log('SASSSSSS', blockId, pin);
                        putMessageWithDelay(this.messages, {
                            type: 'system',
                            data: 'Chat is over',
                            blockId: blockId
                        }, TYPING_EMULATION.delay);*/
                        enableUserInput(this);
                    } else
                    handleBlock(app, bot, outBlock.id, TYPING_EMULATION.delay);

                    if (pinsAsImage && externalLink && externalLink[pin]) {
                        if (externalLink[pin].match(/^http/)) {
                            window.open(externalLink[pin]);
                        } else {
                            window.open('http://' + externalLink[pin]);
                        }
                    }
                },
                invertColor(hex) {
                    let padZero = function(str, len) {
                        len = len || 2;
                        let zeros = new Array(len).join('0');
                        return (zeros + str).slice(-len);
                    };

                    if (hex.indexOf('#') === 0) hex = hex.slice(1);
                    if (hex.length === 3) hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
                    // if (hex.length !== 6) throw new Error('Invalid HEX color.');

                    // invert color components
                    let r = (255 - parseInt(hex.slice(0, 2), 16)).toString(16),
                        g = (255 - parseInt(hex.slice(2, 4), 16)).toString(16),
                        b = (255 - parseInt(hex.slice(4, 6), 16)).toString(16);

                    return '#' + padZero(r) + padZero(g) + padZero(b);
                }
            },
            data: function () {
                GLOBAL_STATE.designObject = design;
                GLOBAL_STATE.chatObject = {
                    ...design,
                    botAvatar: '',
                    name: name,
                    tempInput: '',
                    tempInputMessage: '',
                    messages: [],
                    showInput: false,
                    showChat: !!GLOBAL_STATE.isTest,
                    isTest: !!GLOBAL_STATE.isTest,
                    hoverRatingIndex: -1,
                    totalChatOpacity: 1,
                    isBackgroundImageMode: false,
                    storyTimer: 0
                };
                //
                // design.widget_body.backgroundColor = '#ff0000';

                return GLOBAL_STATE.chatObject;
            },
            template: `<?= file_get_contents('chat.vue') ?>`
        });

        // Set typing emulation
        TYPING_EMULATION.mode = (bot.typing ?bot.typing.typing_emulation_mode :bot.typing_emulation_mode) * 1;
        TYPING_EMULATION.delay = (bot.typing ?bot.typing.typing_emulation_delay :bot.typing_emulation_delay) * 1;
        TYPING_EMULATION.maxDelay = (bot.typing ?bot.typing.typing_emulation_max_delay :bot.typing_emulation_max_delay) * 1;
        TYPING_EMULATION.readingSpeed = (bot.typing ?bot.typing.typing_emulation_speed :bot.typing_emulation_speed) * 1;

        console.log(TYPING_EMULATION.readingSpeed);

        // Init app
        let app = new Vue({ el: '#chat' });
        setInterval(() => {
            if (GLOBAL_STATE.autoScroll)
                app.$refs.chat.$refs.dialogueBody.scrollTop += 4;

            if (app.$refs.chat.storyTimer > 0)
                app.$refs.chat.storyTimer -= 16 / 1000;
        }, 16);
        // app.$refs.chat.showChat = !!GLOBAL_STATE.isTest;

        // Start block logic
        let startBlockId = findEntryPoint(bot, null);
        handleBlock(app, bot, startBlockId, 0);

        let wheel = function(e) {
            e.preventDefault();
            e.stopPropagation();
            GLOBAL_STATE.autoScroll = false;
            let wheelData = (e.wheelDelta > 0 || e.detail < 0) ?1 :-1;
            app.$refs.chat.$refs.dialogueBody.scrollTop += wheelData * -24;
            return false;
        };
        document.querySelector('#chat').addEventListener('mousewheel', wheel);
        document.querySelector('#chat').addEventListener('DOMMouseScroll', wheel);

        /*setTimeout(() => {
            mainEvent({
                event: 'openChat',
                size: {}
            });
        }, 16);*/
        if (!GLOBAL_STATE.isTest) {
            setTimeout(() => {
                // showChat
                mainEvent({
                    event: 'closeChat',
                    size: {
                        width: app.$refs.chat.$refs.chatButton.getBoundingClientRect().width,
                        height: app.$refs.chat.$refs.chatButton.getBoundingClientRect().height
                    }
                });
            }, 32);
        }

        console.log('bobo', bot);
    }

    function mainEvent(data) {
        top.postMessage(data, '*');
    }

    window.onload = function () {
        let hash = window.location.hash.replace('#', '');
        hash = hash.split('&').map(x => x.split('='));
        let obj = {};
        for (let i = 0; i < hash.length; i++) {
            obj[hash[i][0]] = hash[i][1];
        }

        GLOBAL_STATE.server = obj.server;
        GLOBAL_STATE.isTest = obj.test === 'true';

        // Listen message
        if (GLOBAL_STATE.isTest) {
            window.addEventListener("message", (event) => {
                // document.write(JSON.stringify(event.data));
                loadModel(obj.server, obj.secret, event.data, GLOBAL_STATE.isTest);
            });
        } else loadModel(obj.server, obj.secret, {}, GLOBAL_STATE.isTest);
    };

    function loadModel(server, secret, botData, isTest) {
        console.log('botData', botData);

        if (isTest) {
            initChat("Test", {
                "widget_size_and_location": {

                },
                "widget_header": {
                    "fontFamily": "Roboto",
                    "fontSize": "12px",
                    "color": "#ffffff",
                    "backgroundSize": "cover",
                    "backgroundImage": "none",
                    "backgroundColor": "#160207"
                },
                "widget_header_manager_photo": {
                    "backgroundImage": "url(https:\/\/hsto.org\/getpro\/habr\/company\/1fc\/fb4\/e72\/1fcfb4e72178093c8e2dd131965d5789.png)",
                    "backgroundSize": "cover"
                },
                "widget_body": {"backgroundImage": "none", "backgroundColor": "#4d5154", "backgroundSize": "cover"},
                "widget_dialogue_body": {"paddingLeft": "25px", "paddingRight": "25px"},
                "widget_footer": {"color": "#cecece", "backgroundColor": "#2b2b2b", "send": ""},
                "widget_button": {

                },
                "widget_bubble_main": {"maxWidth": "80%", "backgroundSize": ""},
                "widget_bubble": {
                    "fontFamily": "Roboto",
                    "fontSize": "12px",
                    "boxShadow": "0 4px 4px rgba(0, 0, 0, 0.19999999999999996)",
                    "backgroundImage": "none",
                    "backgroundColor": "#dedede",
                    "shape": "bubble-shape-5",
                    "animation": "bubble-animation-down"
                },
                "widget_bubble_admin": {
                    "color": "#2b2b2b", "backgroundColor": "#d0d0d0"
                },
                "widget_bubble_client": {
                    "color": "#2b2b2b", "backgroundColor": "#f6f6f6"
                },
            }, botData);
            return;
        }
        let oReq = new XMLHttpRequest();
        oReq.onload = function () {
            let data = JSON.parse(this.responseText);
            // console.log(data);
            initChat(data.name, data.widget, data.bot);
        };
        oReq.open("get", `${server}client-chat/get/${secret}`, true);
        oReq.send();
    }
</script>
</body>
</html>
