import Vue from 'vue'

function find(items, id) {
    for (let i in items)
        if (items[i].id === id) return i;
    return -1;
}

function messageReplaceTags(data) {
    data.message = formatContent(data.message);
    if (data.message.match(/^\[request=([a-z]+)]$/)) {
        let match = data.message.match(/^\[request=([a-z]+)]$/);
        data.message = 'You requested ' + match[1];
    }
}

function hasTags(content) {
    return !!content.match(/\[img=(.*?)\]/g);
}

function formatContent(content) {
    content = content.replace(/\[img=(.*?)\]/g, function (r1, r2) {
        // if (r2.indexOf('http') === -1) r2 = '/' + r2;
        return `<img src="${r2}" style="width: 100%; height: 100%;">`;
    });
    return content.replace(/\[youtube=(.*?)]/gm, function (r1, r2) {
        r2 = r2.replace(`https://www.youtube.com/watch?v=`, ``);
        r2 = r2.replace(`https://www.youtu.be/watch?v=`, ``);
        r2 = r2.replace(`https://www.youtu.be/`, ``);
        r2 = r2.replace(`https://www.youtube.com/`, ``);
        r2 = r2.replace(`https://youtu.be/`, ``);
        return `<iframe width="100%" height="auto" src="https://www.youtube.com/embed/${r2}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
    });
}

function parseBotStory() {

}

function getTime() {
    return ('00' + new Date().getHours()).slice(-2) + ":" + ('00' + new Date().getMinutes()).slice(-2);
}

export default {
    state: {
        listChat: [],
        selected: -1,
        isMenuVisible: false
    },
    mutations: {
        UPDATE(state, data) {
            console.log(data);
            if (!data || !data.clients) return;

            for (let i in data.clients) {
                let client = data.clients[i];
                let index = find(state.listChat, client.id);

                if (index === -1) {
                    // console.log('sas', client.messages);

                    if (client.messages) {
                        for (let j = 0; j < client.messages.length; j++) {
                            if (client.messages[j].message.match(/^@bot(Green|Red|Burgundy)=/)) {
                                if (client.messages[j].message.match(/^@botGreen=/))
                                    window.chatStatus[client.id + ''] = 'chat-status-green';
                                else if (client.messages[j].message.match(/^@botRed=/))
                                    window.chatStatus[client.id + ''] = 'chat-status-red';
                                else if (client.messages[j].message.match(/^@botBurgundy=/))
                                    window.chatStatus[client.id + ''] = 'chat-status-burgundy';

                                let messageList = JSON.parse(client.messages[j].message
                                    .replace('@botGreen=', '')
                                    .replace('@botRed=', '')
                                    .replace('@botBurgundy=', '')
                                );
                                client.messages.splice(j, 1);
                                console.log('ssx', messageList);
                                messageList.reverse();
                                for (let k = 0; k < messageList.length; k++) {
                                    client.messages.splice(j, 0, messageList[k]);
                                }
                                j -= 1;
                                continue;
                            }

                            messageReplaceTags(client.messages[j]);
                            client.messages[j].time = getTime();
                            if (j > 0 && client.messages[j - 1].is_client === client.messages[j].is_client) {
                                client.messages[j - 1].time = null;
                            }
                        }
                    }

                    let item = {
                        importance: 0,
                        id: client.id,
                        avatar: client.avatar,
                        name: client.name,
                        messages: client.messages ? client.messages : [],
                        mini_massage: [],
                    };

                    for (let i = item.messages.length - 1; i > 0 && i > item.messages.length - 4; i--)
                        item.mini_massage.unshift(item.messages[i]);

                    state.listChat.push(item)
                } else {
                    state.listChat[index].name = client.name ? client.name : state.listChat[index].name;
                    state.listChat[index].avatar = client.avatar ? client.avatar : state.listChat[index].avatar;
                }
            }
            console.log(state.listChat);
        },
        REMOVE_CHAT(state, data) {
            let index = find(state.listChat, data.id);
            if (index === -1) {
                console.log("Error selected");
                return
            }

            if (state.selected === data.id) {
                state.selected = -1
            }

            state.listChat.splice(index, 1);
        },
        MESSAGE(state, data) {
            console.log(data);
            /*if (data.message.match(/^\[request=([a-z]+)]$/)) {
                let match = data.message.match(/^\[request=([a-z]+)]$/);
                data.message = 'You requested ' + match[1];
            }*/
            let index = find(state.listChat, data.client_id);
            if (index === -1) {
                console.log("Error selected");
                return;
            }

            let isAllowMessage = true;
            if (data.message.match(/^@bot(Green|Red|Burgundy)=/)) {
                if (data.message.match(/^@botGreen=/))
                    window.chatStatus[data.client_id + ''] = 'chat-status-green';
                else if (data.message.match(/^@botRed=/))
                    window.chatStatus[data.client_id + ''] = 'chat-status-red';
                else if (data.message.match(/^@botBurgundy=/)) {
                    window.chatStatus[data.client_id + ''] = 'chat-status-burgundy';
                    console.log(data.message);
                }

                // window.chatStatus[data.client_id] = 'chat-status-green';

                isAllowMessage = false;
                let messageList = JSON.parse(data.message
                    .replace('@botGreen=', '')
                    .replace('@botRed=', '')
                    .replace('@botBurgundy=', '')
                );
                for (let i = 0; i < messageList.length; i++) {
                    messageReplaceTags(messageList[i]);
                    state.listChat[index].messages.push(messageList[i]);

                    //  add mini chat
                    state.listChat[index].mini_massage.push(messageList[i]);
                    if (state.listChat[index].mini_massage.length > 4)
                        state.listChat[index].mini_massage.shift();
                }
            }

            if (isAllowMessage) {
                data.time = getTime();
                messageReplaceTags(data);

                state.listChat[index].messages.push(data);
                if (state.listChat[index].messages.length > 1) {
                    if (state.listChat[index].messages[state.listChat[index].messages.length - 1].is_client === state.listChat[index].messages[state.listChat[index].messages.length - 2].is_client) {
                        state.listChat[index].messages[state.listChat[index].messages.length - 2].time = null;
                    }
                }

                //  add mini chat
                state.listChat[index].mini_massage.push(data);
                if (state.listChat[index].mini_massage.length > 4)
                    state.listChat[index].mini_massage.shift();
            }

            if (window.onChatMessage) {
                window.onChatMessage();
            }

            setTimeout(() => {
                let old = window.chatStatus[data.client_id + ''];
                window.chatStatus[data.client_id + ''] = '';
                setTimeout(() => {
                    window.chatStatus[data.client_id + ''] = old;
                }, 16);
            }, 16);
        },
        SELECTED(state, id) {
            state.selected = id;
        },
        OPEN_MENU(state) {
            state.isMenuVisible = !state.isMenuVisible;
            // console.log(state);
        }
    },
    actions: {
        updateChat: function (context, data) {
            context.commit('UPDATE', data);
        },
        removeChat: function (context, data) {
            context.commit('REMOVE_CHAT', data);
        },
        addMessage: function (context, data) {
            context.commit('MESSAGE', data);
        },
        selected: function (context, id) {
            context.commit('SELECTED', id);
        },
        openMenu: function (context) {
            context.commit('OPEN_MENU');
        }
    },
    getters: {
        getListChat: state => {
            return state.listChat;
        },
        getActiveChat: state => {
            let index = find(state.listChat, state.selected);
            if (index === -1) return [];
            return state.listChat[index];
        },
        chatSelected: state => {
            return state.selected;
        },
        isMenuVisible: state => {
            if (window.innerWidth > 530) return true;
            return state.isMenuVisible;
        },
    }
}
