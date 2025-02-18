<template>
    <v-container fluid style="">
        <v-layout row wrap style="margin-left: -5px; margin-right: -5px;">
            <v-flex v-bind="corderProps">
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <v-icon large left>room_service</v-icon>
                            <span class="subheading font-weight-light">CURRENT ORDER</span>
                        </v-card-title>
                        <v-card-text style="height: calc(100% - 120px);">
                            <p class="headline font-weight-bold">{{ currentOrderCount }} Service is runnig</p>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" to="/view-order">VIEW DETAILS</v-btn>
                        </v-card-actions>
                    </v-card>
                </div>
			</v-flex>
			<v-flex v-bind="qorderProps">
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <v-icon large left>create</v-icon>
                            <span class="subheading font-weight-light">QUICK ORDER</span>
                        </v-card-title>
                        <v-card-text style="height: calc(100% - 120px);">
                            <div class="dropdown">
                                <v-text-field height="20px" class="" color="primary" v-model="quickOrderData.request_service" v-on:keyup="findQuickOrder" :error-messages="display_errors.request_service" label="Find Your Service"></v-text-field>
                                <ul class="dropdown-list" v-show="dropdownList">
                                    <li class="dropdown-item" @click="selectItem(quickorderitem)" v-for="(quickorderitem, i) in quickFilteritems" :key="i">{{ quickorderitem }}</li>
                                </ul>
                            </div>

                            <!-- <v-combobox v-model="quickOrderData.request_service" :items="quickorderitems" item-text="name" item-value="id" label="Search for a service" required :error-messages="display_errors.request_service"></v-combobox> -->
                            <v-text-field v-model="quickOrderData.comments" label="Comments/Notes"></v-text-field>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="secondary" to="/quick-order-history">Quick Order History</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="palceQuickOrder">Order Now</v-btn>
                        </v-card-actions>
                    </v-card>
                </div>
			</v-flex>
            <v-flex md3 sm12 xs12 v-if="site_configs.refer ==1">
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <v-icon large left>emoji_events</v-icon>
                            <span class="subheading font-weight-light">REWARD POINT</span>
                        </v-card-title>
                        <v-card-text style="height: calc(100% - 120px);">
                            <p class="font-weight-bold" style="text-align:center; font-size: 30px;">{{ currentRewardPointCount }}</p>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="primary" to="/refer">Earn Money</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="cashOutRequestRequest()">Cash Out</v-btn>
                        </v-card-actions>
                    </v-card>
                    <v-dialog v-if="cashOutRequestModal" v-model="cashOutRequestModal" max-width="480">
                        <v-form ref="cashOutRequestForm" v-model="valid" lazy-validation @submit.prevent="cashOutRequestPrevent">
                            <v-card>
                                <v-card-title class="white--text" style="background-color: #febe00;">Cash out request</v-card-title>
                                <v-alert v-if="currentRewardPointCount < 600" :value="true" type="error">You do not have sufficent reward point for cash out. You need minimum 600 reward point for cash out.</v-alert>
                                <v-card-text>
                                    <v-text-field color="accent" v-model="cashOutRequestFormData.amount" :error-messages="display_errors.amount" label="Reward point" outlined></v-text-field>
                                    <v-select color="accent" :items="itemsMfs" v-model="cashOutRequestFormData.mfs" :error-messages="display_errors.mfs" label="Select MFS"></v-select>
                                    <v-text-field color="accent" v-model="cashOutRequestFormData.mfs_number" :error-messages="display_errors.mfs_number" label="MFS Number (Bkash)"></v-text-field>
                                    <v-text-field color="accent" v-model="cashOutRequestFormData.password" :error-messages="display_errors.password" :type="passShow ? 'text' : 'password'" label="Password" @click:append="passShow = !passShow" outlined></v-text-field>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="secondary" @click="cashOutRequestModal = false">Close</v-btn>
                                    <v-btn color="primary" type="submit">Cash Out</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-form>
                    </v-dialog>
                </div>
			</v-flex>
			<v-flex v-bind="retingProps">
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <v-icon large left>speed</v-icon>
                            <span class="subheading font-weight-light">Rating</span>
                        </v-card-title>
                        <v-card-text class="headline" style="height: calc(100% - 120px);">
                            <p style="text-align:center;"><v-icon style="font-size: 30px;" color="primary">star</v-icon></p>
                            <p class="font-weight-bold" style="text-align:center; font-size: 30px;">{{ clientDetails.rating }}</p>
                        </v-card-text>
                    </v-card>
                </div>
			</v-flex>
        </v-layout>
        <v-card elevation="0">
            <v-card-title class="white--text" style="background-color: #febe00;">
                <h5><v-icon>room_service</v-icon> আপনার সার্ভিস গ্রহণ করুন</h5>
            </v-card-title>
            <v-layout wrap style="margin-left: -5px; margin-right: -5px;">
                <v-flex md2 sm4 xs6 v-for="category in categories" :key="category.id">
                    <router-link :to="{ path: '/service/' + category.slug }" style="display:block; padding: 5px;">
                        <v-img class="white--text" style="" :src="category.thumb" :alt="category.slug"></v-img>
                        <p class="text-center">{{ category.name }}</p>
                    </router-link>
                </v-flex>
            </v-layout>
        </v-card>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
    import { mapState } from "vuex";
    import axios from "../../axios_instance.js";
    import { localStorageService } from "../../helper";

    export default {
        mounted() {
            this.getCurrentOrderCount();
            this.getCatgory();
            this.getRewardPoint();
            this.quickOrderItems();
            this.getClientDetails();
            this.auth_user = localStorageService.getItem("currentUserData");
            this.cashOutRequestFormData.amount = this.auth_user.balance;
            this.cashOutRequestFormData.mfs = this.auth_user.mfs_type;
            this.cashOutRequestFormData.mfs_number = this.auth_user.mfs_no;
            if(this.$route.params.offer == 'offer'){
                this.getOffers(); 
            }            
        },

        data() {
            return {
                valid: null,
                currentOrderCount: 0,
                currentRewardPointCount: 0,
                categories: [],
                snackbar: null,
                alertMessage: null,
                auth_user: [],
                itemsMfs: ["Bkash"],
                clientDetails: [],
                dropdownList: false,
                quickorderitems: [],
                quickFilteritems: [],
                quickOrderData: {
                    request_service: "",
                    comments: "",
                },
                redeemPointFormData: {
                    reward_point: null
                },
                cashOutRequestFormData: {
                    amount: null,
                    mfs: null,
                    mfs_number: null,
                    password: null
                },
                passShow: null,
                // requiredRules: [
                //     v => !!v || "This field cannot be empty"
                // ],
                display_errors: [],
                cashOutRequestModal: false,
                offers: [],
                site_configs:{ 
                    refer: 0, 
                },
                corderProps: {md4: true, sm12 : true, xs12: true },
                qorderProps: {md5: true, sm12 : true, xs12: true },
                retingProps: {md3: true, sm12 : true, xs12: true },
            };
        },
        created() {
            this.getSiteConfig();
            if(this.$route.params.offer == 'offer'){
                this.getOffers(); 
                //this.quickOrderData.request_service='dsgdfgdfgdfg dfgdfg'; 
            }
        },
        methods: {
            async getOffers(){
                let response = await axios.get("offers");
                this.offers = response.data;  
                for (var i = 0; i < this.offers.length; i++) {   // Offer for loop 
                    if(this.offers[i].offers_type == 'quick_order_offer'){   
                        this.quickOrderData.request_service=this.offers[i].description; 
                    }
                }
                console.log('offers',this.offers);
            },
            async findQuickOrder(){
                this.dropdownList = true;
                let response = await axios.post("/quickorderitems", {'find': this.quickOrderData.request_service});
                this.quickFilteritems = response.data.data;
            },
            selectItem (theItem) {
                this.quickOrderData.request_service = theItem;
                this.quickorderitems = [];
                this.dropdownList = false;
            },
            async getClientDetails() {
                let loader = this.$loading.show();
                try {
                    let response = await axios.get("/client-details");
                    this.clientDetails = response.data.data;
                } catch (error) {
                    localStorage.removeItem("currentUserData");
                    localStorage.removeItem("d_token");
                    this.$router.push("/");
                }
                loader.hide();
            }, 
            cashOutRequestRequest(){
                if(this.clientDetails.mfs_no == null)
                {
                    this.$router.push("/profile");
                }
                else
                {
                    this.cashOutRequestModal = true;
                }
            },
            async getCurrentOrderCount() {
                var response = await axios.get("/user-order-count");
                this.currentOrderCount = response.data;
            },
            async getRewardPoint() {
                var response = await axios.get("/user-rewardpoint");
                this.currentRewardPointCount = response.data.available_reward_point;
            },
            async getCatgory() {
                this.categories = localStorageService.getItem("categorys");  
            },
            async quickOrderItems() {
                let quickorderitems = await axios.get("/quickorderitems");
                this.quickorderitems = quickorderitems.data.data;
            },
            async palceQuickOrder() {
                let loader = this.$loading.show();
                this.display_errors = [];
                try {
                    var response = await axios.post("quickorder", {
                        request_service: this.quickOrderData.request_service,
                        name: this.auth_user.name,
                        phone: this.auth_user.phone,
                        address: this.auth_user.address,
                        comments: this.quickOrderData.comments,
                    });
                    this.alertMessage = response.data.message;
                    this.snackbar = true;
                } catch (error) {
                    if(error.response.data.errors)
                    {
                        this.display_errors = error.response.data.errors;
                    }
                    this.alertMessage = error.response.data.message;
                    this.snackbar = true;
                }
                loader.hide();
            },
            async cashOutRequestPrevent() {
                let loader = this.$loading.show();
                if (this.$refs.cashOutRequestForm.validate()) {
                    this.display_errors = [];
                    try {
                        var response = await axios.post("/user-cashout-request", this.cashOutRequestFormData);
                        this.getRewardPoint();
                        this.alertMessage = response.data.message;
                        this.snackbar = true;
                    } catch (error) {
                        if(error.response.data.errors)
                        {
                            this.display_errors = error.response.data.errors;
                        }
                        this.alertMessage = error.response.data.message;
                        this.snackbar = true;
                    }
                }
                loader.hide();
            },
            async getSiteConfig() {
                let response = await axios.get("site-configs");  
                this.site_configs.refer = response.data.refer;  
                if(this.site_configs.refer == 1){
                    this.corderProps= {md3: true, sm12 : true, xs12: true };
                    this.qorderProps= {md4: true, sm12 : true, xs12: true };
                    this.retingProps= {md2: true, sm12 : true, xs12: true }; 
                } 
            }
        }, 
    };
</script>
<style >
    .card-height {
        height: 180px !important;
    }
    
    .empty-box {
        margin: 10px;
        height: 80px;
        text-align: right !important;
        float: right;
    }
    
    .icon-box {
        background-color: #febe00;
        padding: 10px 0;
        height: auto;
        margin-left: 10px;
        margin-top: -22px;
        border-radius: 5px;
        box-shadow: 2px 2px 9px 0px rgba(0, 0, 0, 0.212);
        transition: 0.2s;
    }
    
    .custom-height {
        height: 75px;
    }
    
    .v-card-padding {
        padding: 8px;
        margin: 15px;
    }
    
    .v-card-padding-pic {
        padding: 0px;
        margin: 15px;
    }
    
    .procedures {
        padding: 15px;
    }
    
    .procedures > h4 {
        font-size: 20px;
    }
    
    .procedures > p {
        font-size: 13px;
    }
    
    .electrical_service,
    .plumbing_service,
    .ac_service,
    .generator_service,
    .it_service,
    .cctv_service {
        font-size: 11px !important;
        padding: 5px;
        transition: 0.2s;
    }
    
    .electrical_service > img,
    .plumbing_service > img,
    .ac_service > img,
    .generator_service > img,
    .it_service > img,
    .cctv_service > img {
        width: 32px;
        transition: 0.2s;
    }
    
    // SERVICES ICON CHANGE ON HOVER
    .electrical_service:hover,
    .plumbing_service:hover,
    .ac_service:hover,
    .generator_service:hover,
    .it_service:hover,
    .cctv_service:hover {
        cursor: pointer;
        transition: 0.2s;
        font-size: 0px !important;
        img {
            width: 45px;
            transition: 0.2s;
        }
    }
    
    .row {
        margin-right: 0px;
        margin-left: 0px;
    }
</style>