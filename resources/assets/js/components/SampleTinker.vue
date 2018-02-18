<template>
    <div onload="initMessages();">
        <div class="arrow"></div>
        <ul class="ChatLog">
            <li class="ChatLog__entry" v-for="message in messages" :class="{'ChatLog__entry_mine': message.isMine}">
                <img class="ChatLog__avatar" src="/logo.png" />
                <p class="ChatLog__message">{{ message.text }}</p>
            </li>
        </ul>

        <input type="text" class="ChatInput" @keyup.enter="sendMessage" v-model="newMessage" placeholder="">
    </div>
</template>

<style>
    input.ChatInput {
        width: 300px;
        height: 25px;
        border-radius: 5px;
        border: none;
        padding: 10px;
    }

    ul.ChatLog {
        list-style: none;
    }

    .ChatLog {
        max-width: 20em;
        margin: 0 auto;
    }
    .ChatLog .ChatLog__entry {
        margin: .5em;
    }

    .ChatLog__entry {
        display: flex;
        flex-direction: row;
        align-items: flex-end;
        max-width: 100%;
    }

    .ChatLog__entry.ChatLog__entry_mine {
        flex-direction: row-reverse;
    }

    .ChatLog__avatar {
        flex-shrink: 0;
        flex-grow: 0;
        z-index: 1;
        height: 50px;
        width: 50px;
        border-radius: 25px;

    }

    .ChatLog__entry.ChatLog__entry_mine
    .ChatLog__avatar {
        display: none;
    }

    .ChatLog__entry .ChatLog__message {
        position: relative;
        margin: 0 12px;
    }

    .ChatLog__entry .ChatLog__message::before {
        position: absolute;
        right: auto;
        bottom: .6em;
        left: -12px;
        height: 0;
        content: '';
        border: 6px solid transparent;
        border-right-color: #ddd;
        z-index: 2;
    }

    .ChatLog__entry.ChatLog__entry_mine .ChatLog__message::before {
        right: -12px;
        bottom: .6em;
        left: auto;
        border: 6px solid transparent;
        border-left-color: #08f;
    }

    .ChatLog__message {
        background-color: #ddd;
        padding: .5em;
        border-radius: 4px;
        font-weight: lighter;
        max-width: 70%;
    }

    .ChatLog__entry.ChatLog__entry_mine .ChatLog__message {
        border-top: 1px solid #07f;
        border-bottom: 1px solid #07f;
        background-color: #08f;
        color: #fff;
    }
</style>

<script>

    console.log("compile2");

    const axios = require('axios');
    const API_ENDPOINT = '/shot';
    const API_ENDPOINT_INIT = '/shot/init';

    var aryMessages = loadItems();

    export default {
        data() {
            return {
                messages: aryMessages,
                newMessage: null
            };
        },

        methods: {
            _addMessage(text, attachment, isMine) {
                this.messages.push({
                    'isMine': isMine,
                    'user': isMine ? '👨' : '🤖',
                    'text': text,
                    'attachment': attachment || {},
                });
            },

            sendMessage() {
                let conversation_id = $('#conversation_id').val();
                console.log(conversation_id);
                let messageText = this.newMessage;
                this.newMessage = '';
                if (messageText === 'clear') {
                    this.messages = [];
                    return;
                }

                this._addMessage(messageText, null, true);

                axios.post(API_ENDPOINT+'?conversation_id='+conversation_id, {
                    driver: 'web',
                    userId: 9999999,
                    message: messageText
                }).then(response => {
                    let messages = response.data.messages || [];
                    messages.forEach(msg => {
                        this._addMessage(msg.text, msg.attachment, false);
                    });
                }, response => {

                });
            }
        }
    }


    // Read a page's GET URL variables and return them as an associative array.
    function getUrlVars()
    {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }


    function loadItems(){
        var aryMessages = [];
        console.log(getUrlVars());

        var workid = getUrlVars()["workid"];

        axios.post(API_ENDPOINT_INIT+'?workid='+workid, {
            driver: 'web',
            userId: 9999999,
            message: ''
        }).then(response => {
            let messages = response.data.messages || [];
            messages.forEach(msg => {
                aryMessages.push({
                    'isMine': false,
                    'user': '🤖',
                    'text': msg.text,
                    'attachment': {},
                });
            });
        });
        console.log(aryMessages);
        return aryMessages;
    };


</script>
