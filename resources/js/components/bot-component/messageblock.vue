<template>
    <div ref="message" class="message-block-component"
         :class="[$data._isDraggable ?'message-block-draggable' :'', isDraggable ?'message-absolute' :'']">
        <div class="block-main-area" :class="['question-multiple', 'question-scale', 'question-rating'].indexOf($data._blockType) !== -1 ?'block-green-main-area' :''">
            <div class="block-header">
                <span class="name">{{ $data._blockName }}</span>
                <div v-if="isDraggable && $data._blockType !== 'entry-point'" class="icons">
                    <a @click="copyBlock()" class="copy-button button-white-bg copy-button-black"
                       style="margin-right: 8px;"></a>
                    <a @click.stop="saveBlock()" class="button-white-bg"
                       style="margin-right: 8px;"
                       :class="[$data._isDraggable ?'edit-button edit-button-black' :'save-button save-button-black']"></a>
                    <a @click="removeBlock()" class="remove-button button-white-bg remove-button-black"></a>
                </div>
                <div v-if="!isDraggable && $data._blockType !== 'entry-point'" class="icons">
                    <a @click.stop="saveBlock()" class="button-white-bg save-button save-button-black"></a>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="block-body" v-if="$data._showBaseContent">
                <div class="block-content">
                    <div style="word-wrap: break-word;width: 100%;" v-if="$data._isDraggable"
                         v-html="tagReplace($data._content).replace(/\n/g, '<br>')"></div>
                    <textarea ref="blockContentTextarea" v-show="!$data._isDraggable" v-model="$data._content"
                              placeholder="Click here to edit...">{{ $data._content }}</textarea>
                </div>

                <svg class="pin" data-id="0" @mousedown="bindBlock();"
                     v-if="$data._isDraggable && ['entry-point', 'simple-block', 'question-answer', 'question-request', 'question-scale'].indexOf($data._blockType) !== -1"
                     width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"
                     style="cursor: pointer;position: absolute;top: 0;right: -18px;">
                    <path
                        d="M25 12.5C25 19.4036 19.4036 25 12.5 25C5.59644 25 0 19.4036 0 12.5C0 5.59644 5.59644 0 12.5 0C19.4036 0 25 5.59644 25 12.5Z"
                        fill="#23282B"/>
                    <path
                        d="M12.7855 7.80565L12.7855 11.1436L7.84591 11.1436L7.84591 14.8426L12.7855 14.8426L12.7855 18.1805L17.9629 12.9931L12.7855 7.80565Z"
                        fill="white"/>
                </svg>

                <div v-if="!$data._isDraggable" class="button-list">
                    <!-- <button class="button">Text</button> -->
                    <button @click="openMediaUpload()" class="button" :disabled="canAddAnotherMedia()">Media</button>
                    <button @click="openVariables()" class="button button-black">Variables</button>
                </div>
            </div>
        </div>

        <div class="block-edit-area"
             v-if="['question-multiple', 'question-rating',
             'question-scale', 'question-request', 'block-notification',
             'block-emoji', 'block-ab'].indexOf($data._blockType) !== -1">
            <!-- MULTIPLE CHOICE -->
            <div v-if="$data._blockType === 'question-multiple'">
                <!-- BUTTONS -->
                <div v-for="(pin, index) in $data._pins" class="block-body input-field" :class="[$data._pins[index] ?'input-field' :'input-field-null']" style="display: flex;align-items: center;">
                    <input ref="fileSelectImageButton" type='file' style="position: absolute; opacity: 0; z-index: -10;"/>
                    <input @click="$data._selectedLinkId = index" ref="imageButtonField" type="text" placeholder="Click here to edit"
                           v-model="$data._pins[index]">

                    <!--<img @click="$data._showButtonLink = true; $data._selectedLinkId = index" v-if="!$data._isDraggable"
                         src="/assets/images/design/link.png"
                         style="width: 24px;height: 24px;cursor: pointer;position: absolute;right: 43px;filter: brightness(0);border-radius: 16px;">
                    <img @click="uploadImageButton(index)" v-if="!$data._isDraggable"
                         src="/assets/images/design/background-image.png"
                         style="cursor: pointer;position: absolute;right: 13px;filter: brightness(0);border-radius: 16px;">-->

                    <div @click="$data._showButtonLink = true; $data._selectedLinkId = index" v-if="!$data._isDraggable"
                         style="display: flex; align-items: center; justify-content: center; width: 24px;height: 24px;cursor: pointer;position: absolute;right: 43px;border-radius: 16px; background: #275F14;">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.71463 8.81486L4.22863 9.29786C4.02503 9.49826 3.7508 9.61058 3.46513 9.61058C3.17945 9.61058 2.90522 9.49826 2.70163 9.29786C2.60236 9.19956 2.52356 9.08256 2.46978 8.95362C2.416 8.82468 2.38831 8.68637 2.38831 8.54666C2.38831 8.40696 2.416 8.26864 2.46978 8.1397C2.52356 8.01076 2.60236 7.89376 2.70163 7.79546L4.48963 6.02246C4.85983 5.65466 5.55703 5.11346 6.06523 5.61746C6.11992 5.67578 6.18579 5.72248 6.25893 5.75478C6.33206 5.78707 6.41095 5.80431 6.49089 5.80544C6.57083 5.80658 6.65018 5.79161 6.72421 5.76141C6.79823 5.73121 6.86541 5.6864 6.92174 5.62967C6.97807 5.57294 7.0224 5.50544 7.05207 5.4312C7.08174 5.35696 7.09615 5.27751 7.09444 5.19758C7.09273 5.11765 7.07494 5.03888 7.04212 4.96598C7.0093 4.89308 6.96213 4.82753 6.90343 4.77326C6.04063 3.91646 4.76383 4.07486 3.65203 5.17826L1.86403 6.95186C1.65276 7.16015 1.48515 7.40847 1.371 7.6823C1.25685 7.95614 1.19846 8.24999 1.19923 8.54666C1.19849 8.84333 1.2569 9.13717 1.37105 9.411C1.4852 9.68483 1.65279 9.93316 1.86403 10.1415C2.28984 10.5638 2.86566 10.8003 3.46543 10.7991C4.04563 10.7991 4.62583 10.5801 5.06683 10.1415L5.55343 9.65846C5.60892 9.60358 5.65303 9.53829 5.68322 9.46632C5.71342 9.39435 5.7291 9.31713 5.72938 9.23909C5.72966 9.16104 5.71453 9.08371 5.68485 9.01153C5.65517 8.93935 5.61152 8.87374 5.55643 8.81846C5.44505 8.70678 5.29399 8.64372 5.13627 8.64304C4.97854 8.64237 4.82696 8.70414 4.71463 8.81486ZM10.1338 1.92446C9.20563 1.00406 7.90843 0.954261 7.05043 1.80626L6.44503 2.40746C6.33316 2.51853 6.26999 2.6695 6.26943 2.82714C6.26887 2.98478 6.33095 3.13619 6.44203 3.24806C6.5531 3.35993 6.70406 3.42309 6.8617 3.42366C7.01935 3.42422 7.17076 3.36213 7.28263 3.25106L7.88863 2.65046C8.33263 2.20886 8.91523 2.39186 9.29623 2.76866C9.49783 2.96966 9.60943 3.23606 9.60943 3.51986C9.60943 3.80366 9.49783 4.07006 9.29623 4.27046L7.38823 6.16286C6.51583 7.02746 6.10663 6.62246 5.93203 6.44906C5.87663 6.39406 5.81095 6.35052 5.73873 6.3209C5.66651 6.29129 5.58916 6.27619 5.5111 6.27647C5.43305 6.27675 5.35581 6.2924 5.2838 6.32253C5.21179 6.35265 5.14642 6.39667 5.09143 6.45206C5.03643 6.50745 4.99288 6.57313 4.96327 6.64536C4.93365 6.71758 4.91855 6.79493 4.91883 6.87298C4.91911 6.95104 4.93476 7.02828 4.96489 7.10029C4.99502 7.17229 5.03903 7.23766 5.09443 7.29266C5.49523 7.68986 5.95243 7.88666 6.43123 7.88666C7.01743 7.88666 7.63723 7.59146 8.22703 7.00646L10.135 5.11466C10.3458 4.90606 10.5131 4.65767 10.6271 4.3839C10.7411 4.11012 10.7996 3.81643 10.7992 3.51986C10.7998 3.22308 10.7412 2.92916 10.627 2.65525C10.5127 2.38133 10.3451 2.13291 10.1338 1.92446Z" fill="white"/>
                        </svg>
                    </div>

                    <div @click="uploadImageButton(index)" v-if="!$data._isDraggable"
                         style="display: flex; align-items: center; justify-content: center; width: 24px;height: 24px;cursor: pointer;position: absolute;right: 13px;border-radius: 16px; background: #275F14;">
                        <svg width="11" height="11" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.33333 1.33333V10.6667H10.6667V1.33333H1.33333ZM1.33333 0H10.6667C11.0203 0 11.3594 0.140476 11.6095 0.390524C11.8595 0.640573 12 0.979711 12 1.33333V10.6667C12 11.0203 11.8595 11.3594 11.6095 11.6095C11.3594 11.8595 11.0203 12 10.6667 12H1.33333C0.979711 12 0.640573 11.8595 0.390524 11.6095C0.140476 11.3594 0 11.0203 0 10.6667V1.33333C0 0.979711 0.140476 0.640573 0.390524 0.390524C0.640573 0.140476 0.979711 0 1.33333 0ZM3.66667 4.66667C3.93188 4.66667 4.18624 4.56131 4.37377 4.37377C4.56131 4.18624 4.66667 3.93188 4.66667 3.66667C4.66667 3.40145 4.56131 3.1471 4.37377 2.95956C4.18624 2.77202 3.93188 2.66667 3.66667 2.66667C3.40145 2.66667 3.1471 2.77202 2.95956 2.95956C2.77202 3.1471 2.66667 3.40145 2.66667 3.66667C2.66667 3.93188 2.77202 4.18624 2.95956 4.37377C3.1471 4.56131 3.40145 4.66667 3.66667 4.66667ZM2.66667 7.33333L4 6L5.33333 7.33333L7.33333 5.33333L9.33333 7.33333V9.33333H2.66667V7.33333Z" fill="white"/>
                        </svg>
                    </div>

                    <img v-if="$data._isDraggable" @mousedown.prevent="bindBlock(index)" class="pin pin-icon"
                         :data-id="index" src="/assets/images/bots/add-new.png">
                </div>

                <!-- ADD NEW -->
                <div v-if="!$data._isDraggable" @click="addPin()" class="block-body"
                     style="display: flex;align-items: center;cursor: pointer;background: #C8C8C8;border: 1.5px dashed #595959;">
                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"
                         style="margin-right: 5px;">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M12.5 25C19.4036 25 25 19.4036 25 12.5C25 5.59644 19.4036 0 12.5 0C5.59644 0 0 5.59644 0 12.5C0 19.4036 5.59644 25 12.5 25ZM16.8506 12.1357H13.2822V8.40039H11.6475V12.1357H8.0791V13.6738H11.6475V17.7168H13.2822V13.6738H16.8506V12.1357Z"
                              fill="#275F14"/>
                    </svg>

                    Add another button
                </div>

                <!-- EXTERNAL LINK -->
                <div class="block-header" v-if="!$data._isDraggable && $data._showButtonLink">
                    <span class="name">External link</span>
                </div>
                <div v-if="!$data._isDraggable && $data._showButtonLink" class="block-body"
                     style="display: flex;align-items: center;cursor: pointer;">
                    <input type="text" placeholder="https://" v-model="$data._externalLink[$data._selectedLinkId]">
                </div>
            </div>

            <!-- RATING -->
            <div v-if="$data._blockType === 'question-rating'">
                <!-- BUTTONS -->
                <div v-for="(pin, index) in [1, 2, 3, 4, 5]" class="block-body" style="display: flex;align-items: center;background: #C8C8C8;">
                    <div style="flex: 1; display: flex; padding: 3px;">
                        <!--<svg v-for="y in new Array(index + 1)" style="margin-right: 5px;" width="13" height="12"
                             viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6.80749 0.198113L8.46065 3.98556L12.6939 4.33517C12.9908 4.36237 13.1071 4.71586 12.8824 4.89844L9.67243 7.59432L10.6354 11.6032C10.6996 11.879 10.3907 12.1004 10.1379 11.9528L6.49852 9.82795L2.85917 11.9528C2.61039 12.0965 2.29742 11.879 2.36162 11.6032L3.32462 7.59432L0.114607 4.89844C-0.106082 4.71586 0.0102814 4.35848 0.307208 4.33517L4.54041 3.98168L6.19357 0.198113C6.30592 -0.0660376 6.69112 -0.0660376 6.80749 0.198113Z"
                                fill="#529E37"/>
                        </svg>-->
                        <svg v-for="y in new Array(index + 1)" style="margin-right: 5px;" width="15" height="14" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 0L12.2269 4.5585L17.5595 6.21885L14.2213 10.6965L14.2901 16.2812L9 14.49L3.70993 16.2812L3.7787 10.6965L0.440492 6.21885L5.77306 4.5585L9 0Z" fill="#275F14"/>
                        </svg>
                    </div>
                    <img v-if="$data._isDraggable" @mousedown.prevent="bindBlock(pin)" class="pin pin-icon" :data-id="pin"
                         src="/assets/images/bots/add-new.png">
                </div>

                <!-- DEFAULT -->
                <!--<div class="block-body" style="display: flex;align-items: center;">
                    <div style="flex: 1;">
                        Default
                    </div>
                    <img v-if="$data._isDraggable" @mousedown.prevent="bindBlock(0)" class="pin pin-icon" :data-id="0"
                         src="/assets/images/bots/add-new.png">
                </div>-->
            </div>

            <!-- SCALE -->
            <div v-if="$data._blockType === 'question-scale'">
                <div class="block-body" style="background: none;border: 1.4px solid #576167;">
                    <!-- RANGE -->
                    <div style="display: flex;margin-bottom: 10px;">
                        <input v-model="$data._leftRange" type="text"
                               style="flex: 1; min-width: 0px; border-radius: 8px; padding: 10px;font-size: 12px; margin: 5px; margin-right: 10px;">
                        <input v-model="$data._rightRange" type="text"
                               style="flex: 1; min-width: 0px; border-radius: 8px; padding: 10px;font-size: 12px; margin: 5px;">
                    </div>

                    <!-- LINE -->
                    <div ref="dragScaleArea" style="display: flex;position: relative; ">
                        <div ref="dragLeftScale" style="position: absolute; top: -2px; cursor: pointer; width: 18px; height: 7px; border-radius: 5px;background: #52A436;"></div>
                        <div style="flex: 1;"></div>
                        <div ref="dragRightScale" style="position: absolute; right: 0; top: -2px; cursor: pointer; width: 18px; height: 7px; border-radius: 5px;background: #52A436;"></div>
                    </div>
                    <div style="width: 100%; height: 3px; background: #394146;margin-bottom: 5px;"></div>
                    <div ref="dragAreaLine" style="pointer-events: none;height: 3px; background: #52A436;margin-bottom: 5px;position: absolute; " :style="[$data._isDraggable ?{bottom: '5px'} :{bottom: '10px'}]"></div>
                </div>
            </div>

            <!-- QUESTION REQUEST -->
            <div v-if="$data._blockType === 'question-request'">
                <div class="block-body" style="display: flex; cursor: pointer;">
                    <span @click="$data._showRequestVariables = !$data._showRequestVariables"
                          style="flex: 1;align-self: center;">{{ $data._parameter || 'Choose parameter...' }}</span>
                    <svg v-if="!$data._isDraggable" style="align-self: flex-end;" width="25" height="25" viewBox="0 0 25 25"
                         fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M12.5 25C19.4036 25 25 19.4036 25 12.5C25 5.59644 19.4036 0 12.5 0C5.59644 0 0 5.59644 0 12.5C0 19.4036 5.59644 25 12.5 25ZM17.7907 11.3815C17.7907 11.4838 17.7535 11.5768 17.6791 11.6512L12.6651 16.6745C12.5163 16.8233 12.2744 16.8233 12.1256 16.6745L7.11163 11.6512C6.96279 11.5024 6.96279 11.2606 7.11163 11.1117C7.26047 10.9629 7.50233 10.9629 7.65116 11.1117L12.3953 15.8559L17.1395 11.1117C17.2884 10.9629 17.5302 10.9629 17.6791 11.1117C17.7535 11.1861 17.7907 11.2792 17.7907 11.3815Z"
                              fill="#23282B"/>
                    </svg>
                </div>
                <div @click="$data._showRequestVariables = false; $data._parameter = variable;"
                     v-if="$data._showRequestVariables" v-for="variable in ['@name', '@phone', '@email', '@address']"
                     class="block-body" style="display: flex; cursor: pointer;">
                    {{ variable }}
                </div>
            </div>

            <!-- NOTIFICATION STATUS -->
            <div v-if="$data._blockType === 'block-notification'">
                <!-- Notification type -->
                <div @click="$data._showNotificationTypeList = !$data._showNotificationTypeList" class="block-body"
                     style="display: flex; cursor: pointer;text-transform: capitalize;">
                    <div :class="$data._notificationType + '-notification'"
                         style="margin-right: 5px; width: 28px;height: 28px;display: flex;align-items: center;justify-content: center;border-radius: 32px;">
                        <svg width="7" height="10" viewBox="0 0 7 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.5 10C2.65073 10 1.96215 9.26305 1.96215 8.35325H5.03718C5.03785 9.26305 4.34861 10 3.5 10ZM7 7.22436C7 7.6348 6.68924 7.66112 6.30677 7.66112H0.693227C0.310093 7.66112 0 7.63409 0 7.22436V7.15322C0 6.85802 0.162019 6.60549 0.394422 6.48599L0.61421 4.45156C0.61421 2.96201 1.59761 1.71931 2.90637 1.42552V0.635937C2.90637 0.284535 3.17198 0 3.5 0C3.82736 0 4.09363 0.284535 4.09363 0.635937V1.42623C5.40305 1.71931 6.38579 2.96273 6.38579 4.45227L6.60624 6.48741C6.83865 6.60763 7 6.85944 7 7.15465V7.22436Z"
                                fill="white"/>
                        </svg>
                    </div>
                    <div style="align-self: center;">
                        {{ $data._notificationType }} notification status
                    </div>

                    <svg class="pin" data-id="0" @mousedown="bindBlock();"
                         v-if="$data._isDraggable"
                         width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"
                         style="cursor: pointer;position: absolute;top: 0;right: -18px;">
                        <path
                            d="M25 12.5C25 19.4036 19.4036 25 12.5 25C5.59644 25 0 19.4036 0 12.5C0 5.59644 5.59644 0 12.5 0C19.4036 0 25 5.59644 25 12.5Z"
                            fill="#23282B"/>
                        <path
                            d="M12.7855 7.80565L12.7855 11.1436L7.84591 11.1436L7.84591 14.8426L12.7855 14.8426L12.7855 18.1805L17.9629 12.9931L12.7855 7.80565Z"
                            fill="white"/>
                    </svg>
                </div>
                <div v-if="$data._showNotificationTypeList"
                     style="padding: 10px;margin-bottom: 10px; background: #ffffff;border-radius: 6px;color: #333333;font-size: 12px;">
                    <div @click="$data._notificationType = 'red'; $data._showNotificationTypeList = false;"
                         style="padding: 5px; cursor: pointer;">Red status
                    </div>
                    <div @click="$data._notificationType = 'green'; $data._showNotificationTypeList = false;"
                         style="padding: 5px; cursor: pointer;">Green status
                    </div>
                    <!--<div @click="$data._notificationType = 'purple'; $data._showNotificationTypeList = false;"
                         style="padding: 5px; cursor: pointer;">Purple status
                    </div>-->
                </div>

                <!--<div class="block-body" v-if="($data._isDraggable && $data._agentExtraInfo.login) || !$data._isDraggable">
                    <input type="text" placeholder="Login" v-model="$data._agentExtraInfo.login">
                </div>-->

                <div class="block-body" v-if="($data._isDraggable && $data._agentExtraInfo.phone) || !$data._isDraggable">
                    <input type="text" placeholder="Phone" v-model="$data._agentExtraInfo.phone">
                </div>

                <div class="block-body" v-if="($data._isDraggable && $data._agentExtraInfo.email) || !$data._isDraggable">
                    <input type="text" placeholder="E-Mail" v-model="$data._agentExtraInfo.email">
                </div>

                <!-- Agent popup list -->


                <div v-for="agent in $data._agentIdList" style="padding: 7px;background: #d8ffcf;border-radius: 8px;color: #2b2b2b;margin-bottom: 5px;">
                    <div style="display: flex;">
                        <div class="user-photo-1 online online-big" style="margin-right: 10px;">
                            <img :src="getAgentById(agent).photo" :alt="getAgentById(agent).name">
                        </div>
                        <div style="flex: 1;margin-right: 10px;align-self: center;">Agent - {{getAgentById(agent).name}}
                        </div>
                        <div style="align-self: center;">
                            <a @click="$data._agentIdList.splice($data._agentIdList.indexOf(agent.id), 1)" class="remove-button remove-button-white"></a>
                        </div>
                    </div>
                </div>

                <!-- Choose agent -->
                <div v-if="$data._agents.filter(x => $data._agentIdList.indexOf(x.id) === -1).length" @click="$data._showAgentList = !$data._showAgentList" class="block-body"
                     style="display: flex; margin-bottom: 10px; cursor: pointer;text-transform: capitalize;">
                    <!--<div style="display: flex;">
                        <div class="user-photo-1 online online-big" style="margin-right: 10px;">
                            <img :src="getAgentById($data._agentId).photo" :alt="getAgentById($data._agentId).name">
                        </div>
                        <div style="margin-right: 10px;align-self: center;">Agent - {{getAgentById($data._agentId).name}}
                        </div>
                    </div>-->
                    <div>Choose agent...</div>
                </div>

                <div v-if="$data._showAgentList"
                     style="padding: 10px 10px 5px;background: #ffffff;border-radius: 6px;color: #333333;font-size: 12px;margin-bottom: 10px;">
                    <div @click="$data._showAgentList = false;$data._agentIdList.push(agent.id);" v-for="agent in $data._agents.filter(x => $data._agentIdList.indexOf(x.id) === -1)"
                         style="display: flex;cursor: pointer; margin-bottom: 5px;">
                        <div class="user-photo-1 online online-big" style="margin-right: 10px;">
                            <img :src="agent.photo" :alt="agent.name">
                        </div>
                        <div style="margin-right: 10px;align-self: center;">{{agent.name}}</div>
                    </div>
                </div>
            </div>

            <!-- EMOJI -->
            <div v-if="$data._blockType === 'block-emoji'">
                <!-- Emoji category -->
                <div v-if="!$data._isDraggable" @click="$data._showEmojiTypeList = !$data._showEmojiTypeList"
                     class="block-body" style="display: flex; cursor: pointer;text-transform: capitalize;">
                    <div style="align-self: center;">
                        {{ $data._emojiCategory || 'Select category...' }}
                    </div>
                </div>
                <!-- Category list -->
                <div v-if="$data._showEmojiTypeList"
                     style="padding: 10px;margin-bottom: 10px; background: #ffffff;border-radius: 6px;color: #333333;font-size: 12px;">
                    <div @click="$data._emojiCategory = emoji; $data._showEmojiTypeList = false;"
                         style="padding: 5px; cursor: pointer;" v-for="emoji in $data._emojiCategoryList">{{ emoji }}
                    </div>
                </div>

                <!-- Emoji list -->
                <div v-if="$data._emojiCategory || $data._emojiId" style="display: flex;">
                    <div :class="[$data._emojiId === emoji.id ?'emoji-selected' :'']" @click="$data._emojiId = emoji.id"
                         v-for="(emoji, index) in $data._emoji"
                         style="cursor: pointer;padding: 5px; background: #ffffff; border-radius: 6px;margin-right: 5px;">
                        <img :src="emoji.url" alt="" style="width: 32px;height: 32px;display: block;">
                    </div>

                    <svg class="pin" data-id="0" @mousedown="bindBlock();"
                         v-if="$data._isDraggable"
                         width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"
                         style="cursor: pointer;position: absolute;right: -11px;">
                        <path
                            d="M25 12.5C25 19.4036 19.4036 25 12.5 25C5.59644 25 0 19.4036 0 12.5C0 5.59644 5.59644 0 12.5 0C19.4036 0 25 5.59644 25 12.5Z"
                            fill="#23282B"/>
                        <path
                            d="M12.7855 7.80565L12.7855 11.1436L7.84591 11.1436L7.84591 14.8426L12.7855 14.8426L12.7855 18.1805L17.9629 12.9931L12.7855 7.80565Z"
                            fill="white"/>
                    </svg>
                </div>
            </div>

            <!-- AB -->
            <div v-if="$data._blockType === 'block-ab'">
                <div style="margin-bottom: 5px;">Test A</div>
                <div class="block-body">
                    <input type="text" v-model="$data._ab[0]" style="width: 22px;" maxlength="3"> %
                    <svg v-if="$data._isDraggable" class="pin" data-id="0" @mousedown="bindBlock();"
                         width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"
                         style="cursor: pointer;position: absolute;top: 0;right: -18px;">
                        <path
                            d="M25 12.5C25 19.4036 19.4036 25 12.5 25C5.59644 25 0 19.4036 0 12.5C0 5.59644 5.59644 0 12.5 0C19.4036 0 25 5.59644 25 12.5Z"
                            fill="#23282B"/>
                        <path
                            d="M12.7855 7.80565L12.7855 11.1436L7.84591 11.1436L7.84591 14.8426L12.7855 14.8426L12.7855 18.1805L17.9629 12.9931L12.7855 7.80565Z"
                            fill="white"/>
                    </svg>
                </div>

                <div style="margin-bottom: 5px;">Test B</div>
                <div class="block-body">
                    <input type="text" v-model="$data._ab[1]" style="width: 22px;" maxlength="3"> %
                    <svg v-if="$data._isDraggable" class="pin" data-id="1" @mousedown="bindBlock(1);"
                         width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"
                         style="cursor: pointer;position: absolute;top: 0;right: -18px;">
                        <path
                            d="M25 12.5C25 19.4036 19.4036 25 12.5 25C5.59644 25 0 19.4036 0 12.5C0 5.59644 5.59644 0 12.5 0C19.4036 0 25 5.59644 25 12.5Z"
                            fill="#23282B"/>
                        <path
                            d="M12.7855 7.80565L12.7855 11.1436L7.84591 11.1436L7.84591 14.8426L12.7855 14.8426L12.7855 18.1805L17.9629 12.9931L12.7855 7.80565Z"
                            fill="white"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- SHOW VARIABLES -->
        <div v-if="$data._showVariableList" style="position: absolute;right: -170px;top: 0;">
            <variables ref="variables" :blockId="blockId" @data="selectVar"></variables>
        </div>
    </div>
</template>

<script>
    import variables from "../bot-component/variables"

    export default {
        props: {
            isDraggable: Boolean,
            isEditMode: Boolean,
            blockId: Number,
            emojiId: Number,
            blockType: String,
            contentType: String,
            content: String,
            parameter: String,
            externalLink: Array,
            pins: Array,
            notificationType: String,
            agentId: Number,
            agentIdList: Array,
            ab: Array,
            range: Array,
            agentExtraInfo: Object,
            position: Object
        },
        name: "messageblock",
        components: {variables},
        computed: {},
        mounted() {
            for (let prop in this.$props) {
                this.$data['_' + prop] = this.$data['_' + prop] || this.$props[prop];
            }

            if (this.$props.pins) this.$data._pins = this.$props.pins;
            if (this.$props.ab) this.$data._ab = this.$props.ab;
            if (this.$props.agentIdList) this.$data._agentIdList = this.$props.agentIdList;
            if (this.$props.range) this.$data._range = this.$props.range;
            if (this.$props.externalLink) this.$data._externalLink = this.$props.externalLink;
            if (this.$props.notificationType) this.$data._notificationType = this.$props.notificationType;
            if (this.$props.agentExtraInfo) this.$data._agentExtraInfo = this.$props.agentExtraInfo;

            this.$data._leftRange = this.$data._range[0];
            this.$data._rightRange = this.$data._range[1];

            if (!this.$data._content) this.$data._content = '';

            if (this.$data._blockType === 'entry-point')
                this.$data._blockName = 'Start';

            if (this.$data._blockType === 'simple-block')
                this.$data._blockName = 'Simple Block / No Answer';

            if (this.$data._blockType === 'question-answer')
                this.$data._blockName = 'Question / Type Answer';

            if (this.$data._blockType === 'question-multiple') {
                this.$data._blockName = 'Question / Single or Multiple Choice';
                if (this.$data._pins.length === 0)
                    this.$data._pins.push('Click here to edit');
            }

            if (this.$data._blockType === 'question-rating')
                this.$data._blockName = 'Question Rating';

            if (this.$data._blockType === 'question-scale')
                this.$data._blockName = 'Question Scale';

            if (this.$data._blockType === 'question-request')
                this.$data._blockName = 'Question / @ Answer';

            if (this.$data._blockType === 'block-notification') {
                this.$data._showBaseContent = false;
                this.$data._blockName = 'Notification Status';
            }

            if (this.$data._blockType === 'block-emoji') {
                this.$data._showBaseContent = false;
                this.$data._blockName = 'Emoji';
            }

            if (this.$data._blockType === 'block-ab') {
                this.$data._showBaseContent = false;
                this.$data._blockName = 'A/B Testing';
            }

            let mouseWheelHandler = function (e) {
                e.stopPropagation();
                return false;
            };

            this.$refs.blockContentTextarea.addEventListener('mousewheel', mouseWheelHandler);
            this.$refs.blockContentTextarea.addEventListener('DOMMouseScroll', mouseWheelHandler);

            if (this.$data._blockType === 'question-scale') {
                setTimeout(() => {
                    // Left
                    let isDragLeft = false;
                    let leftOffsetX = 0;
                    let leftStartX = 0;
                    let scrollLeftPositionX = this.$data._leftRange / 100;

                    // Right
                    let isDragRight = false;
                    let rightOffsetX = 0;
                    let rightStartX = 0;
                    let scrollRightPositionX = this.$data._rightRange / 100;

                    this.$refs.dragLeftScale.addEventListener('mousedown', (e) => {
                        isDragLeft = true;
                        leftStartX = e.pageX;
                        leftOffsetX = leftStartX - this.$refs.dragLeftScale.getBoundingClientRect().left;
                    });
                    this.$refs.dragRightScale.addEventListener('mousedown', (e) => {
                        isDragRight = true;
                        rightStartX = e.pageX;
                        rightOffsetX = rightStartX - this.$refs.dragRightScale.getBoundingClientRect().left;
                    });
                    document.addEventListener('mouseup', function (e) {
                        isDragLeft = false;
                        isDragRight = false;
                    });

                    let refreshXX = () => {
                        this.$refs.dragAreaLine.style.left = 15 + ((this.$refs.dragScaleArea.offsetWidth - this.$refs.dragLeftScale.offsetWidth) * scrollLeftPositionX) + 'px';
                        this.$refs.dragAreaLine.style.width = ((this.$refs.dragScaleArea.offsetWidth - this.$refs.dragLeftScale.offsetWidth) * (scrollRightPositionX - scrollLeftPositionX)) + 'px';

                        this.$refs.dragLeftScale.style.left = ((this.$refs.dragScaleArea.offsetWidth - this.$refs.dragLeftScale.offsetWidth) * scrollLeftPositionX) + 'px';
                        this.$data._range[0] = Math.round(scrollLeftPositionX * 100);
                        this.$data._leftRange = Math.round(scrollLeftPositionX * 100);

                        this.$refs.dragRightScale.style.left = ((this.$refs.dragScaleArea.offsetWidth- this.$refs.dragRightScale.offsetWidth) * scrollRightPositionX) + 'px';
                        this.$data._range[1] = Math.round(scrollRightPositionX * 100);
                        this.$data._rightRange = Math.round(scrollRightPositionX * 100);
                    };

                    document.addEventListener('mousemove', (e) => {
                        if (!this.$refs.dragLeftScale) return;

                        if (isDragLeft) {
                            scrollLeftPositionX = (e.pageX - this.$refs.dragScaleArea.getBoundingClientRect().left - leftOffsetX) / (this.$refs.dragScaleArea.getBoundingClientRect().width - this.$refs.dragLeftScale.getBoundingClientRect().width);
                            if (scrollLeftPositionX < 0) scrollLeftPositionX = 0;
                            if (scrollLeftPositionX > 1) scrollLeftPositionX = 1;
                            if (scrollLeftPositionX > scrollRightPositionX - 0.05) scrollLeftPositionX = scrollRightPositionX - 0.05;
                        }

                        if (isDragRight) {
                            scrollRightPositionX = (e.pageX - this.$refs.dragScaleArea.getBoundingClientRect().left - rightOffsetX) / (this.$refs.dragScaleArea.getBoundingClientRect().width - this.$refs.dragRightScale.getBoundingClientRect().width);
                            if (scrollRightPositionX < 0) scrollRightPositionX = 0;
                            if (scrollRightPositionX > 1) scrollRightPositionX = 1;
                            if (scrollRightPositionX < scrollLeftPositionX + 0.05) scrollRightPositionX = scrollLeftPositionX + 0.05;
                        }
                        refreshXX();
                    });

                    refreshXX();
                }, 16);
            }

            setTimeout(() => {
                if (this.$props.isEditMode) this.$data._isDraggable = false;
            }, 64);
        },
        beforeDestroy() {

        },
        methods: {
            selectVar(blockId, content) {
                this.$data._content += '@' + content + ' ';
                this.$data._showVariableList = false;
            },
            tagReplace(content) {
                content = content.replace(/\[img=(.*?)]/gm, function (r1, r2) {
                    return `<img src="${r2}" style="width: 100%;" alt="photo">`;
                });
                return content.replace(/\[youtube=(.*?)]/gm, function (r1, r2) {
                    r2 = r2.replace(`https://www.youtube.com/watch?v=`, ``);
                    r2 = r2.replace(`https://www.youtu.be/watch?v=`, ``);
                    r2 = r2.replace(`https://www.youtu.be/`, ``);
                    r2 = r2.replace(`https://www.youtube.com/`, ``);
                    r2 = r2.replace(`https://youtu.be/`, ``);
                    return `<iframe width="100%" height="auto" src="https://www.youtube.com/embed/${r2}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                });
            },
            uploadImageButton(id) {
                let parent = this;
                let uploadField = this.$refs.fileSelectImageButton[id];
                uploadField.click();
                uploadField.addEventListener('change', function () {
                    if (!this.files && !this.files[0]) return;

                    let formData = new FormData();
                    formData.append("image", uploadField.files[0]);
                    axios.post('/image-upload', formData, {
                        headers: {'Content-Type': 'multipart/form-data'}
                    }).then((response) => {
                        parent.$refs.imageButtonField[id].value = `[img=${response.data.path}]`;
                        parent.$data._pins[id] = `[img=${response.data.path}]`;
                    }).catch((error) => {
                        console.log(error);
                    });
                });
            },
            removeBlock() {
                this.$emit('remove', this.blockId || 0);
            },
            clone() {
                this.$data._range.length = 2;

                let data = {
                    emojiId: this.$data._emojiId,
                    blockType: this.$data._blockType,
                    contentType: this.$data._contentType,
                    content: this.$data._content,
                    pins: [].concat(this.$data._pins),
                    externalLink: [].concat(this.$data._externalLink),
                    parameter: this.$data._parameter,
                    notificationType: this.$data._notificationType,
                    agentId: this.$data._agentId,
                    agentIdList: [].concat(this.$data._agentIdList),
                    agentExtraInfo: Object.assign(this.$data._agentExtraInfo, {}),
                    ab: [].concat(this.$data._ab),
                    range: [this.$data._leftRange, this.$data._rightRange],
                    position: this.$props.position ?JSON.parse(JSON.stringify(this.$props.position)) :null,
                };
                if (!data.position) delete data.position;
                return data;
            },
            saveBlock() {
                if (this.isDraggable) {
                    this.$data._isDraggable = !this.$data._isDraggable;
                    if (this.$data._isDraggable) {
                        this.$emit('save', this.blockId, this.clone());
                    }
                } else {
                    this.$emit('save', this.blockId, this.clone());
                }
                this.$data._showVariableList = false;
            },
            bindBlock(pin) {
                this.$emit('bind', this.blockId || 0, pin || 0);
            },
            copyBlock() {
                this.$emit('copy', this.clone());
            },
            addPin() {
                this.$data._pins.push('');
            },
            openMediaUpload() {
                this.$emit('openMediaUpload', this.blockId || 0);
            },
            openVariables(field) {
                this.$data._showVariableList = !this.$data._showVariableList;
                // this.$emit('openVariables', this.blockId || 0, field || '_content');
            },
            getAgentById(id) {
                return this.$data._agents.find(x => x.id === id) || {};
            },
            canAddAnotherMedia() {
                return !!this.$data._content.match(/\[[a-z]+=(.*)]/g);
            }
        },
        data() {
            return {
                _isDraggable: false,
                _blockName: '',
                _blockType: null,
                _contentType: 'text',
                _content: '',
                _parameter: '',
                _buttonType: 'text',
                _externalLink: [],
                _pins: [],
                _agentId: 0,
                _agentIdList: [],
                _ab: [30, 70],
                _range: [0, 100],
                _leftRange: 0,
                _rightRange: 100,
                _agents: [
                    {
                        id: 1,
                        name: 'Alex Alexov',
                        photo: 'https://hsto.org/getpro/habr/company/1fc/fb4/e72/1fcfb4e72178093c8e2dd131965d5789.png'
                    },
                    {
                        id: 2,
                        name: 'Anna Blazckowich',
                        photo: 'https://hsto.org/getpro/habr/companies/1a9/3a6/c60/1a93a6c606da91af981d0c1def87e382.png'
                    },
                    {
                        id: 3,
                        name: 'Janna Dark',
                        photo: 'https://hsto.org/getpro/habr/company/1c1/79c/e79/1c179ce792168371a7fe9556db69dc7d.png'
                    },
                    {
                        id: 4,
                        name: 'Boris Kiparisov',
                        photo: 'https://hsto.org/getpro/habr/company/95a/244/327/95a244327fc36477b6048c0f8b67df8b.png'
                    },
                ],
                _agentExtraInfo: {
                    email: '',
                    phone: '',
                    login: ''
                },
                _emojiCategory: '',
                _emojiCategoryList: [
                    'A', 'B', 'C', 'D'
                ],
                _emoji: [
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
                ],
                _emojiId: null,
                _showButtonTypes: false,
                _notificationType: 'red',
                _showNotificationTypeList: false,
                _showAgentList: false,
                _showBaseContent: true,
                _showRequestVariables: false,
                _showEmojiTypeList: false,
                _showButtonLink: false,
                _selectedLinkId: 0,
                _showVariableList: false
            }
        },
    }
</script>

<style scoped lang="scss">
    .message-block-component {
        background: #23282B;
        border-radius: 10px;
        // padding: 15px;
        width: 280px;
        margin-bottom: 15px;
        font-size: 12px;
        display: flex;
        flex-direction: column;
        color: #fff;
        user-select: none;
        -moz-user-select: none;

        .emoji-selected {
            background: #51b236 !important;
        }

        .red-notification {
            background: #d50909;
        }

        .green-notification {
            background: #31b723;
        }

        .purple-notification {
            background: #a115d5;
        }

        .green-button {
            cursor: pointer;
            flex: 1;
            background: #529E37;
            padding: 5px 10px;
            border: 0;
            color: #ffffff;
            border-radius: 5px;
        }

        .pin-icon {
            cursor: pointer;
            width: 24px;
            height: 24px;
            position: relative;
            left: 22px;
            user-select: none;
            -moz-user-select: none;
        }

        .block-green-main-area {
            background: #529E37;
        }

        .block-main-area {
            border-radius: 9px;
            padding: 10px 15px 15px;
        }

        .block-edit-area {
            padding: 15px;
        }

        .input-field {
            background: #C8C8C8 !important;

            input {
                background: #C8C8C8;
            }
        }

        .input-field-null {
            background: #384249 !important;

            input {
                background: #384249;
                color: #8C8C8C;
            }
        }

        .block-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            user-select: none;
            -moz-user-select: none;
            padding: 10px 10px 5px;

            .name {
                text-transform: uppercase;
                font-size: 10px;
                width: 100%;
            }

            .icons {
                margin-left: auto;
                display: flex;
            }
        }

        .block-body {
            padding: 10px;
            background: #ffffff;
            border-radius: 10px;
            color: #2b2b2b;
            font-size: 14px;
            margin-bottom: 7px;
            position: relative;
            border: 0;

            textarea {
                flex: 1;
                resize: none;
                border: 0;
                height: 96px;
            }

            input {
                border: 0;
                flex: 1;
            }

            .block-content {
                // margin-right: 10px;
                font-size: 12px;
                display: flex;
            }

            .button-list {
                display: flex;
                margin-top: 10px;

                .button {
                    border: 0;
                    flex: 1;
                    margin-right: 10px;
                }

                .button:last-child {
                    margin-right: 0;
                }
            }

            .button {
                cursor: pointer;
                padding: 5px;
                border-radius: 12px;
                color: #ffffff;
                background: #529E37;
                text-align: center;
                font-size: 12px;
            }

            .button:disabled {
                color: #ddd;
                background: #A0A0A0;
            }

            .button-black {
                color: #ffffff;
                background: #23282B;
            }
        }

        .block-body:last-child {
            margin-bottom: 0;
        }
    }

    .message-block-draggable {
        // padding: 10px;
        width: 220px;
        position: absolute;
        top: 0;
        left: 0;
        // background: #529E37;

        .block-main-area {
            padding: 10px;
        }

        .block-edit-area {
            padding: 10px;
        }

        .block-header {
            padding: 5px;
            margin-bottom: 5px;
        }

        .block-body {
            padding: 5px;
        }
    }

    .message-absolute {
        position: absolute;
    }
</style>
