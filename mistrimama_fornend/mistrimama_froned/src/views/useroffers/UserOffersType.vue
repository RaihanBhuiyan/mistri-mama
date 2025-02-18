<template>
    <v-container fluid style="" v-if="categorys">
        <v-alert class="blinking" icon="mdi-firework" prominent type="warning" :value="is_schedule_charge" v-html="schedule_charge_msg"></v-alert>
        <v-alert class="blinking" type="warning" :value="is_area_charge" v-html="area_charge_msg"></v-alert>
        <div class="service-area" v-if="viewarea == 'service'">
            <v-card elevation="0">
                <v-layout wrap style="text-align: center">
                    <v-flex md2 sm4 xs6 v-for="category in categorys" :key="category.id" :to="'/service/'+category.slug">
                        <div @click="ifChangeServiceCategory(category.slug)" style="display:block; cursor: pointer;">
                            <img style="width:80px" :src="category.thumb" :alt="category.slug" class="" />
                            <p class="text-center">{{ category.name }}</p>
                        </div>
                    </v-flex>
                </v-layout>
                <v-layout style="height:7px">
                    <v-progress-linear class="m-0" v-if="!services.length" v-show="isProgressLoading" :indeterminate="true"></v-progress-linear>
                </v-layout>
                <v-divider></v-divider>
                <v-card-actions v-if="selectedServiceBit.length != 0">
                    <v-list-tile-content>
                        <v-list-tile-title class="font-weight-bold subheading">Total Price: ৳ {{ ((totalPrice(selectedServiceBit) + extraChargeTotal()) - discount) }} Total Service Qty: {{ totalQty(selectedServiceBit) }}</v-list-tile-title>
                    </v-list-tile-content>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="nextView">NEXT</v-btn>
                </v-card-actions>
                <v-divider></v-divider>
                <v-layout row wrap v-if="services.length != 0">
                    <v-flex xs12 sm6 md4 v-for="service in services" :key="service.id">
                        <div style="padding: 10px 5px; cursor:pointer">
                            <v-card @click="loadServiceBit(service.id)" :color="(getServiceBit(service.id).length > 0) ? 'primary' : ''">
                                <v-card-text> 
                                    <p class="text-center subheading font-weight-bold" style="margin:0">{{ service.name }}</p>
                                    <p class="text-center caption" style="margin:0">Under {{ service.serviceBits.length }} Service bits</p>
                                </v-card-text>
                            </v-card>
                        </div>
                    </v-flex>
                </v-layout>
                <v-dialog v-model="serviceBitsDialog" scrollable fullscreen hide-overlay v-show="serviceBitsDialog" style="z-index:9999" transition="dialog-bottom-transition">
                    <v-card>
                        <v-toolbar dark color="primary">
                            <v-btn icon dark @click.stop="serviceBitsDialog = !serviceBitsDialog">
                                <v-icon>close</v-icon>
                            </v-btn>
                            <v-spacer></v-spacer>
                            <v-toolbar-items>
                                <v-btn dark flat @click.stop="serviceBitsDialog = !serviceBitsDialog">Ok</v-btn>
                            </v-toolbar-items>
                        </v-toolbar>
                        <v-list three-line v-for="serviceBit in serviceBits" :key="serviceBit.id" style="border-bottom: 1px solid rgba(0,0,0,0.12);">
                            <v-list-tile style="height:auto">
                                <v-list-tile-content>
                                    <v-checkbox v-model="selectedServiceBit" :value="serviceBit" style="margin-top:0">
                                        <template v-slot:label>
                                            <v-list-tile-content>
                                                <v-list-tile-sub-title>{{serviceBit.name}}</v-list-tile-sub-title>
                                                <v-list-tile-sub-title>Price: <del>৳ {{serviceBit.mrp_price}}</del></v-list-tile-sub-title>
                                                <v-list-tile-sub-title>
                                                    <span style="font-size: 16px; color: #febe00;">Discounted Price: ৳ {{ (checkSelectedBit(serviceBit.id)) ? bitPriceTotal(serviceBit.id) : parseInt(serviceBit.price) }}</span>
                                                </v-list-tile-sub-title>
                                            </v-list-tile-content>
                                        </template>
                                    </v-checkbox>
                                </v-list-tile-content>
                                <v-list-tile-action>
                                    <v-btn-toggle v-if="checkSelectedBit(serviceBit.id)" fab style="margin: 0 auto; background-color:#ddd;">
                                        <v-btn flat small @click="qtyDecrease(serviceBit.id)">
                                            <v-icon dark>remove</v-icon>
                                        </v-btn>
                                        <input type="text" class="text-center" style="width:35px" :value="serviceBit.qty" />
                                        <v-btn flat small @click="qtyIncrease(serviceBit.id)">
                                            <v-icon dark>add</v-icon>
                                        </v-btn>
                                    </v-btn-toggle>
                                </v-list-tile-action>
                                <v-list-tile-action style="min-width: auto;">
                                    <v-btn flat @click="showBrief(serviceBit.id)" style="height: auto; min-width: auto;">
                                        <v-icon>{{breif ? breifActiveId == serviceBit.id ? 'keyboard_arrow_up' : 'keyboard_arrow_down' : 'keyboard_arrow_down'}}</v-icon>
                                    </v-btn>
                                </v-list-tile-action>
                            </v-list-tile>
                            <div style="padding: 0 16px;" v-if="breif ? breifActiveId == serviceBit.id ? true : false : false">
                                <p style="margin:0"><strong>Price : </strong>{{serviceBit.price}}</p>
                                <div v-html="serviceBit.brief"></div>
                            </div>
                        </v-list>
                    </v-card>
                </v-dialog>
                <v-divider></v-divider>
                <v-card-actions v-if="selectedServiceBit.length != 0">
                    <v-list-tile-content>
                        <v-list-tile-title class="font-weight-bold subheading">Total Price: ৳ {{ ((totalPrice(selectedServiceBit) + extraChargeTotal()) - discount) }} Total Service Qty: {{ totalQty(selectedServiceBit) }}</v-list-tile-title>
                    </v-list-tile-content>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="nextView">NEXT</v-btn>
                </v-card-actions>
            </v-card>
        </div>
        <div class="date-area" v-if="viewarea == 'schedule'">
            <v-card>
                <v-card-title>
                    <h5>Schedule Your Service</h5>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-title>
                    <hooper style="height:auto" :settings="hooperDatesSettings">
                        <slide v-for="(value,i) in dates" :key="i">
                            <div class="p-2">
                                <v-btn class="m-0" block large :color="order.date == value.date ? 'primary' : 'secondary'" @click="addDate(value.date)">{{value.date}}<br>{{value.day}}</v-btn>
                            </div>
                        </slide>
                        <hooper-navigation class="hooper-navigation-backend" slot="hooper-addons"></hooper-navigation>
                    </hooper>
                    <hooper style="height:auto" :settings="hooperTimesSettings">
                        <slide v-for="(value,i) in times" :key="i">
                            <div class="p-2">
                                <v-btn class="m-0" block large :color="order.time == value.time ? 'primary' : 'secondary'" @click="addTime(value)">{{value.time}}</v-btn>
                            </div>
                        </slide>
                        <hooper-navigation class="hooper-navigation-backend" slot="hooper-addons"></hooper-navigation>
                    </hooper>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-list-tile-content>
                        <v-list-tile-title class="font-weight-bold subheading">Total Price: ৳ {{ ((totalPrice(selectedServiceBit) + extraChargeTotal()) - discount) }} Total Service Qty: {{ totalQty(selectedServiceBit) }}</v-list-tile-title>
                    </v-list-tile-content>
                    <v-spacer></v-spacer>
                    <v-btn color="secondary" @click="prevView">PREV</v-btn>
                    <v-btn v-if="order.date && order.time" color="primary" @click="nextView">NEXT</v-btn>
                </v-card-actions>
            </v-card>
        </div>

        <div class="confirmation-area" v-if="viewarea == 'confirmation'">
            <v-card>
                <v-card-title>
                    <h5>Confirm Your Order</h5>
                </v-card-title>
                <v-divider></v-divider>
                <v-layout wrap>
                    <v-flex xs12 sm12 md6>
                        <v-card-title>
                            <h5>Order Informations</h5>
                            <v-spacer></v-spacer>
                            <v-switch label="Order For Other" color="warning" v-model="order.orderFor" hide-details value="others" @change="checkOrderFor"></v-switch>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-form ref="guestForm" v-model="valid" @submit.prevent="guestPrevent">
                            <v-card-text>
                                <v-text-field v-if="order.orderFor == 'others'" color="accent" v-model="guestFormData.name" :error-messages="display_errors.name" label="Name"></v-text-field>
                                <p v-else><strong>Name:</strong> {{guestFormData.name}}</p>
                                <v-text-field v-if="order.orderFor == 'others'" color="accent" v-model="guestFormData.phone" :error-messages="display_errors.phone" prefix="+88" label="Phone Number *"></v-text-field>
                                <p v-else><strong>Phone:</strong> {{guestFormData.phone}}</p>

                                <v-select v-if="order.orderFor == 'others'" color="accent" v-model="guestFormData.area" @change="checkOutOfAreaCharge" :error-messages="display_errors.area" :items="cluster" :item-text="'name'" :item-value="'id'" label="Area *"></v-select>
                                <p v-else><strong>Area:</strong> {{guestFormData.area_name}}</p>
                                
                                <v-textarea v-if="order.orderFor == 'others'" color="accent" v-model="guestFormData.address" :error-messages="display_errors.address" label="Address" placeholder="Write down your address"></v-textarea>
                                <p v-else><strong>Address:</strong> {{guestFormData.address}}</p>
                                <v-text-field color="accent" v-model="guestFormData.comments" label="Comments"></v-text-field>
                            </v-card-text>
                        </v-form>
                    </v-flex>
                    <v-flex xs12 sm12 md6>
                        <v-card-title>
                            <h5>Order Summary</h5>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-layout row wrap v-for="(service,i) in order.services" :key="i">
                                <v-flex xs12 sm12 md12>
                                    <strong>{{service[0]}}</strong>
                                </v-flex>
                                <v-flex xs12 sm12 md12 v-for="(bit,i) in service[1]" :key="i">
                                    <v-layout row wrap class="pl-3">
                                        <v-flex xs6 sm6 md6>{{bit.name}}</v-flex>
                                        <v-flex xs3 sm3 md3>
                                            <span style="padding:0 10px">{{bit.qty}} qty</span>
                                        </v-flex>
                                        <v-flex xs3 sm3 md3 class="text-right">
                                            <span style="padding:0 10px">৳ {{bit.qty > 1 ? (bit.qty - 1) * parseInt(bit.additional_price) + parseInt(bit.price) : parseInt(bit.price)}}</span>
                                        </v-flex>
                                    </v-layout>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap v-if="schedule_charge != 0 || area_charge != 0 || discount != 0">
                                <v-flex xs12 sm12 md12>
                                    <strong>Other's Charge</strong>
                                </v-flex>
                                <v-flex>
                                    <v-layout row wrap class="pl-3" v-if="schedule_charge != 0">
                                        <v-flex xs9 sm9 md9>For emergency service hour</v-flex>
                                        <v-flex xs3 sm3 md3 class="text-right"><span style="padding:0 10px">৳ {{ schedule_charge }}</span></v-flex>
                                    </v-layout>
                                    <v-layout row wrap class="pl-3" v-if="area_charge != 0">
                                        <v-flex xs9 sm9 md9>For Outside City charge</v-flex>
                                        <v-flex xs3 sm3 md3 class="text-right"><span style="padding:0 10px">৳ {{ area_charge }}</span></v-flex>
                                    </v-layout>
                                </v-flex>
                            </v-layout>
                                
                            <v-layout row wrap v-if="discount != 0">
                                <v-flex xs12 sm12 md12>
                                    <strong>Promocode Apply</strong>
                                </v-flex>
                                <v-flex>
                                    <v-layout row wrap class="pl-3" v-if="discount != 0">
                                        <v-flex xs9 sm9 md9>Discount</v-flex>
                                        <v-flex xs3 sm3 md3 class="text-right"><span style="padding:0 10px">৳ (-) {{ discount }}</span></v-flex>
                                    </v-layout>
                                </v-flex>
                            </v-layout>
                            <v-btn v-if="discount == 0" class="mt-3" block color="primary" dark @click.stop="openPromoCodeDialog">Use promo code</v-btn>
                            <v-dialog v-model="dialogPromoCode" max-width="400">
                                <v-card>
                                    <v-alert :value="alert_promocode" type="error" v-html="alert_promocode_msg"></v-alert>
                                    <v-card-title>Use your promo code</v-card-title>
                                    <v-card-text v-if="promocodes.length != 0">
                                        <v-chip label v-for="promocode in promocodes" :key="promocode.id" @click="onApply(promocode.code)" v-if="promocode.is_expired == false" color="red" text-color="white">
                                            <v-avatar class="primary" title="Uses Number">{{ parseInt(promocode.promo_code.be_used) - parseInt(promocode.have_used) }}</v-avatar>
                                            <strong>{{ promocode.code }}</strong>
                                        </v-chip>
                                    </v-card-text>
                                    <v-card-text>
                                        <v-text-field color="accent" v-model="promoCode" :error-messages="display_errors.promo_code" type="text" label="Enter your promo code" outlined></v-text-field>
                                    </v-card-text>
                                    <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="green darken-1" @click="closePromoCodeDialog">Close</v-btn>
                                    <v-btn color="green darken-1" @click="applyPromoCode">Apply Promo Code</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-card-text>
                    </v-flex>
                </v-layout>
                <v-card-actions>
                    <v-card-text>Have you any referral code ?</v-card-text>
                    <v-spacer></v-spacer>
                    <v-text-field color="accent" v-model="refCode" type="text" label="Referral code" prepend-icon="tag" outlined></v-text-field>
                </v-card-actions>
                <v-divider></v-divider>
                <v-card-actions v-if="selectedServiceBit.length != 0">
                    <v-list-tile-content>
                        <v-list-tile-title class="font-weight-bold subheading">Total Price: ৳ {{ ((totalPrice(selectedServiceBit) + extraChargeTotal()) - discount) }} Total Service Qty: {{ totalQty(selectedServiceBit) }}</v-list-tile-title>
                    </v-list-tile-content>
                    <v-spacer></v-spacer>
                    <v-btn color="secondary" @click="prevView">PREV</v-btn>
                    <v-btn color="primary" @click="nextView">FINISH</v-btn>
                </v-card-actions>
            </v-card>
        </div>
            
        <v-layout row justify-center>
            <v-dialog v-model="orderDone" v-if="orderPlacingStatus" persistent width="450">
                <v-card color="primary" dark>
                    <v-card-text>
                        Please wait. your order is placing...
                        <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </v-layout>
        <v-dialog v-model="dialogIfCategoryChange" max-width="300">
            <v-card>
                <v-card-title class="headline">Alert</v-card-title>
                <v-card-title>Your selected service items will be removed !</v-card-title>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="dialogIfCategoryChange = false">Cancel</v-btn>
                    <v-btn color="primary" @click="afterChangeCategory()">Ok</v-btn>
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
        components: {
            Hooper,
            Slide,
            HooperProgress,
            HooperNavigation 
        },
        data() {
            return {
                dialogIfCategoryChange: false,
                nextRoute: null,
                dialogPromoCode: false,
                alert_promocode_msg: null,
                alert_promocode: false,
                selectedServiceBit: [],
                categorys: [],
                category: [],
                services: [],
                selectedServices: [],
                serviceBits: [],
                site_configs:{
                    schedule_charge: 0,
                    area_charge: 0,
                    office_start_time: null,
                    office_end_time: null,
                    outside_area_id: []
                },
                serviceBitsDialog: null,
                breif: null,
                breifActiveId: "",
                isProgressLoading: false,
                cluster: [],
                valid: null,
                auth_user: [],
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
                refCode: null,
                promoCode: null,
                promocodes: [],
                guestFormData: {
                    name: null,
                    phone: null,
                    area: null,
                    area_name: null,
                    address: null,
                    comments: null,
                },
                requiredRules: [
                    v => !!v || "This field cannot be empty"
                ],
                 phoneRules: [
                    v => !!v || "This field cannot be empty", 
                    v => v.match(/(^(\+88|0088)?(01){1}[3456789]{1}(\d){8})$/)|| "Invalid mobile number (+8801XXXXXXXXX)",
                   
                ],
                window: {
                    width: 0,
                    height: 0
                },
                viewarea: "service",
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
            };
        },
        created() {
            this.getService();
            this.categorysGet();
            this.getSiteConfig();
            this.handleResize();
            this.getLocations();
            this.auth_user = localStorageService.getItem("currentUserData");
        },
        computed: {},
        watch: {
            $route(to, from) {
                this.clearSelected();
                this.getService();
            },
            layoutAttr: function() {
                if (this.window.width < 100) {
                    return "row";
                } else {
                    return "column";
                }
            }
            // handleResize()
        },
        methods: {
            afterChangeCategory(){
                this.$router.replace('/service/' + this.nextRoute);
                this.dialogIfCategoryChange = false; 
            },
            ifChangeServiceCategory(category_slug){
                if(this.selectedServiceBit.length != 0)
                {
                    this.dialogIfCategoryChange = true;
                    this.nextRoute = category_slug;
                    return false;
                }
                this.$router.replace("/service/" + category_slug);
            },
            async getLocations(){
                let response = await axios.get("division");
                this.cluster = response.data.data[0].cluster;
            },
            async getSiteConfig() {
                let response = await axios.get("site-configs");
                this.dates = response.data.schedule_dates;
                this.times = response.data.schedule_times;
                this.site_configs.schedule_charge = response.data.schedule_charge;
                this.site_configs.area_charge = response.data.area_charge;
                this.site_configs.outside_area_id = response.data.outside_area_id;
                this.site_configs.office_start_time = response.data.office_start_time;
                this.site_configs.office_end_time = response.data.office_end_time;
            },
            async getService() {
                this.isProgressLoading = true;
                var res = await axios.get("/category/service/" + this.$route.params.category);
                res.data ? (this.category = res.data.data) : (this.category = []);
                this.services = this.category.services;
                this.isProgressLoading = false;
            },
            loadServiceBit: function(serviceId) {
                this.serviceBits = "";
                var arr = this.services;
                var selectedService = this.services.find(arr => arr.id == serviceId)
                this.serviceBits = selectedService.serviceBits;
                this.selectedServices.push(selectedService);
                this.serviceBitsDialog = true;
            },
            getServiceBit: function(serviceId) {
                var arr = this.selectedServices;
                var selectedService = this.selectedServices.find(
                    arr => arr.id == serviceId
                );
                if (selectedService) {
                    var arr2 = this.selectedServiceBit;
                    var bits = this.selectedServiceBit.filter(
                        arr2 => arr2.service_id == selectedService.id
                    );
                    return bits;
                } else {
                    return [];
                }
            },
            categorysGet: function() {
                return (this.categorys = localStorageService.getItem("categorys"));
            },
            qtyIncrease: function(serviceBitId) {
                var selectedServiceBit = this.selectedServiceBit.find(
                    selectedServiceBit => selectedServiceBit.id == serviceBitId
                ).qty++;
            },
            qtyDecrease: function(serviceBitId) {
                var selectedServiceBit = this.selectedServiceBit.find(
                    selectedServiceBit => selectedServiceBit.id == serviceBitId
                ).qty;
                if (selectedServiceBit != 1) {
                    this.selectedServiceBit.find(
                        selectedServiceBit => selectedServiceBit.id == serviceBitId
                    ).qty--;
                }
            },
            showBrief(servirceBitId) {
                if (this.breifActiveId != servirceBitId) {
                    this.breifActiveId = servirceBitId;
                    this.breif = true;
                } else {
                    this.breif = false;
                    this.breifActiveId = "";
                }
            },
            checkSelectedBit: function(serviceBitId) {
                var arr = this.selectedServiceBit;
                var slectedBit = this.selectedServiceBit.find(
                    arr => arr.id == serviceBitId
                );
                var arr2 = this.serviceBits;
                var bit = this.serviceBits.find(arr2 => arr2.id == serviceBitId);
            
                if(slectedBit == bit)
                {
                    return true;
                }
                bit.qty = 1
                return false;
            },
            bitPriceTotal: function(serviceBitId) {
                var arr = this.selectedServiceBit;
                var slectedBit = this.selectedServiceBit.find(
                    arr => arr.id == serviceBitId
                );
                var arr2 = this.serviceBits;
                var bit = this.serviceBits.find(arr2 => arr2.id == serviceBitId);
                if (slectedBit == bit) {
                    // console.log(slectedBit);
                    var total =
                        slectedBit.qty > 1 ? parseInt(slectedBit.price) +
                        parseInt(slectedBit.additional_price) * (slectedBit.qty - 1) : parseInt(slectedBit.price);
                    return total;
                }
                // return slectedBit == bit ? true : false;
            },
            clearSelected: function() {
                this.services = [];
                this.serviceBits = [];
                this.selectedServiceBit = [];
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
            handleResize: function() {
                this.window.width = window.innerWidth;
                this.window.height = window.innerHeight;
            },
            nextView: function() { 
                if (this.viewarea == "service") {
                    this.viewarea = "schedule";

                    for (var i = 0; i < this.dates.length; i++) { 
                        if (this.dates[i].checked) {
                            console.log('nextView-checked',this.dates[i].date);

                            this.order.date = this.dates[i].date;
                            this.hooperDatesSettings.initialSlide = i;
                        }
                    }

                    for (var j = 0; j < this.times.length; j++) {
                        if (this.times[j].checked) {
                            console.log('nextView-time',this.times[j].time);
                            this.order.time = this.times[j].time
                            this.addTime(this.times[j]);
                            this.hooperTimesSettings.initialSlide = j;
                        }
                    }

                } else if (this.viewarea == "schedule") {
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
            prevView: function() {
                if (this.viewarea == "schedule") {
                    this.viewarea = "service";
                } else if (this.viewarea == "confirmation") {
                    this.viewarea = "schedule";
                }
            },
            async checkOrderFor()
            {
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
            async placeOrder()
            {
                this.order.userId = this.auth_user.id;
                if (this.order.orderFor == 'self') {
                    this.order.name = this.auth_user.name;
                    this.order.phone = this.auth_user.phone;
                    this.order.email = this.auth_user.email;
                    this.order.area = this.auth_user.area ? this.auth_user.area.id : '';
                    this.order.address = this.auth_user.address;
                }
                if (this.order.orderFor == "others") {
                    this.order.name = this.guestFormData.name;
                    this.order.phone = this.guestFormData.phone;
                    this.order.area = this.guestFormData.area;
                    this.order.address = this.guestFormData.address;
                    this.order.comments = this.guestFormData.comments;
                }
                this.order.orderFrom = 'client';

                this.orderDone = true;
                this.orderPlacingStatus = true;
                this.display_errors = [];
                try {
                    let response = await axios.post("/order", {
                        category_id: this.order.categoryId,
                        user_id: this.order.userId,
                        date: this.order.date,
                        time: this.order.time,
                        services: this.order.services,
                        service_bit: this.order.serviceBit,
                        name: this.order.name,
                        phone: this.order.phone,
                        area: this.order.area,
                        address: this.order.address,
                        comments: this.order.comments,
                        order_from: this.order.orderFrom,
                        ref_code: this.refCode,
                        order_for: this.order.orderFor,
                        schedule_charge: this.schedule_charge,
                        area_charge: this.area_charge,
                        promocode: this.order.promocode,
                    });

                    this.alertMessage = response.data.message;
                    this.snackbar = true;
                    
                    this.is_schedule_charge = false;
                    this.is_area_charge = false;
                    this.schedule_charge = 0,
                    this.area_charge = 0,
                    this.discount = 0,

                    this.order = {
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
                    };
                    this.selectedServiceBit = [];
                    this.serviceBits = [];
                    setTimeout(() => this.$router.replace(this.$route.query.redirect || "/user"), 10000);
                    
                } catch (error) {
                    if(error.response.data.errors){
                        this.display_errors = error.response.data.errors;
                    }
                    if(error.response.data.message)
                    {
                        this.alertMessage = error.response.data.message;
                    }
                    this.snackbar = true;
                    this.snackbarColor = 'error';
                }
                this.orderDone = false;
                this.orderPlacingStatus = false;
            },
            async checkOutOfAreaCharge(id)
            {
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
                    this.is_schedule_charge = false;
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
            extraChargeTotal(){
                return ( parseInt(this.schedule_charge) + parseInt(this.area_charge) );
            },
            async checkAuthentication() {
                if(this.auth_user.name.length != 0)
                {
                    if(this.order.orderFor == 'others')
                    {
                        this.guestPrevent();
                    }
                    else
                    {
                        this.placeOrder();
                    }
                }
                else
                {
                    this.$router.replace("/");
                }
            },
            async guestPrevent() {
                if (this.$refs.guestForm.validate()) {
                    this.placeOrder();
                }
            },
            totalPrice(servicesBits) {
                function indvidualTotal(item) {
                    var total =
                        item.qty > 1 ? (item.qty - 1) * parseInt(item.additional_price) +
                        parseInt(item.price) : parseInt(item.price);
                    return parseInt(total);
                }

                var tp = servicesBits.map(indvidualTotal).reduce((f, n) => n + f, 0);

                return tp;
            },
            totalQty(servicesBits) {
                if (servicesBits) {
                    function indvidualTotal(item) {
                        var total = item.qty;
                        return parseInt(total);
                    }
                    var tp = servicesBits.map(indvidualTotal).reduce((f, n) => n + f, 0);
                    return tp;
                } else {
                    return 0;
                }
            },
            async openPromoCodeDialog() {
                this.alert_promocode_msg = null;
                this.alert_promocode = false;
                try {
                    var response = await axios.get('/user-available-promo-codes');
                    this.promocodes = response.data;
                    this.dialogPromoCode = true;
                    this.promoCode = null;
                } catch (error) {
                }
            },
            closePromoCodeDialog() {
                this.promoCode = null;
                this.dialogPromoCode = false;
                this.alert_promocode_msg = null;
                this.alert_promocode = false;
            },
            onApply(code){
                this.promoCode = code;
            },
            async applyPromoCode(){
                this.alert_promocode = false;
                this.alert_promocode_msg = null;
                this.display_errors = [];
                try {
                    var response = await axios.post('/user-apply-promo-codes', {
                        promo_code: this.promoCode,
                        phone: this.auth_user.phone,
                        amount: this.totalPrice(this.selectedServiceBit)
                    });
                    this.dialogPromoCode = false;
                    this.order.promocode = this.promoCode;
                    this.discount = response.data.discount;
                    this.alertMessage = "You are got " + response.data.discount +" BDT discount for using your promo code.";
                    this.snackbar = true;
                } catch (error) {
                    if(error.response.data.errors)
                    {
                        this.display_errors = error.response.data.errors;
                    }
                    this.alert_promocode_msg = error.response.data.message;
                    this.alert_promocode = true;
                }
            }
        }
    };
</script>

<style scoped>
.v-list--three-line .v-list__tile__sub-title{
    -webkit-line-clamp: inherit;
}
</style>