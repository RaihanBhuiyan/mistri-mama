 <template>
    <v-container fluid style="">
        <v-layout row wrap fill-height style="margin-left: -5px; margin-right: -5px;">
            <v-flex md4 sm6 xs12>
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <v-icon large left>account_balance_wallet</v-icon>
                            <span class="subheading font-weight-light">ব্যালান্স</span>
                        </v-card-title>
                        <v-card-text>
                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'">
                                    বর্তমান একাউন্ট ব্যালান্স ৳ {{ serviceProviderDetails.balance }}
                                </span>
                                <span  v-else>
                                    বর্তমান একাউন্ট ব্যালান্স ৳ {{ e2btransform((serviceProviderDetails.balance).toString()) }}
                                </span>
                            
                            /-</p>
                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'">
                                    উত্তোলনযোগ্য ব্যালান্স ৳ {{ serviceProviderDetails.withdrawable_balance }}
                                </span>
                                <span  v-else>
                                    উত্তোলনযোগ্য ব্যালান্স ৳ {{ e2btransform((serviceProviderDetails.withdrawable_balance).toString()) }}
                                </span> 
                            /-</p>
                        </v-card-text>
                    </v-card>
                </div>
            </v-flex>
            <v-flex md4 sm6 xs12>
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <v-icon large left>room_service</v-icon>
                            <span class="subheading font-weight-light">সর্বশেষ সার্ভিস</span>
                        </v-card-title>
                        <v-card-text>
                            <p class="subheading font-weight-bold text-center">
                                <span  v-if="$i18n.locale=='en'">
                                    {{ serviceProviderDetails.last_order.grant_total }}
                                </span>
                                <span  v-else>
                                    {{ e2btransform((serviceProviderDetails.last_order.grant_total).toString()) }}
                                </span>
                            ৳ /-</p>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="drawerShorboSheshServices = true">বিস্তারিত</v-btn>
                        </v-card-actions>
                    </v-card>
                </div>
            </v-flex>
            <v-flex md4 sm6 xs12>
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <v-icon large left>attach_money</v-icon>
                            <span class="subheading font-weight-light">সর্বশেষ ক্যাশ আউট-এর পরিমান</span>
                        </v-card-title>
                        <v-card-text>
                            <p class="subheading font-weight-bold text-center">৳ 
                                <span  v-if="$i18n.locale=='en'">
                                    {{ serviceProviderDetails.last_withdraw }}
                                </span>
                                <span  v-else>
                                    {{ e2btransform((serviceProviderDetails.last_withdraw).toString()) }}
                                </span>
                            /-</p>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="drawerSheshCashOut = true">বিস্তারিত</v-btn>
                        </v-card-actions>
                    </v-card>
                </div>
            </v-flex>
            <v-flex md4 sm6 xs12>
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <v-icon large left>replay</v-icon>
                            <span class="subheading font-weight-light">সর্বশেষ রিচার্জের পরিমান</span>
                        </v-card-title>
                        <v-card-text>
                            <p class="subheading font-weight-bold text-center">৳ 
                                <span  v-if="$i18n.locale=='en'">
                                    {{ serviceProviderDetails.last_recharge }}
                                </span>
                                <span  v-else>
                                    {{ e2btransform((serviceProviderDetails.last_recharge).toString()) }}
                                </span>
                            /-</p>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="drawerSheshRecharge = true">বিস্তারিত</v-btn>
                        </v-card-actions>
                    </v-card>
                </div>
            </v-flex>
            <v-flex md4 sm6 xs12>
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <v-icon large left>group</v-icon>
                            <span class="subheading font-weight-light">কাজের সারাংশ  (মিস্ত্রিমামা থেকে)</span>
                        </v-card-title>
                        <v-card-text>
                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'">
                                    মোট অর্ডার : {{ serviceProviderDetails.total_mistrimama_order }}
                                </span>
                                <span  v-else>
                                    মোট অর্ডার : {{ e2btransform((serviceProviderDetails.total_mistrimama_order).toString()) }}
                                </span>
                            </p>
                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'">
                                    মোট আয় : ৳ {{ serviceProviderDetails.total_mistrimama_order_income }}
                                </span>
                                <span  v-else>
                                    মোট আয় : ৳ {{ e2btransform((serviceProviderDetails.total_mistrimama_order_income).toString()) }}
                                </span>
                            /-</p>
                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'">
                                    অর্ডার (চলতি মাস) : {{ serviceProviderDetails.this_month_mistrimama_job }}
                                </span>
                                <span  v-else>
                                    অর্ডার (চলতি মাস) : {{ e2btransform((serviceProviderDetails.this_month_mistrimama_job).toString()) }}
                                </span>
                            </p>
                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'">
                                    আয় (চলতি মাস): ৳ {{ serviceProviderDetails.this_month_mistrimama_income }}
                                </span>
                                <span  v-else>
                                    আয় (চলতি মাস): ৳ {{ e2btransform((serviceProviderDetails.this_month_mistrimama_income).toString()) }}
                                </span>

                            /-</p>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" to="/purberkaajlist/mistrimama">বিস্তারিত</v-btn>
                        </v-card-actions>
                    </v-card>
                </div>
			</v-flex>
            <v-flex md4 sm6 xs12>
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <v-icon large left>group</v-icon>
                            <span class="subheading font-weight-light">কাজের সারাংশ  (নিজের থেকে)</span>
                        </v-card-title>
                        <v-card-text>
                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'">
                                    মোট অর্ডার : {{ serviceProviderDetails.total_self_order }}
                                </span>
                                <span  v-else>
                                    মোট অর্ডার : {{ e2btransform((serviceProviderDetails.total_self_order).toString()) }}
                                </span>
                            </p>
                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'">
                                    মোট আয় : ৳ {{ serviceProviderDetails.total_self_order_income }}
                                </span>
                                <span  v-else>
                                    মোট আয় : ৳ {{ e2btransform((serviceProviderDetails.total_self_order_income).toString()) }}
                                </span>
                            /-</p>
                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'">
                                    অর্ডার (চলতি মাস) : {{ serviceProviderDetails.this_month_self_job }}
                                </span>
                                <span  v-else>
                                    অর্ডার (চলতি মাস) : {{ e2btransform((serviceProviderDetails.this_month_self_job).toString()) }}
                                </span>
                            </p>
                            <p class="caption font-weight-bold">
                                <span  v-if="$i18n.locale=='en'">
                                    আয় (চলতি মাস): ৳ {{ serviceProviderDetails.this_month_self_income }}
                                </span>
                                <span  v-else>
                                    আয় (চলতি মাস): ৳ {{ e2btransform((serviceProviderDetails.this_month_self_income).toString()) }}
                                </span>
                                <span  v-if="$i18n.locale=='en'"></span>
                                <span  v-else></span>
                            /-</p>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" to="/purberkaajlist/self">বিস্তারিত</v-btn>
                        </v-card-actions>
                    </v-card>
                </div>
			</v-flex>
        </v-layout>

        <v-card elevation="1">
            <v-card-title class="white--text" style="background-color: #febe00;">
                <h5><v-icon>sync_alt</v-icon> লেনদেন এর বিবরন</h5>
            </v-card-title>
            <v-card-actions>
                <v-flex sm12 md6>
                    <v-dialog ref="startDateModal" v-model="startDateModal" :return-value.sync="start_date" persistent lazy full-width width="290px">
                        <template v-slot:activator="{ on }">
                            <v-text-field v-model="start_date" label="Start Date" prepend-icon="event" readonly v-on="on"></v-text-field>
                        </template>
                        <v-date-picker v-model="start_date" scrollable>
                            <v-spacer></v-spacer>
                            <v-btn flat color="primary" @click="startDateModal = false">Cancel</v-btn>
                            <v-btn flat color="primary" @click="$refs.startDateModal.save(start_date)">OK</v-btn>
                        </v-date-picker>
                    </v-dialog>
                </v-flex>
                <v-flex sm12 md6>
                    <v-dialog ref="endDateModal" v-model="endDateModal" :return-value.sync="end_date" persistent lazy full-width width="290px">
                        <template v-slot:activator="{ on }">
                            <v-text-field v-model="end_date" label="End Date" prepend-icon="event" readonly v-on="on"></v-text-field>
                        </template>
                        <v-date-picker v-model="end_date" scrollable>
                            <v-spacer></v-spacer>
                            <v-btn flat color="primary" @click="endDateModal = false">Cancel</v-btn>
                            <v-btn flat color="primary" @click="$refs.endDateModal.save(end_date)">OK</v-btn>
                        </v-date-picker>
                    </v-dialog>
                </v-flex>
                    <v-spacer></v-spacer>
                <v-btn color="primary" @click="ExportLendenBiboron()"><v-icon class="custom-icon">cloud_download</v-icon> </v-btn>
            </v-card-actions>
            <v-data-table dense v-if="statementsWithIndex.length != 0" :headers="statementsHeaders" disable-sort="true" v-bind:pagination.sync="pagination" :items="statementsWithIndex" :search="search">
                <template v-slot:items="props">
                    <td class="text-xs-left">
                    <span  v-if="$i18n.locale=='en'">
                        {{ props.item.date }}
                    </span>
                    <span  v-else>
                        {{ e2btransform((props.item.date).toString()) }}
                    </span> </td>
                    <td class="text-xs-left">{{ props.item.details }}</td>
                    <td class="text-xs-left">{{props.item.trxno}}</td>
                    <td class="text-xs-right">
                        <v-chip v-if="props.item.status == 'credit' || props.item.status == 'income'">
                            <v-avatar>
                                <v-icon :color="(props.item.status == 'credit' || props.item.status == 'income') ? 'success' : 'red'">{{ (props.item.status == 'credit' || props.item.status == 'income') ? 'add_circle_outline' : 'remove_circle_outline' }}</v-icon>
                            </v-avatar>
                            <span  v-if="$i18n.locale=='en'">
                                {{ props.item.amount }}
                            </span>
                            <span  v-else>
                                {{ e2btransform((props.item.amount).toString()) }}
                            </span>
                            /-
                        </v-chip>
                        <span v-if="props.item.status != 'credit' && props.item.status != 'income' && props.item.status != 'extra_charge'" > --</span> 
                    </td>
                    <td class="text-xs-right">
                        <v-chip  v-if="props.item.status != 'credit' && props.item.status != 'income' && props.item.status != 'extra_charge'" >
                            <v-avatar>
                                <v-icon color="red">remove_circle_outline</v-icon>
                            </v-avatar>
                            <span  v-if="$i18n.locale=='en'">
                                {{ props.item.amount }}
                            </span>
                            <span  v-else>
                                {{ e2btransform((props.item.amount).toString()) }}
                            </span> /-
                        </v-chip>
                        <span v-if="props.item.status == 'credit' || props.item.status == 'income'" > --</span> 
                    </td>
                    <td class="text-xs-right">
                    <span  v-if="$i18n.locale=='en'">
                        {{ props.item.balance }}
                    </span>
                    <span  v-else>
                        {{ e2btransform((props.item.balance).toString()) }}
                    </span> /-</td>
                </template>
            </v-data-table>
            <v-card-text v-else style="text-align:center">There is no transaction here.</v-card-text>
        </v-card>
        
        <dialog-shorbo-shesh-services v-model="drawerShorboSheshServices" :dialogData="lastOrderData"></dialog-shorbo-shesh-services>
        <dialog-shorbo-shesh-cashout v-model="drawerSheshCashOut" :dialogData="rechargeStatmentData"></dialog-shorbo-shesh-cashout>
        <dialog-shobo-shesh-recharge v-model="drawerSheshRecharge" :dialogData="rechargeStatmentData"></dialog-shobo-shesh-recharge>

        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
    import {
        mapState
    }
    from "vuex";
    import DrawerShorboSheshServices from "../ayerbiboroni/DrawerShorboSheshServices";
    import DrawerSheshCashOut from "../ayerbiboroni/DrawerSheshCashOut";
    import DrawerSheshRecharge from "../ayerbiboroni/DrawerSheshRecharge";
    import axios from "../../axios_instance";

    export default {
        components: {
            'dialog-shorbo-shesh-services': DrawerShorboSheshServices,
            'dialog-shorbo-shesh-cashout': DrawerSheshCashOut,
            'dialog-shobo-shesh-recharge': DrawerSheshRecharge
        },
        data() {
            return {
                serviceProviderDetails: {
                    balance: 0,
                    last_order: {
                        total_price: 0
                    },
                    last_withdraw: 0,
                    last_recharge: 0,
                },
                startDateModal: '',
                endDateModal: '',
                start_date: '',
                end_date: '',
                statements: [],
                rechargeStatmentData: [],
                lastOrderData: [],
                drawerShorboSheshServices: false,
                drawerSheshCashOut: false,
                drawerSheshRecharge: false,
                snackbar: null,
                alertMessage: null,
                search: null,
                pagination : {'sortBy': 'date', 'descending': true, 'rowsPerPage': 15},
                statementsHeaders: [{
                    text: "তারিখ",
                    value: "date"
                }, {
                    text: "বিস্তারিত",
                    value: "details"
                }, {
                    text: "টি.এক্স.এন আইডি #",
                    value: "trxno"
                }, {
                    text: "ডেবিট",
                    value: "amount"
                },{
                    text: "ক্রেডিট ",
                    value: "amount"
                } , {
                    text: "ব্যালান্স",
                    value: "carbs",
                    align: 'right',
                }],
            };
        },
        methods: {
            async ExportLendenBiboron(){
                var d = new Date();
                let response = await axios.get('/sp/statements-history-lager-export');  
                let blob = new Blob([response.data], {type: 'text/csv'});
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'statement_'+d.getFullYear()+'-'+ (Number(d.getMonth())+1) + '-'+ d.getDate()+'.csv';
                link.click(); 
            },
            async getServiceProviderDetails() { 
                let loader = this.$loading.show();
                let response = await axios.get("/service-provider-details");
                this.serviceProviderDetails = response.data.data;
                if(this.serviceProviderDetails.last_order != 0)
                {
                    this.lastOrderData.push(this.serviceProviderDetails.last_order);

                }
                loader.hide();
            },
            async getStatements() {
               
                let response = await axios.get("/sp/statements"); 
                this.statements = response.data;
                this.rechargeStatmentData = response.data;
              
            }
        },
        watch: {},
        computed: {
            statementsWithIndex() {
                return this.statements.map((d, index) => ({ ...d, sl: index + 1 })).filter((order) => {
                    if(((this.start_date == '') || (this.end_date == '')))
                    {
                        return true;
                    }
                    else{
                        return (order.date >= this.start_date && order.date <= this.end_date);
                    }
                })
            },
        },
        created() {
            this.getStatements();
            this.getServiceProviderDetails();
        }
    };
</script>
<style  scoped>
    a {
        cursor: pointer;
        color: var(--dark);
    }
    
    a:hover {
        text-decoration: underline;
        color: #febe00;
    }
    
    .custom-height {
        height: 120px;
    }
    
    .v-card-padding {
        padding: 10px;
        margin: 15px;
    }
    
    .services {
        border: 1px solid #febe00;
        font-size: 16px !important;
        padding: 10px;
    }
    
    .box-buttons {
        width: 100%;
        margin: 0px;
    }
    
    .row {
        margin-right: 0px;
        margin-left: 0px;
    }
</style>