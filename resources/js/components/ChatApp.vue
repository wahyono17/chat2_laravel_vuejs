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
                });
             
            axios.get('/contacts')
                .then((response)=>{
                    //console.log(response.data);
                    this.contacts = response.data;
                });
        },
        methods: {
            startConversationWith(contact){
                this.updateUnreadCount(contact,true);

                axios.get(`/conversation/${contact.id}`)
                .then(response=>{
                    this.messages = response.data;
                     
                    this.selectedContact = contact;
                })
            },
            saveNewMessage(message){
                this.messages.push(message);
            },
            handleIncoming(message){
                //ini untuk batasi hanya yang di kirim yang bisa terima chat,, di front end nya waktu push
                //menghilangkan this.selectedContact && karena tidak di perlukan sebenarnya
                if(message.from == this.selectedContact.id){
                    this.saveNewMessage(message);
                    return;
                } else
                
                this.updateUnreadCount(message.from_contact,false);
                //this.sortedContacts();
                //console.log(message);
            },
            //method baru untuk tambahkan count chat, jika tidak di click di contact nya 
            //jika di pasang di method hanleincoming
            updateUnreadCount(contact,reset){
                this.contacts = this.contacts.map((single)=>{
                    if(single.id != contact.id){
                        return single;
                    }
                    if(reset)
                        single.unread = 0;
                    else
                        single.unread += 1;
                        //return Infinity;

                    return single;
                })
            },
            sortedContacts(){
                return _.sortBy(this.contacts,[(contact)=>{
                    return Infinity;    
                }])
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

