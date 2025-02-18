<template>
    <v-container fluid style="" v-if="categorys">
        <v-alert class="blinking" type="warning" :value="is_schedule_charge" v-html="schedule_charge_msg"></v-alert>
        <v-alert class="blinking" type="warning" :value="is_area_charge" v-html="area_charge_msg"></v-alert>
        <div class="service-area" v-if="viewarea == 'service'">
            <v-card elevation="0">
                <v-layout wrap style="text-align: center">
                    <v-flex md2 sm4 xs6 v-for="category in categorys" :key="category.id" :to="'/ownservice/'+category.slug">
                        <div @click="ifChangeServiceCategory(category.slug)" style="display:block; cursor: pointer;" :to="{ path: '/ownservice/' + category.slug }">
                            <img style="width:80px" :src="category.thumb" :alt="category.slug" class="" />
                            <p class="text-center">
                            <span v-if="$i18n.locale=='en'">{{category.name}}</span>
                            <span v-else>{{category.name_bn}}</span> 
                            </p>
                        </div>
                    </v-flex>
                </v-layout>
                <v-layout style="height:7px">
                    <v-progress-linear class="m-0" v-if="!services.length" v-show="isProgressLoading" :indeterminate="true"></v-progress-linear>
                </v-layout>
                <v-divider></v-divider>
                <v-card-actions v-if="selectedServiceBit.length != 0">
                    <v-list-tile-content>
                        <v-list-tile-title class="font-weight-bold subheading" style="font-size:20">Total Price: <span style="color: #008000">৳ {{ ((totalPrice(selectedServiceBit) + extraChargeTotal())) }}</span><span style="color: #008000"> Total Service Qty: {{ totalQty(selectedServiceBit) }}</span></v-list-tile-title>
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
                                    <p class="text-center subheading font-weight-bold" style="margin:0">
                                    <span v-if="$i18n.locale=='en'">{{service.name}}</span>
                                    <span v-else>{{service.name_bn}}</span></p>
                                    <p class="text-center caption" style="margin:0" v-if="$i18n.locale=='en'">
                                     
                                    Under {{ service.serviceBits.length }} Service bits</p>
                                    <p class="text-center caption" style="margin:0" v-else>
                                    {{ $tc('ownservice',0) }} {{ e2btransform((service.serviceBits.length).toString()) }} {{ $tc('ownservice',1) }} </p>
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
                                                <v-list-tile-sub-title>
                                                <span v-if="$i18n.locale=='en'">{{serviceBit.name}}</span>
                                                <span v-else>{{serviceBit.name_bn}}</span> 
                                                </v-list-tile-sub-title>
                                                <v-list-tile-sub-title>{{ $tc('ownservice1',0) }}: ৳ <del>
                                                    
                                                    <span v-if="$i18n.locale=='en'">{{ serviceBit.mrp_price }}</span>
                                                    <span v-else>{{ e2btransform(serviceBit.mrp_price) }}</span>

                                                
                                                </del></v-list-tile-sub-title>
                                                <v-list-tile-sub-title>
                                                    <span style="font-size: 16px; color: #febe00;" v-if="$i18n.locale=='en'">{{ $tc('ownservice1',1) }}: ৳ {{ (checkSelectedBit(serviceBit.id)) ? bitPriceTotal(serviceBit.id) : serviceBit.price }}</span>

                                                    <span v-else style="font-size: 16px; color: #febe00;">{{ $tc('ownservice1',1) }}: ৳ {{ (checkSelectedBit(serviceBit.id)) ? bitPriceTotal(serviceBit.id) : e2btransform(serviceBit.price) }}</span>
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
                        <v-list-tile-title class="font-weight-bold subheading" style="font-size:20">Total Price:  <span style="color: #008000">৳ {{ ((totalPrice(selectedServiceBit) + extraChargeTotal())) }} Total Service Qty: {{ totalQty(selectedServiceBit) }}</span></v-list-tile-title>
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
                        <v-list-tile-title class="font-weight-bold subheading">Total Price: ৳ {{ ((totalPrice(selectedServiceBit) + extraChargeTotal())) }} Total Service Qty: {{ totalQty(selectedServiceBit) }}</v-list-tile-title>
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
                    <h5> {{ $tc('ownservice2',0) }} </h5>
                </v-card-title>
                <v-divider></v-divider>
                <v-layout wrap>
                    <v-flex xs12 sm12 md6>
                        <v-card-title>
                            <h5> {{ $tc('ownservice2',1) }} </h5>
                            <v-spacer></v-spacer>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-form ref="guestForm" v-model="valid" @submit.prevent="guestPrevent">
                            <v-card-text>
                                <v-text-field color="accent" v-model="guestFormData.name" :error-messages="display_errors.name" v-bind:label="$tc('ownservice_from',0)"></v-text-field>
                                <v-text-field color="accent" v-model="guestFormData.phone" :error-messages="display_errors.phone" prefix="+88" v-bind:label="$tc('ownservice_from',1)"></v-text-field>
                                <v-select color="accent" v-model="guestFormData.area" @change="checkOutOfAreaCharge" :error-messages="display_errors.area" :items="cluster" :item-text="'name'" :item-value="'id'" v-bind:label="$tc('ownservice_from',2)"></v-select>
                                <v-textarea color="accent" v-model="guestFormData.address" :error-messages="display_errors.address" v-bind:label="$tc('ownservice_from1',0)" v-bind:placeholder="$tc('ownservice_from1',1)"></v-textarea>
                                <v-text-field color="accent" v-model="guestFormData.comments" v-bind:label="$tc('ownservice_from1',2)"></v-text-field>
                            </v-card-text>
                        </v-form>
                    </v-flex>
                    <v-flex xs12 sm12 md6>
                        <v-card-title>
                            <h5> {{ $tc('ownservice2',2) }} </h5>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-layout row wrap v-for="(service,i) in order.services" :key="i">
                                <v-flex xs12 sm12 md12>
                                    <strong>
                                        <span v-if="$i18n.locale=='en'">{{service[0]}}</span>
                                        <span v-else>{{service[1][0].service_name_bn}}</span> 
                                    </strong>

                                          
                                </v-flex>
                                <v-flex xs12 sm12 md12 v-for="(bit,i) in service[1]" :key="i">
                                    <v-layout row wrap class="pl-3">
                                        <v-flex xs6 sm6 md6>
                                            <span v-if="$i18n.locale=='en'">{{bit.name}} </span>
                                            <span v-else>{{bit.name_bn}}</span>   
                                        </v-flex>
                                        <v-flex xs3 sm3 md3>
                                            <span style="padding:0 10px" v-if="$i18n.locale=='en'">{{ bit.qty }} qty</span>
                                            <span style="padding:0 10px" v-else>{{ e2btransform(bit.qty.toString()) }} {{ $tc('qty',0) }} </span>
                                        </v-flex>
                                        <v-flex xs3 sm3 md3 class="text-right">
                                            <span style="padding:0 10px" v-if="$i18n.locale=='en'">৳ {{bit.qty > 1 ? (bit.qty - 1) * parseInt(bit.additional_price) + parseInt(bit.price) : parseInt(bit.price)}}</span>

                                            <span style="padding:0 10px" v-else>৳ 
                                                {{ e2btransform( (bit.qty > 1 ? (bit.qty - 1) * parseInt(bit.additional_price) + parseInt(bit.price)  : 
                                                parseInt(bit.price) ).toString())}}  
                                            </span>
                                        </v-flex>
                                    </v-layout>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap v-if="schedule_charge != 0 || area_charge != 0">
                                <v-flex xs12 sm12 md12>
                                    <strong> {{ $tc('ownservice4',0) }} </strong>
                                </v-flex>
                                <v-flex>
                                    <v-layout row wrap class="pl-3" v-if="schedule_charge != 0">
                                        <v-flex xs9 sm9 md9>  {{ $tc('ownservice4',1) }} </v-flex>
                                        <v-flex xs3 sm3 md3 class="text-right"><span style="padding:0 10px">৳ {{ schedule_charge }}</span></v-flex>
                                    </v-layout>
                                    <v-layout row wrap class="pl-3" v-if="area_charge != 0">
                                        <v-flex xs9 sm9 md9>For Outside City charge</v-flex>
                                        <v-flex xs3 sm3 md3 class="text-right"><span style="padding:0 10px">৳ {{ area_charge }}</span></v-flex>
                                    </v-layout>
                                </v-flex>
                            </v-layout>
                                
                            <v-layout row wrap v-if="order.reduce_type != null" style="display:none">
                                <v-flex xs12 sm12 md12>
                                    <strong>Customize Service Charge</strong>
                                </v-flex>
                                <v-flex>
                                    <v-layout row wrap class="pl-3">
                                        <v-flex xs9 sm9 md9>{{ order.reduce_type }}</v-flex>
                                        <v-flex xs3 sm3 md3 class="text-right"><span style="padding:0 10px">৳ {{ order.reduce_amount }}</span></v-flex>
                                    </v-layout>
                                </v-flex>
                            </v-layout>
                            <v-btn class="mt-3" block color="primary" dark @click="customServiceChargeModal = true">Apply Customize Service Charge</v-btn>
                        </v-card-text>
                    </v-flex>
                </v-layout>
                <v-divider></v-divider>
                <v-card-actions v-if="selectedServiceBit.length != 0">
                    <v-list-tile-content>
                        <v-list-tile-title class="font-weight-bold subheading">
                        {{ $tc('ownservice3',0) }}: ৳ 
                        <span v-if="$i18n.locale=='en'">{{ (order.grant_total == null) ? (totalPrice(selectedServiceBit) + extraChargeTotal()) : order.grant_total }}</span>
                        <span v-else>
                        {{ e2btransform(((order.grant_total == null) ? (totalPrice(selectedServiceBit) + extraChargeTotal()) : order.grant_total).toString()) }}
                        </span>  

                        {{ $tc('ownservice3',1) }}: 
                        <span v-if="$i18n.locale=='en'">{{ totalQty(selectedServiceBit) }}</span>
                        <span v-else> {{e2btransform(totalQty(selectedServiceBit).toString())}}</span></v-list-tile-title>
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
        <v-dialog v-if="customServiceChargeModal" v-model="customServiceChargeModal" max-width="480">
            <v-card>
                <v-card-title class="white--text" style="background-color: #febe00;">Customize Service Charge</v-card-title>
                <v-card-text>
                    <v-text-field color="accent" v-model="customServiceCharge" label="Amount" :error-messages="display_errors.service_charge" outlined></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="secondary" @click="customServiceChargeModal = false">Close</v-btn>
                    <v-btn color="primary" @click="customServiceChargePrevent()">Submit</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
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
    import { required, numeric  } from "vuelidate/lib/validators";
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
                user_type : localStorageService.getItem("currentUserData").type ,
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
                    orderFor: "others",
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
                    reduce_type: null,
                    custom_service_charge: 0,
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
                customServiceChargeModal: false,
                customServiceCharge: 0,
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
            ifChangeServiceCategory(category_slug){
                if(this.selectedServiceBit.length != 0)
                {
                    this.dialogIfCategoryChange = true;
                    this.nextRoute = category_slug;
                    return false;
                }
                this.$router.replace("/ownservice/" + category_slug);
            },
            async customServiceChargePrevent() {
                let loader = this.$loading.show();
                let total_price  = 0;
                if(this.selectedServiceBit.length != 0)
                {
                    total_price = this.totalPrice(this.selectedServiceBit) + this.extraChargeTotal();
                }

                // let grant_total = this.orders[runningOrderindex].grant_total;
                let custom_service_charge = this.customServiceCharge;

                if(total_price > custom_service_charge)
                {
                    // Discount Amount
                    this.order['reduce_type'] = "Discount Amount";
                    this.order['reduce_amount'] = (total_price - custom_service_charge);
                    this.order['grant_total'] = custom_service_charge;
                    this.order['custom_service_charge'] = custom_service_charge;
                }
                else if(total_price < custom_service_charge)
                {
                    // Supplementary Service Charge
                    this.order['reduce_type'] = "Supplementary Service Charge";
                    this.order['reduce_amount'] = (custom_service_charge - total_price);
                    this.order['grant_total'] = total_price + (custom_service_charge - total_price);
                    this.order['custom_service_charge'] = custom_service_charge;
                }
                else
                {
                    this.order['reduce_type'] = null;
                    this.order['reduce_amount'] = 0;
                    this.order['grant_total'] = 0;
                    this.order['custom_service_charge'] = 0;
                }
                
                this.customServiceChargeModal = false;
                loader.hide();
            },
            async getLocations()
            {
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
                    //return total;
                    if(this.$i18n.locale=='en'){
                        return total;
                    }else{
                        return this.e2btransform(total.toString());
                    } 
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
                            this.order.date = this.dates[i].date;
                            this.hooperDatesSettings.initialSlide = i;
                        }
                    }

                    for (var j = 0; j < this.times.length; j++) {
                        if (this.times[j].checked) {
                            this.order.time = this.times[j].time
                            this.addTime(this.times[j]);
                            this.hooperTimesSettings.initialSlide = j;
                        }
                    }

                } else if (this.viewarea == "schedule") {
                    this.viewarea = "confirmation";
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
            async placeOrder()
            {
                this.order.name = this.guestFormData.name;
                this.order.phone = this.guestFormData.phone;
                this.order.area = this.guestFormData.area;
                this.order.address = this.guestFormData.address;
                this.order.comments = this.guestFormData.comments;
                this.order.orderFrom = this.user_type ;

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
                        custom_service_charge: this.order.custom_service_charge,
                    });

                    this.alertMessage = response.data.message;
                    this.snackbar = true;
                    
                    this.is_schedule_charge = false;
                    this.is_area_charge = false;
                    this.schedule_charge = 0,
                    this.area_charge = 0,

                    this.order = {
                        category: null,
                        categoryId: null,
                        services: [],
                        serviceBit: [],
                        date: null,
                        time: null,
                        orderFor: "others",
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
                        custom_service_charge: 0,
                    };
                    this.selectedServiceBit = [];
                    this.serviceBits = [];
                    this.$router.replace(this.$route.query.redirect || "/shokolkaaj");
                    
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
                    this.guestPrevent();
                }
                else
                {
                    this.$router.replace("/");
                }
            },
            async guestPrevent() {
                let loader = this.$loading.show();
                this.display_errors = [];
                this.placeOrder();
                // if (this.$refs.guestForm.validate()) {
                //     try {
                //         let response = await axios.post("/guest-register", {
                //             name: this.guestFormData.name,
                //             phone: this.guestFormData.phone,
                //             area: this.guestFormData.area,
                //             address: this.guestFormData.address,
                //         });
                //         this.order.userId = response.data.id;
                //         this.placeOrder();
                //     } catch (error) {
                //         if(error.response.data.errors)
                //         {
                //             this.display_errors = error.response.data.errors;
                //         }
                //         this.alertMessage = error.response.data.message;
                //         this.snackbar = true;
                //     }
                // }
                loader.hide();
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
            e2btransform: function(input) {
                var numbers = {0: '০', 1: '১', 2: '২', 3: '৩', 4: '৪', 5: '৫', 6: '৬', 7: '৭', 8: '৮', 9:'৯' }; 
                var output = [];
                for (var i = 0; i < input.length; ++i) {
                    if (numbers.hasOwnProperty(input[i])) {
                      output.push(numbers[input[i]]);
                    } else {
                      output.push(input[i]);
                    }
                }
                return output.join('');
            }, 
        }
    };
</script>

<style scoped>
.v-list--three-line .v-list__tile__sub-title{
    -webkit-line-clamp: inherit;
}
</style>