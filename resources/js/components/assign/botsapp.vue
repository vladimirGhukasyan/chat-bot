<template>
    <div class="botsapp-container" style="display: flex; flex: 1;">
        <div v-if="!selectedBotId" class="botsapps">
            <div class="botsapp-header">
                <div>Name</div>
                <div>Agents</div>
                <div style="justify-content: flex-end;">
                    <button class="create-new" @click="createNewBotsApp()">
                        <div>+</div>
                        NEW
                    </button>
                </div>
            </div>

            <VuePerfectScrollbar class="scroll-area" style="flex: 1;">
            <div style="border-radius: 0 0 10px 10px;overflow: hidden;">
                <div class="botsapp" v-for="botApp in botsAppList">
                    <div>{{botApp.name}}</div>
                    <div>
                        <div class="add-item">+</div>
                        <div class="agent" v-for="agent in botApp.agents">
                            <div class="user-photo-1 online online-big" style="margin-right: 10px;">
                                <img :src="agent.photo" :alt="agent.name">
                            </div>
                            <div style="margin-right: 10px;">{{agent.name}}</div>
                            <a @click="removeAgent(botApp.id, agent.id)">x</a>
                        </div>
                    </div>
                    <div style="justify-content: flex-end;">
                        <a @click="showCode(botApp.id)" class="copy-button big-button" style="margin-right: 5px;"></a>
                        <a @click="editApp(botApp.id)" class="edit-button big-button" style="margin-right: 5px;"></a>
                        <a @click="removeApp(botApp.id)" class="remove-button big-button"></a>
                    </div>
                </div>
            </div>
            </VuePerfectScrollbar>
        </div>

        <div v-if="selectedBotId" class="add-botsapp">
            <div style="align-items: flex-start;margin: 0 auto;margin-top: 100px; display: flex;width: 90%;">
                <!-- WIDGETS -->
                <div class="block">
                    <div style="display: flex; margin-bottom: 10px;">
                        <div style="align-self: center;margin-right: auto;">WIDGETS</div>
                        <button class="create-new">
                            <div>+</div>
                            NEW
                        </button>
                    </div>
                    <VuePerfectScrollbar class="scroll-area">
                        <div class="sub-block" v-for="item in widgetList">
                            <div style="margin-right: 20px;display: flex;">
                                <checkbox :data-id="item.id" ref="widgetSelect" style="align-self: center;"
                                          v-on:data="selectWidget(item.id, $event)"></checkbox>
                            </div>
                            <div style="border-radius: 9px;flex: 1;margin-right: 20px;height: auto;margin-bottom: 0;justify-content: center;align-items: center;display: flex;"
                                 :style="item.widget_body">
                                <div class="title-no-hover" style="border-radius: 9px;width: 60%;height: 29px;display: flex;align-items: center;justify-content: center;" :style="item.widget_bubble_admin">{{ item.name }}</div>
                            </div>
                            <div>
                                <a @click="removeWidgetById(item.id)" class="remove-button remove-button-white"
                                   style="margin-bottom: 5px;"></a>
                                <a class="copy-button copy-button-white"></a>
                            </div>
                        </div>
                    </VuePerfectScrollbar>
                </div>

                <!-- BOT -->
                <div class="block">
                    <div style="display: flex; margin-bottom: 10px;">
                        <div style="align-self: center;margin-right: auto;">BOT</div>
                        <button class="create-new">
                            <div>+</div>
                            NEW
                        </button>
                    </div>
                    <VuePerfectScrollbar class="scroll-area">
                        <div class="sub-block" v-for="item in botList">
                            <div style="margin-right: 20px;display: flex;">
                                <checkbox :data-id="item.id" ref="botSelect" style="align-self: center;"
                                          v-on:data="selectBot(item.id, $event)"></checkbox>
                            </div>
                            <div class="sub-block-container" style="margin-right: 20px;">
                                {{item.name}}
                            </div>
                            <div>
                                <a @click="removeBotById(item.id)" class="remove-button remove-button-white"
                                   style="margin-bottom: 5px;"></a>
                                <a class="copy-button copy-button-white"></a>
                            </div>
                        </div>
                    </VuePerfectScrollbar>
                </div>

                <!-- AGENTS -->
                <div class="block">
                    <div style="display: flex; margin-bottom: 10px;">
                        <div style="align-self: center;margin-right: auto;">AGENTS</div>
                        <button class="create-new">
                            <div>+</div>
                            NEW
                        </button>
                    </div>
                    <VuePerfectScrollbar class="scroll-area">
                        <div class="sub-block chat-themes" v-for="item in agentList">
                            <div style="margin-right: 20px;display: flex;">
                                <checkbox style="align-self: center;"></checkbox>
                            </div>
                            <div class="sub-block-container" style="margin-right: 20px;justify-content: flex-start;">
                                <div class="user-photo-1 online online-big" style="margin-right: 10px;">
                                    <img :src="item.photo" :alt="item.name">
                                </div>
                                {{item.name}}
                            </div>
                            <div>
                                <a @click="removeAgentById(item.id)" class="remove-button remove-button-white"
                                   style="margin-bottom: 5px;"></a>
                                <a class="copy-button copy-button-white"></a>
                            </div>
                        </div>
                    </VuePerfectScrollbar>
                </div>
            </div>
            <svg width="138" height="138" viewBox="0 0 138 138" fill="none" xmlns="http://www.w3.org/2000/svg"
                 style="align-self: flex-end; position: absolute;  right: 20px;bottom: 20px;z-index: -1;">
                <path
                    d="M75.6436 131.173L81.7046 128.021C84.9454 126.335 86.2013 122.29 84.528 119.044L82.5751 115.248C85.5287 112.622 88.0341 109.592 90.1216 106.206L94.1603 107.503C95.834 108.038 97.6297 107.884 99.1882 107.072C100.747 106.261 101.918 104.857 102.446 103.169L104.519 96.6036C105.614 93.0941 103.692 89.3476 100.212 88.2375L94.2337 86.3243C92.8789 85.8927 91.4699 86.6391 91.0442 88.0053C90.6186 89.3716 91.3605 90.7943 92.7153 91.2259L98.6931 93.1391C99.4893 93.3838 99.9259 94.259 99.6846 95.0619L97.6109 101.628C97.488 102.003 97.2325 102.338 96.861 102.526C96.5151 102.712 96.1047 102.747 95.7066 102.625L89.7288 100.712C88.5602 100.343 87.3109 100.841 86.7303 101.907C84.4763 106.063 81.4764 109.658 77.8011 112.606C76.8582 113.364 76.5608 114.72 77.1178 115.793L80.002 121.413C80.3763 122.163 80.0925 123.074 79.3518 123.476L73.2908 126.629C72.945 126.815 72.5345 126.85 72.1364 126.728C71.764 126.603 71.4318 126.345 71.2447 125.97L68.3605 120.35C67.8012 119.25 66.5658 118.704 65.3823 119.066C60.896 120.389 56.2251 120.763 51.5815 120.248C50.3815 120.116 49.2313 120.866 48.8648 122.019L46.9777 128.047C46.7364 128.85 45.8696 129.289 45.0734 129.044L38.5626 126.942C38.1902 126.817 37.858 126.559 37.6708 126.184C37.4859 125.835 37.4504 125.421 37.5711 125.019L39.4582 118.991C39.8225 117.812 39.3269 116.551 38.2688 115.964C34.1459 113.683 30.5788 110.651 27.6512 106.938C26.8978 105.986 25.5541 105.684 24.4909 106.244L18.9252 109.146C18.5537 109.334 18.169 109.367 17.7709 109.244C17.3985 109.12 17.0663 108.861 16.8791 108.486L13.7454 102.366C13.5604 102.017 13.525 101.603 13.6457 101.202C13.7686 100.826 14.0241 100.491 14.3956 100.303L19.9613 97.4017C21.0501 96.8391 21.5638 95.5957 21.2288 94.3989C19.9109 89.8705 19.5333 85.1573 20.0367 80.473C20.1657 79.2626 19.4202 78.1009 18.2773 77.7294L12.2995 75.8162C11.5033 75.5715 11.0667 74.6963 11.308 73.8934L13.3817 67.3277C13.623 66.5248 14.4898 66.0855 15.286 66.3303L21.2638 68.2435C22.4324 68.6128 23.6817 68.1147 24.2623 67.0481C26.5163 62.8919 29.5162 59.2976 33.1915 56.3495C34.1344 55.5908 34.4318 54.2356 33.8748 53.1621L30.9906 47.5422C30.8056 47.193 30.7702 46.7789 30.8908 46.3774C31.0137 46.0019 31.2693 45.6671 31.6408 45.4789L37.7018 42.3265C38.0476 42.1405 38.4581 42.1053 38.8561 42.2277C39.2286 42.3523 39.5608 42.6107 39.7479 42.9857L42.6321 48.6056C43.2838 49.8796 44.8248 50.3734 46.0609 49.7201C47.3226 49.0645 47.8098 47.5105 47.1603 46.2624L44.2761 40.6426C43.4695 39.0689 42.0761 37.8845 40.4024 37.3498C39.5528 37.0836 38.6907 36.9749 37.8442 37.0473C36.9977 37.1198 36.141 37.3757 35.3746 37.7802L29.3136 40.9326C27.7552 41.7439 26.5833 43.1479 26.056 44.8358C25.5286 46.5236 25.6837 48.3355 26.4903 49.9091L28.4432 53.7052C25.4896 56.3308 22.9841 59.3613 20.8966 62.7472L16.8579 61.4502C13.3778 60.3401 9.66738 62.2743 8.57243 65.7838L6.49877 72.3495C5.40383 75.8589 7.32663 79.6055 10.8067 80.7156L14.8454 82.0125C14.6157 85.9694 14.9547 89.9298 15.8536 93.79L12.0941 95.7545C10.5356 96.5659 9.3638 97.9699 8.83644 99.6577C8.30907 101.346 8.46418 103.157 9.27073 104.731L12.4045 110.851C13.211 112.425 14.6044 113.609 16.2781 114.144C17.9518 114.678 19.7475 114.525 21.3059 113.713L25.0655 111.749C27.6724 114.733 30.6821 117.291 34.0133 119.379L32.734 123.452C32.2067 125.139 32.3618 126.951 33.1683 128.525C33.9749 130.099 35.3683 131.283 37.042 131.818L43.5528 133.92C47.0329 135.03 50.7433 133.096 51.8382 129.586L53.1175 125.514C57.0398 125.751 60.9645 125.415 64.7893 124.514L66.7422 128.311C67.5487 129.884 68.9421 131.069 70.6158 131.603C72.2895 132.138 74.0852 131.984 75.6436 131.173Z"
                    fill="white" fill-opacity="0.6"/>
                <path
                    d="M34.8594 86.4C35.9157 97.8344 45.9672 106.282 57.2798 105.314C57.3311 105.31 57.3824 105.305 57.4337 105.301C62.9445 104.777 67.9379 102.133 71.4724 97.8414C75.0068 93.5494 76.6605 88.1148 76.1584 82.5518C76.0388 81.1541 74.787 80.1139 73.4017 80.2325L73.3761 80.2347C71.9652 80.3555 70.9362 81.6169 71.0837 83.0384C71.4684 87.2295 70.2169 91.326 67.5496 94.5789C64.8823 97.8319 61.125 99.8222 56.9716 100.204C56.9459 100.206 56.8946 100.21 56.869 100.213C48.3268 100.944 40.7511 94.5523 39.962 85.9372C39.1684 77.2702 45.4873 69.585 54.0741 68.7717C55.485 68.6509 56.514 67.3895 56.3665 65.968C56.2468 64.5703 54.995 63.5301 53.6098 63.6487C53.6098 63.6487 53.6098 63.6487 53.5841 63.6509C42.1756 64.7057 33.7986 74.9139 34.8594 86.4Z"
                    fill="white" fill-opacity="0.6"/>
                <path
                    d="M91.7603 3.6978L86.8094 4.12161C83.7824 4.38072 81.5325 7.07644 81.794 10.1308L82.0134 12.6934C79.6084 13.5772 77.3326 14.7628 75.2373 16.2459L73.2865 14.5877C72.1718 13.6402 70.7356 13.1634 69.2734 13.2886C67.8113 13.4137 66.4748 14.1018 65.5396 15.2509L62.3522 19.0698C61.4148 20.193 60.9444 21.6413 61.0707 23.1167C61.197 24.5921 61.881 25.9416 63.0214 26.887L64.9721 28.5452C63.8801 30.881 63.1092 33.3459 62.6549 35.8879L60.1154 36.1053C57.0884 36.3644 54.8385 39.0601 55.1 42.1145L55.5276 47.1102C55.7891 50.1646 58.4644 52.4387 61.4914 52.1796L64.0309 51.9622C64.9106 54.39 66.0892 56.6879 67.5624 58.8042L65.9219 60.7699C64.9844 61.8931 64.5141 63.3413 64.6404 64.8168C64.7667 66.2922 65.4507 67.6416 66.591 68.587L70.3811 71.8087C71.4958 72.7562 72.932 73.233 74.3941 73.1078C75.8563 72.9826 77.1928 72.2946 78.128 71.1455L79.7686 69.1799C82.0854 70.2852 84.5296 71.0668 87.0499 71.529L87.2692 74.0916C87.5307 77.146 90.206 79.4201 93.233 79.161L98.1838 78.7372C101.211 78.478 103.461 75.7823 103.199 72.7279L102.98 70.1654C105.385 69.2816 107.661 68.0959 109.756 66.6129L111.707 68.2711C112.821 69.2186 114.258 69.6954 115.72 69.5702C117.182 69.445 118.518 68.757 119.454 67.6079L122.641 63.789C124.586 61.4583 124.285 57.938 121.972 55.9718L120.021 54.3136C121.113 51.9777 121.884 49.5129 122.338 46.9709L124.878 46.7535C127.905 46.4944 130.155 43.7987 129.893 40.7443L129.466 35.7486C129.204 32.6942 126.529 30.4201 123.502 30.6792L120.962 30.8966C120.083 28.4688 118.904 26.1708 117.431 24.0546L119.071 22.0889C121.017 19.7582 120.715 16.2379 118.402 14.2718L114.612 11.0501C113.497 10.1026 112.061 9.6258 110.599 9.75097C109.137 9.87613 107.8 10.5642 106.865 11.7133L105.225 13.6789C102.908 12.5735 100.464 11.7919 97.9434 11.3297L97.724 8.76717C97.4369 5.71499 94.7616 3.44088 91.7603 3.6978ZM104.632 19.127C105.092 19.3744 105.593 19.488 106.08 19.4463C106.747 19.3892 107.366 19.0755 107.812 18.5419L110.741 15.0319C110.835 14.9196 110.935 14.8849 111.012 14.8783C111.089 14.8717 111.194 14.8888 111.305 14.9836L115.095 18.2052C115.263 18.3474 115.285 18.6062 115.144 18.7747L112.215 22.2848C111.441 23.2114 111.405 24.5965 112.184 25.5467C114.268 28.1582 115.788 31.1309 116.709 34.3374C117.042 35.5084 118.169 36.2984 119.375 36.1952L123.915 35.8065C124.146 35.7868 124.341 35.9526 124.361 36.1856L124.788 41.1813C124.808 41.4142 124.644 41.6108 124.414 41.6306L119.873 42.0192C118.667 42.1224 117.689 43.0667 117.562 44.303C117.2 47.6194 116.207 50.8072 114.597 53.735C114.016 54.8016 114.262 56.1625 115.182 56.9442L118.665 59.9053C118.832 60.0474 118.854 60.3063 118.714 60.4747L115.527 64.2937C115.433 64.406 115.332 64.4407 115.255 64.4473C115.178 64.4538 115.074 64.4367 114.962 64.342L111.474 61.3291C110.555 60.5474 109.182 60.5085 108.241 61.293C105.656 63.3917 102.712 64.9213 99.5351 65.8451C98.375 66.1791 97.5937 67.315 97.6978 68.5316L98.09 73.1132C98.11 73.3461 97.9459 73.5427 97.715 73.5624L92.7642 73.9863C92.5333 74.006 92.3382 73.8402 92.3183 73.6072L91.9261 69.0257C91.8219 67.8091 90.8846 66.8203 89.659 66.6905C86.3714 66.3201 83.2102 65.3131 80.3059 63.6843C79.2479 63.097 77.8994 63.3428 77.126 64.2694L74.1964 67.7795C74.1027 67.8918 74.0023 67.9265 73.9253 67.9331C73.8484 67.9397 73.7435 67.9226 73.6321 67.8278L69.842 64.6062C69.7306 64.5114 69.696 64.4101 69.6894 64.3324C69.6828 64.2548 69.6995 64.149 69.7933 64.0367L72.7228 60.5266C73.4962 59.6 73.5327 58.2149 72.7537 57.2647C70.6696 54.6532 69.149 51.6805 68.2285 48.474C67.8957 47.303 66.7686 46.513 65.563 46.6162L61.0225 47.0049C60.7917 47.0246 60.5966 46.8588 60.5767 46.6258L60.149 41.6301C60.1291 41.3972 60.2931 41.2006 60.524 41.1808L65.0644 40.7922C66.2701 40.689 67.2487 39.7447 67.3754 38.5084C67.7375 35.192 68.7308 32.0042 70.3407 29.0764C70.9212 28.0098 70.6755 26.6489 69.7559 25.8672L66.2724 22.9061C66.1609 22.8114 66.1264 22.71 66.1198 22.6324C66.1131 22.5547 66.1299 22.449 66.2236 22.3367L69.411 18.5177C69.5047 18.4054 69.6051 18.3707 69.6821 18.3641C69.759 18.3575 69.8639 18.3746 69.9753 18.4694L73.4588 21.4305C74.3785 22.2122 75.7513 22.2511 76.6919 21.4666C79.2772 19.368 82.2212 17.8383 85.398 16.9145C86.5581 16.5805 87.3394 15.4446 87.2352 14.228L86.8431 9.64647C86.8231 9.41351 86.9872 9.21695 87.218 9.19718L92.1689 8.77337C92.3998 8.75361 92.5948 8.91943 92.6148 9.1524L93.007 13.734C93.1111 14.9505 94.0485 15.9393 95.2741 16.0691C98.5661 16.4913 101.727 17.4983 104.632 19.127Z"
                    fill="white" fill-opacity="0.6"/>
                <path
                    d="M77.4229 42.6808C78.1408 51.0675 85.4979 57.3213 93.8092 56.6098C102.121 55.8983 108.282 48.4873 107.564 40.1007C106.846 31.7141 99.4891 25.4603 91.1778 26.1717C82.8665 26.8832 76.705 34.2942 77.4229 42.6808ZM102.485 40.5355C102.961 46.1006 98.8601 51.0147 93.3449 51.4868C87.8296 51.959 82.9528 47.8134 82.4764 42.2483C82 36.6831 86.1013 31.769 91.6165 31.2969C97.1318 30.8248 102.011 34.9962 102.485 40.5355Z"
                    fill="white" fill-opacity="0.6"/>
            </svg>
            <button @click="openSaveNewBotsApp()" class="create-new"
                    style="align-self: flex-start; position: absolute;right: 25px;top: 85px;">
                <div>+</div>
                SAVE
            </button>
        </div>

        <div v-show="saveBot" id="bot-name"
             style="justify-content: center;align-content: center;display: flex;align-items: center;background: rgba(0, 0, 0, 0.54);position: absolute;color: #ffffff;border-radius: 8px;">
            <div
                style="display: flex;flex-direction: column;width: 40%; height: 35%; background: #FFFFFF; border-radius: 10px;">
                <svg @click="saveBot = false" width="12" height="14" viewBox="0 0 12 14" fill="none"
                     xmlns="http://www.w3.org/2000/svg"
                     style="cursor: pointer; align-self: flex-end; right: 20px; top: 20px;position: relative;">
                    <path
                        d="M11.8575 13.031L6.68798 6.99986L11.8575 0.968765C12.0475 0.747144 12.0475 0.387764 11.8575 0.166115C11.6675 -0.0555343 11.3595 -0.0555343 11.1695 0.166115L6 6.19721L0.830471 0.166115C0.64051 -0.0555343 0.332472 -0.0555343 0.142488 0.166115C-0.047496 0.387736 -0.047496 0.747116 0.142488 0.968765L5.31199 6.99986L0.142488 13.031C-0.047496 13.2526 -0.047496 13.612 0.142488 13.8337C0.332449 14.0553 0.640487 14.0553 0.830471 13.8337L6 7.80251L11.1695 13.8337C11.3595 14.0553 11.6675 14.0553 11.8575 13.8337C12.0475 13.612 12.0475 13.2526 11.8575 13.031Z"
                        fill="#5A5E60"/>
                </svg>
                <div
                    style="margin-top: auto;margin-bottom: auto;display: flex;align-content: center;align-items: center;justify-items: center;flex-direction: column;">
                    <input id="botsapp-name" placeholder="Write name of your Botsapp" type="text"
                           style="width: 60%; background: rgba(56, 58, 60, 0.1) none repeat scroll 0% 0%; border-radius: 10px;border: 0;padding: 9px;">
                    <button @click="saveNewBotsApp()" class="create-new"
                            style="margin-top: 15px; width: 40%; text-align: center; justify-content: center;padding: 15px 10px;">
                        SAVE
                    </button>
                </div>
            </div>
        </div>

        <!-- BOTSAPP CODE -->
        <div v-show="showCodeStatus" id="show-code">
            <div>
                <svg @click="showCodeStatus = false" width="12" height="14" viewBox="0 0 12 14" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M11.8575 13.031L6.68798 6.99986L11.8575 0.968765C12.0475 0.747144 12.0475 0.387764 11.8575 0.166115C11.6675 -0.0555343 11.3595 -0.0555343 11.1695 0.166115L6 6.19721L0.830471 0.166115C0.64051 -0.0555343 0.332472 -0.0555343 0.142488 0.166115C-0.047496 0.387736 -0.047496 0.747116 0.142488 0.968765L5.31199 6.99986L0.142488 13.031C-0.047496 13.2526 -0.047496 13.612 0.142488 13.8337C0.332449 14.0553 0.640487 14.0553 0.830471 13.8337L6 7.80251L11.1695 13.8337C11.3595 14.0553 11.6675 14.0553 11.8575 13.8337C12.0475 13.612 12.0475 13.2526 11.8575 13.031Z"
                        fill="#5A5E60"></path>
                </svg>
                <div>
                    <a @click="copyToClipboard()" class="copy-button copy-button-white"></a>
                    <textarea id="tempCode" readonly>{{ tempCode }}</textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import checkbox from "../tool-component/checkbox";
    import VuePerfectScrollbar from 'vue-perfect-scrollbar'

    export default {
        components: {
            checkbox,
            VuePerfectScrollbar
        },
        computed: {},
        mounted() {
            axios.get(`/client-chat`)
                .then((response) => {
                    console.log(response.data);
                    for (let i = 0; i < response.data.length; i++) {
                        this.botsAppList.push({
                            ...response.data[i],
                            id: response.data[i].id,
                            name: response.data[i].name,
                            agents: []
                        });
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        methods: {
            copyToClipboard() {
                let copyText = document.getElementById("tempCode");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
                this.showCodeStatus = false;
            },
            editApp(id) {
                // this.selectedBotId = id;
            },
            showCode(id) {
                this.showCodeStatus = true;
                // this.tempCode = this.botsAppList.find(x => x.id === id).secret;
                // console.log(this.botList);

                let rand = Math.random().toString().replace('.', '_');
                this.tempCode = "<div id=\"container_" + (rand) + "\"></div>\n";
                this.tempCode += `<script src=\"//${window.location.host}/bot/botico.js\"><\/script>\n`;
                this.tempCode += "<script>\n";
                this.tempCode += "var boticoChat = new BoticoChat({\n" +
                    `   server: '//${window.location.host}/',\n` +
                    "   container: '#container_" + rand + "',\n" +
                    "   secret: '" + this.botsAppList.find(x => x.id === id).secret + "'\n" +
                    "});\n";
                this.tempCode += "<\/script>";

                document.querySelector('#show-code').style.width =
                    document.querySelector('.botsapps').getBoundingClientRect().width + 'px';
                document.querySelector('#show-code').style.height =
                    document.querySelector('.botsapps').getBoundingClientRect().height + 'px';
            },
            selectWidget(id, status) {
                for (let i = 0; i < this.$refs.widgetSelect.length; i++) {
                    if (this.$refs.widgetSelect[i].$el.getAttribute('data-id') * 1 === id * 1) continue;
                    this.$refs.widgetSelect[i].data = false;
                }
                if (!status) this.selectedWidget = 0;
                else this.selectedWidget = id;
            },
            selectBot(id, status) {
                for (let i = 0; i < this.$refs.botSelect.length; i++) {
                    if (this.$refs.botSelect[i].$el.getAttribute('data-id') * 1 === id * 1) continue;
                    this.$refs.botSelect[i].data = false;
                }
                if (!status) this.selectedBot = 0;
                else this.selectedBot = id;
            },
            removeWidgetById(id) {
                for (let i = 0; i < this.widgetList.length; i++) {
                    if (this.widgetList[i].id === id) {
                        this.widgetList.splice(i, 1);
                        return;
                    }
                }
            },
            removeBotById(id) {
                for (let i = 0; i < this.botList.length; i++) {
                    if (this.botList[i].id === id) {
                        this.botList.splice(i, 1);
                        return;
                    }
                }
            },
            removeAgentById(id) {
                for (let i = 0; i < this.agentList.length; i++) {
                    if (this.agentList[i].id === id) {
                        this.agentList.splice(i, 1);
                        return;
                    }
                }
            },
            removeApp(appId) {
                for (let i = 0; i < this.botsAppList.length; i++) {
                    if (this.botsAppList[i].id === appId) {
                        this.botsAppList.splice(i, 1);
                        axios.delete(`/client-chat/${appId}`);
                        return;
                    }
                }
            },
            removeAgent(appId, agentId) {
                for (let i = 0; i < this.botsAppList.length; i++) {
                    for (let j = 0; j < this.botsAppList[i].agents.length; j++) {
                        if (this.botsAppList[i].id === appId && this.botsAppList[i].agents[j].id === agentId) {
                            this.botsAppList[i].agents.splice(j, 1);
                            return;
                        }
                    }
                }
            },
            createNewBotsApp() {
                this.selectedBotId = -1;

                this.loadWidgets();
                this.loadBots();
            },
            openSaveNewBotsApp() {
                if (!this.selectedWidget || !this.selectedBot) return;
                this.saveBot = true;
                document.querySelector('#bot-name').style.width =
                    document.querySelector('.add-botsapp').getBoundingClientRect().width + 'px';
                document.querySelector('#bot-name').style.height =
                    document.querySelector('.add-botsapp').getBoundingClientRect().height + 'px';
            },
            saveNewBotsApp() {
                if (!document.querySelector('#botsapp-name').value) return;
                if (!this.selectedWidget || !this.selectedBot) return;
                this.selectedBotId = 0;
                this.saveBot = false;

                axios.post(`/client-chat`, {
                    name: document.querySelector('#botsapp-name').value,
                    bot_id: this.selectedBot,
                    widget_id: this.selectedWidget
                })
                    .then((response) => {
                        document.querySelector('#botsapp-name').value = '';
                        this.botsAppList.push({
                            ...response.data,
                            id: response.data.id,
                            name: response.data.name,
                            agents: []
                        });
                    })
                    .catch((error) => {
                        console.log(error);
                    });

                /*this.botsAppList.push({
                    id: 1,
                    name: 'sgasx',
                    agents: []
                });*/


                /**/
            },
            loadWidgets() {
                axios.get('/client-widget')
                    .then((response) => {
                        console.log(response);
                        this.widgetList.length = 0;
                        for (let i = 0; i < response.data.length; i++) {
                            this.widgetList.push({
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
            loadBots() {
                axios.get('/client-bot')
                    .then((response) => {
                        console.log(response);
                        this.botList.length = 0;
                        for (let i = 0; i < response.data.length; i++) {
                            this.botList.push({name: response.data[i].name, id: response.data[i].id});
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        data() {
            return {
                selectedWidget: 0,
                selectedBot: 0,
                // createNewBotApp: false,
                selectedBotId: 0,
                saveBot: false,
                showCodeStatus: false,
                tempCode: '',
                botsAppList: [
                    /*{
                        id: 1,
                        name: 'Test bot',
                        agents: [
                            { id: 1, name: 'Alex', photo: 'https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png' },
                            { id: 2, name: 'Anna', photo: 'https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png' },
                        ]
                    }*/
                ],
                widgetList: [
                    /*{ id: 1, name: 'Red', theme: 'theme-red' },
                    { id: 2, name: 'Neon', theme: 'theme-neon' },
                    { id: 3, name: 'Summertime', theme: 'theme-summertime' }*/
                ],
                botList: [
                    /*{ id: 1, name: 'Alpha' },
                    { id: 2, name: 'Beta' },*/
                ],
                agentList: [
                    {
                        id: 1,
                        name: 'Alex',
                        photo: 'https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png'
                    },
                    {
                        id: 2,
                        name: 'Anna',
                        photo: 'https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png'
                    },
                    {
                        id: 1,
                        name: 'Alex',
                        photo: 'https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png'
                    },
                    {
                        id: 2,
                        name: 'Anna',
                        photo: 'https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png'
                    },
                    {
                        id: 1,
                        name: 'Alex',
                        photo: 'https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png'
                    },
                    {
                        id: 2,
                        name: 'Anna',
                        photo: 'https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png'
                    },
                    {
                        id: 1,
                        name: 'Alex',
                        photo: 'https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png'
                    },
                    {
                        id: 2,
                        name: 'Anna',
                        photo: 'https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png'
                    },
                ],
            }
        }
    }
</script>

<style lang="scss" scoped>
    .botsapp-container {
        .scroll-area {
            height: 274px;
        }

        #show-code {
            justify-content: center;
            align-content: center;
            display: flex;
            align-items: center;
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.54);
            position: absolute;
            color: #ffffff;

            > div {
                display: flex;
                position: relative;
                flex-direction: column;
                width: 50%;
                height: 60%;
                background: #FFFFFF;
                border-radius: 10px;

                svg {
                    cursor: pointer;
                    align-self: flex-end;
                    margin-right: 10px;
                    margin-top: 10px;
                }

                > div {
                    position: relative;
                    width: 80%;
                    height: 80%;
                    align-self: center;
                    margin-top: auto;
                    margin-bottom: auto;

                    .copy-button {
                        margin-right: 5px;
                        position: absolute;
                        top: 12px;
                        right: 7px;
                    }

                    textarea {
                        padding: 25px;
                        border: 0;
                        margin-top: auto;
                        margin-bottom: auto;
                        align-self: center;
                        background: rgba(56, 58, 60, 0.32);
                        border-radius: 10px;
                        width: 100%;
                        height: 100%;
                        display: block;
                        box-sizing: border-box;
                    }
                }
            }
        }
    }

    .add-botsapp {
        display: flex;
        flex: 1;
        background: linear-gradient(129.9deg, rgba(255, 255, 255, 0.5) -44.9%, rgba(251, 249, 249, 0.255841) 51.5%, rgba(97, 97, 97, 0) 130.26%);
        box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
        border-radius: 10px;

        .block {
            padding: 15px;
            color: #ffffff;
            background: #23282B;
            border-radius: 10px;
            margin-right: 10px;
            flex: 1;

            .sub-block {
                display: flex;
                background: #FFFFFF;
                border-radius: 10px;
                padding: 15px;
                margin-bottom: 10px;

                .sub-block-container {
                    background: #23282B;
                    margin-right: 10px;
                    padding: 10px;
                    display: flex;
                    align-items: center;
                    border-radius: 9px;
                    flex: 1;
                    text-align: center;
                    justify-content: center;
                }
            }
        }
    }

    .botsapps {
        flex: 1;
        display: flex;
        flex-direction: column;
        border-radius: 8px;
        overflow: hidden;

        .agent {
            background: #23282B;
            padding: 5px 10px;
            border-radius: 6px;
            margin-right: 10px;
            display: flex;

            div {
                align-self: center;
            }
        }

        .add-item {
            width: 20px;
            height: 20px;
            background: #fff;
            border-radius: 14px;
            color: #655f62;
            font-weight: bold;
            text-align: center;
            line-height: 18px;
            margin-right: 5px;
            justify-content: center;
        }

        .botsapp-header {
            background: #2D3235;
            border-radius: 10px 10px 0 0;
            display: flex;
            padding: 15px 20px;
            color: #fff;

            > div {
                flex: 1;
                align-self: center;
                text-align: center;
                display: flex;
                //align-items: center;
                //justify-content: center;
            }
        }

        .botsapp {
            background: #7D7D7D;
            display: flex;
            padding: 15px 20px;
            color: #fff;
            cursor: pointer;


            > div {
                flex: 1;
                text-align: center;
                display: flex;
                align-items: center;
                //justify-content: center;
            }

            &:hover {
                background: #8f8f8f;
            }
        }
    }
</style>
