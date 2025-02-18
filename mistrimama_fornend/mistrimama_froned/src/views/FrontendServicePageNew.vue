<template>
    <div class="order-page-fixed-background" style="padding-top: 104px;">
        <v-container style="padding-top:30px; padding-bottom:30px; max-width: 900px;">
            <v-stepper alt-labels style="box-shadow:none" :value="viewarea == 'service' ? 1 : viewarea == 'schedule' ? 2 : viewarea == 'confirmation' ? 3 : ''">
                <v-stepper-header class="pt-4 pb-2">
                    <v-stepper-step step="1" :complete="viewarea == 'schedule' || viewarea == 'confirmation'"> {{ $tc('stepper',0) }}</v-stepper-step>
                    <v-divider></v-divider>
                    <v-stepper-step step="2" :complete="viewarea == 'schedule' || viewarea == 'confirmation'">{{ $tc('stepper',1) }}</v-stepper-step>
                    <v-divider></v-divider>
                    <v-stepper-step step="3" :complete="viewarea == 'as'">{{ $tc('stepper',2) }}</v-stepper-step>
                </v-stepper-header>
            </v-stepper>
            <v-layout style="height:7px">
                <v-progress-linear class="m-0" v-if="!services.length" v-show="isProgressLoading" :indeterminate="true"></v-progress-linear>
            </v-layout>
            <v-alert class="blinking" type="warning" :value="is_schedule_charge" v-html="schedule_charge_msg"></v-alert>
            <v-alert class="blinking" type="warning" :value="is_area_charge" v-html="area_charge_msg"></v-alert>
            <div class="service-area" v-if="viewarea == 'service'">
                <v-layout wrap v-if="categorys">
                    <v-flex v-for="category in categorys" :key="category.id" xs6 sm6 md2>
                        <v-card class="" :style="($route.params.category == category.slug) ? 'background-color: #333' : ''" elevation-1>
                            <div @click="ifChangeServiceCategory(category.slug)" style="margin: 2px; padding:15px; display:block; text-decoration: none; cursor: pointer;" class="text-center">
                                <span :style="($route.params.category == category.slug) ? 'color: #fff' : 'color: #333'" v-if="$i18n.locale=='en'">{{category.name}}</span>
                                <span :style="($route.params.category == category.slug) ? 'color: #fff' : 'color: #333'" v-else>{{category.name_bn}}</span>
                            </div>
                        </v-card>
                    </v-flex>
                </v-layout>
                <v-layout wrap v-if="services.length != 0" style="height:250px; overflow-y:scroll">
                    <v-list v-if="services" style="padding:0; width:100%;">
                        <v-list-tile class="services-item-list" :class="(getServiceBit(service.id).length > 0) ? 'active' : ''" avatar v-for="service in services" :key="service.id" @click="loadServiceBit(service.id)">
                            <v-list-tile-avatar tile>
                                <img :src="service.thumb" alt="MM">
                            </v-list-tile-avatar>
                            <v-list-tile-content style="justify-content: space-around;">
                                <v-list-tile-title>
                                    <span v-if="$i18n.locale=='en'">{{ service.name }}</span>
                                    <span v-else>{{ service.name_bn }}</span></v-list-tile-title>
                                <ul>
                                    <li v-for="bit in getServiceBit(service.id)" :key="bit.id">
                                        <p v-if="$i18n.locale=='en'" class="caption white--text" style="margin:0;">{{bit.name}} : {{bit.qty}} {{bit.unit_type}}</p>
                                        <p v-else class="caption white--text" style="margin:0;">{{bit.name_bn}} : {{bit.qty}} {{bit.unit_type}}</p>
                                    </li>
                                </ul>
                            </v-list-tile-content>
                            <v-list-tile-action>
                                <v-list-tile-action-text class="caption" v-if="(totalPrice(getServiceBit(service.id)) > 0)">
                                {{ $tc('price',0) }}
                                </v-list-tile-action-text>
                                <v-list-tile-action-text class="caption" v-if="(totalPrice(getServiceBit(service.id)) == 0)">
                                {{ $tc('price',1) }}
                                </v-list-tile-action-text>

                                <v-list-tile-action-text class="caption" v-if="(totalPrice(getServiceBit(service.id)) > 0)">
                                   ৳ <span v-if="$i18n.locale=='en'"> 
                                        {{ totalPrice(getServiceBit(service.id)).toString() }}
                                    </span>
                                    <span v-else> 
                                        {{ e2btransform(totalPrice(getServiceBit(service.id)).toString()) }}
                                    </span>
                                 
                                </v-list-tile-action-text>


                                <v-list-tile-action-text class="caption" v-if="(totalPrice(getServiceBit(service.id)) == 0)">
                                    <span v-if="((parseInt(service.minimum_mrp_price) - parseInt(service.minimum_price)) > 0)" class="minimum_mrp_price">
                                        <span v-if="$i18n.locale=='en'"> 
                                            <del>৳ {{ service.minimum_mrp_price }}</del>  
                                            &nbsp; ৳ {{service.minimum_price}} &nbsp;
                                            <span class="discount_percentage">{{service.discount_percentage}} 
                                            {{ $tc('price',3) }}
                                        </span>                                            
                                        </span>
                                        <span v-else>
                                            <del>৳ {{ e2btransform(service.minimum_mrp_price) }}</del>  
                                            &nbsp; ৳ {{ e2btransform(service.minimum_price)}} &nbsp;
                                            <span class="discount_percentage">
                                            {{ e2btransform(service.discount_percentage.toString()) }} 
                                            {{ $tc('price',3) }}
                                            </span>
                                        </span>
                                    </span>
                                    <span v-else class="minimum_price">৳ 
                                        <span v-if="$i18n.locale=='en'"> {{ service.minimum_price }} </span>
                                        <span v-else> {{ e2btransform(service.minimum_price) }} </span> 
                                    </span>
                                </v-list-tile-action-text> 

                            </v-list-tile-action>
                        </v-list-tile>
                    </v-list>
                    <v-dialog v-model="serviceBitsDialog" scrollable max-width="900px" v-show="serviceBitsDialog" style="z-index:9999">
                        <v-card>
                            <v-card-text style="max-height: 300px; padding:0">
                                <v-list three-line v-for="serviceBit in serviceBits" :key="serviceBit.id" style="border-bottom: 1px solid rgba(0,0,0,0.12);">
                                    <v-list-tile style="height:auto">
                                        <v-list-tile-content>
                                            <v-checkbox v-model="selectedServiceBit" :value="serviceBit" style="margin-top:0">
                                                <template v-slot:label>
                                                    <v-list-tile-content style="padding: 5px 0">
                                                        <v-list-tile-sub-title>
                                                            <span v-if="$i18n.locale=='en'">{{serviceBit.name}}</span>
                                                            <span v-else>{{serviceBit.name_bn}}</span>
                                                        </v-list-tile-sub-title>
                                                        <v-list-tile-sub-title>
                                                            <span v-if="$i18n.locale=='en'">Price: <del>৳ {{serviceBit.mrp_price}}</del></span>
                                                            <span v-else>মূল্য: <del>৳ {{ e2btransform(serviceBit.mrp_price) }}</del></span> 
                                                        </v-list-tile-sub-title>
                                                        <v-list-tile-sub-title>
                                                            <span v-if="$i18n.locale=='en'" style="font-size: 16px; color: #febe00;">
                                                            Discounted Price: ৳ {{ (checkSelectedBit(serviceBit.id)) ? bitPriceTotal(serviceBit.id) : parseInt(serviceBit.price) }}
                                                            </span>
                                                            <span v-else style="font-size: 16px; color: #febe00;">
                                                            মূল্য ছাড়: ৳ {{ (checkSelectedBit(serviceBit.id)) ? bitPriceTotal(serviceBit.id) : e2btransform(serviceBit.price.toString()) }}
                                                            </span>
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
                                        <p style="margin:0">
                                            <strong>Price : </strong>
                                            {{serviceBit.price}}
                                        </p>
                                        <div v-html="serviceBit.brief"></div>
                                    </div>
                                </v-list>
                            </v-card-text>
                            <v-divider></v-divider>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="secondary" @click.stop="serviceBitsDialog = !serviceBitsDialog">{{ $tc('btn',0) }}</v-btn>
                                <v-btn color="primary" @click.stop="serviceBitsDialog = !serviceBitsDialog">{{ $tc('btn',1) }}</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-layout>
                <v-layout wrap v-else>
                    <v-flex>
                        <v-card>
                            <v-card-title v-show="!isProgressLoading" class="headline font-weight-bold">No Service Found</v-card-title>
                        </v-card>
                    </v-flex>
                </v-layout>
                <v-layout wrap style="margin-top:7px;">
                    <v-flex>
                        <v-card>
                            <v-card-actions>
                                <v-list-tile-content>
                                    <v-list-tile-title class="font-weight-bold" style="font-size: 14px;">
                                        <span v-if="$i18n.locale=='en'">
                                        <span class="">{{ $tc('stepper_footer',0) }}: <span style="color: #ff8a00">৳ 
                                        {{  ((totalPrice(selectedServiceBit) + extraChargeTotal()) - discount) }}&nbsp;</span></span>

                                        <span class="">&nbsp; {{ $tc('stepper_footer',1) }}: <span style="color: #ff8a00">
                                        {{ totalQty(selectedServiceBit) }}</span></span>
                                        </span>
                                        <span v-else>

                                        <span class="">{{ $tc('stepper_footer',0) }}: <span style="color: #ff8a00">৳ 
                                        {{ e2btransform( ((totalPrice(selectedServiceBit) + extraChargeTotal()) - discount).toString()) }}&nbsp;</span></span>
                                        <span class="">&nbsp; {{ $tc('stepper_footer',1) }}: <span style="color: #ff8a00">
                                        {{ e2btransform(totalQty(selectedServiceBit).toString()) }}</span></span>
                                        </span>


                                     </v-list-tile-title>
                                </v-list-tile-content>
                            </v-card-actions>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="secondary" to="/">{{ $tc('stepper_footer',2) }}</v-btn>
                                <v-btn color="primary" v-if="selectedServiceBit.length != 0"  @click="nextView">{{ $tc('order_summary',2) }}</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </div>
            
            <div class="date-area" v-if="viewarea == 'schedule'">
                <v-layout row wrap style="background-color: #fff">
                    <v-flex xs12 sm6 md6>
                        <hooper style="height:auto" :settings="hooperDatesSettings">
                            <slide v-for="(value,i) in dates" :key="i">
                                <div class="p-2">
                                    <v-btn class="m-0" block large :color="order.date == value.date ? 'primary' : 'dark'" @click="addDate(value.date)">{{value.date}}<br>{{value.day}}</v-btn>
                                </div>
                            </slide>
                            <hooper-navigation class="hooper-navigation-frontend" slot="hooper-addons"></hooper-navigation>
                        </hooper>
                    </v-flex>
                    <v-flex xs12 sm6 md6>
                        <hooper style="height:auto" :settings="hooperTimesSettings">
                            <slide v-for="(value,i) in times" :key="i">
                                <div class="p-2">
                                    <v-btn class="m-0" block large :color="order.time == value.time ? 'primary' : 'dark'" @click="addTime(value)">{{value.time}}</v-btn>
                                </div>
                            </slide>
                            <hooper-navigation class="hooper-navigation-frontend" slot="hooper-addons"></hooper-navigation>
                        </hooper>
                    </v-flex>
                </v-layout>
                <v-layout wrap style="margin-top:7px;">
                    <v-flex v-if="selectedServiceBit.length != 0">
                        <v-card>
                            <v-card-actions>
                                <v-list-tile-content>
                                    <v-list-tile-title class="font-weight-bold subheading" style="font-size:20">
                                    {{ $tc('stepper_footer',0) }}: <span style="color: #ff8a00">৳ 
                                    <span v-if="$i18n.locale=='en'">
                                    {{ ((totalPrice(selectedServiceBit) + extraChargeTotal()) - discount) }}
                                    </span>
                                    <span v-else>
                                        {{ e2btransform( ((totalPrice(selectedServiceBit) + extraChargeTotal()) - discount).toString()) }}
                                    </span>
                                    </span> {{ $tc('stepper_footer',1) }}: <span style="color: #ff8a00">
                                        <span v-if="$i18n.locale=='en'">
                                            {{ totalQty(selectedServiceBit) }}
                                        </span>
                                        <span v-else>
                                            {{ e2btransform(totalQty(selectedServiceBit).toString()) }}
                                        </span> 
                                    </span></v-list-tile-title>
                                </v-list-tile-content>
                                <v-layout align-center justify-end>
                                    <v-btn color="secondary" @click="prevView">{{ $tc('order_summary',1) }}</v-btn>
                                    <v-btn color="primary" v-if="order.date && order.time" @click="nextView">{{ $tc('btn',2) }}</v-btn>
                                </v-layout>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </div>
            <div class="confirmation-area" v-if="viewarea == 'confirmation'">
                <v-layout row wrap fill-height style="background-color: #fff">
                    <v-flex xs12 sm12 md6 style="border-right: 1px solid #ddd">
                        <v-card class="elevation-0">
                            <v-tabs grow :value="currentTab" @change="onTabChange">
                                <v-tab ripple>Login</v-tab>
                                <v-tab ripple v-if="(this.isLoggedIn == false)">Sign Up</v-tab>
                                <v-tab ripple>Guest</v-tab>
                                <v-tab-item>
                                    <v-card-title>
                                        <h5 class="text-center">Hello there!</h5>
                                    </v-card-title>
                                    <v-divider class="m-0"></v-divider>
                                    <v-card-text>
                                        <v-form ref="loginForm" v-model="valid" lazy-validation @submit.prevent="loginPrevent" v-if="isLoggedIn == false">
                                            <v-text-field color="accent" v-model="loginFormData.phone" :error-messages="display_errors.phone" type="text" label="Phone Number *" append-icon="call" outlined></v-text-field>
                                            <v-text-field color="accent" v-model="loginFormData.password" :error-messages="display_errors.password"  :append-icon="passShow ? 'visibility' : 'visibility_off'" :type="passShow ? 'text' : 'password'" @click:append="passShow = !passShow" label="Password *" outlined></v-text-field>
                                            <v-checkbox v-model="rememberMe" label="Remember Me"></v-checkbox>
                                            <v-btn color="primary" type="submit">Login</v-btn>
                                        </v-form>
                                        <p v-if="isLoggedIn == true"><strong>Name:</strong> {{auth_user.name}}</p>
                                        <p v-if="isLoggedIn == true"><strong>Phone:</strong> {{auth_user.phone}}</p>
                                        <p v-if="isLoggedIn == true"><strong>Area:</strong> {{auth_user.area.name}}</p>
                                        <p v-if="isLoggedIn == true"><strong>Address:</strong> {{auth_user.address}}</p>
                                    </v-card-text>
                                </v-tab-item>
                                <v-tab-item v-if="(this.isLoggedIn == false)">
                                    <v-card-title>
                                        <h5 class="text-center">Signup now to track your order</h5>
                                    </v-card-title>
                                    <v-divider class="m-0"></v-divider>
                                    <v-card-text v-if="this.otpVerified == false">
                                        <v-form v-if="otp == false" ref="signupForm" v-model="valid" lazy-validation @submit.prevent="signupPrevent">
                                            <v-text-field color="accent" v-model="signupFormData.name" :error-messages="display_errors.name" type="text" label="Name *" append-icon="person" outlined></v-text-field>
                                            <v-text-field color="accent" v-model="signupFormData.phone" prefix="+88" :error-messages="display_errors.phone" type="text" label="Phone Number *" append-icon="call" outlined></v-text-field>
                                            <v-text-field color="accent" v-model="signupFormData.email" :error-messages="display_errors.email" type="text" label="Email" append-icon="email" outlined></v-text-field>
                                            <v-select color="accent" v-model="signupFormData.area" @change="checkOutOfAreaCharge" :error-messages="display_errors.area" :items="cluster" :item-text="'name'" :item-value="'id'" label="Area *" append-icon="map" outlined></v-select>
                                            <v-textarea color="accent" v-model="signupFormData.address" :error-messages="display_errors.address" label="Address *" placeholder="Write down your address"></v-textarea>
                                            <v-text-field color="accent" v-model="signupFormData.password" :error-messages="display_errors.password"  :append-icon="passShow ? 'visibility' : 'visibility_off'" :type="passShow ? 'text' : 'password'" @click:append="passShow = !passShow" label="Password *" outlined></v-text-field>
                                            <v-text-field color="accent" v-model="signupFormData.confirmPassword"  :append-icon="passShow ? 'visibility' : 'visibility_off'" :type="passShow ? 'text' : 'password'" @click:append="passShow = !passShow" label="Confirm Password" outlined></v-text-field>
                                            <v-btn color="primary" type="submit">Signup</v-btn>
                                        </v-form>
                                        <div v-if="otp == true">
                                            <p color="info">A code has been sent to your phone number. Please enter the code below.</p>
                                            <v-text-field color="accent" v-model="otpCode" type="text" label="Enter Code *" append-icon="mobile_screen_share" outlined></v-text-field>
                                            <v-btn block @click="varifyOtp()" color="primary">DONE</v-btn>
                                        </div>
                                    </v-card-text>
                                    <v-card-text v-if="this.otpVerified">
                                        <p color="info">OTP code is successfully verified. Please comfirmed your order.</p>
                                    </v-card-text>
                                </v-tab-item>
                                <v-tab-item>
                                    <v-card-title>
                                        <h5 class="text-center">Without resgitration you will miss many amazing things</h5>
                                    </v-card-title>
                                    <v-divider class="m-0"></v-divider>
                                    <v-card-text>
                                        <v-form ref="guestForm" v-model="valid" lazy-validation @submit.prevent="guestPrevent">
                                            <v-text-field color="accent" v-model="guestFormData.name" label="Name"></v-text-field>
                                            <v-text-field color="accent" v-model="guestFormData.phone" prefix="+88" :error-messages="display_errors.phone" label="Phone Number *"></v-text-field>
                                            <v-select color="accent" v-model="guestFormData.area" @change="checkOutOfAreaCharge" :error-messages="display_errors.area" :items="cluster" :item-text="'name'" :item-value="'id'" label="Area *" append-icon="map" outlined></v-select>
                                            <v-textarea color="accent" v-model="guestFormData.address" :error-messages="display_errors.address" label="Address" placeholder="Write down your address"></v-textarea>
                                            <v-text-field color="accent" v-model="guestFormData.comments" label="Comments"></v-text-field>
                                        </v-form>
                                    </v-card-text>
                                </v-tab-item>
                            </v-tabs>
                        </v-card>
                    </v-flex>
                    <v-flex xs12 sm12 md6>
                        <v-card class="elevation-0">
                            <v-card-title>
                                <h4 class="text-center">{{ $tc('order_summary',0) }} </h4>
                            </v-card-title>
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
                                                <span v-if="$i18n.locale=='en'">{{ bit.name }}</span>
                                                <span v-else>{{ bit.name_bn }}</span>                                                
                                            </v-flex>
                                            <v-flex xs3 sm3 md3>
                                                <span style="padding:0 10px" v-if="$i18n.locale=='en'">

                                                {{ bit.qty }} {{ $tc('qty',0) }}

                                                </span>
                                                <span style="padding:0 10px" v-else>

                                                {{ e2btransform(bit.qty.toString() ) }} {{ $tc('qty',0) }}

                                                </span>
                                            </v-flex>
                                            <v-flex xs3 sm3 md3 class="text-right">
                                                <span style="padding:0 10px" v-if="$i18n.locale=='en'">৳ 
                                                {{ bit.qty > 1 ? (bit.qty - 1) * parseInt(bit.additional_price) + parseInt(bit.price) : 
                                                parseInt(bit.price)}} 
                                                </span>
                                                <span style="padding:0 10px" v-else>৳ 

                                                {{ e2btransform( (bit.qty > 1 ? (bit.qty - 1) * parseInt(bit.additional_price) + parseInt(bit.price)  : 
                                                parseInt(bit.price) ).toString())}} 

                                                </span>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                </v-layout>
                                
                                <v-layout row wrap v-if="schedule_charge != 0 || area_charge != 0 || discount != 0">
                                    <v-flex xs12 sm12 md12>
                                        <strong>{{ $tc('extra_charge',0) }} </strong>
                                    </v-flex>
                                    <v-flex>
                                        <v-layout row wrap class="pl-3 pt-1" v-if="schedule_charge != 0">
                                            <v-flex xs9 sm9 md9>{{ $tc('extra_charge',1) }} </v-flex>
                                            <v-flex xs3 sm3 md3 class="text-right">
                                                <span style="padding:0 10px" v-if="$i18n.locale=='en'">৳ {{ schedule_charge }}</span>
                                                <span style="padding:0 10px" v-else>৳ {{ e2btransform(schedule_charge) }}</span>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout row wrap class="pl-3 pt-1" v-if="area_charge != 0">
                                            <v-flex xs9 sm9 md9>{{ $tc('extra_charge',2) }} </v-flex>
                                            <v-flex xs3 sm3 md3 class="text-right">
                                                <span v-if="$i18n.locale=='en'">{{area_charge}}</span>
                                                <span velse style="padding:0 10px">৳ {{ e2btransform(area_charge) }}</span>
                                            </v-flex>
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
                            </v-card-text>
                            <v-card-text v-if="((userType == 'login') || (userType == 'signup')) && (discount == 0)">
                                <v-card-text v-if="isLoggedIn == true">{{ $tc('use_promo',0) }}</v-card-text>
                                <v-card-text v-if="isLoggedIn == false">Please sign in for using promo code</v-card-text>
                                <v-btn class="mt-3" :disabled="isLoggedIn ? false : true" block color="primary" @click.stop="openPromoCodeDialog">{{ $tc('use_promo',1) }}</v-btn>
                            </v-card-text>
                            <v-card-text v-if="userType == 'guest' && (discount == 0)">
                                <v-card-text>{{ $tc('use_promo',0) }}</v-card-text>
                                <v-btn class="mt-3" :disabled="(guestFormData.phone == '') ? true : false" block color="primary" @click.stop="openPromoCodeDialog">{{ $tc('use_promo',1) }}</v-btn>
                            </v-card-text>
                        </v-card>
                    </v-flex>
                </v-layout>
                <v-dialog v-model="dialogPromoCode" max-width="400">
                    <v-card>
                        <v-alert :value="alert_promocode" type="error" v-html="alert_promocode_msg"></v-alert>
                        <v-card-title>Use your promo code</v-card-title>
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
                <v-card class="elevation-0" color="primary" dark v-if="site_configs.refer ==1">
                    <v-card-actions >
                        <v-card-text>Have you any referral code ? </v-card-text>
                        <v-spacer></v-spacer>
                        <v-text-field color="accent" v-model="refCode" type="text" label="Referral code" prepend-icon="tag" outlined></v-text-field>
                    </v-card-actions>
                </v-card>
                <v-layout wrap style="margin-top:7px;">
                    <v-flex v-if="selectedServiceBit.length != 0">
                        <v-card>
                            <v-card-actions>
                                <v-list-tile-content>
                                    <v-list-tile-title class="font-weight-bold subheading" style="font-size:20">
                                        {{ $tc('stepper_footer',0) }}: 
                                        <span style="color: #ff8a00">৳ 
                                            <span v-if="$i18n.locale=='en'">
                                                {{ ((totalPrice(selectedServiceBit) + extraChargeTotal()) - discount) }}
                                            </span>
                                            <span v-else>
                                            {{ e2btransform( ((totalPrice(selectedServiceBit) + extraChargeTotal()) - discount).toString()) }}
                                            </span>
                                        </span>
                                        {{ $tc('stepper_footer',1) }}: 
                                        <span style="color: #ff8a00">
                                            <span v-if="$i18n.locale=='en'">
                                                {{ totalQty(selectedServiceBit) }}
                                            </span> 
                                            <span v-else>
                                                {{ e2btransform(totalQty(selectedServiceBit).toString()) }}
                                            </span> 
                                        </span>

                                    </v-list-tile-title>
                                </v-list-tile-content>
                                <v-layout align-center justify-end>
                                    <v-btn color="secondary" @click="prevView">{{ $tc('order_summary',1) }}</v-btn>
                                    <v-btn color="primary" @click="nextView">{{ $tc('use_promo',2) }}</v-btn>
                                </v-layout>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
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
        </v-container>
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
    </div>
</template>

<script>
import axios from "../axios_instance.js";
import { localStorageService, Helper } from "../helper.js"; 
import VueCtkDateTimePicker from "vue-ctk-date-time-picker";
import "vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css";
import {
    Hooper,
    Slide,
    Progress as HooperProgress,
    Navigation as HooperNavigation
} from "hooper";
import "hooper/dist/hooper.css";
export default {
    components: {
        Hooper,
        Slide,
        HooperProgress,
        HooperNavigation, 
        VueCtkDateTimePicker
    },
    data() {
        return {
            dialogIfCategoryChange: false,
            nextRoute: null,
            thankyouDialog: false,
            dialogPromoCode: false,
            alert_promocode_msg: null,
            alert_promocode: false,
            page: "",
            pageBanner: "",
            serviceBitsDialog: null,
            breif: null,
            breifActiveId: "",
            selectedServiceBit: [],
            categorys: [],
            category: [],
            services: [],
            selectedServices: [],
            serviceBits: [],
            isProgressLoading: false,
            userType: "login",
            currentTab: 0,
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
            cluster: [],
            site_configs:{
                schedule_charge: 0,
                area_charge: 0,
                office_start_time: null,
                office_end_time: null,
                outside_area_id: [],
                refer: 0
            },
            orderDone: false,
            orderPlacingStatus: false,
            hooperDatesSettings: {
                vertical: true,
                itemsToShow: 6,
                itemsToSlide: 4,
                centerMode: false,
                wheelControl: false,
                infiniteScroll: false,
                initialSlide: 0,
                autoPlay: false,
                playSpeed: 10000,
                transition: 1000,
            },
            hooperTimesSettings: {
                vertical: true,
                itemsToShow: 6,
                itemsToSlide: 4,
                centerMode: false,
                wheelControl: false,
                infiniteScroll: false,
                initialSlide: 0,
                autoPlay: false,
                playSpeed: 10000,
                transition: 1000,
            },
            display_errors: [],
            valid: null,
            otpCode: null,
            otp: false,
            otpVerified: false,
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
                email: null,
                area: null,
                address: null,
                comments: null,
                refCode: null,
                orderFrom: null,
                schedule_charge: null,
                area_charge: null,
                promocode: null,
            },
            isLoggedIn: false,
            auth_user: null,
            refCode: null,
            promoCode: null,
            loginFormData: {
                phone: null,
                password: null,
            },
            signupFormData: {
                name: null,
                phone: null,
                email: null,
                area: null,
                address: null,
                password: null,
                confirmPassword: null,
            },
            guestFormData: {
                name: null,
                phone: null,
                area: null,
                address: null,
                comments: null,
            },
            // nameRules: [
            //     v => !!v || "This field cannot be empty",
            //     v => (v && v.length <= 20) || "Name must be less than 20 characters"
            // ],
            // phoneRules: [
            //     v => !!v || "This field cannot be empty",
            //     v => (v && v.length == 11) || "Invalid mobile number"
            // ],
            // emailRules: [
            //     v => /.+@.+/.test(v) || 'E-mail must be valid'
            // ],
            // requiredRules: [
            //     v => !!v || "This field cannot be empty"
            // ],
            // addressRules: [
            //     v => (v && v.length <= 250) || "Address must be less than 250 characters"
            // ],
            rememberMe: false,
            passShow: null,
            alertMessage: null,
            snackbar: false,
            snackbarColor: 'info',
            tabs: 0,
            datetime: null,
        };
    },
    created() {
        this.getService();
        this.categorysGet();
        this.handleResize();
        this.getSiteConfig();
        this.getLocations();
        this.auth_user = localStorageService.getItem("currentUserData");
        if (localStorage.d_token) {
            this.isLoggedIn = true;
        } else {
            this.isLoggedIn = false;
        };
    },
    computed: {
        checkAfterNowTime: function () {
            let now = new Date();
            let date = now.getFullYear()+'-'+(now.getMonth()+1)+'-'+now.getDate();
            let time = now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
            let dateTime = date +' '+ time;
            return dateTime;
        }
    },
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
    },
    methods: {
        async onTabChange(clickedTab)
        {
            if(this.isLoggedIn == false)
            {
                var array = ["login", "signup", "guest"];
            }

            if(this.isLoggedIn == true)
            {
                var array = ["login", "guest"];
            }
            this.currentTab = clickedTab;
            this.userType = array[clickedTab];
            this.area_charge = 0;
            this.is_area_charge = false;
            this.area_charge_msg = null;
            this.signupFormData.area = null;
            this.guestFormData.area = null;
            if((this.isLoggedIn == true) && (this.userType == 'login')){
                this.checkOutOfAreaCharge(this.auth_user.area.id)
            }
        },
        afterChangeCategory()
        {
            this.$router.replace('/order/' + this.nextRoute);
            this.dialogIfCategoryChange = false; 
        },
        ifChangeServiceCategory(category_slug){
            if(this.selectedServiceBit.length != 0)
            {
                this.dialogIfCategoryChange = true;
                this.nextRoute = category_slug;
                return false;
            }
            this.$router.replace("/order/" + category_slug);
        },
        async checkOutOfAreaCharge(id)
        {
            var outside_area_id = this.site_configs.outside_area_id;
            var found = Object.keys(outside_area_id).filter(function(key) {
                return outside_area_id[key] == id;
            });
            this.area_charge = 0;
            this.is_area_charge = false;
            if(found.length)
            {
                this.is_area_charge = true;
                this.area_charge_msg = "N.B. Outside City extra charge BDT&nbsp;<strong>"+this.site_configs.area_charge+"</strong>&nbsp;will be added.";
                this.area_charge = this.site_configs.area_charge;
                this.order.area_charge = this.site_configs.area_charge;
            }
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
            this.site_configs.refer = response.data.refer;
            //console.log('site-configs', response.data.refer);
        },
        async getService() {
            this.isProgressLoading = true;
            var category_id = this.$route.params.category;
            var res = await axios.get("/category/service/" + category_id);
            res.data ? (this.category = res.data.data) : (this.category = []);
            this.services = this.category.services;
            this.isProgressLoading = false;
        },
        loadServiceBit: function(serviceId) {
            // this.services.id == servicesId;
            this.serviceBits = "";
            var arr = this.services;
            var selectedService = this.services.find(arr => arr.id == serviceId);
            this.serviceBits = selectedService.serviceBits;
            this.selectedServices.push(selectedService);
            this.serviceBitsDialog = true;
            // this.$router.replace(selectedService.slug);
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
                var total = slectedBit.qty > 1 ? parseInt(slectedBit.price) + parseInt(slectedBit.additional_price) * (slectedBit.qty - 1) : parseInt(slectedBit.price);
                var totalQty = slectedBit.qty;
                if(this.$i18n.locale=='en'){
                    return total.toString();
                }else{
                   return this.e2btransform(total.toString()); 
                }
                
            }
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
        prevView: function() {
            if (this.viewarea == "schedule") {
                this.viewarea = "service";
            } else if (this.viewarea == "confirmation") {
                this.viewarea = "schedule";
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
                this.schedule_charge_msg = "N.B. For emergency service hour&nbsp;<strong>("+this.site_configs.office_end_time+" to "+this.site_configs.office_start_time+")</strong>&nbsp;an additional&nbsp;<strong>BDT "+this.site_configs.schedule_charge+"</strong>&nbsp;will be added.";
                this.schedule_charge = this.site_configs.schedule_charge;
                this.order.schedule_charge = this.site_configs.schedule_charge;
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
            return parseInt(this.area_charge);
            // emergency charge off
            //return ( parseInt(this.schedule_charge) + parseInt(this.area_charge) );
        },
        totalPrice(servicesBits) {
            if (servicesBits) {
                function indvidualTotal(item) {
                    var total =
                        item.qty > 1 ? (item.qty - 1) * parseInt(item.additional_price) +
                        parseInt(item.price) : parseInt(item.price);
                    return parseInt(total);
                }
                var tp = servicesBits.map(indvidualTotal).reduce((f, n) => n + f, 0);
                return tp;
            } else {
                return 0;
            }
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
                if(this.site_configs.refer ==1){
                    this.refCode = this.$route.params.refer_key;
                }                
                if(this.isLoggedIn == true){
                    this.checkOutOfAreaCharge(this.auth_user.area.id)
                }
            } else if (this.viewarea == "confirmation") {
                if ((this.userType == "login") && (this.isLoggedIn == false)) {
                    this.loginPrevent();
                    // this.alertMessage = "Please sign in first.";
                    // this.snackbar = true;
                    return false;
                }
                if ((this.userType == "signup") && (this.isLoggedIn == false)) {
                    this.signupPrevent();
                    // this.alertMessage = "Please sign up first.";
                    // this.snackbar = true;
                    return false;
                }
                if (this.userType == "guest") {
                    this.guestPrevent();
                    // this.alertMessage = "Please fillup required fields";
                    // this.snackbar = true;
                    // return false;
                }
                this.placeOrder();
            } else {
                this.alertMessage = "Something went wrong!";
                this.snackbar = true;
            }
            this.selectedServiceBitAddToOrder();
            this.order.category = this.category.name;
            this.order.categoryId = this.category.id;
        },
        async placeOrder() {
            if((this.isLoggedIn == true) && ((this.userType == 'login'))){
                this.order.userId = this.auth_user.id;
                this.order.name = this.auth_user.name;
                this.order.phone = this.auth_user.phone;
                this.order.email = this.auth_user.email;
                this.order.area = this.auth_user.area.id;
                this.order.address = this.auth_user.address;
            }
            if(this.userType == 'guest'){
                this.order.name = this.guestFormData.name;
                this.order.phone = this.guestFormData.phone;
                this.order.email = this.guestFormData.email;
                this.order.area = this.guestFormData.area;
                this.order.address = this.guestFormData.address;
                this.order.comments = this.guestFormData.comments;
            }
            this.order.orderFrom = this.userType;
            
            this.orderDone = true;
            this.orderPlacingStatus = true;

            let loader = this.$loading.show();
            this.display_errors = [];
            try {
                let response = await axios.post((this.userType == 'guest') ? '/guest-order' :  '/order', {
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
                    schedule_charge: this.order.schedule_charge,
                    area_charge: this.order.area_charge,
                    promocode: this.order.promocode,
                });
                this.$router.replace("/order/" + this.$route.params.category + "/order_confirmed");
                // this.alertMessage = response.data.message;
                // this.snackbar = true;

                // this.is_schedule_charge = false;
                // this.is_area_charge = false;
                // this.schedule_charge = 0,
                // this.area_charge = 0,
                // this.discount = 0,

                // this.order = {
                //     category: null,
                //     categoryId: null,
                //     services: [],
                //     serviceBit: [],
                //     date: null,
                //     time: null,
                //     orderFor: "self",
                //     userId: null,
                //     status: 0,
                //     name: null,
                //     phone: null,
                //     email: null,
                //     area: null,
                //     address: null,
                //     comments: null,
                //     refCode: null,
                //     orderFrom: null,
                //     schedule_charge: null,
                //     area_charge: null,
                //     promocode: null,
                // };
                // this.selectedServiceBit = [];
                // this.serviceBits = [];
                // this.viewarea = "service";

                // if (this.userType == "login" || this.userType == "signup") {
                //     this.$router.replace("/user");
                // }
                // else
                // {
                //     this.thankyouDialog = true;
                // }
            } catch (error) {
                if(error.response.data.errors)
                {
                    this.display_errors = error.response.data.errors;
                }
                this.alertMessage = error.response.data.message;
                this.snackbar = true;;
            }
            
            this.orderDone = false;
            this.orderPlacingStatus = false;
            loader.hide();
        },

        async loginPrevent() {
            let loader = this.$loading.show();
            this.display_errors = [];
            if (this.$refs.loginForm.validate()) {
                try {
                    let response = await axios.post("/login", {
                        phone: this.loginFormData.phone,
                        password: this.loginFormData.password
                    });
                    this.$store.commit("setUserInfo", {
                        afterLoginUserData: response.data.user,
                        d_token: response.data.access_token
                    });
                    this.auth_user = localStorageService.getItem("currentUserData");
                    this.checkOutOfAreaCharge(this.auth_user.area.id)
                    this.alertMessage = response.data.message;
                    this.snackbar = true;
                    this.isLoggedIn = true;
                } catch (error) {
                    if(error.response.data.errors)
                    {
                        this.display_errors = error.response.data.errors;
                    }
                    this.alertMessage = error.response.data.message;
                    this.snackbar = true;
                }
            }
            loader.hide();
        },
        async signupPrevent() {
            let loader = this.$loading.show();
            this.display_errors = [];
            if (this.$refs.signupForm.validate()) {
                try {
                    let response = await axios.post("/register", {
                        name: this.signupFormData.name,
                        phone: this.signupFormData.phone,
                        email: this.signupFormData.email,
                        area: this.signupFormData.area,
                        address: this.signupFormData.address,
                        password: this.signupFormData.password,
                        password_confirmation: this.signupFormData.confirmPassword
                    });
                    this.otp = true;
                    this.alertMessage = response.data.message;
                    this.snackbar = true;
                } catch (error) {
                    if(error.response.data.errors)
                    {
                        this.display_errors = error.response.data.errors;
                    }
                    this.alertMessage = error.response.data.message;
                    this.snackbar = true;
                }
            }
            loader.hide();
        },
        async varifyOtp() {
            let loader = this.$loading.show();
            this.display_errors = [];
            try {
                let response = await axios.post("/varify-otp", {
                    phone: this.signupFormData.phone,
                    password: this.signupFormData.password,
                    otp_code: this.otpCode
                });
                this.$store.commit("setUserInfo", {
                    afterLoginUserData: response.data.user,
                    d_token: response.data.access_token
                });
                this.auth_user = localStorageService.getItem("currentUserData");
                this.checkOutOfAreaCharge(this.auth_user.area.id)
                this.alertMessage = response.data.message;
                this.snackbar = true;
                this.otpVerified = true;
                this.isLoggedIn = true;
                this.discount = 0;
                this.currentTab = 'login';
            } catch (error) {
                if(error.response.data.errors)
                {
                    this.display_errors = error.response.data.errors;
                }
                this.alertMessage = error.response.data.message;
                this.snackbar = true;
            }
            loader.hide();
        },

        async guestPrevent() {
            let loader = this.$loading.show();
            this.display_errors = [];
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
        async openPromoCodeDialog() {
            this.alert_promocode_msg = null;
            this.alert_promocode = false;
            this.dialogPromoCode = true;
        },
        closePromoCodeDialog() {
            this.promoCode = null;
            this.dialogPromoCode = false;
            this.alert_promocode_msg = null;
            this.alert_promocode = false;
        },
        async applyPromoCode()
        {
            this.snackbar = false;
            this.alertMessage = null;
            this.display_errors = [];
            try {
                var response = await axios.post((this.isLoggedIn == true) ? '/user-apply-promo-codes' : '/guest-apply-promo-codes', {
                    promo_code: this.promoCode,
                    phone: (this.isLoggedIn == true) ? this.auth_user.phone : this.guestFormData.phone,
                    amount: this.totalPrice(this.selectedServiceBit)
                });
                
                this.order.promocode = this.promoCode;
                this.discount = response.data.discount;
                this.alertMessage = "You are got " + response.data.discount +" BDT discount for using your promo code.";
                this.snackbar = true;
                this.dialogPromoCode = false;
            } catch (error) {
                if(error.response.data.errors)
                {
                    this.display_errors = error.response.data.errors;
                }
                this.alertMessage = error.response.data.message;
                this.snackbar = true;
                this.snackbarColor = 'error';
            }
        }
    }
};
</script>
<style scoped>
.v-alert{
    display: block
}
ul {
  list-style: none;
}
.v-list__tile--link {
  height: auto !important;
  min-height: 60px;
}
.v-list__tile--link:hover > .v-list__tile__title {
  color: #fff !important;
}
.v-list__tile--link:hover {
  background-color: #90909094 !important;
}
.v-list__tile__action-text{
  color: #333 !important;
}
.services-item-list{
  background-color: transparent;
  border-bottom: 1px solid #201111;
}

.services-item-list.active{
  background-color: #6a6c6d;
}
.services-item-list.active .v-list__tile__title,
.services-item-list.active .v-list__tile__action-text{
  color: #fff !important;
}

@media only screen and (max-width: 767px){
    .v-btn-toggle .v-btn{
        padding: 0 0px;
    }
}
</style>