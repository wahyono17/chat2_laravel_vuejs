<template>
    <div class="conversation">
        <h1>{{contact ? contact.name : 'select a contact'}}</h1>
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
            messages:{
                type:Array,
                default:[]
            }
        },
        methods: {
            sendMessage(text){
                //console.log(text);
                if(!this.contact){
                return;
                }
                axios.post('/conversation/send',{
                    contact_id : this.contact.id,
                    text : text
                }).then ((response)=>{
                    console.log(response.data);
                    this.$emit('new',response.data);
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
