<template>
    <v-container fluid>
        <v-card elevation="0"  v-if="orders.length != 0" >
            <v-card-title style="background-color: #febe00;">
                <h5><v-icon>swap_horizontal_circle</v-icon> অপেক্ষমান কাজ  </h5>
            </v-card-title>
            <v-expansion-panel class="elevation-0" v-for="(order,orderindex)  in orders" :key="order.id"  >
                <v-expansion-panel-content>
                    <template v-slot:header>
                        <v-layout align-center row spacer>
                            <v-flex xs4 sm2 md1>
                                <v-avatar size="50px">
                                    <img v-if="order.photo" :src="order.photo" alt="Avatar">
                                    <v-icon v-else color="primary">people</v-icon>
                                </v-avatar>
                            </v-flex>

                            <v-flex sm5 md3>
                                <strong>Order# {{ order.order_no }}</strong>
                                <span class="grey--text">&nbsp;({{ order.items.length }})</span>
                                <div>
                                    <v-chip label small>
                                        {{order.category_name}}
                                    </v-chip>
                                </div>
                            </v-flex>

                            <v-flex no-wrap xs5 sm3 hidden-xs-only>
                                <p>{{order.date}}</p>
                                <p>{{order.time}}</p>
                            </v-flex>

                            <v-flex ellipsis hidden-sm-and-down><v-icon>room</v-icon>{{order.area}}, {{order.address}}</v-flex>
                            <v-flex>
                                <v-chip v-if="user_type == 'esp'" label :color="order.state == 1 ? 'primary' : order.state == 2 ? 'red' : 'success'" text-color="white" style="float: right;">
                                    <v-avatar>
                                        <v-icon>check_circle</v-icon>
                                    </v-avatar>
                                    {{order.state == 1 ? "Allocated" : order.state == 2 ? 'Wroking' : 'Waiting For Payment' }}
                                </v-chip>
                                <v-chip v-if="user_type == 'fsp'" label :color="order.state == 1 ? 'primary' : order.state == 2 ? 'red' : 'success'" text-color="white" style="float: right;">
                                    <v-avatar>
                                        <v-icon>check_circle</v-icon>
                                    </v-avatar>
                                    {{order.state == 1 ? "Accepted" : order.state == 2 ? 'Wroking' : 'Waiting For Payment' }}
                                </v-chip>
                            </v-flex>
                        </v-layout>
                    </template>
                    <v-card class="elevation-0" >
                        <v-list three-line>
                            <v-list-tile style="padding: 0 8px;">
                                <v-list-tile-avatar size="50px">
                                    <img :src="order.photo">
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>{{order.name}}</v-list-tile-title>
                                    <v-list-tile-sub-title class="text--primary">{{order.phone}}</v-list-tile-sub-title>
                                    <v-list-tile-sub-title>{{order.area}}, {{order.address}}</v-list-tile-sub-title>
                                </v-list-tile-content>
                                <v-list-tile-action>
                                    <v-list-tile-action-text>Total Service Taken : <strong>{{ order.total_service_taken }}</strong></v-list-tile-action-text>
                                    <v-spacer></v-spacer>
                                    <v-rating :value="order.user_rating" @input="giveRate($event, order.id)"></v-rating>
                                </v-list-tile-action>
                            </v-list-tile>
                        </v-list>
                        <v-divider style="margin:0"></v-divider>

                    <v-data-table :items="order.items" :headers="headers" hide-actions class>
                            <template v-slot:items="props">
                                <td class="text-xs-left" style="cursor: pointer">{{ props.item.service_name }}</td>
                                <td class="text-xs-left" style="cursor: pointer">{{ props.item.service_bit_name }}</td>
                                <td class="text-xs-center" style="cursor: pointer">
                                    <span v-if="props.item.status != 0 || order.state != 2">{{ props.item.quantity }}</span>
                                    <template v-if="order.state == 2 && props.item.status == 0">
                                        <v-btn-toggle fab style="background-color:#ddd">
                                            <v-btn flat small @click="qtyDecrease(orderindex,props.index)">
                                                <v-icon dark>remove</v-icon>
                                            </v-btn>
                                            <input type="text" class="custom-form-control" :value="props.item.quantity" readonly />
                                            <v-btn flat small @click="qtyIncrease(orderindex,props.index)">
                                                <v-icon dark>add</v-icon>
                                            </v-btn>
                                        </v-btn-toggle>
                                    </template>
                                </td>
                                <td class="text-xs-left" style="cursor: pointer">{{ props.item.total_price }}</td>
                                <td class="text-xs-left" style="cursor: pointer">
                                    <div v-if="props.item.status == 0">
                                        <v-icon v-if="order.state == 1">remove</v-icon>
                                        <v-icon v-if="order.state == 3">close</v-icon>
                                        <v-btn v-if="order.state == 2" @click="changeItemStatus(orderindex,props.index)" small fab color="primary">
                                            <v-icon>done</v-icon>
                                        </v-btn>
                                    </div>
                                    <v-icon v-else>done_all</v-icon>
                                </td>
                            </template>
                        </v-data-table>
                        <v-card-text>
                            <p v-if="order.state == 3"><strong>Collect Payment</strong></p>
                            <div v-if="order.state > 2">
                                <p class="caption">Total Price {{order.total_price }}/-</p>
                                <p class="caption">Emergency Charge {{order.emergency_charge}}/-</p>
                                <p class="caption">Outside DMC Charge {{order.outside_charge}}/-</p>
                                <p class="caption">Discount {{order.discount}}/-</p>
                                <v-divider style="margin:0"></v-divider>
                                <p class="caption">Total {{( parseInt( order.total_price) + (parseInt(order.emergency_charge) + parseInt(order.outside_charge))) - order.discount}}/-</p>
                            </div>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn v-if="order.state != 3" :disabled="checkItemDone(orderindex)" @click="changeOrderState(orderindex)" color="success">
                                {{ order.state == 1 ? 'Start Working' : order.state == 2 ? 'Finished' : order.state == 3 ? 'Collect Payment' : 'Finish Job'}}
                            </v-btn>
                            <div v-if="order.state == 3">
                                <v-btn small @click="collectCash(orderindex)">Cash</v-btn>
                                <v-btn small v-if="order.order_from != 'ondemand'" @click="payDigital(orderindex)">Digital</v-btn>
                                <v-btn small v-if="order.order_from == 'ondemand' || order.order_from == 'affiliation'" @click="payLater(order.id)">Pay Later</v-btn>
                            </div>
                        </v-card-actions>
                        <!-- <v-card-text style="height: 100px; position: relative">
                            <v-btn color="success" dark absolute bottom left fab @click="addNewService(order)">
                                <v-icon>add</v-icon>
                            </v-btn>
                        </v-card-text> -->
                    </v-card>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-card> 
        <v-card elevation="0" v-else>
            <v-card-title style="background-color: #febe00;">
                <h5><v-icon>gavel</v-icon>অপেক্ষমান কাজ  </h5>
            </v-card-title>
            <v-card-text style="text-align:center">You have no order.</v-card-text>
        </v-card>
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
        props: {
            role: {
                type: String,
                default: ""
            }
        },
        data() {
            return {
                  user_type : localStorageService.getItem("currentUserData").type ,
                  snackbar: false,
                    alertMessage: "",
                    headers: [{
                        text: "Service",
                        sortable: false
                    }, {
                        text: "Service Bit",
                        sortable: false
                    }, {
                        text: "Quantiy",
                        sortable: false,
                        align: "center"
                    }, {
                        text: "Price",
                        sortable: false
                    }, {
                        text: "Action",
                        sortable: false
                    }],
                orders:{},
                newHeadersItem: [{
                        text: "সার্ভিস",
                        value: "service"
                    }, {
                        text: "সার্ভিস বিট",
                        value: "service bit"
                    }, {
                        text: "পরিমান",
                        value: "quantity"
                    }, {
                        text: "মূল্য",
                        value: "price"
                    }, {
                        text: "মোট মূল্য",
                        value: "total price"
                    }, {
                        text: "Action",
                        value: "action"
                    }],
            };
        },
        methods: {
          
             async getWatingOrder() {
                let loader = this.$loading.show();
                var res = await axios.get("/waiting-orders");
                this.orders = res.data.data; 
                loader.hide();
            },
 
            async allocatedOrder() {
                let loader = this.$loading.show();
                var orders = await axios.get("/allowcated-orders/comrade");
                this.orders = orders.data.data;
                loader.hide();
            },
            changeItemStatus(orderIndex, itemIndex) {
                this.itemDone(this.orders[orderIndex].items[itemIndex].id);
                this.orders[orderIndex].items[itemIndex].status = 1;
            },
            async itemDone(itemId) {
                let loader = this.$loading.show();
                var res = await axios.get(`/item-status-change/${itemId}`);
                if (res.data.message == "Work Done") {
                    this.snackbar = true;
                    this.alertMessage = res.data.message;
                }
                loader.hide();
            },
            changeOrderState(orderIndex) {
                if (this.orders[orderIndex].state == 1) {
                    this.startServicing(this.orders[orderIndex].id);
                }

                if (this.orders[orderIndex].state == 2) {
                    this.finishServicing(this.orders[orderIndex].id, orderIndex);
                }
                this.orders[orderIndex].state++;
                this.orders[orderIndex].status++;
            },

            async startServicing(orderId) {
                let loader = this.$loading.show();
                var res = await axios.post("start-servicing", {
                    id: orderId
                });
                if (res.data.message == "Status Updated") {
                    this.snackbar = true;
                    this.alertMessage = res.data.message;
                }
                loader.hide();
            },

            async finishServicing(orderId, orderIndex) {
                let loader = this.$loading.show();
                var res = await axios.post("finish-servicing", {
                    id: orderId
                });
                if (res.data.message == "Order Finished") {
                    this.snackbar = true;
                    this.alertMessage = "Order finished successfully wait for payment";
                    this.orders[orderIndex].discount = res.data.discount;
                    this.orders[orderIndex].total_price = res.data.total_price;
                }
                loader.hide();
            },

            async collectPayment(orderId) {
                let loader = this.$loading.show();
                var response = await axios.post("collect-payment", {
                    id: orderId
                });
                
                this.snackbar = true;
                this.alertMessage = response.data.message;
                loader.hide();
            },
            collectCash(orderIndex) {
                if (this.orders[orderIndex].state == 3) {
                    this.collectPayment(this.orders[orderIndex].id);
                    this.orders[orderIndex].status++;
                    this.orders[orderIndex].state++;
                }
            },
            payDigital(orderIndex) {
                if (this.orders[orderIndex].state == 3) {
                    this.paySSL(this.orders[orderIndex].id);
                }

                // this.orders[orderIndex].status =
                //   parseInt(this.orders[orderIndex].status) + 1;
                // this.orders[orderIndex].state =
                //   parseInt(this.orders[orderIndex].state) + 1;
            },
            payLater(order_id) {
                let loader = this.$loading.show();
                axios.get(`/outstanding/add/${order_id}`).then(res => {
                    if (res.data == 1) {
                        this.allocatedOrder();
                    } else {
                        alert("Something went wrong");
                    }
                });
                loader.hide();
            },
            checkItemDone(orderIndex) {
                var arr = this.orders[orderIndex].items;
                var doneItems = this.orders[orderIndex].items.filter(
                    arr => arr.status == "1"
                ).length;

                if (this.orders[orderIndex].state == "1") {
                    return false;
                } else if (this.orders[orderIndex].state == "2" && doneItems == 0) {
                    return true;
                } else {
                    return false;
                }
            },
            qtyIncrease(orderIndex, itemIndex) {
                var item = this.orders[orderIndex].items[itemIndex];
                item.quantity++;
                if (item.quantity > 1) {
                    item.total_price = parseFloat(
                        parseInt(item.price) +
                        parseInt((item.quantity - 1) * item.additional_price)
                    ).toFixed(2);
                } else {
                    item.total_price = parseFloat(item.price).toFixed(2);
                }

                this.qtyUpdate(item.id, item.quantity);
            },
            qtyDecrease(orderIndex, itemIndex) {
                var item = this.orders[orderIndex].items[itemIndex];
                if (item.quantity != 1) {
                    item.quantity--;
                    item.total_price = parseFloat(
                        parseInt(item.price) +
                        parseInt((item.quantity - 1) * item.additional_price)
                    ).toFixed(2);
                }

                if (item.quantity == 1) {
                    item.total_price = parseFloat(item.price).toFixed(2);
                }

                this.qtyUpdate(item.id, item.quantity);
            },

            async qtyUpdate(itemId, qty) {
                let loader = this.$loading.show();
                var res = await axios.get(`item-quantity-update/${itemId}/${qty}`);
                if ((res.data.message = "success")) {
                    console.log("Quantity Update");
                }
                loader.hide();
            },

            async addNewService(order) {
                this.addNewServiceDrawer = true;
                let loader = this.$loading.show();
                var res = await axios.get(`/category/service_by_id/${order.category_id}`);
                this.selectedOrder = order;
                this.addNewItems = res.data.data.services;
                loader.hide();
            },

            checkSelectedBit: function(serviceBitId) {
                var arr = this.selectedNewItems;
                var slectedBit = this.selectedNewItems.find(
                    arr => arr.id == serviceBitId
                );
                var arr2 = this.addNewItems;
                var bit = this.addNewItems.find(arr2 => arr2.id == serviceBitId);
                return slectedBit == bit ? true : false;
            },

            async newSelectedItemsAdd() {
                
                let loader = this.$loading.show();
                var response = await axios.post(`add_new_service/${this.selectedOrder.id}`, {
                    data: this.selectedNewItems
                });
                var findOrder = this.orders.find(order => order.id == this.selectedOrder.id);
                findOrder.items = response.data.data;
                
                console.log(this.orders);
                // var findService = this.orders.find(order => order.id == this.selectedOrder.id);
                // findService.items = response.data.data;
                // this.orders = findService;
                // console.log(this.orders);
                
                // var existsOrder = this.orders;
                // var findService = this.orders.find(order => order.id == this.selectedOrder.id);
                // existsOrder.items = response.data.data;

                // console.log(findService);
                this.addNewServiceDrawer = false;


                // // console.log(this.selectedNewItems);
                // var res = await axios.post(`add_new_service/${this.selectedOrder.id}`, {
                //     data: this.selectedNewItems
                // });

                // var order = this.orders;
                // var corder = this.orders.find(order => order.id == this.selectedOrder.id);
                // console.log(corder.items);
                // //corder.items.push.apply(corder.items, res.data.data);
                // corder.items = res.data.data;
                // this.addNewServiceDrawer = false;
                loader.hide();
            },
            async paySSL(orderid) {
                let loader = this.$loading.show();
                var res = await axios.get(`pay/ssl/${orderid}`);
                if (res.data.status == "success") {
                    axios.post("pay/offline", {
                        orderId: orderid,
                        payUrl: res.data.data
                    }).then(res2 => {
                        this.alertMessage = res.data.message;
                    });
                    // window.location.href = res.data.data;
                    // //window.open(res.data.data, '_blank');
                    // this.payDialog = false;
                }else{
                        this.alertMessage = res.data.message;
                }
                loader.hide();
            }
        },
        created() {
            this.getWatingOrder();
        }
    };
</script>

<style  scoped>

</style>