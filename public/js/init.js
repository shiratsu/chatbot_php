window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('botman-tinker', require('./components/BotManTinker.vue'));

window.onload = function(){

    const axios = require('axios');
    const API_ENDPOINT_INIT = '/botman/init';


    var app = new Vue({
        el: '#app',
        data: function(){
            return {
                "messages" : loadAzureDomain()
            };
        },
        methods : {
            _addMessage(text, attachment, isMine) {
                this.messages.push({
                    'isMine': isMine,
                    'user': isMine ? '👨' : '🤖',
                    'text': text,
                    'attachment': attachment || {},
                });
            },

            sendMessage() {
                let messageText = this.newMessage;
                this.newMessage = '';
                if (messageText === 'clear') {
                    this.messages = [];
                    return;
                }

                this._addMessage(messageText, null, true);

                axios.post(API_ENDPOINT, {
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
    });
};