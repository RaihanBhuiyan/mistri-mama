<template>
    <v-container fluid style="padding:0">
        <v-card elevation="0">
            <v-card-title class="white--text" style="background-color: #febe00;">
                <h5><v-icon>gavel</v-icon>নতুন অর্ডার</h5>
            </v-card-title>
            <v-expansion-panel style="margin-bottom: 10px" v-if="(serviceProviderDetails.withdrawable_balance > 0) && (orders.length != 0)">
                <v-expansion-panel-content v-for="(order, orderindex) in orders" :key="orderindex">
                    <template v-slot:header>
                        <v-layout row wrap>
                            <v-flex xs12 sm6 md2>
                                <p class="subheading font-weight-bold text-left">Order No# {{order.order_no}}</p>
                                <p class="caption font-weight-bold hidden-md-and-up text-left"><strong>Service Category : </strong><span class="grey--text">{{order.category_name}}</span></p>
                                <p class="caption font-weight-bold hidden-md-and-up text-left"><strong>Service Area : </strong><span class="grey--text">{{order.area}}</span></p>
                                <p class="caption font-weight-bold hidden-md-and-up text-left"><strong>Date/Time : </strong><span class="grey--text">{{order.date}} {{order.time}}</span></p>
                            </v-flex>
                            <v-flex md4 hidden-sm-and-down>
                                <strong>Service Category : </strong>
                                <span class="grey--text">{{order.category_name}}</span>
                            </v-flex>
                            <v-flex xs12 md3 hidden-sm-and-down>
                                <strong>Service Area : </strong>
                                <span class="grey--text">{{order.area}}</span>
                            </v-flex>
                            <v-flex xs12 md3 hidden-sm-and-down>
                                <strong>Date/Time : </strong>
                                <span class="grey--text">{{order.date}} {{order.time}}</span>
                            </v-flex>
                        </v-layout>
                    </template>

                    <v-card class="elevation-0">
                        <v-divider></v-divider>
                        <v-data-table :items="order.items" :headers="orderItemHeader" hide-actions>
                            <template v-slot:items="props">
                                <td class="text-xs-left" style="cursor: pointer">{{ props.item.service_name }}</td>
                                <td class="text-xs-left" style="cursor: pointer">{{ props.item.service_bit_name }}</td>
                                <td class="text-xs-left" style="cursor: pointer">{{ props.item.quantity }}</td>
                                <td class="text-xs-left" style="cursor: pointer">{{ props.item.total_price }}</td>
                            </template>
                        </v-data-table>
                        <v-card-text class="text-right">
                            <p class="text-right"><strong>Bill Summary</strong></p>
                            <p class="caption text-right">Total Price {{order.total_price }}/-</p>
                            <p class="caption text-right">Emergency Charge {{order.emergency_charge}}/-</p>
                            <p class="caption text-right">Outside DMC Charge {{order.outside_charge}}/-</p>
                            <p class="caption text-right">Order Discount {{order.discount}}/-</p>
                            <v-divider style="margin:0"></v-divider>
                            <p class="caption text-right">Total {{ order.grant_total }}/-</p>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error" @click="rejectJob(order, orderindex)">Reject</v-btn>
                            <v-btn color="success" @click="acceptJob(order, orderindex)">Accept</v-btn>
                            <v-btn color="primary" v-if="user_type == 'esp'" @click="openDrawerComrade(order)">Allocate</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-expansion-panel-content>
            </v-expansion-panel>
            <v-card-text v-else caption font-weight-bold style="text-align:center">
                {{ (serviceProviderDetails.withdrawable_balance > 0) ? "নতুন কোনো অর্ডার নাই" : "অর্ডার পেতে প্রথমে আপনাকে রিচার্জ করতে হবে" }}
            </v-card-text>
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
                            <v-list-tile-title>{{ (drawerComradeData.order.name == null) ? 'কাস্টমার এর নাম নাই' : drawerComradeData.order.name }}</v-list-tile-title>
                            <v-list-tile-sub-title>{{ drawerComradeData.order.phone }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-divider style="margin:0"></v-divider>
                <v-list subheader two-line>
                    <v-subheader>সহকারী নির্বাচন করুন</v-subheader>
                    <v-radio-group v-if="drawerComradeData.comrades.length > 0" :mandatory="true" v-model="drawerComradeData.selectedComrade" style="display:block">
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
                    <v-list-tile v-else>
                        <v-list-tile-content>
                            <v-list-tile-title>সহকারী পাওয়া যায়নি</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
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
    name: 'AvaibaleOrder',
    props: ['orders'],
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
            orderItemHeader: [
                {
                    text: "Service",
                    value: 'service'
                }, {
                    text: "Service Bit",
                    value: 'bit'
                }, {
                    text: "Quantiy",
                    value: 'qty'
                }, {
                    text: "Price",
                    value: 'price'
                }
            ]
        };
    },
    created() {
        // Echo.channel("orderChannel").listen("OrderEvent", res => {
        //     this.getNewAvaibaleOrder();
        // });
        // this.getNewAvaibaleOrder();
        this.getServiceProviderDetails();
        //  this.$parent.getServiceProviderDetails();
        //  this.$emit('countNewJob', 5);
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
        async acceptJob(order, orderindex){
            //accept-order
            let loader = this.$loading.show();
            var res = await axios.post("/accept-order", { 
                    order_id: order.id
                });
            this.orders.splice(orderindex, 1);
            this.alertMessage = res.data.message;
            this.snackbar = true;
            this.$parent.getServiceProviderDetails();
            loader.hide();
        }, 
        async rejectJob(order, orderindex){
            //accept-order
            let loader = this.$loading.show();
            var res = await axios.post("/reject-order", { 
                    order_id: order.id
                });
            this.orders.splice(orderindex, 1);
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
            // let loader = this.$loading.show();
            // var orders = await axios.get("/avaiable-order");
            // console.log(orders.data);
            // this.orders = orders.data.data;
            // loader.hide();
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
                try {
                    var response = await axios.post("/allowcate-comrade", {
                        comrade_id: this.drawerComradeData.selectedComrade,
                        order_id: this.drawerComradeData.order.id
                    });
                    this.drawerComrade = false;
                    this.getNewAvaibaleOrder();
                    this.snackbar = true;
                    this.alertMessage = response.data.message;
                    this.$parent.getServiceProviderDetails();
                } catch (error) {
                    this.alertMessage = error.response.data.message;
                    this.snackbar = true;
                }
                loader.hide();
            }
        },
    },
    mounted() {}
};
</script>
<style scoped>
.v-input__control {
    width: 100% !important;
}
</style>