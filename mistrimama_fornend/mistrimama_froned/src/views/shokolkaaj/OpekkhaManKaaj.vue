<template>
    <v-container fluid style="padding: 0">
        <v-card elevation="0">
            <v-card-title class="white--text" style="background-color: #febe00;">
                <h5><v-icon>gavel</v-icon>অপেক্ষমান কাজ</h5>
            </v-card-title>
            <v-expansion-panel v-if="orders.length != 0">
                <v-expansion-panel-content v-for="(order,waitingOrderindex)  in orders" :key="order.id">
                    <template v-slot:header>
                        <v-layout row wrap>
                            <v-flex xs12 sm6 md2>
                                <p class="subheading font-weight-bold text-left">Order No# {{order.order_no}}</p>
                                <p class="caption font-weight-bold hidden-md-and-up text-left"><strong>Service Category : </strong><span class="grey--text">{{order.category_name}}</span></p>
                                <p class="caption font-weight-bold hidden-md-and-up text-left"><strong>Service Area : </strong><span class="grey--text">{{order.area}}, {{order.address}}</span></p>
                                <p class="caption font-weight-bold hidden-md-and-up text-left"><strong>Date/Time : </strong><span class="grey--text">{{order.date}} {{order.time}}</span></p>
                                <p class="caption font-weight-bold hidden-md-and-up text-left" v-if="user_type == 'esp'" label :color="order.state == 1 ? 'primary' : order.state == 2 ? 'red' : 'success'">
                                    <strong>Order Status : </strong>
                                    <span class="grey--text">
                                        {{
                                        (order.state == 0 &&  order.comrade_id == null) ? "Accepted" : 
                                        (order.state == 1 &&  order.comrade_id != null) ? "Allocate" : 
                                        (order.state == 2) ? 'Wroking' : 
                                        (order.pay_type == '') ? 'Waiting For Payment' : 
                                        (order.pay_type > 1) ? 'Pay with Digital Payment' : 'Pay with Cash'
                                        }}
                                    </span>
                                </p>
                                <p class="caption font-weight-bold hidden-md-and-up text-left" v-if="user_type == 'fsp'" label :color="order.state == 1 ? 'primary' : order.state == 2 ? 'red' : 'success'">
                                    <strong>Order Status : </strong>
                                    <span class="grey--text">
                                        {{
                                        (order.state == 1) ? "Accepted" : 
                                        (order.state == 2) ? 'Wroking' : 
                                        (order.pay_type == '') ? 'Waiting For Payment' : 
                                        (order.pay_type > 1) ? 'Pay with Digital Payment' : 'Pay with Cash'
                                        }}
                                    </span>
                                </p>
                            </v-flex>
                            <v-flex md2 hidden-sm-and-down>
                                <strong>Service Category : </strong>
                                <span class="grey--text" v-if="$i18n.locale=='en'">
                                 {{order.items[0].category_name}}</span>
                                 <span class="grey--text" v-else>
                                 {{order.items[0].category_name_bn}}</span>
                            </v-flex>
                            <v-flex xs12 md3 hidden-sm-and-down>
                                <strong>Service Area : </strong>
                                <span class="grey--text">{{order.area}}, {{order.address}}</span>
                            </v-flex>
                            <v-flex xs12 md3 hidden-sm-and-down>
                                <strong>Date/Time : </strong>
                                <span class="grey--text">{{order.date}} {{order.time}}</span>
                            </v-flex>
                            <v-flex xs12 md2 hidden-sm-and-down>
                                <v-chip v-if="user_type == 'esp'" label :color="order.state == 1 ? 'primary' : order.state == 2 ? 'red' : 'success'" text-color="white" style="float: right;">
                                    <v-avatar>
                                        <v-icon>check_circle</v-icon>
                                    </v-avatar>
                                    {{
                                    (order.state == 0 &&  order.comrade_id == null) ? "Accepted" : 
                                    (order.state == 1 &&  order.comrade_id != null) ? "Allocate" : 
                                    (order.state == 2) ? 'Wroking' : 
                                    (order.pay_type == '') ? 'Waiting For Payment' : 
                                    (order.pay_type > 1) ? 'Pay with Digital Payment' : 'Pay with Cash'
                                    }}
                                </v-chip>
                                <v-chip v-if="user_type == 'fsp'" label :color="order.state == 1 ? 'primary' : order.state == 2 ? 'red' : 'success'" text-color="white" style="float: right;">
                                    <v-avatar>
                                        <v-icon>check_circle</v-icon>
                                    </v-avatar>
                                    {{ 
                                    (order.state == 1) ? "Accepted" : 
                                    (order.state == 2) ? 'Wroking' : 
                                    (order.pay_type == '') ? 'Waiting For Payment' : 
                                    (order.pay_type > 1) ? 'Pay with Digital Payment' : 'Pay with Cash'
                                    }}
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
                        <v-data-table :items="order.items" :headers="opekkhamankaajHeaders" hide-actions class>
                            <template v-slot:items="props">
                                <td class="text-xs-left" style="cursor: pointer"> 
                                <span v-if="$i18n.locale=='en'">{{ props.item.service_name }}</span>
                                <span v-else>{{ props.item.service_name_bn }}</span> </td>
                                <td class="text-xs-left" style="cursor: pointer">
                                    <span v-if="$i18n.locale=='en'">{{ props.item.service_bit_name }}</span>
                                    <span v-else>{{ props.item.service_bit_name_bn }}</span> 
                                </td>
                                <td class="text-xs-center" style="cursor: pointer">
                                    <template v-if="(order.reduce_type != null)">
                                        {{ props.item.quantity }}
                                    </template>
                                    <template v-if="(order.reduce_type == null)">
                                        <v-btn-toggle fab style="background-color:#ddd">
                                            <v-btn flat small @click="qtyDecrease(waitingOrderindex,props.index)">
                                                <v-icon dark>remove</v-icon>
                                            </v-btn>
                                            <input type="text" class="custom-form-control text-center" :value="props.item.quantity" readonly />
                                            <v-btn flat small @click="qtyIncrease(waitingOrderindex,props.index)">
                                                <v-icon dark>add</v-icon>
                                            </v-btn>
                                        </v-btn-toggle>
                                    </template>
                                </td>
                                <td class="text-xs-left" style="cursor: pointer">{{ props.item.price }}</td>
                                <td class="text-xs-left" style="cursor: pointer">{{ props.item.total_price }}</td>
                                <td class="text-xs-left" style="cursor: pointer">
                                    <v-icon>remove</v-icon>
                                </td>
                            </template>
                        </v-data-table>
                        <v-card-text class="text-right">
                            <p class="text-right"><strong>{{ (order.state == 3) ? "Collect Payment" : "Bill Summary" }}</strong></p>
                            <p class="caption text-right">Total Price {{order.total_price }}/-</p>
                            <p class="caption text-right">Emergency Charge {{order.emergency_charge}}/-</p>
                            <p class="caption text-right">Outside DMC Charge {{order.outside_charge}}/-</p>
                            <p class="caption text-right">Order Discount {{order.discount}}/-</p>
                            <p class="caption text-right" v-if="order.reduce_type != null">{{ order.reduce_type }} {{ order.reduce_amount }}/-</p>
                            <v-divider style="margin:0"></v-divider>
                            <p class="caption text-right">Total {{ order.grant_total }}/-</p>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn v-if="order.comrade_id != null &&  order.state == 1 &&  user_type == 'esp'  "  @click="openShohokariPoribortonDialog(waitingOrderindex, 'shohokariporiborton')" color="primary">Change comrade</v-btn>
                            <v-btn v-if="order.comrade_id == null &&  order.state == 0 &&  user_type == 'esp'  "  @click="openShohokariPoribortonDialog(waitingOrderindex, 'shohokariporiborton')" color="primary">Allocate</v-btn>

                            <v-btn v-if="order.status == 2" :disabled="checkItemDone(waitingOrderindex)" @click="startServicing(order.id, waitingOrderindex)" color="success">Start Working</v-btn>
                            <!-- <div v-if="order.state == 3 && order.pay_type != null">
                                <v-btn color="primary" small v-if="order.pay_type == 1" @click="collectCash(waitingOrderindex)">Confirm Cash Payment</v-btn>
                                <v-btn color="info" small v-if="order.pay_type > 1 && order.order_from != 'ondemand'" @click="payDigital(waitingOrderindex)">Confirm Digital Payment</v-btn>
                                <v-btn color="secondary" small v-if="order.order_from == 'ondemand' || order.order_from == 'affiliation'" @click="payLater(order.id)">Pay Later</v-btn>
                            </div>
                            <div v-else-if="order.state == 3 && order.pay_type == null">
                                <v-btn color="primary" small @click="collectCash(waitingOrderindex)">Cash</v-btn>
                                <v-btn color="info" small v-if="order.order_from != 'ondemand'" @click="payDigital(waitingOrderindex)">Digital</v-btn>
                                <v-btn color="secondary" small v-if="order.order_from == 'ondemand' || order.order_from == 'affiliation'" @click="payLater(order.id)">Pay Later</v-btn>
                            </div> -->
                        </v-card-actions> 
                        <v-btn v-if="(order.reduce_type == null)" color="success" dark absolute bottom left fab @click="addNewService(order)"><v-icon>add</v-icon></v-btn>
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
                <v-list two-line>
                    <v-list-group v-for="item in addNewItems" :key="item.title" no-action>
                        <template v-slot:activator>
                            <v-list-tile>
                                <v-list-tile-content>
                                    <v-list-tile-title>{{item.name}}</v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </template>

                        <v-list-tile v-for="bit in item.serviceBits" :key="bit.id">
                            <v-checkbox v-model="selectedNewItems" :value="bit">
                                <template v-slot:label>
                                    <v-list-tile-content>
                                        <v-list-tile-title class="pt-1">{{bit.name}}</v-list-tile-title>
                                         <v-list-tile-sub-title>Price: {{bit.price}}</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </template>
                            </v-checkbox>
                            <v-list-tile-action >
                                <v-btn-toggle fab style="background-color:#ddd">
                                    <v-btn flat small @click="bit.qty == 1 ? 1 :bit.qty--">
                                        <v-icon dark>remove</v-icon>
                                    </v-btn>
                                    <input type="text" class="text-center" style="width:50px" :value="bit.qty" />
                                    <v-btn flat small @click="bit.qty++">
                                        <v-icon dark>add</v-icon>
                                    </v-btn>
                                </v-btn-toggle>
                            </v-list-tile-action>
                            <v-list-tile-action style="min-width: auto;">
                                <v-btn color="primary" flat @click="showBrief(bit)" style="height: auto; min-width: auto;">
                                    <v-icon>info</v-icon>
                                </v-btn>
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
        <v-dialog  v-if="shohokariDialog"  v-model="shohokariDialog"  fullscreen transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="shohokariDialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>সহকারী পরিবর্তন</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark flat  :disabled="checked_comrade ==''" @click="ShohokariChange()">Save</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-divider style="margin:0"></v-divider>
                <v-list subheader two-line>
                    <v-subheader>সহকারী নির্বাচন করুন</v-subheader>
                    <v-radio-group v-if="comrades.length > 0" :mandatory="true" style="display:block">
                        <template v-for="comrade in comrades">
                            <v-list-tile @click="checkedComrade(comrade.id)">
                                <v-list-tile-action>
                                    <v-radio  :value="comrade.id" :key="comrade.id"></v-radio>
                                </v-list-tile-action>
                                <v-list-tile-content>
                                    <v-list-tile-title>{{ comrade.name }}</v-list-tile-title>
                                    <v-list-tile-title>{{ comrade.phone }}</v-list-tile-title>
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
        <v-dialog v-model="breif" max-width="640">
            <v-card>
                <v-card-title class="title">{{ bitBreif.name }}</v-card-title>
                <v-card-text>
                    <p style="margin:0"><strong>Price : </strong>{{bitBreif.price}}</p>
                    <div v-html="bitBreif.brief"></div>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="breif = false">Close</v-btn>
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
import { localStorageService } from "../../helper.js";

export default {
    name: 'OpekkhaManKaaj',
    props: {
        waitingOrders: Array,
    },
    data() {
        return {
            user_type : localStorageService.getItem("currentUserData").type ,
            comrades: {},
            opekkhamankaajHeaders: [{
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
                text: "মূল্য",
                value: "total_price"
            },
            {
                text: "অবস্থা",
                sortable: false
            }],
            orders:{},
            shohokariDialog : false ,
            selectorderindex : '',
            checked_comrade : '',
            addNewServiceDrawer: false,
            addNewItems: [],
            selectedNewItems: [],
            selectedOrder: "",
            breif: false,
            bitBreif: [],
            snackbar: false,
            alertMessage: "",
        };
    },
    methods: {
        async getWatingOrder() {
            let loader = this.$loading.show();
            var response = await axios.get("/waiting-orders");
            console.log('waiting-orders',response.data.data);
            this.orders = response.data.data;
            loader.hide();
        },
        async ShohokariChange(){
            let res = await axios.get("/change-comrade/"+ this.orders[this.selectorderindex].id+"/"+this.checked_comrade);
                this.shohokariDialog = false ;
                this.snackbar = true;
                this.alertMessage = res.data.message;
                this.getWatingOrder();
        },
        checkedComrade(comrade){
            this.checked_comrade = comrade ;
        },
        openShohokariPoribortonDialog: function(index, value) {
            console.log(this.orders[index].category_id);
            if(this.user_type == 'esp'){
                this.getComrades(this.orders[index].category_id);
            }
            this.selectorderindex =  index ;
            this.shohokariDialog = true ;
        },
        async getComrades(category_id)
        {
            let loader = this.$loading.show();
            var response = await axios.get("/sp/comrades/" + category_id);
            this.comrades = response.data; 
            loader.hide();
        },
        changeItemStatus(waitingOrderindex, itemIndex) {
            this.itemDone(this.orders[waitingOrderindex].items[itemIndex].id);
            this.orders[waitingOrderindex].items[itemIndex].status = 1;
        },
        async itemDone(itemId) {
            var res = await axios.get(`/item-status-change/${itemId}`);
            if (res.data.message == "Work Done") {
                this.snackbar = true;
                this.alertMessage = res.data.message;
            }
        },
        

        async startServicing(orderId, waitingOrderindex) {
            let loader = this.$loading.show();
            try {
                var response = await axios.post("start-servicing", {
                    id: orderId
                });
                this.orders[waitingOrderindex].state++;
                this.orders[waitingOrderindex].status++;
                this.orders.splice(waitingOrderindex, 1);
                this.alertMessage = response.data.message;
                this.snackbar = true;
                this.$parent.getServiceProviderDetails();
            } catch (error) {
                this.alertMessage = error.response.data.message;
                this.snackbar = true;
            }
            loader.hide();
        },
        
        checkItemDone(waitingOrderindex) {
            var arr = this.orders[waitingOrderindex].items;
            var doneItems = this.orders[waitingOrderindex].items.filter(
                arr => arr.status == "1"
            ).length;

            if (this.orders[waitingOrderindex].state == "1") {
                return false;
            } else if (this.orders[waitingOrderindex].state == "2" && doneItems == 0) {
                return true;
            } else {
                return false;
            }
        },
        qtyIncrease(waitingOrderindex, itemIndex) {
            var item = this.orders[waitingOrderindex].items[itemIndex];
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
        qtyDecrease(waitingOrderindex, itemIndex) {
            var item = this.orders[waitingOrderindex].items[itemIndex];
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
            var res = await axios.get(`item-quantity-update/${itemId}/${qty}`);
            if ((res.data.message = "success")) {
            }
        },

        async addNewService(order) {
            this.addNewServiceDrawer = true;
            var res = await axios.get(`/category/service_by_id/${order.category_id}`);
            this.selectedOrder = order;
            this.addNewItems = res.data.data.services;
        },
        showBrief(servirceBit) {
            this.breif = true;
            this.bitBreif = servirceBit;
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
            var response =  await axios.post(`add_new_service/${this.selectedOrder.id}`, {
                data: this.selectedNewItems
            });
            var findOrder = this.orders.find(order => order.id == this.selectedOrder.id);
            findOrder.items = response.data.data;  
            this.addNewServiceDrawer = false; 
            loader.hide();
        },
    },
    created() {
        this.getWatingOrder();
        this.$parent.getServiceProviderDetails();
    }
};
</script>

<style  scoped>

</style>