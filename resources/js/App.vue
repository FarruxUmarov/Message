<script>
import axios from 'axios';
import {ref} from 'vue';

export default {
    props: {
        user: null,
    },
    data() {
        return {
            messages: ref([]),
            newMessage: "",
            rooms: []
        };
    },
    methods: {
        async postMessage(text) {
            try {
                await axios.post(`/message`, {
                    text,
                })
                // After posting, retrieve messages to include the new one
                await this.getMessages();
            } catch (err) {
                console.log(err.message);
            }
        },
        async getMessages() {
            try {
                const response = await axios.get('/messages');
                this.messages = response.data;
                // Scroll to the bottom after messages are updated
                this.scrollToBottom();
            } catch (err) {
                console.log(err.message);
            }
        },
        sendMessage() {
            if (this.newMessage.trim() !== "") {
                this.postMessage(this.newMessage.trim());
                this.newMessage = "";
            }
        },
        scrollToBottom() {
            this.$nextTick(() => {
                const messageList = document.getElementById('messagelist');
                if (messageList) {
                    messageList.scrollTop = messageList.scrollHeight;
                }
            });
        },
        async getRooms() {
            const response = await axios.get('http://localhost:9900/api/rooms');
            this.rooms = response.data;
        },
        async getRoomMessages(id) {
            const response = await axios.get('http://localhost:9900/api/rooms/' + id + '/messages');
            this.messages = response.data;
        }
    },
    created() {
        // this.getMessages();

        // window.Echo.private("channel_for_everyone")
        //     .listen('GotMessage', (e) => {
        //         this.getMessages();
        //     });

        window.Echo.private("room.1")
            .listen('GotMessage', (e) => {
                this.getMessages();
            });

        this.getRooms()
    },
};
</script>

<template>
    <div class="container">
        <ul class="rooms-list">
            <li v-for="room in rooms"
                class="room">
                <button @click="getRoomMessages(room.id)">
                    {{ room.id }}
                </button>
            </li>
        </ul>
        <div class="chat-box" id="messagelist">
            <div v-for="(message, index) in messages" :key="index" class="message">
                <strong>{{ message.user_id }}:</strong> {{ message.text }}
                <small class="text-muted float-right">{{ message.created_at }}</small>
            </div>
        </div>
        <div class="input-area">
            <input v-model="newMessage" @keyup.enter="sendMessage" type="text"
                   placeholder="Type your message here..."/>
            <button @click="sendMessage">Send</button>
        </div>
    </div>
</template>

<style scoped>
.chat-box {
    border: 1px solid #ccc;
    padding: 10px;
    max-height: 300px;
    overflow-y: auto;
}

.message {
    margin-bottom: 10px;
}
</style>
