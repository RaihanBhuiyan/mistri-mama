<template>
    <v-content>
        <v-toolbar app height="64" dark color="primary">
            <v-toolbar-side-icon @click.stop="secondarySidebar = !secondarySidebar"></v-toolbar-side-icon>
            <v-toolbar-title>{{ this.$route.name }}</v-toolbar-title>
            <!-- <span class="" style="background-color: red; border: 1px solid blue;">
                    <select v-model="$i18n.locale"> 
                      <option v-for="(lang, i) in langs" :key="`lang${i}`" :value="lang">{{ lang }}</option>
                    </select> 
                </span>  -->
            <v-spacer></v-spacer>
            <v-menu bottom left offset-y>
                <template v-slot:activator="{ on }">
                    <v-btn dark icon v-on="on" @click="marklAsReadNotifications()" style="margin-right:10px" to="/user-notifications">
                        <v-badge overlap color="red">
                            <template v-slot:badge>
                                <span>{{ notifications.unread_notifications }}</span>
                            </template>
                            <v-icon large color="grey darken-1">notifications_none</v-icon>
                        </v-badge>
                    </v-btn>

                </template>
            </v-menu>
            <v-menu transition="slide-x-transition" offset-y left>
                <template v-slot:activator="{ on }">
                    <img style="border-radius: 100%; border: 1px solid #ddd; height: 40px; width: 40px" v-on="on" :src="(auth_user.photo) ? auth_user.photo : 'https://image.flaticon.com/icons/png/512/236/236832.png'" alt="MM" class="avatar">
                </template>
                <v-card>
                    <v-list>
                        <v-list-tile avatar to="/profile">
                            <v-list-tile-avatar>
                                <img style="border-radius: 100%; border: 1px solid #ddd; height: 50px; width: 50px" :src="(auth_user.photo) ? auth_user.photo : 'https://image.flaticon.com/icons/png/512/236/236832.png'" alt="MM">
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-title>{{ (auth_user.name) ? auth_user.name : '' }}</v-list-tile-title>
                                <v-list-tile-sub-title>{{ (auth_user.phone) ? auth_user.phone : '' }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                            <v-list-tile-action></v-list-tile-action>
                        </v-list-tile>
                        <v-list-tile>
                            <router-link to="/change-password-client">Change Password</router-link>
                        </v-list-tile>
                    </v-list>
                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="secondary" flat @click="logout()"><v-icon>power_settings_new</v-icon>&nbsp;Logout</v-btn>
                    </v-card-actions>
                </v-card>
            </v-menu>
        </v-toolbar>
        <v-navigation-drawer v-model="secondarySidebar" temporary fixed dark>
            <!-- <v-toolbar class="transparent">
                <v-list style="text-align: center; background: url('https://img.freepik.com/free-vector/abstract-modern-yellow-background_42581-368.jpg?size=626&ext=jpg'); background-position:center; background-repeat:no-repeat; background-size:cover">
                    <img style="height:50px" src="https://staging.mistrimama.com/backend/public/frontend/logo.png">
                </v-list>
            </v-toolbar> -->

            <v-list color="primary" dark>
                <v-list-tile avatar ripple v-for="item in userSidebarItems" :key="item.title" :to="item.link">
                    <v-list-tile-avatar>
                        <v-icon class="amber white--text">{{ item.avatar }}</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                        <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile avatar ripple @click.stop href="tel: +8809610222111">
                    <v-list-tile-avatar>
                        <v-icon class="amber white--text">phone</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                        <v-list-tile-title>CONTACT US</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile avatar ripple @click="logout">
                    <v-list-tile-avatar>
                        <v-icon class="amber white--text">power_settings_new</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                        <v-list-tile-title>LOGOUT</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <v-dialog v-model="payDialog" v-if="waitngPayOrder">
            <v-card>
                <v-card-title class="headline">Your order has been finished. Service Provider Wating for payment</v-card-title>
                <v-layout row>
                    <v-flex xs4 sm12>
                        <v-card-text>
                            <p><strong>User:</strong></p>
                            <p>{{waitngPayOrder.name}}</p>
                            <p>{{waitngPayOrder.phone}}</p>
                            <p>{{waitngPayOrder.address}}</p>
                        </v-card-text>
                    </v-flex>
                    <v-flex xs4 sm12>
                        <v-card-text>
                            <p><strong>Order no #{{waitngPayOrder.order_no}}</strong></p>
                            <p>{{waitngPayOrder.category_name}}</p>
                        </v-card-text>
                    </v-flex>
                    <v-flex xs4 sm12>
                        <v-card-text>
                            <p><strong>Technician:</strong></p>
                            <p>{{waitngPayOrder.comrade_name}}</p>
                            <p>{{waitngPayOrder.comrade_phone}}</p>
                            <p>{{waitngPayOrder.comrade_address}}</p>
                        </v-card-text>
                    </v-flex>
                </v-layout>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Service Bit</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Price</th>
                            <th class="text-right">Adtnl. Price</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody v-if="waitngPayOrder.status == 4">
                        <tr v-for="item in waitngPayOrder.items" :key="item.id">
                            <td>{{item.service_name}}</td>
                            <td>{{item.service_bit_name}}</td>
                            <td class="text-center">{{item.quantity}}</td>
                            <td class="text-right">{{item.price}}</td>
                            <td class="text-right">{{ item.quantity > 1 ? item.additional_price : '0.00'}}</td>
                            <td class="text-right">{{item.total_price}}</td>
                            <td class="text-right">
                                <v-icon v-if="item.status == 0">close</v-icon>
                                <v-icon v-if="item.status == 1">done_all</v-icon>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <v-card-text class="text-right">
                    <p class="text-right"><strong>Bill Summary</strong></p>
                    <p class="caption text-right">Total Price {{waitngPayOrder.total_price }}/-</p>
                    <p class="caption text-right">Emergency Charge {{waitngPayOrder.emergency_charge}}/-</p>
                    <p class="caption text-right">Outside Charge {{waitngPayOrder.outside_charge}}/-</p>
                    <p class="caption text-right">Order Discount {{waitngPayOrder.discount}}/-</p>
                    <p class="caption text-right" v-if="waitngPayOrder.reduce_type != null">{{ waitngPayOrder.reduce_type }} {{ waitngPayOrder.reduce_amount }}/-</p>
                    <v-divider style="margin:0"></v-divider>
                    <p class="caption text-right">Total {{ waitngPayOrder.grant_total }}/-</p>
                </v-card-text>
                <v-card-actions v-if="waitngPayOrder.state == 3 && waitngPayOrder.pay_type == null">
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="paySSL(waitngPayOrder.id)">
                        <v-icon>payment</v-icon> Digital
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="giveFeedBackNratingDialog" persistent max-width="290">
            <v-card>
                <v-card-title class="headline">Order No #{{ getFeedbackData.order_no }}</v-card-title>
                <div class="text-xs-center">
                    <v-rating v-model="getFeedbackData.rating" color="yellow darken-3" background-color="grey darken-1" hover></v-rating>
                    ({{ getFeedbackData.rating }})
                </div>
                <v-divider></v-divider>
                <div v-if="getFeedbackData.feedback_questions">
                    <v-card-text style="padding-bottom:0" v-for="(item, index ) in getFeedbackData.feedback_questions" :key="index" class="subheading" primary-title>
                        {{ item.question }}
                        <v-radio-group v-model="feedbackAnswer[index]" style="margin-top: 4px;">
                            <v-radio
                                v-for="option in item.options"
                                :key="option.id"
                                :label=option.title
                                :value="[item.id, option.id]"
                            ></v-radio>
                        </v-radio-group>
                    </v-card-text>
                </div>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" flat @click="giveFeedBackNrating()">No Thanks</v-btn>
                    <v-btn color="green darken-1" flat @click="giveFeedBackNrating()">Submit</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <router-view></router-view>
    </v-content>
</template>

<script>
    import axios from "../axios_instance.js";
    import { localStorageService } from "../helper.js";
    export default {
        name: "DashboardUser",
        data: () => ({
            payDialog: false,
            giveFeedBackNratingDialog: false,
            getFeedbackData: {
                order_no: null,
                rating: 0,
            },
            feedbackAnswer: [],
            waitngPayOrder: [], 
            auth_user : localStorageService.getItem("currentUserData"),
            isPayActive: false,
            secondarySidebar: null,
            site_configs:{ 
                refer: 0
            },
            userSidebarItems: [{
                title: "DASHBOARD",
                link: "/user",
                avatar: "dashboard"
            }, {
                title: "ORDER HISTORY",
                link: "/order-history",
                avatar: "history"
            }, {
                title: "PROMO CODE",
                link: "/promo",
                avatar: "code"
            },  
            {
                title: "OFFERS",
                link: "/offers",
                avatar: "local_offer"
            }, {
                title: "FAQ",
                link: "/user-frequently-asked-questions",
                avatar: "question_answer"
            }],
            notifications: [],
            langs: ['en', 'bn'],
        }),
        created() {
            Echo.channel("orderPaymentChannel").listen("OrderPaymentEvent", response => {
                this.watingPayOrder();
                this.payDialog = true;
            });
            this.watingPayOrder();

            Echo.channel("NotificationFrontendEvent").listen("NotificationFrontendEvent", response => {
                this.getNotifications();
            });
            this.getNotifications();

            
            Echo.channel("orderFeedBackEvent").listen("OrderFeedBackEvent", response => {
                this.giveFeedBackNratingDialog = false ;
                this.watingFeedbackOrder(); 
            });
            this.watingFeedbackOrder();
            this.getSiteConfig();
        },
        watch: {
            $route(to, from) {
                this.watingPayOrder();
            }
        },
        methods: {
            async getNotifications()
            {
                let response = await axios.get("/notifications");
                this.notifications = response.data;
                // for(var i = 0; i <= response.data.length; i++){
                // alert();
                // this.alertMessage.push(this.notifications[i].title);
                // }
            },
            async getSiteConfig() {
                let response = await axios.get("site-configs");  
                this.site_configs.refer = response.data.refer; 
                if(this.site_configs.refer ==1){
                    this.userSidebarItems.push({title: "REFER", link: "/refer", avatar: "group"});
                    this.userSidebarItems.push({title: "CASHOUT HISTORY", link: "/user-cashout-history", avatar: "history"});
                    //this.userSidebarItems.push({title: "REFER", link: "/refer", avatar: "group"});
                }
            },
            async marklAsReadNotifications()
            {
                let response = await axios.get("/mark_as_read");
                this.notifications.unread_notifications = response.data;
            },
            denyServiceProviderRating()
            {
                this.giveUserFeedBackDialog = false;
                this.watingPayOrder();
            },
            logout() {
                localStorage.removeItem("currentUserData");
                localStorage.removeItem("d_token");
                this.$router.push("/");
            },
            async watingPayOrder() {
                var order = await axios.get("/user/check-waiting-payment");
                this.waitngPayOrder = order.data.data;
                this.payDialog = true;
            },
            async watingFeedbackOrder(){
                var orderFeedbacks = await axios.get("/check-feedback-order/client");
                if(orderFeedbacks.data.data != null)
                {
                    this.getFeedbackData = orderFeedbacks.data.data;
                    this.giveFeedBackNratingDialog = true; 
                }
            }, 
            async giveFeedBackNrating(){ 
                var response = await axios.post("/give-feedback-rating-process", {
                    rating: this.getFeedbackData.rating,
                    type: 'user_to_sp',
                    feedback_answer: this.feedbackAnswer,
                    order_id: this.getFeedbackData.order_id,
                }); 
                this.giveFeedBackNratingDialog = false;
            },
            async payCash(orderid) {
                var response = await axios.get('pay/cash/' + orderid);
                if (response.status == 200) {
                    this.payDialog = false;
                    this.giveServiceProviderRatingDialog = true;
                }
                this.payDialog = false;
            },
            async paySSL(orderid) {
                var response = await axios.get('pay/ssl/' + orderid);
                console.log(response);

                if(response.status == 200)
                {
                    window.location.href =  response.data.data ; 
                }else{
                    this.alertMessage = response.data.message;
                }
                this.payDialog = false;
            },
            getUser() {
                return localStorageService.getItem("currentUserData");
            },
        },
        computed: {
        },
        async mounted() {
        }
    };
</script>