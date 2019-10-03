<template>
    <div style="display: flex;flex: 1;">
        <div class="history" :class="chatSelected != -1 ?'hidden-mobile' :''">
            <!-- HEADER -->
            <div class="history-header">
                <div>Status</div>
                <div>Time</div>
                <div class="hidden-mobile">Channel</div>
                <div>Agent</div>
                <div class="hidden-mobile">Bot</div>
                <div class="close">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.8338 13.0311L7.80265 6.99997L13.8337 0.968887C14.0554 0.747265 14.0554 0.387886 13.8337 0.166237C13.6121 -0.0554122 13.2527 -0.0554122 13.0311 0.166237L7 6.19732L0.968883 0.166237C0.747262 -0.0554122 0.387884 -0.0554122 0.166236 0.166237C-0.055412 0.387858 -0.055412 0.747238 0.166236 0.968887L6.19733 6.99997L0.166236 13.0311C-0.055412 13.2528 -0.055412 13.6121 0.166236 13.8338C0.387857 14.0554 0.747235 14.0554 0.968883 13.8338L7 7.80262L13.0311 13.8338C13.2527 14.0554 13.6121 14.0554 13.8338 13.8338C14.0554 13.6121 14.0554 13.2528 13.8338 13.0311Z" fill="white"/>
                    </svg>
                </div>
            </div>

            <!-- SUB HEADER -->
            <!--<div class="history-sub-header">
                <div>Client</div>
                <div>Time</div>
                <div class="hidden-mobile">Waiting answer</div>
                <div>Lasting</div>
                <div class="hidden-mobile">Mark</div>
            </div>-->

            <!-- DIALOGUE LIST -->
            <div class="dialogue-list">
                <!-- DIALOGUE ITEM -->
                <a @click="openChat(chat.id)" v-for="chat in archivedChatList" class="dialogue-item">
                    <div class="user-item">
                        <div class="online" :class="{'status-1':chat.user.isOnline}">
                            <img :src="chat.user.photoUrl" alt="">
                        </div>
                        <div class="name">{{ chat.user.name }}</div>
                        <div class="role">Test client</div>
                    </div>
                    <div>4:20</div>
                    <div class="hidden-mobile">-</div>
                    <div>
                        <div>14 hour, 22 min, 8 sek</div>
                        <div>1 message</div>
                    </div>
                    <div class="hidden-mobile">-</div>
                </a>
            </div>
        </div>

        <chat v-if="chatSelected !== -1"></chat>
    </div>
</template>

<script>
    import chat from './chat'

    export default {
        name: "archive",
        components: {
            chat
        },
        computed: {
            chatSelected: function () {
                return this.$store.getters.chatSelected
            }
        },
        methods: {
            openChat: function (id) {
                // Same shit as in chat manager
                this.$store.dispatch('selected', id);
                this.selectedChatId = id;
            }
        },
        data() {
            return {
                selectedChatId: 0,
                archivedChatList: [
                    // Some data for example
                    { id: 1,
                        user: {
                            name: 'petox',
                            isOnline: false,
                            photoUrl: 'https://hsto.org/getpro/habr/company/1c1/79c/e79/1c179ce792168371a7fe9556db69dc7d.png'
                        }
                    },
                    {
                        id: 2,
                        user: {
                            name: 'kokos',
                            isOnline: true,
                            photoUrl: 'https://hsto.org/getpro/habr/company/5f8/ece/f58/5f8ecef58d48004476cb1e01c12f914e.png'
                        }
                    },
                    {
                        id: 3,
                        user: {
                            name: 'down',
                            isOnline: false,
                            photoUrl: 'https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png'
                        }
                    },
                    {
                        id: 4,
                        user: {
                            name: 'mox loh',
                            isOnline: false,
                            photoUrl: 'https://hsto.org/getpro/habr/company/95a/244/327/95a244327fc36477b6048c0f8b67df8b.png'
                        }
                    },
                ]
            }
        }
    }
</script>

<style scoped>

</style>
