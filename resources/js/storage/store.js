import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

import socket from './socket'
import chat from './chat'

export default new Vuex.Store({
    modules: {
        socket,
        chat,
    }
})