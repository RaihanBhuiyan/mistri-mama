<template>
    <v-container fluid>
        <v-card elevation="0" v-if="orders.length != 0">
         
            <v-expansion-panel v-if="serviceProviderDetails.withdrawable_balance > 0">
                <v-expansion-panel-content v-for="order in orders" :key="order.id">
                    <template v-slot:header>
                        <span><v-icon>label</v-icon> Order#{{order.order_no}} </span>
                        <span><v-icon>change_history</v-icon> Category: {{order.category_name}} </span>
                        <span><v-icon>room</v-icon> Area: {{order.area}} </span>
                        <span><v-icon>calendar_today</v-icon> Date/Time: {{order.date}} </span>
                        <span><v-icon>query_builder</v-icon> Time: {{order.time}}</span>
                    </template>
                    <v-data-table :items="order.items" :headers="orderItemHeader" hide-actions>
                        <template v-slot:items="props">
                            <td class="text-xs-left" style="cursor: pointer">{{ props.item.service_name }}</td>
                            <td class="text-xs-left" style="cursor: pointer">{{ props.item.service_bit_name }}</td>
                            <td class="text-xs-left" style="cursor: pointer">{{ props.item.quantity }}</td>
                            <td class="text-xs-left" style="cursor: pointer">{{ props.item.total_price }}</td>
                        </template>
                    </v-data-table>
                    <v-card-text class="text-right">
                        <p><strong>Bill Summary</strong></p>
                        <p class="caption">Total Price {{order.total_price }}/-</p>
                        <p class="caption">Emergency Charge {{order.emergency_charge}}/-</p>
                        <p class="caption">Outside DMC Charge {{order.outside_charge}}/-</p>
                        <p class="caption">Discount {{order.discount}}/-</p>
                        <v-divider style="margin:0"></v-divider>
                        <p class="caption">Total {{ order.grant_total }}/-</p>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="success"   @click="acceptJob(order)">Accept</v-btn>
                        <v-btn color="primary"  v-if="user_type == 'esp'"   @click="openDrawerComrade(order)">Allocate</v-btn>
                        
                    </v-card-actions>
                </v-expansion-panel-content>
            </v-expansion-panel>
            <v-card-text v-else style="text-align:center">কাজ পেতে প্রথমে আপনার অ্যাকাউন্টটি রিচার্জ করুন</v-card-text>
        </v-card>
        <v-card elevation="0" v-else>
            <v-card-text style="text-align:center">You have no order.</v-card-text>
        </v-card>
        <v-dialog v-model="drawerComrade" fullscreen transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="drawerComrade = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>সহকারী নির্বাচন করুন</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark flat @click="allowcateComrade()">Save</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-list subheader two-line>
                    <v-subheader>কাস্টমার এর তথ্য</v-subheader>
                    <v-list-tile>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ drawerComradeData.order.name }}</v-list-tile-title>
                            <v-list-tile-sub-title>{{ drawerComradeData.order.phone }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-divider style="margin:0"></v-divider>
                <v-list subheader two-line>
                    <v-subheader>সহকারী নির্বাচন করুন</v-subheader>
                    <v-radio-group :mandatory="true" v-model="drawerComradeData.selectedComrade">
                        <template v-for="comrade in drawerComradeData.comrades">
                            <v-list-tile @click="checkedComrade(comrade)">
                                <v-list-tile-action>
                                    <v-radio :value="comrade.id" :key="comrade.id"></v-radio>
                                </v-list-tile-action>

                                <v-list-tile-content>
                                    <v-list-tile-title>{{ comrade.name }}</v-list-tile-title>
                                    <v-list-tile-sub-title>{{ comrade.phone }}</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </template>
                    </v-radio-group>
                </v-list>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
    import axios from "../../axios_instance";
    import { localStorageService } from "../../helper.js";
    export default {
        data() {
                return {
                    snackbar: false,
                    alertMessage: "",
                    user_type : localStorageService.getItem("currentUserData").type,
                    serviceProviderDetails: {
                        balance: 0,
                        withdrawable_balance: 0
                    },
                    drawerComrade: null,
                    drawerComradeData: {
                        order: "",
                        comrades: "",
                        selectedComrade: ""
                    },

                    orders: [],
                    orderItemHeader: [{
                        text: "Service"
                    }, {
                        text: "Service Bit"
                    }, {
                        text: "Quantiy"
                    }, {
                        text: "Price"
                    }]
                };
            },
            created() {
                this.getNewAvaibaleOrder();
                this.checkAvailableOrder();
                Echo.channel("orderChannel").listen("OrderEvent", res => {
                    this.getNewAvaibaleOrder();
                });
                this.getServiceProviderDetails();
            },
            methods: {
                checkedComrade(comrade){
                    this.drawerComradeData.selectedComrade = comrade.id;
                },
                async getServiceProviderDetails() {
                    let loader = this.$loading.show();
                    let response = await axios.get("/service-provider-details");
                    this.serviceProviderDetails = response.data.data;
                    loader.hide();
                },
                async acceptJob(order){
                    //accept-order
                    let loader = this.$loading.show();
                    var res = await axios.post("/accept-order", { 
                            order_id: order.id
                        });
                    console.log(res);
                    this.alertMessage = res.data.message;
                    this.snackbar = true;
                    loader.hide();
                }, 
                getServices() {
                    let order = this.orders.find(orders => orders.id == 1);
                    //  console.log(order);
                    let items = order.items;
                    //  console.log(items);
                    let services = items.filter(items => items.service_id == 3);

                    console.log(services);
                },

                openDrawerComrade(itemObject) {
                    this.drawerComrade = !this.drawerComrade;
                    this.drawerComradeData.order = itemObject;

                    this.getComrades(itemObject.category_id);
                },

                async getNewAvaibaleOrder() {
                    let loader = this.$loading.show();
                    var orders = await axios.get("/phone-order");
                    // console.log(orders.data);
                    this.orders = orders.data.data;
                    loader.hide();
                },

                async getComrades(category) {
                    let loader = this.$loading.show();
                    this.drawerComradeData.comrades = "";
                    var comrade = await axios.get('sp/comrades/' + category);
                    this.drawerComradeData.comrades = comrade.data;
                    loader.hide();
                },

                async allowcateComrade() {
                    if(this.drawerComradeData.selectedComrade.length != 0)
                    {
                        let loader = this.$loading.show();
                        var res = await axios.post("/allowcate-comrade", {
                            comrade_id: this.drawerComradeData.selectedComrade,
                            order_id: this.drawerComradeData.order.id
                        });
                        this.drawerComrade = false;
                        this.getNewAvaibaleOrder();
                        this.snackbar = true;
                        this.alertMessage = res.data.message;
                        loader.hide();
                    }
                    this.snackbar = true;
                    this.alertMessage = "Please select comrade.";
                },
                checkAvailableOrder() {
                }
            },
            mounted() {}
    };
</script>
<style scoped>
.v-input__control {
    width: 100% !important;
}
</style>