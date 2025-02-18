<template>
    <v-container fluid style="">
        <v-card elevation="1" v-if="orders">
            <v-card-title class="white--text" style="background-color: #febe00;">
                <h5><v-icon>work</v-icon> পূর্বের কাজ</h5>
            </v-card-title>  
            <v-card-actions>
                <v-flex sm12 md4>
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
                <v-flex sm12 md4>
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
                <v-flex sm12 md4>
                    <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                </v-flex>
            </v-card-actions>
            <v-data-table :headers="ordersHeaders" :items="ordersWithIndex" :search="search" class="elevation-0">
                <template v-slot:items="props">
                    <td class="text-xs-left">
                        <span v-if="$i18n.locale=='en'">{{ props.item.sl }}</span>
                        <span v-else>{{ e2btransform((props.item.sl).toString()) }}</span>
                    </td>
                    <td class="text-xs-left">
                        <span v-if="$i18n.locale=='en'">{{ props.item.order_no }}</span>
                        <span v-else>{{ e2btransform((props.item.order_no).toString()) }}</span>
                    </td> 
                    <td class="text-xs-left">
                        <span v-if="$i18n.locale=='en'">{{ props.item.category.name }}</span>
                        <span v-else>{{ props.item.category.name_bn }}</span> 

                    </td>
                    <td class="text-xs-center">
                        <a class="d-block" @click="openDrawer(props.item.items)" href="javaScript:;">View Details</a>
                    </td>
                    <td class="text-xs-left">
                        <span v-if="$i18n.locale=='en'">{{ props.item.total_unit }}</span>
                        <span v-else>{{ e2btransform((props.item.total_unit).toString()) }}</span>
                    </td>
                    <td class="text-xs-left">
                        <span v-if="$i18n.locale=='en'">{{ props.item.total_unit_price }}</span>
                        <span v-else>{{ e2btransform((props.item.total_unit_price).toString()) }}</span>
                    </td>
                    <td class="text-xs-left">
                        <span v-if="$i18n.locale=='en'">{{ props.item.emergency_charge }}</span>
                        <span v-else>{{ e2btransform((props.item.emergency_charge).toString()) }}</span>
                   </td>
                    <td class="text-xs-left">
                        <span v-if="$i18n.locale=='en'">{{ props.item.outside_charge }}</span>
                        <span v-else>{{ e2btransform((props.item.outside_charge).toString()) }}</span>

                    </td>
                    <td class="text-xs-left">
                        <span v-if="$i18n.locale=='en'">{{ props.item.discount }}</span>
                        <span v-else>{{ e2btransform((props.item.discount).toString()) }}</span>

                   </td>
                    <td class="text-xs-left">
                        <span v-if="$i18n.locale=='en'">{{ props.item.reduce_amount }}</span>
                        <span v-else>{{ e2btransform((props.item.reduce_amount).toString()) }}</span>

                     </td>
                    <td class="text-xs-left">
                        <span v-if="$i18n.locale=='en'">{{ props.item.grant_total }}</span>
                        <span v-else>{{ e2btransform((props.item.grant_total).toString()) }}</span> 
                    </td>
                    <td class="text-xs-left">
                        <span v-if="$i18n.locale=='en'">{{ props.item.service_provider_income }}</span>
                        <span v-else>{{ e2btransform((props.item.service_provider_income).toString()) }}</span>
                    </td>
                    <td class="text-xs-left">
                        <span v-if="$i18n.locale=='en'">{{ props.item.date }}</span>
                        <span v-else>{{ e2btransform((props.item.date).toString()) }}</span>
                    </td> 
                    <td class="text-xs-left">
                        <span v-if="$i18n.locale=='en'">{{ props.item.time }}</span>
                        <span v-else>{{ e2btransform((props.item.time).toString()) }}</span>
                    </td> 
                    <td class="text-xs-left">
                        <span v-if="$i18n.locale=='en'">{{ props.item.orders_place_time }}</span>
                        <span v-else>{{ e2btransform((props.item.orders_place_time).toString()) }}</span>
                    </td>  
                    <td class="text-xs-left">{{ props.item.name}}</td>
                    <td class="text-xs-left">{{ props.item.comrade_id }}</td>
                    <td class="text-xs-left">{{ (props.item.comrade_name == null) ? 'নিজ' : props.item.comrade_name }}</td>
                    <td class="text-xs-left">{{ (props.item.status >= 5) ? 'সম্পন্ন হয়েছে' : 'বাতিল হয়েছে' }}</td>
                    <td class="text-xs-left">{{ props.item.cancel_note }}</td>
                </template>
            </v-data-table> 
        </v-card>

        <v-dialog v-if="drawer" v-model="drawer" fullscreen transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="drawer = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>সার্ভিস-এর বিস্তারিত</v-toolbar-title>
                </v-toolbar>
                <v-data-table :headers="orderItemsHeaders" :items="orderItemsWithIndex" hide-actions>
                    <template v-slot:items="props">
                        <td class="text-xs-left">{{ props.item.sl }} </td>
                        <td class="text-xs-left">
                        <span v-if="$i18n.locale=='en'">{{ props.item.category_name }}</span>
                        <span v-else>{{ props.item.category_name_bn}}</span> 

                        </td>
                        <td class="text-xs-left">
                        <span v-if="$i18n.locale=='en'">{{ props.item.service_name }}</span>
                        <span v-else>{{ props.item.service_name_bn}}</span>

                         </td>
                        <td class="text-xs-left">
                        <span v-if="$i18n.locale=='en'">{{ props.item.service_bit_name }}</span>
                        <span v-else>{{ props.item.service_bit_name_bn}}</span>

                         </td>
                        <td class="text-xs-left">{{ props.item.quantity }}</td>
                        <td class="text-xs-left">{{ props.item.price }}</td>
                        <td class="text-xs-left">{{ props.item.total_price }}</td>
                        <td class="text-xs-left">
                            <v-icon>{{ props.item.status == 1 ? 'done' : 'close' }}</v-icon>
                        </td>
                    </template>
                </v-data-table>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
    import Vue from "vue";
    import { mapState} from "vuex";
    import axios from "../../axios_instance";

    export default {
        data() {
            return {
                orders: [],
                startDateModal: '',
                endDateModal: '',
                start_date: '',
                end_date: '',
                orders_self : [] ,
                orderItems: "",
                drawer: false,
                search: null,
                snackbar: null,
                alertMessage: null,
                serviceProviderDetails : [],
                ordersHeaders: [
                    {
                        text: "নং",
                        value: "sl"
                    },
                    {
                        text: "অর্ডার নং",
                        value: "order_no"
                    },
                    {
                        text: "সার্ভিস",
                        value: "category"
                    },
                    {
                        text: "বিস্তারিত",
                        value: "details"
                    },
                    {
                        text: "পরিমান",
                        value: "unit"
                    },
                    {
                        text: "পরিমান মূল্য",
                        value: "unit_price"
                    },
                    {
                        text: "জরুরি মূল্য",
                        value: "emergency_charge",
                    },
                    {
                        text: "ঢাকা মহানগরীর বাহিরের চার্জ",
                        value: "outside_charge",
                    },
                    {
                        text: "ছাড়",
                        value: "discount",
                    },
                    {
                        text: "বিশেষ ছাড়",
                        value: "special_price",
                    },
                    {
                        text: "মোট মূল্য",
                        value: "total_price",
                    },
                    {
                        text: "আয়",
                        value: "income"
                    },
                    {
                        text: "তারিখ",
                        value: "date"
                    },
                    {
                        text: "সময়",
                        value: "time"
                    },
                    {
                        text: "অর্ডার সময়",
                        value: "order_time"
                    },
                    {
                        text: "গ্রাহকের নাম",
                        value: "customar_name"
                    },
                    {
                        text: "সহকর্মী নং",
                        value: "comrade_no"
                    },
                    {
                        text: "সহকর্মী নাম",
                        value: "comrade_name"
                    },
                    {
                        text: "অবস্থা",
                        value: "status"
                    },
                    {
                        text: "বাতিলের কারণ",
                        value: "cancellation"
                    }
                ],
                orderItemsHeaders: [
                    {
                        text: "নং",
                        value: "sno"
                    },
                    {
                        text: "সার্ভিস",
                        value: "service"
                    },
                    {
                        text: "সার্ভিস ধরন",
                        value: "service_type"
                    },
                    {
                        text: "সার্ভিস বিট",
                        value: "service_bit"
                    },
                    {
                        text: "পরিমান",
                        value: "quantity"
                    },
                    {
                        text: "পরিমান মূল্য",
                        value: "quantity_price"
                    },
                    {
                        text: "মূল্য",
                        value: "total_price"
                    },
                    {
                        text: "অবস্থা",
                        value: "status"
                    }
                ],
                drawer: null,
            };
        },
        methods: {
            openDrawer: function(itemObject) {
                console.log(itemObject);
                this.drawer = true;
                this.orderItems = itemObject;
            },
            async getOrders() {
                let loader = this.$loading.show();
                var response = await axios.get("/sp/order-history");
                this.orders = response.data.data;
                loader.hide();
            }, 
            
        },
        watch: {},
        created() {
            this.getOrders();  
        },
        computed: {
            ordersWithIndex(){
                return this.orders.map((d, index) => ({ ...d, sl: index + 1 })).filter((order) => {
                    if(((this.start_date == '') || (this.end_date == '')))
                    {
                        return true;
                    }
                    else{
                        return (order.date >= this.start_date && order.date <= this.end_date);
                    }
                })
            },
            orderItemsWithIndex() {
                return this.orderItems.map((d, index) => ({ ...d, sl: index + 1 }))
            }
        },
    };
</script>