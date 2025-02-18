<template>
    <v-container grid-list-md fluid style="">
        <v-layout v-if="offers.length != 0" row wrap style="margin-left: -5px; margin-right: -5px;">
            <v-flex md4 sm12 xs12 v-for="offer in offers" :key="offer.id">
                <v-card style="margin-bottom:15px" color="primary">
                    <v-img :src="offer.offer_image" aspect-ratio="1.9"></v-img>
                    <v-card-title>
                        <h5><v-icon>tag</v-icon>{{ offer.title }}</h5> 
                    </v-card-title>
                    <v-card-text class="headline font-weight-bold truncat">{{ offer.description }}</v-card-text>
                    <v-card-text>Expire Date {{ offer.expire_date }}</v-card-text>
                    <v-card-actions> 
                        <v-btn v-if="offer.offers_type =='discount_offer'" @click="discount_offer">Get Offers</v-btn>  
                        <v-btn v-if="offer.offers_type =='general_offer'"> Get Offers </v-btn>  
                        <v-btn v-if="offer.offers_type =='quick_order_offer'"  @click="quick_order_offer"> Get Offers  </v-btn>
                        <v-btn v-if="offer.offers_type =='referral_offer'"  @click="refer_offer"> Get Offers </v-btn>  
                    </v-card-actions> 

                </v-card>
            </v-flex>
        </v-layout>
        <div v-else>
            <v-card color="error">
                <v-card-text class="headline text-center">Currently no offer is running</v-card-text>
            </v-card>
        </div>
    </v-container>
</template>
<script>
import axios from "../../axios_instance.js"; 
import { localStorageService, Helper } from "../../helper.js"; 
import {
    Hooper,
    Slide,
    Progress as HooperProgress,
    Navigation as HooperNavigation
}
from "hooper";
import "hooper/dist/hooper.css";
export default {
    name: "UserOffers",
    components: {
        Hooper,
        Slide,
        HooperProgress,
        HooperNavigation 
    },
    data() {
        return {
            offers: [],
            discount_offer_slug: '',
        };
    },
    created() {
        this.getOffers();  
        this.auth_user = localStorageService.getItem("currentUserData");
    },
    methods: {
        async getOffers()
        {
            let response = await axios.get("offers");
            this.offers = response.data;

            this.categorys = localStorageService.getItem("categorys");
            for (var i = 0; i < this.offers.length; i++) {   // Offer for loop
                if(this.offers[i].offers_type == 'discount_offer'){  
                    localStorageService.setItem('discount_offer',this.offers[i].discount_offer);

                    for (var j = 0; j < this.categorys.length; j++) {  // Categores by 
                        if(this.categorys[j].id == this.category_id){
                            this.discount_offer_slug = this.categorys[j].slug;

                            this.service_bit_id = this.offers[i].discount_offer.service_bit_id
                            var res = await axios.get("/category/service/" + this.categorys[j].slug);  
                            res.data ? (this.category = res.data.data) : (this.category = []);
                            this.services = this.category.services; 
                            for (var k = 0; k < this.services.length; k++) {
                                if(this.offers[i].discount_offer.service_id == this.services[k].id){  
                                    //this.loadServiceBit(this.services[k].id); // service_id
                                }

                                for (var l = 0; l < this.services[k].serviceBits.length; l++) { 
                                    if(this.offers[i].discount_offer.service_bit_id == this.services[k].serviceBits[l].id){  
                                        //this.loadServiceBit(this.services[k].serviceBits[l].id); // service_bit_id
                                        this.checkSelectedBit(this.services[k].serviceBits[l].id); // service_bit_id
                                        console.log('bitssss',this.services[k].serviceBits[l].name); 
                                    }
                                    
                                } 
                            } 
                        } 
                    } 
                }
            }
        },
        toggle (index) {

        }, 
        discount_offer: function() { 
            this.categorys = localStorageService.getItem("categorys");
                for (var i = 0; i < this.offers.length; i++) {   // Offer for loop
                    if(this.offers[i].offers_type == 'discount_offer'){   
                        for (var j = 0; j < this.categorys.length; j++) {  // Categores by  
                            if(this.categorys[j].id == this.offers[i].discount_offer.category_id){ 
                                this.discount_offer_slug = this.categorys[j].slug; 
                            }
                        }
                    }
                }
            this.$router.replace('/service/' + this.discount_offer_slug +'/offer');   
        }, 
        refer_offer: function() { 
            this.$router.replace('/refer');   
        },  
        quick_order_offer: function() { 
            this.$router.replace('/user/offer');   
        },  
    }
};
</script>
<style type="text/css">
.truncat{
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    display: -webkit-box;
    height: 86px;
    overflow: hidden;
}
</style>