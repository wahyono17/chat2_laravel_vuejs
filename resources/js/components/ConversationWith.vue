<template>
    <div class="conversation">
        <h1>{{contact.name}}</h1>
        <messageFeed :contact="contact" :messages="messages"/>
        <messageComposer @send="sendMessage"/>
    </div>
</template>

<script>
import messageComposer from './messageComposer';
import messageFeed from './messageFeed';
    export default {
        props:{
            contact:{
                type:Object,
                default:null
            },
            user:{
                type:Object,
                required:true
            }
        },
        data(){
            return {
                messages:[]
            }
        },
        mounted(){
            Echo.private(`messages.${this.user.id}`)
                .listen('NewMessage', (e) => {
                    this.messages.push(e.message);
                });
            axios.get(`/conversation/${this.contact.id}`)
                .then(response=>{
                    this.messages = response.data;
                });
            
        },
        methods: {
            //ini adalah method untuk save message ke database
            sendMessage(text){
                //console.log(text);
                if(!this.contact){
                return;
                }
                axios.post('/conversation/send',{
                    contact_id : this.contact.id,
                    text : text
                }).then ((response)=>{
                    this.messages.push(response.data);//munculkan pesan di perkacapan
                    //console.log(response.data);
                    //this.$emit('new',response.data);
                })
            }
        },
        components:{messageComposer,messageFeed}
    }
</script>
<style lang="scss">
.conversation{
    flex: 5;
    display: flex;
    flex-direction: column;
    justify-content: space-between;

    h1 {
        font-size: 20px;
        padding: 10px;
        margin: 0;
        border-bottom: 1px dashed lightgray;
    }
}
</style>
