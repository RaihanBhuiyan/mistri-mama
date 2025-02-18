<template>
    <v-container fluid>
        <v-card elevation="0">
            <v-card-title class="white--text" style="background-color: #febe00;">
                <h5><v-icon>gavel</v-icon>পূর্বের  কাজ</h5>
            </v-card-title>
            <v-expansion-panel v-if="orders.length != 0">
                <v-expansion-panel-content v-for="(order,orderindex) in orders" :key="order.id">
                    <template v-slot:header>
                        <v-layout row wrap>
                            <v-flex xs12 sm6 md2>
                                <p class="subheading font-weight-bold text-left">Order No# {{order.order_no}}</p>
                                <p class="caption font-weight-bold hidden-md-and-up text-left"><strong>Service Category : </strong><span class="grey--text">{{order.category_name}}</span></p>
                                <p class="caption font-weight-bold hidden-md-and-up text-left"><strong>Service Area : </strong><span class="grey--text">{{order.area}}, {{order.address}}</span></p>
                                <p class="caption font-weight-bold hidden-md-and-up text-left"><strong>Date/Time : </strong><span class="grey--text">{{order.date}} {{order.time}}</span></p>
                                <p class="caption font-weight-bold hidden-md-and-up text-left" label :color="order.state == 1 ? 'primary' : order.state == 2 ? 'red' : 'success'">
                                    <strong>Order Status : </strong><span class="grey--text">{{order.state == 1 ? "Allocated" : order.state == 2 ? 'Wroking' : 'Waiting For Payment' }}</span>
                                </p>
                            </v-flex>
                            <v-flex md2 hidden-sm-and-down>
                                <strong>Service Category : </strong>
                                <span class="grey--text">{{order.category_name}}</span>
                            </v-flex>
                            <v-flex xs12 md3 hidden-sm-and-down>
                                <strong>Service Area : </strong>
                                <span class="grey--text">{{order.area}}, {{order.address}}</span>
                            </v-flex>
                            <v-flex xs12 md3 hidden-sm-and-down>
                                <p style="margin:0">
                                    <strong>Service Date/Time : </strong>
                                    <span class="grey--text">{{order.date}} {{order.time}}</span>
                                </p>
                                <p style="margin:0">
                                    <strong>Order Date/Time : </strong>
                                    <span class="grey--text">{{order.orders_place_time}}</span>
                                </p>
                            </v-flex>
                            <v-flex xs12 md2 hidden-sm-and-down>
                                <v-chip label color="success" text-color="white" style="float: right;">
                                    <v-avatar>
                                        <v-icon>check_circle</v-icon>
                                    </v-avatar>
                                    Payment completed
                                </v-chip>
                            </v-flex>
                        </v-layout>
                    </template>
                    <v-card class="elevation-0">
                        <v-list three-line>
                            <v-list-tile style="padding: 0 8px;">
                                <v-list-tile-content>
                                    <v-list-tile-title>{{order.name}}</v-list-tile-title>
                                    <v-list-tile-sub-title class="text--primary">{{order.phone}}</v-list-tile-sub-title>
                                    <v-list-tile-sub-title>{{order.area}}, {{order.address}}</v-list-tile-sub-title>
                                </v-list-tile-content>
                                <v-list-tile-action>
                                    <v-list-tile-action-text>Total Service Taken : <strong>{{ order.total_service_taken }}</strong></v-list-tile-action-text>
                                    <v-spacer></v-spacer>
                                    <v-rating :value="order.client_rating" :dense="true" :readonly="true"></v-rating>
                                </v-list-tile-action>
                            </v-list-tile>
                        </v-list>
                        <v-card-text class="subheading font-weight-bold text-right" v-if="order.cancel_note">Order Cancel Reason : {{ order.cancel_note }}</v-card-text>
                        <v-divider></v-divider>
                        <v-data-table :items="order.items" :headers="headers" hide-actions class>
                            <template v-slot:items="props">
                                <td class="text-xs-left" style="cursor: pointer">{{ props.item.service_name }}</td>
                                <td class="text-xs-left" style="cursor: pointer">{{ props.item.service_bit_name }}</td>
                                <td class="text-xs-left" style="cursor: pointer">{{ props.item.quantity }}</td>
                                <td class="text-xs-left" style="cursor: pointer">{{ props.item.price }}</td>
                                <td class="text-xs-left" style="cursor: pointer">{{ (props.item.quantity > 1 ) ? props.item.quantity - 1 : '' }}</td>
                                <td class="text-xs-left" style="cursor: pointer">{{ (props.item.quantity > 1 ) ? props.item.additional_price : '' }}</td>
                                <td class="text-xs-left" style="cursor: pointer">{{ props.item.total_price }}</td>
                                <td class="text-xs-left" style="cursor: pointer"><v-icon>done_all</v-icon></td>
                            </template>
                        </v-data-table>
                        <v-card-text class="text-right">
                            <p class="text-right"><strong>Collect Payment</strong></p>
                            <p class="caption text-right">Total Price {{order.total_price }}/-</p>
                            <p class="caption text-right">Emergency Hour Charge {{order.emergency_charge}}/-</p>
                            <p class="caption text-right">Outside DMC Charge {{order.outside_charge}}/-</p>
                            <p class="caption text-right">Order Discount {{order.discount}}/-</p>
                            <v-divider style="margin:0"></v-divider>
                            <p class="caption text-right">Total {{ ( parseInt(order.total_price) + (parseInt(order.emergency_charge) + parseInt(order.outside_charge))) - parseInt(order.discount) }}/-</p>
                        </v-card-text>
                    </v-card>
                </v-expansion-panel-content>
            </v-expansion-panel>
            <v-card-text v-else caption font-weight-bold style="text-align:center">আপনার কোনো অর্ডার নাই</v-card-text>
        </v-card>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
    import axios from "../../axios_instance";
    export default {
        data() {
                return {
                    snackbar: false,
                    alertMessage: "",
                    headers: [{
                        text: "সার্ভিস ধরন",
                        sortable: false
                    }, {
                        text: "সার্ভিস বিট",
                        sortable: false
                    }, {
                        text: "পরিমান",
                        sortable: false,
                        align: "center"
                    }, {
                        text: "পরিমান মূল্য",
                        sortable: false
                    },
                    {
                        text: " অতিঃ পরিমান",
                        value: "additional_quantity"
                    },
                    {
                        text: " অতিঃমূল্য",
                        value: "additional_price"
                    },
                    {
                        text: "মূল্য",
                        value: "total_price"
                    },
                    {
                        text: "অবস্থা",
                        sortable: false
                    }],
                    orders: [],
                };
            },
            methods: {
                async OrderHistory() {
                     let loader = this.$loading.show();
                    var orders = await axios.get("/orders-history/comrade");
                    this.orders = orders.data.data;
                    loader.hide();
                },
            },
            created() {
                this.OrderHistory();
            },
    };
</script>