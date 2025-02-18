<template>
    <v-container fluid style="">
        <v-layout row wrap fill-height style="margin-left: -5px; margin-right: -5px;">
			<v-flex md3 sm6 xs12>
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <v-icon large left>account_balance_wallet</v-icon>
                            <span class="subheading font-weight-light">ব্যালান্স</span>
                        </v-card-title>
                        <v-card-text>
                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'">বর্তমান একাউন্ট ব্যালান্স ৳ {{serviceProviderDetails.balance}} </span>
                                <span v-else >বর্তমান একাউন্ট ব্যালান্স ৳ {{ e2btransform((serviceProviderDetails.balance).toString()) }}</span> /-
                            </p>
                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'">উত্তোলনযোগ্য ব্যালান্স ৳ {{ serviceProviderDetails.withdrawable_balance }}</span>
                                <span  v-else>উত্তোলনযোগ্য ব্যালান্স ৳ {{ e2btransform((serviceProviderDetails.withdrawable_balance).toString()) }}</span>
                                
                                /-</p>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="openCashoutDialog()">ক্যাশ আউট</v-btn>
                        </v-card-actions>
                    </v-card>
                </div>
			</v-flex>
			<v-flex md3 sm6 xs12>
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <v-icon large left>group</v-icon>
                            <span class="subheading font-weight-light">কাজের সারাংশ  (মিস্ত্রিমামা থেকে)</span>
                        </v-card-title>
                        <v-card-text>
                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'"> মোট অর্ডার : 
                            {{ serviceProviderDetails.total_mistrimama_order }}</span>
                                <span  v-else>মোট অর্ডার : 
                            {{ e2btransform((serviceProviderDetails.total_mistrimama_order).toString()) }}</span>
                                </p>

                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'">মোট আয় : ৳ {{ serviceProviderDetails.total_mistrimama_order_income }} </span>
                                <span  v-else>মোট আয় : ৳ {{ e2btransform((serviceProviderDetails.total_mistrimama_order_income).toString()) }}</span>
                            /-</p>

                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'"> অর্ডার (চলতি মাস) : {{ serviceProviderDetails.this_month_mistrimama_job }}</span>
                                <span  v-else>অর্ডার (চলতি মাস) : {{ e2btransform((serviceProviderDetails.this_month_mistrimama_job).toString()) }}</span>
                            </p>

                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'"> আয় (চলতি মাস): ৳ {{ serviceProviderDetails.this_month_mistrimama_income }}</span>
                                <span  v-else>আয় (চলতি মাস): ৳ {{ e2btransform((serviceProviderDetails.this_month_mistrimama_income).toString()) }}</span>
                            /-</p>

                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" to="/purberkaajlist/mistrimama">বিস্তারিত</v-btn>
                        </v-card-actions>
                    </v-card>
                </div>
			</v-flex>
			<v-flex md3 sm6 xs12>
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <v-icon large left>group</v-icon>
                            <span class="subheading font-weight-light">কাজের সারাংশ  (নিজের থেকে)</span>
                        </v-card-title>
                        <v-card-text>
                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'">মোট অর্ডার : {{ serviceProviderDetails.total_self_order }}</span>
                                <span  v-else>মোট অর্ডার : {{ e2btransform((serviceProviderDetails.total_self_order).toString()) }}</span>
                                </p>
                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'">মোট আয় : ৳ {{ serviceProviderDetails.total_self_order_income }}</span>
                                <span  v-else>মোট আয় : ৳ {{ e2btransform((serviceProviderDetails.total_self_order_income).toString()) }}</span>
                            /-</p>
                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'">অর্ডার (চলতি মাস) : {{ serviceProviderDetails.this_month_self_job }}</span>
                                <span  v-else>অর্ডার (চলতি মাস) : {{ e2btransform((serviceProviderDetails.this_month_self_job).toString()) }}</span>
                            </p>
                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'">আয় (চলতি মাস): ৳ {{ serviceProviderDetails.this_month_self_income }}</span>
                                <span  v-else>আয় (চলতি মাস): ৳ {{ e2btransform((serviceProviderDetails.this_month_self_income).toString())}}</span>
                            /-</p>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" to="/purberkaajlist/self">বিস্তারিত</v-btn>
                        </v-card-actions>
                    </v-card>
                </div>
			</v-flex>
			<v-flex md3 sm6 xs12>
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <v-icon large left>speed</v-icon>
                            <span class="subheading font-weight-light">রেটিং</span>
                        </v-card-title>
                        <v-card-text class="headline font-weight-bold text-center">
                            <span  v-if="$i18n.locale=='en'">
                                {{ serviceProviderDetails.rating }}
                            </span>
                            <span  v-else>
                                {{ e2btransform((serviceProviderDetails.rating).toString()) }}
                            </span> 
                            <v-icon style="font-size: 30px;" color="primary">star</v-icon>
                        </v-card-text>
                    </v-card>
                </div>
			</v-flex>
			<v-flex md12 sm12 xs12>
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <v-icon large left>speed</v-icon>
                            <span class="subheading font-weight-light">স্কিম</span>
                        </v-card-title>
                        <v-card-text class="headline font-weight-bold">
                            <p class="caption font-weight-bold">টার্গেট</p>
                            <div class="progress" style="height: 3rem; font-size: 1.75rem; text-align: center;">
                                <div style="width: 33.33%; background-color: transparent; color: #333; line-height: 3rem; position: relative;">
                                    <span style="position: relative; z-index: 1">
                                        <span  v-if="$i18n.locale=='en'">
                                            C (15)
                                        </span>
                                        <span  v-else> C
                                            ({{ e2btransform((15).toString()) }})
                                        </span>
                                    </span>
                                    <span :style="'display: block; background-color: #5cb85c; width: '+scheme_last_week.scheme.a+'%; position: absolute; left: 0; top: 0'">&nbsp;</span>
                                </div>
                                <div style="width: 33.33%; background-color: transparent; color: #333; line-height: 3rem; position: relative;">
                                    <span style="position: relative; z-index: 1">
                                        <span  v-if="$i18n.locale=='en'">
                                            B (20)
                                        </span>
                                        <span  v-else> 
                                            B ({{ e2btransform((20).toString()) }})
                                        </span>
                                    </span>
                                    <span :style="'display: block; background-color: #5bc0de; width: '+scheme_last_week.scheme.b+'%; position: absolute; left: 0; top: 0'">&nbsp;</span>
                                </div>
                                <div style="width: 33.33%; background-color: transparent; color: #333; line-height: 3rem; position: relative;">
                                    <span style="position: relative; z-index: 1">
                                        <span  v-if="$i18n.locale=='en'">
                                            A (30)
                                        </span>
                                        <span  v-else> A
                                            ({{ e2btransform((30).toString()) }})
                                        </span>
                                    </span>
                                    <span :style="'display: block; background-color: #f0ad4e; width: '+scheme_last_week.scheme.c+'%; position: absolute; left: 0; top: 0'">&nbsp;</span>
                                </div>
                            </div>
                            <p class="subheading font-weight-bold text-center mt-3" v-html="(scheme_last_week.total_order > 0) ? 'আপনি '+scheme_last_week.total_order+' টি কাজ করেছেন' : 'আপনি এখনো নিজের কোনো কাজ করেননি'"></p>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" to="/scheme">বিস্তারিত</v-btn>
                        </v-card-actions>
                    </v-card>
                </div>
			</v-flex>
        </v-layout>

        <div style="padding: 5px 0;">
            <mullmenu-lenden></mullmenu-lenden>
        </div>
        
        <v-card elevation="0">
            <v-card-title class="white--text" style="background-color: #febe00;">
                <h5><v-icon>room_service</v-icon> আপনার সার্ভিস সমূহ</h5>
            </v-card-title>
            <v-layout wrap style="text-align: center">
                <v-flex md2 sm4 xs6 v-for="service in serviceProviderDetails.avail_services" :key="service.id">
                    <div class="avail_services">
                        <img :src="service.thumb" :alt="service.slug" class="avail_services" />
                        <p class="text-center" v-if="$i18n.locale=='en'">{{ service.name }}</p>
                        <p class="text-center" v-else>{{ service.name_bn }}</p>
                    </div>
                </v-flex>
            </v-layout>
        </v-card>

        <v-dialog v-model="cashoutDialog" fullscreen transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="cashoutDialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>ক্যাশ আউট করুন </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark flat @click="cashOutRequest()">Submit</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-container fluid>
                    <v-form lazy-validation @submit.prevent>
                        <v-text-field color="accent" clearable v-model="amount" :error-messages="display_errors.amount" label="Amount" placeholder="Enter an amount *"></v-text-field>
                        <v-select color="accent" :items="itemsMfs" v-model="mfs" :error-messages="display_errors.mfs" label="Select Service *"></v-select>
                        <v-text-field color="accent" clearable   prefix="+88" v-model="mfsNumber" :error-messages="display_errors.mfs_number" label="MFS Number (Bkash) *" placeholder="Enter MFS No."></v-text-field>
                        <v-text-field color="accent" clearable type="password" v-model="confirmPassword" label="Confirm Password *" placeholder="Please enter your password"></v-text-field>
                    </v-form>
                </v-container>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
    import { mapState } from "vuex"; 
    import Mulmenu_Lenden from "../mulmenu/Mulmenu_Lenden";
    import DrawerBalance from "../mulmenu/DrawerBalance";
    import axios from "../../axios_instance";
    import { localStorageService } from "../../helper";
    import Wating_Order from "../mulmenu/Wating_Order";
    export default {
        components: {
            'mullmenu-lenden': Mulmenu_Lenden,
            'wating-order': Wating_Order,
            'dialog-cashout': DrawerBalance,
        },
        data() {
            return {
                scheme :  0 ,
                user_type : localStorageService.getItem("currentUserData").type ,
                serviceProviderDetails: {
                    balance: 0,
                    withdrawable_balance: 0
                },
                rating: 0,
                waiting_order : [], 
                scheme_last_week : {
                    scheme: {
                        a:0,
                        b:0,
                        c:0
                    }
                }, 
                dataLoaded: true,
                cashoutDialog : false,
                pageCount: 1,
                statements: [],
                dialogCashout: false,
                pagination: null,
                snackbar: null,
                alertMessage: null,
                ownerSearch: null,
                headersLenden: [{
                    text: "তারিখ",
                    align: "left",
                    value: "name"
                }, {
                    text: "বিস্তারিত",
                    value: "calories"
                }, {
                    text: "TXN ID",
                    value: "fat"
                }, {
                    text: "পরিমান",
                    value: "carbs"
                }],
                desserts: [],
                sideData: [],
                amount: null,
                mfs: null,
                mfsNumber: null,
                confirmPassword: null,
                itemsMfs: ["Bkash"],
                alertMessage: "",
                snackbar: null,
                display_errors: [], 
                // phoneRules: [
                //     v => !!v || "This field cannot be empty", 
                //     v => v.match(/(^(\+88|0088)?(01){1}[3456789]{1}(\d){8})$/)|| "Invalid mobile number (+8801XXXXXXXXX)",
                // ],
            };
        },
        methods: {
            openCashoutDialog() {
                if(this.serviceProviderDetails.withdrawable_balance == 0){
                    this.alertMessage = "You have not enough balance for withdraw money";
                    this.snackbar = true;
                    return false;
                }
                this.cashoutDialog = true ;
            },
            async getServiceProviderDetails() {
                let loader = this.$loading.show();
                let response = await axios.get("/service-provider-details");
                this.serviceProviderDetails = response.data.data;
                loader.hide();
            }, 
            async cashOutRequest() {
                let loader = this.$loading.show();
                this.display_errors = [];
                try {
                    let response = await axios.post("cashout-request", {
                        amount: this.amount,
                        mfs: this.mfs,
                        mfs_number: this.mfsNumber,
                        password: this.confirmPassword
                    });
                     this.getServiceProviderDetails();
                    this.alertMessage = response.data.message;
                    this.snackbar = true;
                    this.cashoutDialog = false ;
                } catch (error) {
                    if(error.response.data.errors){
                        this.display_errors = error.response.data.errors;
                    }
                    this.alertMessage = error.response.data.message;
                    this.snackbar = true;
                }
                loader.hide();
            },
            async getScheme() { 
                let response = await axios.get("/sp/scheme-last-week"); 
                this.scheme_last_week =  response.data;
            },
        },
        watch: {

        },
        computed: {

        },
        created() {
            this.getServiceProviderDetails();
            this.getScheme();            
        }
    };
</script>
<style scoped>
.progress-bar-success{
    background-color: #5cb85c;
}
.progress-bar-info {
    background-color: #5bc0de;
}
.progress-bar-warning {
    background-color: #f0ad4e;
}
</style>