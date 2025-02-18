<template>
	<v-container fluid style="">
		<real-time-orders :orders="AvaibaleOrder"></real-time-orders>
        <v-layout row wrap fill-height style="margin-left: -5px; margin-right: -5px;">
			<v-flex md4 sm4 xs12>
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%" color="primary" hover @click="selectTab('waiting_order')">
                        <v-card-title>
                            <v-icon large left>pan_tool</v-icon>
                            <span class="subheading font-weight-light">অপেক্ষামান কাজ সমূহ</span>
                        </v-card-title>
                        <v-card-text class="subheading font-weight-bold text-center">
                            <span v-if="$i18n.locale=='en'">{{serviceProviderDetails.total_job_waiting}}</span>
                            <span v-else>{{ e2btransform((serviceProviderDetails.total_job_waiting).toString()) }}</span>
                        </v-card-text>
                    </v-card>
                </div>
			</v-flex>
			<v-flex md4 sm4 xs12>
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%" color="primary" hover @click="selectTab('running_order')">
                        <v-card-title>
                            <v-icon large left>check</v-icon>
                            <span class="subheading font-weight-light">চলতি কাজ সমূহ</span>
                        </v-card-title>
                        <v-card-text class="subheading font-weight-bold text-center">
                            <span v-if="$i18n.locale=='en'">{{serviceProviderDetails.total_job_running}}</span>
                            <span v-else>{{ e2btransform((serviceProviderDetails.total_job_running).toString()) }}</span>
                        </v-card-text>
                    </v-card>
                </div>
			</v-flex>
			<v-flex md4 sm4 xs12>
                <div style="padding: 5px; height:100%">
                    <v-card class="mx-auto" style="height:100%" color="primary" hover to="/purberkaaj">
                        <v-card-title>
                            <v-icon large left>work</v-icon>
                            <span class="subheading font-weight-light">সম্পন্ন কাজ সমূহ</span>
                        </v-card-title>
                        <v-card-text class="subheading font-weight-bold text-center">
                            <span v-if="$i18n.locale=='en'">{{serviceProviderDetails.total_job_done}}</span>
                            <span v-else>{{ e2btransform((serviceProviderDetails.total_job_done).toString()) }}</span>
                        </v-card-text>
                    </v-card>
                </div>
			</v-flex>
		</v-layout>
        <v-dialog v-model="giveFeedBackNratingDialog" persistent max-width="290">
            <v-card>
                <v-card-title class="headline">Order No #{{ getFeedbackData.order_no }}</v-card-title>
                <div class="text-xs-center">
                    <v-rating v-model="getFeedbackData.rating" color="yellow darken-3" background-color="grey darken-1" hover></v-rating>
                    ({{ getFeedbackData.rating }})
                </div>
                <v-divider></v-divider>
                <div v-if="getFeedbackData.feedback_questions">
                    <v-card-text style="padding-bottom:0" v-for="(item, index ) in getFeedbackData.feedback_questions" :key="index" class="subheading" primary-title>
                        {{ item.question }}
                        <v-radio-group v-model="feedbackAnswer[index]" style="margin-top: 4px;">
                            <v-radio
                                v-for="option in item.options"
                                :key="option.id"
                                :label=option.title
                                :value="[item.id, option.id]"
                            ></v-radio>
                        </v-radio-group>
                    </v-card-text>
                </div>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" flat @click="giveFeedBackNrating()">No Thanks</v-btn>
                    <v-btn color="green darken-1" flat @click="giveFeedBackNrating()">Submit</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
		
		<opekkhaiman-kaaj v-if="(selected == 'waiting_order')"></opekkhaiman-kaaj>
		<shokol-kaaj-cholti-kaaj v-if="(selected == 'running_order')"></shokol-kaaj-cholti-kaaj>
		
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
import OpekkhaManKaaj from "../shokolkaaj/OpekkhaManKaaj";
import RealtimeOrders from "../shokolkaaj/RealtimeOrders";

export default {
	name: "ShokolKaaj",
	components: {
		"shokol-kaaj-cholti-kaaj": ShokolKaajCholtiKaaj,
		"opekkhaiman-kaaj": OpekkhaManKaaj,
		"real-time-orders": RealtimeOrders
	},
	data() {
		return {
			selected: '',
            giveFeedBackNratingDialog: false,
            getFeedbackData: {
                order_no: null,
                rating: 0,
            },
            feedbackAnswer: [],
			serviceProviderDetails: {
				total_job_running: 0,
				total_job_waiting: 0,
				total_job_done: 0
			},
            AvaibaleOrder: [],
			snackbar: null,
			alertMessage: null
		};
	},
	methods: {
		selectTab(selectedTab) {
			this.selected = selectedTab;
		},
		async getServiceProviderDetails() {
            let loader = this.$loading.show();
			let response = await axios.get("/service-provider-details");
			this.serviceProviderDetails = response.data.data;
			loader.hide();
		},
        async watingFeedbackOrder(){
			var orderFeedbacks = await axios.get("/check-feedback-order/sp");
			if(orderFeedbacks.data.data != null)
			{
				this.getFeedbackData = orderFeedbacks.data.data;
				this.giveFeedBackNratingDialog = true; 
			}
        }, 
        async giveFeedBackNrating(){ 
            var response = await axios.post("/give-feedback-rating-process", {
                rating: this.getFeedbackData.rating,
                type: 'sp_to_user',
                feedback_answer: this.feedbackAnswer,
                order_id: this.getFeedbackData.order_id,
            }); 
            this.giveFeedBackNratingDialog = false;
        },
        async getNewAvaibaleOrder() {
            let loader = this.$loading.show();
            var orders = await axios.get("/avaiable-order");
            this.AvaibaleOrder = orders.data.data;

            if (this.AvaibaleOrder.length == 0) {
                this.watingFeedbackOrder();
            }
            loader.hide();
        },
	},
    created() {
        this.getServiceProviderDetails();
        Echo.channel("orderFeedBackEvent").listen("OrderFeedBackEvent", response => {
            this.giveFeedBackNratingDialog = false ;
            this.watingFeedbackOrder(); 
        });
        Echo.channel("orderChannel").listen("OrderEvent", response => {
            this.getNewAvaibaleOrder(); 
        });
        this.getNewAvaibaleOrder();
    },
};
</script>