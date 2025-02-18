<template>
    
</template>


<script>
import { mapState } from "vuex";
import axios from "../axios_instance.js";
import { async } from "q";

export default {
    data() {
        return {
            giveFeedBackNratingDialog: true,
            getFeedbackData: {
                order_no: 0.00,
                rating: 0,
            },
            feedbackAnswer: [],
            comrades: [],
        };
    },
    methods: {
        async watingFeedbackOrder(){
            var orderFeedbacks = await axios.get("/check-feedback-order/sp");
            this.getFeedbackData = orderFeedbacks.data.data ; 
        }, 
        async giveFeedBackNrating(){ 
            var response = await axios.post("/give-feedback-rating-process", {
                rating: this.getFeedbackData.rating,
                type: 'sp_to_user',
                feedback_answer: this.feedbackAnswer,
                order_id: this.getFeedbackData.order_id,
            }); 
        },
    },
    watch: {},
    computed: {
    },
    created() {
        this.watingFeedbackOrder();
    }
};
</script>