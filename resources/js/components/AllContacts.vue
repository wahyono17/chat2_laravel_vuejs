<template>
<div>
    
    <input v-model="search_contact" @keyup="find" class="biru form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
    
    <div class="contact-list">
        <ul>
            <li v-for="contact in contacts" :key="contact.id" @mouseover="selectContact(contact)" :class="{ 'selected': contact == selected }">
                <div class="avatar"><img :src="contact.profile_image" :alt="contact.name"></div>
                <div class="contact">
                    <a :href="`/chatwith/${contact.id}`"><p class="name">{{contact.name}}</p></a> 
                    <p class="email">{{contact.email}}</p>
                </div>

            </li>
        </ul>
    </div>
</div>    
</template>
<script>
export default {
    props:{
        user:{
            type:Object,
            required:true
        }
    },
    data(){
        return {
            contacts:[], search_contact: '',selected:0
        }
    },
    mounted(){
        axios.get('/allcontactsjs')
            .then((response)=>{
            //console.log(response.data);
            this.contacts = response.data;
            });
    },
    methods: {
        find(e){
            e.preventDefault();
            axios.get(`/contact/${this.search_contact}`)
            .then((response)=>{
                //console.log(response.data);
                this.contacts = response.data;
            });
        },
        selectContact(contact){
                this.selected = contact;   
        }
    },
}
</script>
<style lang="scss">
.biru{
    background-color: rgb(196, 235, 235)
}
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

            
        }
    }
}
}
</style>
