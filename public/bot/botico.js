class BoticoChat {
    constructor(params) {
        let previousSize = {};

        // Listen message
        window.addEventListener("message", (event) => {
            if (event.data.event === "openChat") {
                document.querySelector(params.container).style.width = previousSize.width;
                document.querySelector(params.container).style.height = previousSize.height;
                document.querySelector(params.container).style.minWidth = previousSize.minWidth;
                document.querySelector(params.container).style.right = previousSize.right;
                document.querySelector(params.container).style.bottom = previousSize.bottom;
                document.querySelector(params.container).style.left = previousSize.left;
                document.querySelector(params.container).style.top = previousSize.top;
            }
            if (event.data.event === "closeChat") {
                previousSize = {
                    width: document.querySelector(params.container).style.width,
                    height: document.querySelector(params.container).style.height,
                    minWidth: document.querySelector(params.container).style.minWidth,
                    right: document.querySelector(params.container).style.right,
                    bottom: document.querySelector(params.container).style.bottom,
                    left: document.querySelector(params.container).style.left,
                    top: document.querySelector(params.container).style.top,
                };
                document.querySelector(params.container).style.width = event.data.size.width + 1 + 'px';
                document.querySelector(params.container).style.height = event.data.size.height + 1 + 'px';
                document.querySelector(params.container).style.minWidth = event.data.size.width + 1 + 'px';
                document.querySelector(params.container).style.left = 'auto';
                document.querySelector(params.container).style.top = 'auto';
                document.querySelector(params.container).style.right = '10px';
                document.querySelector(params.container).style.bottom = '10px';
            }
        });

        let uniqueId = 'chat_' + (Math.random() + '').replace('.', '_');

        let loadModel = function (server, secret, isTest, onload) {
            let s = {
                "id": 22,
                "company_id": 1,
                "secret": "1QLYyjNddKEHQT1XsI1rdaYzVhctRUzUtw2R4dFS2jeYTwTnqx3kf84CCPg9",
                "name": "test",
                "bot_id": 19,
                "widget_id": 20,
                "created_at": "2019-09-05 14:14:34",
                "updated_at": "2019-09-05 14:14:34",
                "widget": {

                },
                "bot": {
                    "id": 19,
                    "company_id": 1,
                    "name": "BOT1",
                    "created_at": "2019-09-05 13:15:47",
                    "updated_at": "2019-09-05 13:15:47",
                    "blocks": [{
                        "id": 2857133,
                        "blockType": "simple-block",
                        "content": "Hi",
                        "positionX": 1307,
                        "positionY": 810,
                        "parameter": "2",
                        "externalLink": "2",
                        "notificationType": "red",
                        "agentExtraInfo": {"email": null, "phone": null, "login": null},
                        "emojiId": 2,
                        "agentId": 2,
                        "range": [0, 100],
                        "pins": [],
                        "ab": [30, 70]
                    }, {
                        "id": 5039833,
                        "blockType": "question-answer",
                        "content": "Type your name",
                        "positionX": 1488,
                        "positionY": 540,
                        "parameter": "2",
                        "externalLink": "2",
                        "notificationType": "red",
                        "agentExtraInfo": {"email": null, "phone": null, "login": null},
                        "emojiId": 2,
                        "agentId": 2,
                        "range": [0, 100],
                        "pins": [],
                        "ab": [30, 70]
                    }, {
                        "id": 7355099,
                        "blockType": "question-rating",
                        "content": "Has[img=images\/1567992534.jpg]",
                        "positionX": 1021,
                        "positionY": 433,
                        "parameter": "2",
                        "externalLink": "2",
                        "notificationType": "red",
                        "agentExtraInfo": {"email": null, "phone": null, "login": null},
                        "emojiId": 2,
                        "agentId": 2,
                        "range": [0, 100],
                        "pins": [],
                        "ab": [30, 70]
                    }, {
                        "id": 15533940,
                        "blockType": "simple-block",
                        "content": "XA",
                        "positionX": 1196,
                        "positionY": 961,
                        "parameter": "2",
                        "externalLink": "2",
                        "notificationType": "red",
                        "agentExtraInfo": {"email": null, "phone": null, "login": null},
                        "emojiId": 2,
                        "agentId": 2,
                        "range": [0, 100],
                        "pins": [],
                        "ab": [30, 70]
                    }, {
                        "id": 15877863,
                        "blockType": "question-multiple",
                        "content": "Sas",
                        "positionX": 575,
                        "positionY": 516,
                        "parameter": "2",
                        "externalLink": "2",
                        "notificationType": "red",
                        "agentExtraInfo": {"email": null, "phone": null, "login": null},
                        "emojiId": 2,
                        "agentId": 2,
                        "range": [0, 100],
                        "pins": ["[img=images\/1568001153.jpg]", "[img=images\/1568001067.jpg]", "[img=images\/1568001076.jpg]", "[img=images\/1567992534.jpg]", "[img=images\/1567992534.jpg]"],
                        "ab": [30, 70]
                    }],
                    "connections": [[7355099, 5039833, 1], [7355099, 2857133, 0], [15877863, 7355099, 0], [15877863, 15533940, 1]]
                }
            };

            if (isTest) {
                onload({
                    widget: {
                        "widget_size_and_location": {
                            "embedType": "chat-full-page",
                            "width": "100%",
                            "height": "100%"
                        }
                    }
                });
                return;
            }
            let oReq = new XMLHttpRequest();
            oReq.onload = function () {
                console.log(this.responseText);
                onload(JSON.parse(this.responseText));
            };
            oReq.open("get", `${server}client-chat/get/${secret}`, true);
            oReq.send();
        };

        loadModel(params.server, params.secret, params.isTestMode, function (data) {
            console.log(data);
            // console.log(data.widget.widget_size_and_location);

            document
                .querySelector(params.container)
                .innerHTML = `
                <iframe id="${uniqueId}" src="${params.server}bot/botico.chat.php?r=${Math.random()}#secret=${params.secret}&test=${params.isTestMode}&server=${params.server}" style="position: absolute; pointer-events: visible; width: 0;height: 0;border: 0;">
                </iframe>`;

            // Load
            document
                .querySelector(`#${uniqueId}`).onload = () => {

                if (params.isTestMode)
                    document.querySelector(`#${uniqueId}`).contentWindow.postMessage(params.bots, '*');

                document.querySelector(`#${uniqueId}`).style.width = '100%';
                document.querySelector(`#${uniqueId}`).style.height = '100%';

                if (data.widget.widget_size_and_location.embedType === 'chat-embed') {
                    document.querySelector(params.container).style.position = 'relative';
                } else {
                    document.querySelector(params.container).style.position = 'fixed';
                }

                // Set size and position
                document.querySelector(params.container).style.pointerEvents = 'visible';
                document.querySelector(params.container).style.zIndex = 16777216;

                document.querySelector(params.container).style.width = data.widget.widget_size_and_location.width;
                document.querySelector(params.container).style.height = data.widget.widget_size_and_location.height;
                document.querySelector(params.container).style.minWidth = '310px';

                if (data.widget.widget_size_and_location.embedType === 'chat-classic') {
                    if (data.widget.widget_size_and_location.align === 'flex-start') {
                        document.querySelector(params.container).style.left = '5px';
                        document.querySelector(params.container).style.bottom = '5px';
                    }
                    if (data.widget.widget_size_and_location.align === 'flex-end') {
                        document.querySelector(params.container).style.right = '5px';
                        document.querySelector(params.container).style.bottom = '5px';
                    }
                    if (data.widget.widget_size_and_location.align === 'center') {
                        let width = document.querySelector(params.container).getBoundingClientRect().width;
                        document.querySelector(params.container).style.left = window.innerWidth / 2 - width / 2 + 'px';
                        document.querySelector(params.container).style.bottom = '5px';
                    }
                }

                // Detect embed mode
                if (data.widget.widget_size_and_location.embedType === 'chat-full-page') {
                    let width = document.querySelector(params.container).getBoundingClientRect().width;
                    let height = document.querySelector(params.container).getBoundingClientRect().height;

                    if (data.widget.widget_size_and_location.width === '100%') {
                        document.querySelector(params.container).style.left = 0;
                        document.querySelector(params.container).style.top = 0;
                    } else {
                        document.querySelector(params.container).style.left = window.innerWidth / 2 - width / 2 + 'px';
                        document.querySelector(params.container).style.top = window.innerHeight / 2 - height / 2 + 'px';
                    }
                }
                /* else {

                }*/
            };
        });
    }
}
