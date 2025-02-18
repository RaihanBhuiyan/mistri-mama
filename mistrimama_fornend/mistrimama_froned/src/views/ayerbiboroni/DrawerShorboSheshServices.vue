<template>
    <v-dialog v-model="drawer" fullscreen transition="dialog-bottom-transition">
        <v-card>
            <v-toolbar dark color="primary">
                <v-btn icon dark @click="drawer = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>সর্বশেষ সার্ভিস সমূহ</v-toolbar-title>
            </v-toolbar>
            <v-list three-line>
                <v-list-tile style="padding: 0 8px;">
                    <v-list-tile-content>
                        <v-list-tile-title> Client : {{dialogData[0].name}}</v-list-tile-title>
                        <v-list-tile-sub-title class="text--primary">{{dialogData[0].phone}}</v-list-tile-sub-title>
                        <v-list-tile-sub-title>{{dialogData[0].area}}, {{dialogData[0].address}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                    <v-list-tile-content v-if="dialogData[0].comrade">
                        <v-list-tile-title>Comrade  :{{dialogData[0].comrade.name}}</v-list-tile-title>
                        <v-list-tile-sub-title class="text--primary">{{dialogData[0].comrade.phone}}</v-list-tile-sub-title>
                        <v-list-tile-sub-title> {{dialogData[0].comrade.address}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                    <v-list-tile-action>
                        <v-list-tile-action-text>Total Service Taken : <strong>{{ dialogData[0].total_service_taken }}</strong></v-list-tile-action-text>
                        <v-spacer></v-spacer>
                    </v-list-tile-action>
                </v-list-tile>
            </v-list>
            <v-data-table :headers="ordersHeaders" :items="dialogDataWithIndex" hide-actions>
                <template v-slot:items="props">
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.sl }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.sl).toString()) }}
                        </span>
                    </td>
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.order_no }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.order_no).toString()) }}
                        </span>
                    </td>
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                        {{ props.item.category_name }}
                        </span>
                        <span  v-if="$i18n.locale=='bn'">
                        {{ props.item.category.name_bn }}
                        </span> 
                    </td>
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.total_unit }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.total_unit).toString()) }}
                        </span>
                    </td>
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.total_unit_price }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.total_unit_price).toString()) }}
                        </span>
                    </td>
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.emergency_charge }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.emergency_charge).toString()) }}
                        </span>
                    </td>
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.outside_charge }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.outside_charge).toString()) }}
                        </span>
                    </td>
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.discount }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.discount).toString()) }}
                        </span>
                    </td>
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.total_price }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.total_price).toString()) }}
                        </span>
                    </td>
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.service_provider_income }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.service_provider_income).toString()) }}
                        </span>
                    </td>
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.date }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.date).toString()) }}
                        </span>
                    </td> 
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.time }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.time).toString()) }}
                        </span>
                    </td>  
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.orders_place_time }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.orders_place_time).toString()) }}
                        </span>
                    </td>   
                    <td class="text-xs-left">{{ props.item.name}}</td>
                    <td class="text-xs-left">{{ props.item.comrade_id }}</td>
                    <td class="text-xs-left">{{ (props.item.comrade_name == null) ? 'নিজ' : props.item.comrade_name }}</td>
                    <td class="text-xs-left">{{ (props.item.status >= 5) ? 'সম্পন্ন হয়েছে' : 'বাতিল হয়েছে' }}</td>
                    <td class="text-xs-left">{{ props.item.cancel_note }}</td>
                </template>
            </v-data-table>
            
            <v-data-table :headers="orderItemsHeaders" :items="orderItemsHistoryWithIndex" hide-actions>
                <template v-slot:items="props"> 
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.sl }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.sl).toString()) }}
                        </span>
                    </td>
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                        {{ props.item.category_name }}
                        </span>
                        <span  v-if="$i18n.locale=='bn'">
                        {{ props.item.category_name_bn }}
                        </span> 
                    </td>
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                        {{ props.item.service_name }}
                        </span>
                        <span  v-if="$i18n.locale=='bn'">
                        {{ props.item.service_name_bn }}
                        </span> 
                    </td> 
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                        {{ props.item.service_bit_name }}
                        </span>
                        <span  v-if="$i18n.locale=='bn'">
                        {{ props.item.service_bit_name_bn }}
                        </span> 
                    </td>  
                     
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.quantity }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.quantity).toString()) }}
                        </span>
                    </td>
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.price }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.price).toString()) }}
                        </span>
                    </td>
                    <td class="text-xs-left">
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.total_price }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.total_price).toString()) }}
                        </span> 
                    </td>
                    <td class="text-xs-left">
                        <v-icon>{{ props.item.status == 1 ? 'done' : 'close' }}</v-icon>
                    </td>
                </template>
            </v-data-table>
        </v-card>
    </v-dialog>
</template>

<script>
    import axios from "../../axios_instance.js";

    export default {
        name: "LastServices",
        props: {
            dialogData: Array,
            value: Boolean
        },
        data() {
            return {
                orderHistory: {
                    items: null,
                },
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
            };
        },
        computed: {
            drawer: {
                get () {
                return this.value
                },
                set (value) {
                    this.$emit('input', value)
                }
            },
            orderItemsHistory: function()
            {
                if(this.dialogData.length > 0)
                {
                    return this.dialogData[0].items;
                }
            },
            dialogDataWithIndex() {
                return this.dialogData.map((d, index) => ({ ...d, sl: index + 1 }))
            },
            orderItemsHistoryWithIndex() {
                return this.orderItemsHistory.map((d, index) => ({ ...d, sl: index + 1 }))
            },
        }
    };
</script>