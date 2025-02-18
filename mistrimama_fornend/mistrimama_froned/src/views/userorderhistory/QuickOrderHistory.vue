<template>
    <v-container fluid style="">
        <v-card v-if="orders.lenght != 0">
            <v-card-title class="white--text" style="background-color: #febe00;">
                <v-icon class="custom-icon">history</v-icon>
                <span class="title white--text font-weight-light">QUICK ORDER HISTORY</span>
            </v-card-title>
            <v-card-actions>
                <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
            </v-card-actions>
            <v-data-table :headers="ordersHeaders" :items="orders" :search="search" class="elevation-0">
                <template v-slot:items="props">
                    <td class="text-xs-left">{{ props.item.name }}</td>
                    <td class="text-xs-left">{{ props.item.request_service }}</td>
                    <td class="text-xs-left">{{ props.item.phone }}</td>
                    <td class="text-xs-left">{{ props.item.address }}</td>
                    <td class="text-xs-left">{{ props.item.comments }}</td>
                    <td class="text-xs-right">{{ (props.item.status == 0) ? 'এখনও প্রক্রিয়া করা হয়নি' : 'অর্ডার প্রক্রিয়া করা হয়েছে' }}</td>
                </template>
            </v-data-table>
        </v-card>
        <v-card v-else>
            <v-card-title class="white--text" style="background-color: #febe00;">
                <v-icon class="custom-icon">history</v-icon>
                <span class="title white--text font-weight-light">QUICK ORDER HISTORY</span>
            </v-card-title>
            <v-card-text>You have no quick orders</v-card-text>
        </v-card>
        
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" color="info" :right="'right'" :timeout="10000" style="z-index:99999" >
            {{ alertMessage }}
            <v-btn flat @click="snackbar = false" > Close </v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
    import { mapState } from "vuex";
    import axios from "../../axios_instance.js";

    export default {
        data() {
            return {
                alertMessage: null,
                search: null,
                snackbar: null,
                orders: [],
                ordersHeaders: [
                    {
                        text: "নাম",
                        value: "name"
                    },
                    {
                        text: "ধরন",
                        value: "category_name"
                    }, {
                        text: "মোবাইল নং",
                        value: "phone"
                    }, {
                        text: "ঠিকানা",
                        value: "address"
                    }, {
                        text: "মন্তব্য",
                        value: "comments",
                    }, {
                        text: "অবস্থান",
                        value: "status",
                        align: 'right'
                    }
                ],
            };
        },
        methods: {
            async getQuickOrder() {
                var response = await axios.get("/user-quick-orders");
                this.orders = response.data;
            }
        },
        watch: {},
        computed: {},
        created() {
            this.getQuickOrder();
        }
    };
</script>