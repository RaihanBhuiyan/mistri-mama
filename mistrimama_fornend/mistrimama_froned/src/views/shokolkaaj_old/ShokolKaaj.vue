<template>
    <v-container fluid style="padding-left:0; padding-right:0;">
        <v-layout row wrap fill-height>
            <v-flex md4>
                <div class="total-box" style="height:100%">
                    <v-card style="height:100%">
                        <v-layout wrap>
                            <v-flex xs3>
                                <div class="icon-box floating text-center">
                                    <v-icon>check</v-icon>
                                </div>
                            </v-flex>
                            <v-flex xs9>
                                <v-card-text class="text-right">
                                    <p class="red--text">চলতি কাজের সংখ্যা</p>
                                    <p>&nbsp;</p>
                                    <p class="text-center" style="font-size: 40px;">{{ serviceProviderDetails.total_job_running }}</p>
                                    <p>&nbsp;</p>
                                    <p>&nbsp;</p>
                                </v-card-text>
                            </v-flex>
                        </v-layout>
                    </v-card>
                </div>
            </v-flex>
            <v-flex md4>
                <div class="total-box" style="height:100%">
                    <v-card style="height:100%">
                        <v-layout wrap>
                            <v-flex xs3>
                                <div class="icon-box floating text-center">
                                    <v-icon>pan_tool</v-icon>
                                </div>
                            </v-flex>
                            <v-flex xs9>
                                <v-card-text class="text-right">
                                    <p class="red--text">অপেক্ষামান কাজের সংখ্যা</p>
                                    <p>&nbsp;</p>
                                    <p class="text-center" style="font-size: 40px;">{{ serviceProviderDetails.total_job_waiting }}</p>
                                    <p>&nbsp;</p>
                                    <p>&nbsp;</p>
                                </v-card-text>
                            </v-flex>
                        </v-layout>
                    </v-card>
                </div>
            </v-flex>
            <v-flex md4>
                <div class="total-box" style="height:100%">
                    <v-card style="height:100%">
                        <v-layout wrap>
                            <v-flex xs3>
                                <div class="icon-box floating text-center">
                                    <v-icon>work</v-icon>
                                </div>
                            </v-flex>
                            <v-flex xs9>
                                <v-card-text class="text-right">
                                    <p class="red--text">মোট সম্পন্ন কাজ</p>
                                    <p>&nbsp;</p>
                                    <p class="text-center" style="font-size: 40px;">{{ serviceProviderDetails.total_job_done }}</p>
                                    <p>&nbsp;</p>
                                    <p>&nbsp;</p>
                                </v-card-text>
                            </v-flex>
                        </v-layout>
                    </v-card>
                </div>
            </v-flex>
        </v-layout>

        <v-container fluid>
            <v-tabs icons-and-text centered grow v-model="tab">
                <v-tabs-slider color="accent"></v-tabs-slider>
                <v-tab class="classic-tabs">ফোন অর্ডার <v-icon>local_phone</v-icon></v-tab>
                <v-tab class="classic-tabs">চলতি কাজ <v-icon>av_timer</v-icon></v-tab>
                <v-tabs-items v-model="tab">
                    <v-tab-item style="padding:0">
                        <shokol-kaaj-phone-order></shokol-kaaj-phone-order>
                    </v-tab-item>
                    <v-tab-item style="padding:0">
                        <shokol-kaaj-cholti-kaaj></shokol-kaaj-cholti-kaaj>
                    </v-tab-item>
                </v-tabs-items>
            </v-tabs>
        </v-container>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
  </v-container>
</template>

<script>
import { mapState } from "vuex";
import axios from "../../axios_instance";
import ShokolKaajCholtiKaaj from "../shokolkaaj/ShokolKaajCholtiKaaj";
import ShokolKaajPhoneOrder from "../shokolkaaj/ShokolKaajPhoneOrder";

export default {
    components: {
        'shokol-kaaj-cholti-kaaj': ShokolKaajCholtiKaaj,
        'shokol-kaaj-phone-order': ShokolKaajPhoneOrder
    },
    data() {
        return {
            tab: 0,
            serviceProviderDetails: {
                'total_job_running': 0,
                'total_job_waiting': 0,
                'total_job_done': 0
            },
            snackbar: null,
            alertMessage: null,
        };
    },
    methods: {
        async getServiceProviderDetails() {
            let response = await axios.get("/service-provider-details");
            this.serviceProviderDetails = response.data.data;
        }
    },
    created() {
        this.getServiceProviderDetails();
    }
}
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
.empty-box > h3 > .a {
  color: var(--secondary);
  font-size: 30px !important;
  background-color: var(--primaryTwo);
  // padding-right: 15px !important;
  // padding-left: 7px !important;
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
