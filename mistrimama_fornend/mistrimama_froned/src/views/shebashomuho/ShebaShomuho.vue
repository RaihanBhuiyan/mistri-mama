<template>
    <v-container fluid style="">
        <v-layout row wrap fill-height style="margin-left: -5px; margin-right: -5px;">
            <v-flex md6 sm6 xs12>
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <v-icon large left>room_service</v-icon>
                            <span class="subheading font-weight-light">সার্ভিস</span>
                        </v-card-title>
                        <v-card-text>
                            <p class="subheading font-weight-bold text-center">
                            <span v-if="$i18n.locale=='en'">{{serviceProviderDetails.total_avail_service}}</span>
                            <span v-else>{{ e2btransform((serviceProviderDetails.total_avail_service).toString()) }}</span>
                            </p>
                        </v-card-text>
                    </v-card>
                </div>
            </v-flex>
            <v-flex md6 sm6 xs12>
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%">
                        <v-card-title class="white--text" style="background-color: #febe00;">
                            <v-icon large left>description</v-icon>
                            <span class="subheading font-weight-light">সাব সার্ভিস</span>
                        </v-card-title>
                        <v-card-text>
                            <p class="subheading font-weight-bold text-center">
                                <span v-if="$i18n.locale=='en'">{{serviceProviderDetails.total_avail_sub_service}}</span>
                                <span v-else>{{ e2btransform((serviceProviderDetails.total_avail_sub_service).toString()) }}</span>
                            </p>
                        </v-card-text>
                    </v-card>
                </div>
            </v-flex>
        </v-layout>
        <v-card elevation="1">
            <v-card-title class="white--text" style="background-color: #febe00;">
                <h5><v-icon>work</v-icon> সেবা সমূহের বর্ণনা</h5>
            </v-card-title>
            <v-card-actions>
                <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
            </v-card-actions>
            <v-data-table :items="servicesWithIndex" :search="search">
                <template slot="headers">
                    <tr>
                        <th style="padding: 10px" rowspan="2">নং</th>
                        <th style="padding: 10px" rowspan="2">ক্যাটাগরি</th>
                        <th style="padding: 10px" rowspan="2">সাব ক্যাটাগরি</th>
                        <th style="padding: 10px" rowspan="2">কাজের ধরণ</th>
                        <th style="padding: 10px" colspan="4">মূল কাজের মূল্য</th>
                    </tr>
                    <tr>
                        <th style="padding: 10px">পরিমান</th>
                        <th style="padding: 10px">ধরণ</th>
                        <th style="padding: 10px">সার্ভিস মূল্য</th>
                        <th style="padding: 10px">পার্টনার-এর আয়</th>
                    </tr>
                </template>
                <template v-slot:items="props">
                    <td class="text-xs-left" style="cursor: pointer">{{ props.item.sl }}  </td>
                    <td class="text-xs-left" style="cursor: pointer"> 
                        <span v-if="$i18n.locale=='en'">{{ props.item.category_name }}</span>
                        <span v-else>{{ props.item.category_name_bn}}</span>
                    </td>
                    <td class="text-xs-left" style="cursor: pointer"> 
                        <span v-if="$i18n.locale=='en'">{{ props.item.service_name  }}</span>
                        <span v-else>{{ props.item.service_name_bn}}</span>
                    </td>
                    <td class="text-xs-left" style="cursor: pointer"> 
                        <span v-if="$i18n.locale=='en'">{{ props.item.name }}</span>
                        <span v-else>{{ props.item.name_bn }}</span>
                    </td>
                    <td class="text-xs-left" style="cursor: pointer">
                        <span v-if="$i18n.locale=='en'">{{ props.item.unit_remarks }}</span>
                        <span v-else>{{ e2btransform(props.item.unit_remarks) }}</span>
                    </td>
                    <td class="text-xs-left" style="cursor: pointer">{{ props.item.unit_type }}</td>
                    <td class="text-xs-left" style="cursor: pointer">
                        <span v-if="$i18n.locale=='en'">{{ props.item.price }}</span>
                        <span v-else>{{ e2btransform(props.item.price) }}</span>
                    </td>
                    <td class="text-xs-left" style="cursor: pointer">
                        <span v-if="$i18n.locale=='en'">{{ props.item.service_provider_income }}</span>
                        <span v-else>{{ e2btransform(props.item.service_provider_income) }}</span>
                    </td>
                </template>
            </v-data-table>
        </v-card>
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
    import axios from "../../axios_instance.js";
    import ShokolKaajCholtiKaaj from "../shokolkaaj/ShokolKaajCholtiKaaj";
    import ShokolKaajPhoneOrder from "../shokolkaaj/ShokolKaajPhoneOrder";

    export default {
        components: {
            ShokolKaajCholtiKaaj,
            ShokolKaajPhoneOrder
        },
        data() {
            return {
                serviceProviderDetails: {
                    total_avail_service: 0,
                },
                snackbar: false,
                alertMessage: null,
                search: null,
                services: [],
            };
        },
        methods: {
            async getServices() {
                let loader = this.$loading.show();
                var allservices = await axios.get("/sp/services");
                console.log('allservices',allservices)
                this.services = allservices.data.data;
                loader.hide();
            },
            async getServiceProviderDetails() {
                let response = await axios.get("/service-provider-details");
                this.serviceProviderDetails = response.data.data;
            }
        },
        watch: {},
        computed: {
            servicesWithIndex() {
                return this.services.map((d, index) => ({ ...d, sl: index + 1 }))
            },
        },
        created() {
            this.getServices();
            this.getServiceProviderDetails();
        }
    };
</script>
<style scoped>
    a {
        cursor: pointer;
        color: var(--dark);
    }
    
    a:hover {
        text-decoration: underline;
        color: #febe00;
    }
    
    .custom-height {
        height: 75px;
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
    
    .header-font {
        font-size: 30px;
    }
    
    th {
        border: 1px solid rgba(0, 0, 0, 0.12);
        text-align: center !important;
    }
    
    td {
        text-align: center !important;
    }
    
    .empty-box > h3 > .a {
        color: var(--secondary);
        font-size: 30px !important;
        background-color: var(--primaryTwo);
        margin-right: 15px !important;
        padding: 5px;
        padding-left: 10px;
        padding-right: 10px;
        border-radius: 0px 0px 10px 10px;
        box-shadow: 0px 1px 1px rgba(31, 31, 31, 0.26);
    }
    
    .empty-box > h3 {
        margin-top: 0px;
    }
    
    .empty-box {
        margin: 0px;
        height: 60px;
        text-align: right !important;
        float: right;
    }
    
    .icon-box {
        background-color: #febe00;
        margin-left: 10px;
        height: 100%;
        width: 100%;
        margin-top: -22px;
        border-radius: 5px;
        box-shadow: 2px 2px 9px 0px rgba(0, 0, 0, 0.212);
        transition: 0.2s;
    }
    
    .icon-box > i {
        font-size: 30px;
        color: var(--third) !important;
        line-height: 160%;
    }
    
    .row {
        margin-right: 0px;
        margin-left: 0px;
    }
</style>