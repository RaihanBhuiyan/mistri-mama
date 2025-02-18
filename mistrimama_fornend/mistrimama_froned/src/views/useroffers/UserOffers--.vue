<template>
    <v-container grid-list-md fluid style="">
        <v-layout v-if="offers.length != 0" row wrap style="margin-left: -5px; margin-right: -5px;">
            <v-flex md4 sm12 xs12 v-for="offer in offers" :key="offer.id">
                <v-card style="margin-bottom:15px" color="primary">
                    <v-img :src="offer.offer_image" aspect-ratio="1.9"></v-img>
                    <v-card-title>
                        <h5><v-icon>tag</v-icon>{{ offer.title }}</h5>
                        <span v-if="offer.offers_type =='discount_offer'">
                            <v-btn color="primary" @click="discount_offer">NEXT</v-btn>
                            <v-btn :href="'offers-type/'+offer.offers_type">
                              {{ offer.offers_type }}
                            </v-btn>
                        </span>
                        <span v-if="offer.offers_type =='general_offer'">
                            <v-btn :href="'offers-type/'+offer.offers_type">
                              {{ offer.offers_type }}
                            </v-btn>
                        </span>
                        <span v-if="offer.offers_type =='referral_offer'">
                            <v-btn :href="'offers-type/'+offer.offers_type">
                              {{ offer.offers_type }}
                            </v-btn>
                        </span>  
                    </v-card-title>
                    <v-card-text class="headline font-weight-bold truncat">{{ offer.description }}</v-card-text>
                    <v-card-text>Expire Date {{ offer.expire_date }}</v-card-text>
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
            dates: [],
            times: [],
            selectedServiceBit: [],
            categorys: [],
            category: [],
            services: [],
            selectedServices: [],
            site_configs:{
                schedule_charge: 0,
                area_charge: 0,
                office_start_time: null,
                office_end_time: null,
                outside_area_id: []
            }, 
            window: {
                width: 0,
                height: 0
            },
            viewarea: "schedule",
            order: {
                category: null,
                categoryId: null,
                services: [],
                serviceBit: [],
                date: null,
                time: null,
                orderFor: "self",
                userId: null,
                status: 0,
                name: null,
                phone: null,
                area: null,
                address: null,
                comments: null,
                refCode: null,
                orderFrom: null,
                schedule_charge: null,
                area_charge: null,
                promocode: null,
            },
            is_schedule_charge: false,
            is_area_charge: false,
            schedule_charge_msg: null,
            area_charge_msg: null,
            schedule_charge: 0,
            area_charge: 0,
            discount: 0,
            dates: [],
            times: [],
            orderDone: false,
            orderPlacingStatus: false,
            display_errors: [],
            hooperDatesSettings: {
                itemsToShow: 4,
                itemsToSlide: 4,
                centerMode: false,
                wheelControl: false,
                infiniteScroll: false,
                initialSlide: 0,
                autoPlay: false,
                playSpeed: 10000,
                transition: 1000,
                breakpoints: {
                    1023: {
                        itemsToShow: 4,
                        itemsToSlide: 4,
                    },
                    767: {
                        itemsToShow: 3,
                        itemsToSlide: 3,
                    },
                    599: {
                        itemsToShow: 2,
                        itemsToSlide: 2,
                    },
                    0: {
                        itemsToShow: 1,
                        itemsToSlide: 1,
                    },
                }
            },
            hooperTimesSettings: {
                itemsToShow: 4,
                itemsToSlide: 4,
                centerMode: false,
                wheelControl: false,
                infiniteScroll: false,
                initialSlide: 0,
                autoPlay: false,
                playSpeed: 10000,
                transition: 1000,
                breakpoints: {
                    1023: {
                        itemsToShow: 4,
                        itemsToSlide: 4,
                    },
                    767: {
                        itemsToShow: 3,
                        itemsToSlide: 3,
                    },
                    599: {
                        itemsToShow: 2,
                        itemsToSlide: 2,
                    },
                    0: {
                        itemsToShow: 1,
                        itemsToSlide: 1,
                    },
                }
            },
            alertMessage: null,
            snackbar: false,
            auth_user: [],
            guestFormData: {
                name: null,
                phone: null,
                area: null,
                area_name: null,
                address: null,
                comments: null,
            },
            selectedServiceBit: [],
        };
    },
    created() {
        this.getOffers();
        this.getSiteConfig();
        this.categorysGet();
        this.auth_user = localStorageService.getItem("currentUserData");
    },
    methods: {
        async getOffers()
        {
            let response = await axios.get("offers");
            this.offers = response.data;
        },
        toggle (index) {

        },
        categorysGet: function() {
            return (this.categorys = localStorageService.getItem("categorys"));
        },
        discount_offer: function() {
            if (this.viewarea == "service") {
                for (var i = 0; i < this.dates.length; i++) {
                    if (this.dates[i].checked) {
                        this.order.date = this.dates[i].date;
                        this.hooperDatesSettings.initialSlide = i;
                    }
                }

                for (var j = 0; j < this.times.length; j++) {
                    if (this.times[j].checked) {
                        this.order.time = this.times[j].time
                        this.addTime(this.times[j]);
                        this.hooperTimesSettings.initialSlide = j;
                    }
                }

            } else if (this.viewarea == "schedule") {
                for (var i = 0; i < this.dates.length; i++) {
                    if (this.dates[i].checked) {
                        this.order.date = this.dates[i].date;
                        this.hooperDatesSettings.initialSlide = i;
                    }
                }

                for (var j = 0; j < this.times.length; j++) {
                    if (this.times[j].checked) {
                        this.order.time = this.times[j].time
                        this.addTime(this.times[j]);
                        this.hooperTimesSettings.initialSlide = j;
                    }
                }

                this.viewarea = "confirmation";
                this.checkOrderFor();
            } else if (this.viewarea == "confirmation") {
                this.checkAuthentication();
            } else {
                this.alertMessage = "Something went wrong!";
                this.snackbar = true;
            }
            this.selectedServiceBitAddToOrder();
            this.order.category = this.category.name;
            this.order.categoryId = this.category.id;
        },
        selectedServiceBitAddToOrder: function() {
                this.order.serviceBit = this.selectedServiceBit;
                var arr = this.selectedServiceBit;
                var services = Helper.groupBy(
                    this.selectedServiceBit,
                    arr => arr.service_name
                );
                this.order.services = [...services];
            },
        addDate: function(date) {
            this.order.date = date;
            this.addTime({'time': '0.00 PM'});
        },
        addTime: function(value) {
            this.order.time = value.time;

            this.checkExpiredTime(this.order.date, this.order.time);

            this.is_schedule_charge = false;
            this.schedule_charge = 0;
            if(value.is_office_hour == false){
                this.is_schedule_charge = true;
                this.schedule_charge_msg = "N.B. For emergency service hour ("+this.site_configs.office_end_time+" to "+this.site_configs.office_start_time+") an additional BDT "+this.site_configs.schedule_charge+" will be added.";
                this.schedule_charge = this.site_configs.schedule_charge;
            }
        },
        checkExpiredTime(date, time)
        {
            var orderDateTime = new Date(date + ' ' + time.split(" - ")[0]);
            let today = new Date();
            
            let options = {  
                weekday: "long", year: "numeric", month: "long",  
                day: "numeric", hour: "2-digit", minute: "2-digit"  
            };  

            var selectedDateTime = orderDateTime.toLocaleTimeString("en-us", options);
            var currentDateTime = today.toLocaleTimeString("en-us", options);
            
            if (new Date(currentDateTime).getTime() > new Date(selectedDateTime).getTime()) {
                this.order.time = null;
                this.alertMessage = "This time already expired. Please select another time.";
                this.snackbar = true;
            }
        },
        async checkOrderFor(){
            this.area_charge = 0;
            this.is_area_charge = false;
            if(this.order.orderFor != 'others')
            {
                this.guestFormData.name = this.auth_user.name;
                this.guestFormData.phone = this.auth_user.phone;
                this.guestFormData.email = this.auth_user.email;
                this.guestFormData.area_name = this.auth_user.area.name;
                this.guestFormData.address = this.auth_user.address;
                this.order.orderFor = 'self';
            }
            this.checkOutOfAreaCharge(this.auth_user.area.id);
            this.guestFormData.area = parseInt(this.auth_user.area.id);
        },
        async checkOutOfAreaCharge(id) {
            var outside_area_id = this.site_configs.outside_area_id;
            var found = Object.keys(outside_area_id).filter(function(key) {
                return outside_area_id[key] == id;
            });
            this.area_charge = 0;
            this.is_area_charge = false;
            if(found.length != 0)
            {
                this.is_area_charge = true;
                this.area_charge_msg = "N.B. Outside City extra charge BDT "+this.site_configs.area_charge+" will be added.";
                this.area_charge = this.site_configs.area_charge;
            }
        },
        async getSiteConfig() {
            let response = await axios.get("site-configs");
            console.log(response);
            this.dates = response.data.schedule_dates;
            this.times = response.data.schedule_times;
            this.site_configs.schedule_charge = response.data.schedule_charge;
            this.site_configs.area_charge = response.data.area_charge;
            this.site_configs.outside_area_id = response.data.outside_area_id;
            this.site_configs.office_start_time = response.data.office_start_time;
            this.site_configs.office_end_time = response.data.office_end_time;
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