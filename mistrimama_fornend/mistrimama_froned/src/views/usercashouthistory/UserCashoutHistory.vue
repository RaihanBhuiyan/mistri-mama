<template>
    <v-container fluid style="">
        <v-card elevation="1">
            <v-card-title class="white--text" style="background-color: #febe00;">
                <h5><v-icon>sync_alt</v-icon> রিওয়ার্ড পয়েন্ট ক্যাশ আউট বিবরণ</h5>
            </v-card-title>
            <v-layout row wrap>
                <v-flex md4 sm12 xs12>
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
                <v-flex md4 sm12 xs12>
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
                <v-flex md4 sm12 xs12 class="text-right">
                    <v-btn color="primary" @click="ExportCashoutHistory()"><v-icon class="custom-icon">cloud_download</v-icon> </v-btn>
                </v-flex>
            </v-layout>
            <v-data-table v-if="userCashoutWithIndex.length != 0" :headers="userCashoutHistoryHeaders" :items="userCashoutWithIndex" :search="search">
                <template v-slot:items="props">
                    <td>{{ props.item.sl }}</td>
                    <td>{{ props.item.request_date }}</td>
                    <td>{{ props.item.receive_date }}</td>
                    <td>{{ props.item.mfs_number }}</td>
                    <td>{{ props.item.trxno }}</td>
                    <td>{{ props.item.amount }}</td>
                    <td>{{ props.item.available_reward_point }}</td>
                    <td>{{ props.item.available_reward_balance }}</td>
                    <td>
                        <a class="d-block" @click="openDrawer()" href="javaScript:;">View Details</a>
                    </td>
                </template>
            </v-data-table>
            <v-card-text v-else style="text-align:center">There is no transaction here.</v-card-text>
        </v-card>
        <v-dialog v-if="drawer" v-model="drawer" fullscreen transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="drawer = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>এম এফ এস নম্বর হিস্ট্রি</v-toolbar-title>
                </v-toolbar>
                <v-data-table :headers="mfsHistoryHeaders" :items="mfsNumberHistoryWithIndex" hide-actions>
                    <template v-slot:items="props">
                        <td class="text-xs-left">{{ props.item.sl }}</td>
                        <td class="text-xs-left">{{ props.item.mfs_number }}</td>
                        <td class="text-xs-right">{{ props.item.created_at }}</td>
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
    import { mapState } from "vuex";
    import axios from "../../axios_instance";

    export default {
        data() {
            return {
                startDateModal: '',
                endDateModal: '',
                start_date: '',
                end_date: '',
                userCashoutHistory: [],
                mfsNumberHistory: [],
                snackbar: null,
                alertMessage: null,
                search: null,
                userCashoutHistoryHeaders: [
                    {
                        text: "নং",
                        value: "sl"
                    },
                    {
                        text: "অনুরোধ তারিখ",
                        value: "request_date"
                    },
                    {
                        text: "গ্রহণ তারিখ",
                        value: "receive_date"
                    },
                    {
                        text: "এম এফ এস নম্বর",
                        value: "mfs_number"
                    },
                    {
                        text: "টি.এক্স.এন আইডি #",
                        value: "trxno"
                    },
                    {
                        text: "পরিমান",
                        value: "amount"
                    },
                    {
                        text: "Total Reward Point",
                        value: "reward_point"
                    },
                    {
                        text: "RP Equivalent Amount",
                        value: "equivalent_amount"
                    },
                    {
                        text: "MFS Number History",
                        value: "number_history"
                    }
                ],
                mfsHistoryHeaders: [
                    {
                        text: "নং",
                        value: "sno"
                    },
                    {
                        text: "এম এফ এস নম্বর",
                        value: "mfs_number"
                    },
                    {
                        text: "তারিখ",
                        value: "date",
                        align: "right"
                    },
                ],
                drawer: null,
            };
        },
        methods: {
            openDrawer: function() {
                this.drawer = true;
                this.getmfsNumberHistory();
            },
            async getUserCashoutHistory() {
                let loader = this.$loading.show();
                let response = await axios.get("user-cashout-history");
                this.userCashoutHistory = response.data.data;
                loader.hide();
            },
            async getmfsNumberHistory() {
                let loader = this.$loading.show();
                let response = await axios.get("user-mfs-number-history");
                this.mfsNumberHistory = response.data;
                loader.hide();
            },
            async ExportCashoutHistory(){
                var d = new Date();
                let response = await axios.get('/export-cashout-history');  
                let blob = new Blob([response.data], {type: 'text/csv'});
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'cashout_history'+d.getFullYear()+'-'+ (Number(d.getMonth())+1) + '-'+ d.getDate()+'.csv';
                link.click(); 
            },
        },
        watch: {},
        computed: {
            userCashoutWithIndex() {
                return this.userCashoutHistory.map((d, index) => ({ ...d, sl: index + 1 })).filter((cashout) => {
                    if(((this.start_date == '') || (this.end_date == '')))
                    {
                        return true;
                    }
                    else{
                        return (cashout.request_date >= this.start_date && cashout.request_date <= this.end_date);
                    }
                })
            },
            mfsNumberHistoryWithIndex() {
                return this.mfsNumberHistory.map((d, index) => ({ ...d, sl: index + 1 }))
            },
        },
        created() {
            this.getUserCashoutHistory();
        }
    };
</script>