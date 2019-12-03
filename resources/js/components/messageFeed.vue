<template>
    <div class="feed" ref="feed">
        <ul v-if="contact">
            <li v-for="message in messages" :class="`message${message.to == contact.id ?'send':'received'}`" :key="message.id">
                <div class="text">
                    {{message.text}}
                </div>
            </li>
        </ul>
    </div>
</template>
<script>
export default {
    props:{
       contact:{
           type:Object,
       },
       messages:{
           type:Array,
           required:true
       },      
    },
    methods: {
       scrollToButtom(){
           setTimeout(()=>{
            this.$refs.feed.scrollTop =  this.$refs.feed.scrollHeight - this.$refs.feed.clientHeight;
           },50)   
       }
    },
    watch: {
       contact(contact){
           this.scrollToButtom();
       },
       messages(){
           this.scrollToButtom();
       }
    },
}
</script>
</script>
<style lang="scss" scoped>
.feed {
    background: #f0f0f0;
    height: 100%;
    max-height: 470px;
    overflow: scroll;

    ul {
        list-style-type: none;
        padding: 5px;

        .text {
        max-width: 600px;
        border-radius: 5px;
        padding: 12px;
        display: inline-block;
        margin-top: 5px;
        }

        li.messagereceived {
            
            text-align: right;
            .text{
                background: #b2b2b2;
            }
            
        }
        li.messagesend{
            text-align: left;

                .text {
                background: #81c4f9;
            }
        }
    }
}
</style>