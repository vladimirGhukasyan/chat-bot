<template>
    <div class="setting-account-container">
        <VuePerfectScrollbar class="scroll-area">
            <form class="user-setting-container">
                <!-- PHOTO -->
                <div class="field-row photo-container">
                    <div>
                        <img :src="userData.photo" alt="">
                    </div>
                    <div>
                        <div class="label">Avatar</div>
                        <div @click="uploadAgentPhoto()" class="input">
                            <input ref="fileSelect" type='file'/>
                            <div class="add">+</div>
                            <span>Upload avatar image</span>
                        </div>
                    </div>
                </div>

                <!-- COMPANY INFO -->
                <div class="field-row">
                    <div>
                        <div class="label">Company name</div>
                        <input type="text" placeholder="Write name of agent" v-model="userData.name">
                    </div>
                    <div>
                        <div class="label">Company size</div>
                        <input type="text" placeholder="Write user name of agent" v-model="userData.login">
                    </div>
                </div>

                <!-- PHONE & EMAIL -->
                <div class="field-row">
                    <div>
                        <div class="label">Phone</div>
                        <input type="text" placeholder="Write phone here" v-model="userData.phone">
                    </div>
                    <div>
                        <div class="label">Email</div>
                        <input type="text" placeholder="Write email here" v-model="userData.email">
                    </div>
                </div>

                <!-- PASSWORD -->
                <div class="field-row">
                    <div>
                        <div class="label">Password</div>
                        <input type="text" placeholder="Password" v-model="userData.password">
                    </div>
                    <div>
                        <div class="label">Repeat password</div>
                        <input type="text" placeholder="Repeat the password" v-model="userData.password2">
                    </div>
                </div>

                <!-- SUBMIT -->
                <div class="field-row button-group">
                    <div>
                        <button class="button-white">Cancel</button>
                    </div>
                    <div>
                        <button>Save</button>
                    </div>
                </div>
            </form>
        </VuePerfectScrollbar>
    </div>
</template>

<script>
    import VuePerfectScrollbar from 'vue-perfect-scrollbar'

    export default {
        components: {
            VuePerfectScrollbar
        },
        computed: {},
        mounted() {

        },
        methods: {},
        data() {
            return {
                userData: {
                    photo: '/assets/images/image-photo-icon.png',
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .setting-account-container {
        display: flex;
        flex: 1;
        background: linear-gradient(129.9deg, rgba(255, 255, 255, 0.5) -44.9%, rgba(251, 249, 249, 0.255841) 51.5%, rgba(97, 97, 97, 0) 130.26%);
        box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
        border-radius: 10px;

        .scroll-area {
            display: flex;
        }

        .user-setting-container {
            align-self: center;
            margin: 0 auto;
            width: 80%;

            .photo-container {
                > div:first-child {
                    flex: 0;
                    margin-bottom: 0;

                    img {
                        width: 96px;
                        height: 96px;
                        border-radius: 96px;
                    }
                }

                > div:last-child {
                    align-self: center;
                    margin-top: 0;

                    .input {
                        cursor: pointer;
                        max-height: 18px;

                        /*span {
                            text-overflow: ellipsis;
                            overflow: hidden;
                            white-space: nowrap;
                        }*/
                    }

                    input {
                        position: absolute;
                        opacity: 0;
                        z-index: -10;
                        left: 0;
                    }
                }
            }

            .button-group {
                justify-content: flex-end !important;

                > div {
                    flex: 0 0 auto !important;
                    width: 80px !important;
                }
            }

            .field-row {
                display: flex;
                color: #FFFFFF;

                button {
                    background: #529E37;
                    color: #ffffff;
                    border-radius: 7px;
                    border: 0;
                    padding: 12px;
                    font-size: 12px;
                    cursor: pointer;

                    &:hover {
                        opacity: 0.8;
                    }

                    &:active {
                        position: relative;
                        top: 1px;
                    }
                }

                .button-white {
                    background: #ffffff;
                    color: #2b2b2b;
                }

                > div {
                    display: flex;
                    flex-direction: column;
                    flex: 1;
                    margin: 10px;

                    .label {
                        margin-bottom: 10px;
                    }
                }

                input, .input {
                    background: rgba(255, 255, 255, 0.8);
                    box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
                    border: 0;
                    border-radius: 5px;
                    padding: 10px;
                    flex: 1;
                    color: rgb(131, 145, 148);
                    display: flex;
                    align-items: center;

                    .add {
                        width: 20px;
                        height: 20px;
                        background: #529e37;
                        border-radius: 14px;
                        color: #fff;
                        font-weight: bold;
                        text-align: center;
                        line-height: 18px;
                        margin-right: 5px;
                    }
                }
            }
        }
    }

    @media (max-width: 530px) {
        .setting-account-container {
            flex-direction: column;

            .scroll-area {
                display: block;
            }

            .user-setting-container {
                margin: 0;
                width: 100%;

                .photo-container {
                    flex-direction: row !important;
                }

                .field-row {
                    flex-direction: column;
                }

                .button-group {
                    > div {
                        width: auto !important;
                    }
                }
            }
        }
    }
</style>
