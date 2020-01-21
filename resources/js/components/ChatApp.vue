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
            
            Echo.private(`messages.${this.user.id}`)
                .listen('NewMessage', (e) => {
                    this.handleIncoming(e.message);
                    //this.messages.push(e.message);
                    //console.log(e.message);
                });
                
            //console.log(`messages.${this.user.id}`)
            axios.get('/contacts')
                .then((response)=>{
                    //console.log(response.data);
                    this.contacts = response.data;
                });
        },
        methods: {
            startConversationWith(contact){
                //this.updateUnreadCount(contact,true)

                axios.get(`/conversation/${contact.id}`)
                .then(response=>{
                    this.messages = response.data;
                    //console.log(contact);
                    
                    this.selectedContact = contact;
                    //console.log(message.from);
                })
            },
            saveNewMessage(message){
                this.messages.push(message);
                //console.log(message);
            },
            handleIncoming(message){
                //if(this.selectedContact && message.from == this.selectedContact.id){
                    this.saveNewMessage(message);
                    //this.updateUnreadCount(contact,false)
                    alert(message.text);
                    //return;
                    //this.messages.push(message); //ini sudah di lakukan di baris atas
                //}
                //alert(message.text);
            },
            //method baru
            updateUnreadCount(contact,reset){
                this.contacts = this.contacts.map((single)=>{
                    if(single.id != contact.id){
                        return single;
                    }
                    if(reset)
                        single.unread = 0;
                    else
                        single.unread += 1;

                    return single;
                })
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

