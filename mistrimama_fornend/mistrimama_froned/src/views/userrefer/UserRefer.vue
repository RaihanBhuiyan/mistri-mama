<template>
    <v-container fluid style="">
        <v-card elevation="1" style="margin-bottom: 15px;">
            <v-card-title class="white--text" style="background-color: #febe00;">
                <h5><v-icon>group</v-icon> Earn Money</h5>
            </v-card-title>
            <v-card-text>
                <p>"Are you a social magnet? Earn some money by telling your friends or family about Mistri Mama services. Get started by creating a Mistri Mama user account. Spread the word by sharing your unique referral code or referral link through blog posts, articles, emails, Facebook posts and tweets. You’ll get paid for every single service completion through your referral code or referral link</p>
                <p><strong>Reward Point Modality</strong></p>
                <p>Referrer will get 30% of RP (reward point) of each successful referred service’s payable amount. (e.g.- Mr. X referred AC detergent service, BDT 1000 to Mr. Y. After successful service, Mr. X will get 30% or 300 reward point). 3 reward points is equivalent to BDT 1. Referrer can withdraw his/her amount, if he/she has minimum 600 reward points, which is equivalent to BDT 200. User or referrer can make service payment through reward point. Only half of total service amount can be adjusted through reward point."</p>
            </v-card-text>
        </v-card>
        <v-card elevation="1" style="margin-bottom: 15px;">
            <v-card-title class="white--text" style="background-color: #febe00;">
                <h5><v-icon>group</v-icon> Share referral code/link</h5>
            </v-card-title>
            <v-list two-line>
                <v-list-tile v-if="site_configs.refer ==1">
                    <v-list-tile-action >
                        <v-list-tile-action-text>CODE - </v-list-tile-action-text>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title id="getCode">{{ auth_user.ref_code }}</v-list-tile-title>
                    </v-list-tile-content>
                    <v-list-tile-action>
                        <v-tooltip left>
                            <template v-slot:activator="{ on }">
                                <v-icon @click="copyText('code')" v-on="on">file_copy</v-icon>
                            </template>
                            <span>Copy</span>
                        </v-tooltip>
                    </v-list-tile-action>
                </v-list-tile>
                <v-divider style="margin:0"></v-divider>
                <v-list-tile v-if="site_configs.refer ==1">
                    <v-list-tile-action>
                        <v-list-tile-action-text>LINK - </v-list-tile-action-text>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title id="getLink">{{ auth_user.ref_url }}</v-list-tile-title>
                    </v-list-tile-content>
                    <v-list-tile-action>
                        <v-tooltip left>
                            <template v-slot:activator="{ on }">
                                <v-icon @click="copyText('link')" v-on="on">file_copy</v-icon>
                            </template>
                            <span>Copy</span>
                        </v-tooltip>
                    </v-list-tile-action>
                </v-list-tile>
                <v-list-tile v-else>
                    <v-list-tile-action>
                        <v-list-tile-action-text>LINK - </v-list-tile-action-text>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title id="getLink">https://mistrimama.com/order/electrical</v-list-tile-title>
                    </v-list-tile-content>
                    <v-list-tile-action>
                        <v-tooltip left>
                            <template v-slot:activator="{ on }">
                                <v-icon @click="copyText('link')" v-on="on">file_copy</v-icon>
                            </template>
                            <span>Copy</span>
                        </v-tooltip>
                    </v-list-tile-action>
                </v-list-tile>
            </v-list>
            <v-card-actions>
                <p>Share referral code/link</p>
                <v-spacer></v-spacer>
                <facebook class="mr-1" style="cursor:pointer" :url="ref_url_facebook" title="Check me on Facebook" scale="2"></facebook>
                <twitter class="ml-1" style="cursor:pointer" :url="ref_url_twitter" title="Check me on Twitter" scale="2"></twitter>
            </v-card-actions>
        </v-card>
        <v-card elevation="1" v-if="rewardPointHistory">
            <v-card-title class="white--text" style="background-color: #febe00;">
                <h5><v-icon>history</v-icon> REFER HISTORY</h5>
            </v-card-title>
            <v-card-actions>
                <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
            </v-card-actions>
            <template>
                <v-data-table v-if="calculatedRewardPoint.length != 0" :headers="headers" :items="calculatedRewardPoint" :search="search" :items-per-page="10" class="elevation-0">
                    <template v-slot:items="props">
                        <td class="text-xs-left" style="cursor: pointer">{{ props.item.datetime }}</td>
                        <td class="text-xs-left" style="cursor: pointer">{{ props.item.details }}</td>
                        <td class="text-xs-left" style="cursor: pointer">{{ props.item.reward_point }}</td>
                        <td class="text-xs-left" style="cursor: pointer">{{ props.item.reward_balance }}</td>
                        <td class="text-xs-left" style="cursor: pointer">{{ props.item.equivalent_balance }}</td>
                    </template>
                </v-data-table>
            </template>
        </v-card>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
    import { mapState } from "vuex";
    import axios from "../../axios_instance.js";
    import { localStorageService } from "../../helper.js";
    import { Facebook, Twitter } from "vue-socialmedia-share";

    export default {
        name: "UserRefer",
        components: {
            Facebook, Twitter
        },
        data() {
            return {
                ref_url_facebook: null,
                snackbar: null,
                alertMessage: null,
                formData: {
                    promoCode: null
                },
                auth_user: [],
                site_configs:{ 
                    refer: 0
                },
                text: null,
                headers: [{
                    text: "Refer Date Time",
                    value: "date_time"
                }, {
                    text: "Details",
                    value: "details"
                }, {
                    text: "Reward Point",
                    value: "reward_point",
                }, {
                    text: "Total Reward Point (Balance)",
                    value: "balance"
                }, {
                    text: "RP Equivalent Amount (Balance)",
                    value: "equivalent_amount",
                }],
                rewardPointHistory: [],
                search: null
            };
        },
        methods: {
            copyText(type) {
                let copyText = null;
                if (type == "code") {
                    copyText = document.getElementById("getCode");
                } else {
                    copyText = document.getElementById("getLink");
                }
                let selection = window.getSelection();
                let range = document.createRange();
                range.selectNodeContents(copyText);
                selection.removeAllRanges();
                selection.addRange(range);
                document.execCommand("copy");
            },
            async getRewardPointHistory()
            {
                var response = await axios.get("/user-rewardpoint-history");
                this.rewardPointHistory = response.data;
            },
            async getSiteConfig() {
                let response = await axios.get("site-configs");   
                this.site_configs.refer = response.data.refer;
            }
        },
        watch: {
            //
        },
        created() {
            this.auth_user = localStorageService.getItem("currentUserData");
            this.ref_url_facebook = this.auth_user.ref_url
            this.ref_url_twitter = this.auth_user.ref_url
            this.getRewardPointHistory();
        },
        computed: {
             calculatedRewardPoint() {
                    const newArray = [];
                    let total_reward_point = 0;
                    let total_equivalent_balance = 0;
                    for(let i = 0; i < this.rewardPointHistory.length; i++) {
                        if(this.rewardPointHistory[i].status == 'add'){
                             total_reward_point += Number(this.rewardPointHistory[i].rp);
                             total_equivalent_balance += Number(this.rewardPointHistory[i].rp/3);
                        }else if(this.rewardPointHistory[i].status == 'remove'){
                             total_reward_point -= Number(this.rewardPointHistory[i].rp);
                             total_equivalent_balance -= Number(this.rewardPointHistory[i].rp/3);
                        }
                       
                        newArray.push({
                            datetime : this.rewardPointHistory[i].created_at, 
                            details : this.rewardPointHistory[i].details ,
                            reward_point : this.rewardPointHistory[i].rp,
                            reward_balance : total_reward_point,
                            equivalent_balance : Math.round(total_equivalent_balance, 2),
                        });
                    }
                    return newArray;
                },

        },
    };
</script>