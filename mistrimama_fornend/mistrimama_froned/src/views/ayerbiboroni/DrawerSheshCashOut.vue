<template>
    <v-dialog v-model="drawer" fullscreen transition="dialog-bottom-transition">
        <v-card>
            <v-toolbar dark color="primary">
                <v-btn icon dark @click="drawer = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>সর্বশেষ ক্যাশ আউট সমূহ</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="downloadCashoutHistory()">
                    <v-icon>cloud_download</v-icon>
                </v-btn>
                <v-btn icon dark @click="mailCashoutHistory()">
                    <v-icon>mail</v-icon>
                </v-btn>
            </v-toolbar>
            <v-data-table v-if="cashOutStatment.length != 0" :headers="statementsHeaders" :items="cashOutStatment" hide-actions>
                <template v-slot:items="props">
                    <td>
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.relations.created_at }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.relations.created_at).toString()) }}
                        </span> 
                    </td>
                    <td>
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.date }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.date).toString()) }}
                        </span>
                    </td>
                    <td>{{ props.item.relations.mfs }} - {{ props.item.relations.mfs_number }}</td>
                    <td>{{ props.item.trxno}}</td>
                    <td class="text-right">
                        <span  v-if="$i18n.locale=='en'">
                            {{ props.item.amount }}
                        </span>
                        <span  v-else>
                            {{ e2btransform((props.item.amount).toString()) }}
                        </span>
                    /-</td>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog max-width="480" v-if="drawerMailToCashoutHistory" v-model="drawerMailToCashoutHistory" transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="drawerMailToInvoice = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>ক্যাশ আউট সমূহ ইমেইল করুন</v-toolbar-title>
                </v-toolbar>
                <v-form @submit.prevent>
                    <v-card-text>
                        <v-text-field v-model="emailAddress" :error-messages="display_errors.email_address" label="ইমেইল এর ঠিকানা দিন"></v-text-field>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn @click="sendToCashoutHistoryMail" type="submit" color="primary">ইমেইল করুন</v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-dialog>
    </v-dialog>
</template>

<script>
    import axios from "../../axios_instance.js";

    export default {
        name: "LastCashOut",
        props: {
            dialogData: Array,
            value: Boolean
        },
        data() {
            return {
                drawerMailToCashoutHistory: false,
                emailAddress: null,
                statementsHeaders: [{
                    text: "অনুরোধের তারিখ",
                    value: "request_date"
                },{
                    text: "গ্রহণের তারিখ",
                    value: "receive_date"
                },{
                    text: "এম এফ এস নাম্বর",
                    value: "mfs_number"
                }, {
                    text: "টি.এক্স.এন আইডি #",
                    value: "trxno"
                }, {
                    text: "পরিমান",
                    value: "amount",
                    align: "right"
                }],
                display_errors: [],
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
            cashOutStatment: function()
            {
                return this.dialogData.filter(function(q){
                    return q.ref == 'withdraw'
                });
            }
        },
        methods: {
            async downloadCashoutHistory()
            {
                axios({
                    url: "/download-cashout-history/web",
                    method: 'GET',
                    responseType: 'blob',
                }).then((response) => {
                    var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                    var fileLink = document.createElement('a');

                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', 'cashout_history.pdf');
                    document.body.appendChild(fileLink);

                    fileLink.click();
                });
            },
            async mailCashoutHistory(){
                this.drawerMailToCashoutHistory = true;
            },
            async sendToCashoutHistoryMail(){
                this.display_errors = [];
                try {
                    let response = await axios.post("/send-cashout-history-via-mail", {
                        email_address: this.emailAddress,
                    });
                    this.alertMessage = response.data.message;
                    this.snackbar = true;
                    this.emailAddress = null;
                } catch (error) {
                    if(error.response.data.errors){
                        this.display_errors = error.response.data.errors;
                    }
                    this.alertMessage = error.response.data.message;
                    this.snackbar = true;
                }
            }
        }
    };
</script>