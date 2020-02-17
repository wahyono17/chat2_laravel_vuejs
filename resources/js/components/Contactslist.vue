<template>
    <div class="contact-list">
        <ul>
            <li v-for="contact in sortArrays" :key="contact.id" @click="selectContact(contact)" :class="{ 'selected': contact == selected }">
                <div class="avatar"><img :src="contact.profile_image" :alt="contact.name"></div>
                <div class="contact">
                    <p class="name">{{contact.name}}</p>
                    <p class="email">{{contact.email}}</p>
                </div>
                
                <span class="unread" v-if="contact.unread">{{contact.unread}}</span>
                
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props:{
            contacts:{
                type:Array,
                default:[]
            }
        },
        data() {
            return {
                selected: this.contacts.length ? this.contacts[0] : null
            }  
        },
        
        methods: {
            //remove index in v-for contact and @click=selectContact(index,contact)
            selectContact(contact){
                this.selected = contact;//before is index
                this.$emit('selected',contact);
            },
        },
        computed:{
            /*
            //tidak di pakai karena susah untuk desc
            sortedContacts(){
                // Set slice() to avoid to generate an infinite loop!
                return this.contacts.slice().sort(function(a, b) {
                    return a.messageid - b.messageid;
                });    
            },
            */
            sortArrays() {
                return _.orderBy(this.contacts, 'messageid', 'desc');
            }
            
        }
    }
</script>
<style lang="scss">
.contact-list{
    flex: 1;
    max-height: 600px;
    overflow: scroll;
    border-left: 1px solid #b9b9c1;
ul{
    list-style-type: none;
    padding-left: 0;
    li{
        display: flex;
        padding: 2px;
        border-bottom: 1px solid rgb(220, 226, 226);
        height: 80px;
        position: relative;
        cursor: pointer;

         &.selected {
                background: #dfdfdf;
            }
        span.unread{
            background: #4aef4a;
            color: white;
            position: absolute;
            right: 11px;
            top: 15px;
            display: flex;
            font-weight: 700;
            /* min-width: 200px; */
            justify-content: center;
        }    

        .avatar{
            flex: 1;
            display: flex;
            align-items: center;

            img{
                width: 35px;
                border-radius: 50%;
                margin: 0 auto;
            }
        }

        .contact{
            flex: 3;
            font-size: 10px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;

            p{
                margin: 0;
                
                &.name {
                        font-weight: bold;
                    }
            }    
        }
    }
}
}
</style>
