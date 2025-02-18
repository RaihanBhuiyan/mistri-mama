<template>
    <v-container fluid>
        <v-card class="mx-auto" style="height:100%">
            <v-card-title class="white--text" style="background-color: #febe00;">
                <span class="subheading font-weight-light">স্কিম ইতিহাস</span>
            </v-card-title>
            <v-card-text style="margin-bottom: 15px" class="headline font-weight-bold" v-for="(item,index)  in items" :key="item">
                <p class="caption font-weight-bold">টার্গেট {{ items.length - index }} সপ্তাহ  ({{ item.timeline }})</p>
                <div class="progress" style="height: 3rem; font-size: 1.75rem; text-align: center;">
                    <div style="width: 33.33%; background-color: transparent; color: #333; line-height: 3rem; position: relative;">
                        <span style="position: relative; z-index: 99999">C (15)</span>
                        <span :style="'display: block; background-color: #5cb85c; width: '+item.scheme.a+'%; position: absolute; left: 0; top: 0'">&nbsp;</span>
                    </div>
                    <div style="width: 33.33%; background-color: transparent; color: #333; line-height: 3rem; position: relative;">
                        <span style="position: relative; z-index: 99999">B (20)</span>
                        <span :style="'display: block; background-color: #5bc0de; width: '+item.scheme.b+'%; position: absolute; left: 0; top: 0'">&nbsp;</span>
                    </div>
                    <div style="width: 33.33%; background-color: transparent; color: #333; line-height: 3rem; position: relative;">
                        <span style="position: relative; z-index: 99999">A (30)</span>
                        <span :style="'display: block; background-color: #f0ad4e; width: '+item.scheme.c+'%; position: absolute; left: 0; top: 0'">&nbsp;</span>
                    </div>
                </div>
                <p class="subheading font-weight-bold text-center mt-3" v-html="(item.total_job > 0) ? 'আপনি '+item.total_job+' টি কাজ করেছেন' : 'আপনি এখনো নিজের কোনো কাজ করেননি'"></p>
            </v-card-text>
        </v-card>
        <v-snackbar v-model="snackbar" :vertical="true" :bottom="'bottom'" :right="'right'" :timeout="10000">
            {{ alertMessage }}
            <v-btn class="snackButton" color="primary" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
    import { mapState } from "vuex";
    import ImageCropper from "../components/ImageCropper";
    import { localStorageService } from "../helper.js";
    import axios from "../axios_instance.js";

    export default {
        name: "ServiceProviderProfile",
        components: {
            ImageCropper
        },
        data() {
            return {
                uploadImageOption: false, 
                valid: true,
                display_errors: [], 
                snackbar: false,
                alertMessage: null,
                items: [],
                scheme :  0 ,
                serviceProviderDetails: {
                    balance: 0,
                    withdrawable_balance: 0
                },
            };
        },
        methods: {
             async getScheme() {
                let loader = this.$loading.show();
                let response = await axios.get("/sp/scheme");
                this.items = response.data.data;
                loader.hide();
            },
              async getServiceProviderDetails() {
                let loader = this.$loading.show();
                let response = await axios.get("/service-provider-details");
                this.serviceProviderDetails = response.data.data;

                this.scheme = Math.ceil((this.serviceProviderDetails.total_self_order/30)*100 );
                loader.hide();
            }, 
            
        },
        computed: {
            //  calculatedItems() {
            //     const newArray = []; 
            //     console.log(this.items);
            //     for(let i = (this.items.length -1 ); i >=0 ; i--) { 
            //         newArray.push({
            //             start_date: this.items[i].start_date,
            //             end_date : this.items[i].end_date, 
            //             order : this.items[i].order,
            //             scheme : this.items[i].scheme , 
            //             item: this.items[i],
            //         });
            //     }
            //     return newArray; ;
            // },
        },
         created() {
             this.getServiceProviderDetails();
            this.getScheme();
        },
        watch: {
        },
    };
</script>