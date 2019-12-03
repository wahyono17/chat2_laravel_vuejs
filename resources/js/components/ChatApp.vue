<template>
    <div class="chat-app">
        <Conversation :contact="selectedContact" :messages="messages" @new="saveNewMessage"></Conversation>
        <Contactslist :contacts="contacts" @selected="startConversationWith"></Contactslist>

    </div>
</template>

<script>
import Conversation from './Conversation';
import Contactslist from './Contactslist';
    export default {
        props:{
            user:{
                type:Object,
                required:true
            }
        },
        data() {
            return {
                selectedContact:null,messages:[],contacts:[]
            }
        },
        mounted() {
            //console.log(this.user);
           axios.get('/contacts')
                .then((response)=>{
                    //console.log(response.data);
                    this.contacts = response.data;
                });
        },
        methods: {
            startConversationWith(contact){
                axios.get(`/conversation/${contact.id}`)
                .then(response=>{
                    this.messages = response.data;
                    this.selectedContact = contact;
                })
            },
            saveNewMessage(text){
                this.messages.push(text);
            }
        },
        components:{Conversation,Contactslist}
    }
</script>
<style lang="scss">
    .chat-app{
        display: flex
    }
</style>

