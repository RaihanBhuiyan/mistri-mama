<template>
    <v-container fluid style="">
        <v-card elevation="0">
            <v-card-title class="white--text" style="background-color: #febe00;">
                <h5><v-icon>gavel</v-icon>চলমান কাজ</h5>
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
                                <v-chip label :color="order.state == 1 ? 'primary' : order.state == 2 ? 'red' : 'success'" text-color="white" style="float: right;">
                                    <v-avatar>
                                        <v-icon>check_circle</v-icon>
                                    </v-avatar>
                                    {{order.state == 1 ? "Allocated" : order.state == 2 ? 'Wroking' : 'Waiting For Payment' }}
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
                        <v-divider></v-divider>
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
                                <td class="text-xs-left" style="cursor: pointer">{{ props.item.price }}</td>
                                <td class="text-xs-left" style="cursor: pointer">{{ (props.item.quantity > 1 ) ? props.item.quantity - 1 : '' }}</td>
                                <td class="text-xs-left" style="cursor: pointer">{{ (props.item.quantity > 1 ) ? props.item.additional_price : '' }}</td>
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
                        <v-card-text class="text-right">
                            <p class="text-right"><strong>{{ (order.state == 3) ? "Collect Payment" : "Bill Summary" }}</strong></p>
                            <p class="caption text-right">Total Price {{order.total_price }}/-</p>
                            <p class="caption text-right">Emergency Hour Charge {{order.emergency_charge}}/-</p>
                            <p class="caption text-right">Outside DMC Charge {{order.outside_charge}}/-</p>
                            <p class="caption text-right">Order Discount {{order.discount}}/-</p>
                            <v-divider style="margin:0"></v-divider>
                            <p class="caption text-right">Total {{ order.grant_total }}/-</p>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn v-if="order.state != 3" :disabled="checkItemDone(orderindex)" @click="changeOrderState(orderindex)" color="success">
                                {{ order.state == 1 ? 'Start Working' : order.state == 2 ? 'Finished' : order.state == 3 ? 'Collect Payment' : 'Finish Job'}}
                            </v-btn>
                            <div v-if="order.state == 3 && order.pay_type != null">
                                <v-btn color="primary" small @click="collectCash(orderindex)">Cash</v-btn>
                                <v-btn color="info" small v-if="order.pay_type > 1 && order.order_from != 'ondemand'" @click="payDigital(orderindex)">Confirm Digital Payment</v-btn>
                                <v-btn color="secondary" small v-if="order.order_from == 'ondemand' || order.order_from == 'affiliation'" @click="payLater(order.id)">Pay Later</v-btn>
                            </div>
                            <div v-else-if="order.state == 3 && order.pay_type == null">
                                <v-btn color="primary" small @click="collectCash(orderindex)">Cash</v-btn>
                                <v-btn color="info" small v-if="order.order_from != 'ondemand'" @click="payDigital(orderindex)">Digital</v-btn>
                                <v-btn color="secondary" small v-if="order.order_from == 'ondemand' || order.order_from == 'affiliation'" @click="payLater(order.id)">Pay Later</v-btn>
                            </div>
                        </v-card-actions>
                        <v-btn color="success" v-if="order.state < 3" dark absolute bottom left fab @click="addNewService(order)"><v-icon>add</v-icon></v-btn>
                    </v-card>
                </v-expansion-panel-content>
            </v-expansion-panel>
            <v-card-text v-else caption font-weight-bold style="text-align:center">আপনার কোনো অর্ডার নাই</v-card-text>
        </v-card>

        <v-dialog v-if="addNewServiceDrawer" v-model="addNewServiceDrawer" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-toolbar dark color="primary">
                <v-btn icon dark @click.stop="addNewServiceDrawer = false; $emit('clicked', false)">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>সার্ভিস-এর বিস্তারিত</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark flat @click="newSelectedItemsAdd">Save</v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-card class="elevation-0">
                <v-list>
                    <v-list-group v-for="item in addNewItems" :key="item.title" no-action>
                        <template v-slot:activator>
                            <v-list-tile>
                                <v-list-tile-content>
                                    <v-list-tile-title>{{item.name}}</v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </template>

                        <v-list-tile v-for="subItem  in item.serviceBits" :key="subItem.id">
                            <v-checkbox v-model="selectedNewItems" :value="subItem">
                                <template v-slot:label>
                                    <v-list-tile-content>
                                        <v-list-tile-title class="pt-1">{{subItem.name}}  </v-list-tile-title>
                                    </v-list-tile-content>
                                </template>
                            </v-checkbox>
                            <v-list-tile-action >
                                <v-btn-toggle fab style="background-color:#ddd">
                                    <v-btn flat small @click="subItem.qty == 1 ? 1 :subItem.qty--">
                                        <v-icon dark>remove</v-icon>
                                    </v-btn>
                                    <input type="text" class="custom-form-control" :value="subItem.qty" />
                                    <v-btn flat small @click="subItem.qty++">
                                        <v-icon dark>add</v-icon>
                                    </v-btn>
                                </v-btn-toggle>
                            </v-list-tile-action>
                        </v-list-tile>
                    </v-list-group>
                </v-list>
                <v-card-actions>
                    <v-btn color="secondary" @click.stop="addNewServiceDrawer = false; $emit('clicked', false)">Close</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="newSelectedItemsAdd">Save</v-btn>
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
                giveFeedBackNratingDialog: false,
                getFeedbackData: {
                    order_no: 0.00,
                    rating: 0,
                },
                feedbackAnswer: [],
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
                addNewServiceDrawer: false,
                addNewItems: [],
                selectedNewItems: [],
                selectedOrder: "",
                
            };
        },
        methods: {
            async watingFeedbackOrder(){
                var orderFeedbacks = await axios.get("/check-feedback-order/comrade");
                if(orderFeedbacks.data.data != null)
                {
                    this.getFeedbackData = orderFeedbacks.data.data;
                    this.giveFeedBackNratingDialog = true; 
                }
            }, 
            async giveFeedBackNrating(){ 
                var response = await axios.post("/give-feedback-rating-process", {
                    rating: this.getFeedbackData.rating,
                    type: 'sp_to_user',
                    feedback_answer: this.feedbackAnswer,
                    order_id: this.getFeedbackData.order_id,
                }); 
                this.giveFeedBackNratingDialog = false;
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
                if (response.status == 200) {
                    this.snackbar = true; 
                    this.alertMessage = response.data.message;
                } else {
                    this.snackbar = true;
                    this.alertMessage = "Something went wrong";
                }
                this.allocatedOrder();
                loader.hide();
            },
            collectCash(orderIndex) {
                if (this.orders[orderIndex].state == 3) { 
                    this.collectPayment(this.orders[orderIndex].id);
                    this.feedbackQuestion(orderIndex); 
                    this.orders[orderIndex].status++;
                    this.orders[orderIndex].state++;

                    this.user_rating_dialog.order_id = this.orders[orderIndex].id;
                    this.user_rating_dialog.order_no = this.orders[orderIndex].order_no;
                    // this.giveUserRatingDialog = true;
                }
            },
            payDigital(orderIndex) {
                if (this.orders[orderIndex].state == 3) {
                    this.paySSL(this.orders[orderIndex].id);
                    this.orders[orderIndex].status++;
                    this.orders[orderIndex].state++;

                    this.user_rating_dialog.order_id = this.orders[orderIndex].id;
                    this.user_rating_dialog.order_no = this.orders[orderIndex].order_no;
                    //this.giveUserRatingDialog = true;
                }
            },
            async paySSL(orderid) {
                let loader = this.$loading.show();
                var response = await axios.get('pay/ssl/' + orderid);
                if(response.status == 200)
                {
                    this.allocatedOrder();
                    axios.post("pay/offline", {
                        orderId: orderid,
                        payUrl: response.data.data
                    }).then(error => {
                        this.alertMessage = error.data.message;
                    });
                }else{
                    this.alertMessage = response.data.message;
                }
                loader.hide();
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
                
                let loader = this.$loading.show();
                var res = await axios.get(`/category/service_by_id/${order.category_id}`);
                this.selectedOrder = order;
                this.addNewItems = res.data.data.services;
                this.addNewServiceDrawer = true;
                console.log(this.addNewItems);
                loader.hide();
            },

            checkSelectedBit: function(serviceBitId) { 
                let items = this.selectedOrder.items ;
                let is_check =   items.filter(x => x.service_bit_id == serviceBitId );
                if(is_check.length > 0 ){
                    return  1 ;
                }else{
                    return  0 ;
                } 
            },

            async newSelectedItemsAdd() {
                
                let loader = this.$loading.show();
                var response = await axios.post(`add_new_service/${this.selectedOrder.id}`, {
                    data: this.selectedNewItems
                });
                var findOrder = this.orders.find(order => order.id == this.selectedOrder.id);
                findOrder.items = response.data.data; 
                this.addNewServiceDrawer = false; 
                    this.selectedNewItems = [] ;
                loader.hide();
            }, 
        },
        created() {
            this.allocatedOrder();
            Echo.channel("orderFeedBackEvent").listen("OrderFeedBackEvent", response => {
                this.giveFeedBackNratingDialog = false ;
                this.watingFeedbackOrder(); 
            });
            this.watingFeedbackOrder();
        },
    };
</script>

<style>
    .v-expansion-panel--popout .v-expansion-panel__container,
    .v-expansion-panel--inset .v-expansion-panel__container {
        max-width: 100% !important;
    }
    
    .v-btn--bottom.v-btn--absolute {
        bottom: 8px;
    }
    .v-list.option.theme--light {
        padding: 0 0 0 44px;
    }
</style>