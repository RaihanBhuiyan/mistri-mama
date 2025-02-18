<template>
    <v-container fluid style="">
        <v-card elevation="0">
            <v-card-title class="white--text" style="background-color: #febe00;">
                <v-icon class="custom-icon">history</v-icon>
                <span class="title white--text font-weight-light">CURRENT ORDER</span>
                
            </v-card-title>
            <v-expansion-panel v-if="orders.length != 0">
                <v-expansion-panel-content v-for="order in orders" :key="order.id" style="overflow-x: scroll;">
                    <template v-slot:header>
                        <v-layout row wrap>
                            <v-flex xs12 sm6 md2>
                                <p class="subheading font-weight-bold text-left">Order No# {{order.order_no}}</p>
                                <v-chip class="hidden-md-and-up" :color="(order.status >= 0) ? 'primary' : 'secondary'" text-color="white">
                                    <v-avatar>1</v-avatar>
                                    Order Placed
                                    <v-icon right>check_circle</v-icon>
                                </v-chip>
                                <v-chip class="hidden-md-and-up" :color="(order.status >= 2) ? 'primary' : 'secondary'" text-color="white">
                                    <v-avatar>2</v-avatar>
                                    Technician Allocated
                                    <v-icon right>check_circle</v-icon>
                                </v-chip>
                                <v-chip class="hidden-md-and-up" :color="(order.status >= 3) ? 'primary' : 'secondary'" text-color="white">
                                    <v-avatar>3</v-avatar>
                                    Technician Start Working
                                    <v-icon right>check_circle</v-icon>
                                </v-chip>
                                <v-chip class="hidden-md-and-up" :color="(order.status >= 4) ? 'primary' : 'secondary'" text-color="white">
                                    <v-avatar>4</v-avatar>
                                    Order Status : {{ (order.pay_type == null) ? 'Waiting For Payment' : (order.pay_type > 1) ? 'Pay with Digital Payment' : 'Pay with Cash' }}
                                    <v-icon right>check_circle</v-icon>
                                </v-chip>
                                <v-chip class="hidden-md-and-up" :color="(order.status >= 5) ? 'primary' : 'secondary'" text-color="white">
                                    <v-avatar>5</v-avatar> Feedback <v-icon right>check_circle</v-icon></v-chip>
                            </v-flex>
                            <v-flex md10 hidden-sm-and-down>
                                <v-stepper alt-labels style="box-shadow:none">
                                    <v-stepper-header>
                                        <v-stepper-step style="flex-basis: 100px; padding: 0;" step="1" :complete="order.status >= 0">Order Placed</v-stepper-step>
                                        <v-stepper-step style="flex-basis: 100px; padding: 0;" step="2" :complete="order.status >= 2">Technician Allocated</v-stepper-step>
                                        <v-stepper-step style="flex-basis: 100px; padding: 0;" step="3" :complete="order.status >= 3">Technician Start Working</v-stepper-step>
                                        <v-stepper-step style="flex-basis: 100px; padding: 0;" step="4" :complete="order.status >= 4">
                                            {{ (order.pay_type == null) ? 'Waiting For Payment' : (order.pay_type > 1) ? 'Pay with Digital Payment' : 'Pay with Cash' }}
                                        </v-stepper-step>
                                        <v-stepper-step style="flex-basis: 100px; padding: 0;" step="5" :complete="order.status >= 5">Feedback</v-stepper-step>
                                    </v-stepper-header>
                                </v-stepper>
                            </v-flex>
                        </v-layout>
                    </template>
                    <v-card class="elevation-0" >
                        <v-list three-line>
                            <v-list-tile style="padding: 0 8px;">
                                <v-list-tile-content>
                                    <v-list-tile-title>{{order.name}}</v-list-tile-title>
                                    <v-list-tile-sub-title class="text--primary">{{order.phone}}</v-list-tile-sub-title>
                                    <v-list-tile-sub-title>{{order.area}}, {{order.address}}</v-list-tile-sub-title>
                                </v-list-tile-content>
                                <v-list-tile-content v-if="order.comrade">
                                    <v-list-tile-title>Comrade  :{{order.comrade.name}}</v-list-tile-title>
                                    <v-list-tile-sub-title class="text--primary">{{order.comrade.phone}}</v-list-tile-sub-title>
                                    <v-list-tile-sub-title> {{order.comrade.address}}</v-list-tile-sub-title>
                                </v-list-tile-content>
                                <v-list-tile-action>
                                    <v-list-tile-action-text>Total Service Taken : <strong>{{ order.total_service_taken }}</strong></v-list-tile-action-text>
                                    <v-spacer></v-spacer>
                                    <v-rating :value="order.client_rating" :dense="true" :readonly="true"></v-rating>
                                </v-list-tile-action>
                            </v-list-tile>
                        </v-list>
                        
                        <v-divider></v-divider>
                        <v-data-table :items="order.items" :headers="headers" hide-actions class>
                            <template v-slot:items="props">
                                <td class="text-xs-left" style="cursor: pointer">
                                    <span v-if="$i18n.locale=='en'">{{ props.item.service_name }} </span>
                                    <span v-else>{{ props.item.service_name_bn }} </span> 
                                </td>
                                <td class="text-xs-left" style="cursor: pointer">
                                    <span v-if="$i18n.locale=='en'">{{ props.item.service_bit_name }}</span>
                                    <span v-else>{{ props.item.service_bit_name_bn }}</span> 
                                    </td>
                                <td class="text-xs-left" style="cursor: pointer">{{ props.item.quantity }}</td>
                                <td class="text-xs-left" style="cursor: pointer">{{ props.item.price }}</td>
                                <td class="text-xs-right" style="cursor: pointer">{{ props.item.total_price }}</td>
                            </template>
                        </v-data-table>
                        <v-card-text class="text-right">
                            <p><strong>Bill Summary</strong></p>
                            <p class="caption">Total Price {{order.total_price }}/-</p>
                            <p class="caption">Emergency Charge {{order.emergency_charge}}/-</p>
                            <p class="caption">Outsite Charge {{order.outside_charge}}/-</p>
                            <p class="caption">Discount {{order.discount}}/-</p>
                            <v-divider style="margin:0"></v-divider>
                            <p class="caption">Total {{ order.grant_total }}/-</p>
                        </v-card-text>
                        <v-card-actions v-if="order.status == 4 && order.pay_type == null">
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="paySSL(order.id)">
                                <v-icon>payment</v-icon> Pay with Digital
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-expansion-panel-content>
            </v-expansion-panel>
            <v-card-text v-else caption font-weight-bold style="text-align:center">আপনার কোনো অর্ডার নাই</v-card-text>
        </v-card>
        
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" color="info" :right="'right'" :timeout="10000" style="z-index:99999" >
            {{ alertMessage }}
            <v-btn flat @click="snackbar = false" > Close </v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
    import {
        mapState
    }
    from "vuex";
    
    import axios from "../../axios_instance.js";

    export default {
        data() {
            return {
                alertMessage: null,
                dataLoaded: true,
                snackbar: null,
                orders: [],
                headers: [{
                    text: "সার্ভিস ধরন",
                    sortable: false
                }, {
                    text: "সার্ভিস বিট",
                    sortable: false
                }, {
                    text: "পরিমান",
                    sortable: false,
                }, {
                    text: "পরিমান মূল্য",
                    sortable: false
                },
                {
                    text: "মূল্য",
                    value: "total_price",
                    align: "right"
                }],
                langs: ['en', 'bn'],
            };
        },
        methods: {
            async getCurrentOrder() {
                //let loader = this.$loading.show();
                var currentOrders = await axios.get("/user-orders");
                this.orders = currentOrders.data.data;
                //loader.hide();
            },                  
            async payCash(orderid) {
                var response = await axios.get('pay/cash/' + orderid);
            },
            async paySSL(orderid) {
                var response = await axios.get('pay/ssl/' + orderid);
                if(response.status == 200)
                {
                    window.location.href =  response.data.data ;
                }else{
                    this.alertMessage = response.data.message;
                }
                this.payDialog = false;
            },
        },
        watch: {},
        computed: {},
        created() {
            this.getCurrentOrder();
        }
    };
</script>
<style>
.theme--light.v-stepper .v-stepper__label{
    text-align: center;
}
</style>